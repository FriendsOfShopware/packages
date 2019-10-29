{
  network.enableRollback = true;

  webserver = { config, lib, pkgs, ... }:
    let
      phpPackage = pkgs.php73;
      phpPackages = pkgs.php73Packages;
      phpOptions = ''
        extension=${phpPackages.apcu}/lib/php/extensions/apcu.so
        extension=${phpPackages.redis}/lib/php/extensions/redis.so
        zend_extension = opcache.so
        opcache.enable = 1
        memory_limit = 256M
        post_max_size = 1M
        upload_max_filesize = 1M
        php_admin_value[error_log] = '/tmp/php.log'
        php_admin_flag[log_errors] = on
        max_execution_time = 300
      '';
      symfony_cmd = pkgs.writeScriptBin "symfony-console" ''
        #! ${pkgs.stdenv.shell}
        cd ${pkgs.packages}
        exec /run/wrappers/bin/sudo -u packages \
          ${phpPackage}/bin/php \
          -c ${pkgs.writeText "php.ini" phpOptions}\
          bin/console $*
      '';
    in {
      imports = [ ./hardware-configuration.nix ];
      nixpkgs.overlays = import ./overlays.nix;
      deployment.targetHost = "78.46.250.5";

      boot.loader.grub.enable = true;
      boot.loader.grub.version = 2;
      boot.loader.grub.device = "/dev/sda";

      environment.systemPackages = with pkgs; [
        htop
        php73
        redis
      ];

      services.openssh.enable = true;

      time.timeZone = "Europe/Berlin";

      networking.firewall.allowedTCPPorts = [ 80 443 ];

      users.extraUsers.packages = {
        home = "/var/lib/packages";
        group = "nginx";
        createHome = true;
      };

      services.mysql = {
        enable = true;
        package = pkgs.mariadb;
      };

      services.phpfpm.pools.packages = {
        user = "packages";
        group = "nginx";
        phpOptions = phpOptions;
        phpPackage = pkgs.php73;
        settings = {
          "pm" = "dynamic";
          "pm.max_children" = "32";
          "pm.start_servers" = "2";
          "pm.min_spare_servers" = "2";
          "pm.max_spare_servers" = "4";
          "pm.max_requests" = "500";
          "listen.owner" = "nginx";
          "listen.group" = "nginx";
          "user" = "packages";
          "group" = "nginx";
          "env[PATH]" =
            "/run/wrappers/bin:/nix/var/nix/profiles/default/bin:/run/current-system/sw/bin:/usr/bin:/bin";
        };
      };

      services.nginx = {
        enable = true;
        recommendedGzipSettings = true;
        recommendedOptimisation = true;
        recommendedProxySettings = true;
        recommendedTlsSettings = true;
        virtualHosts = {
          "packages.friendsofshopware.de" = {
            root = "${pkgs.packages}/public";
            http2 = true;
            sslCertificate = "/etc/ssl/cloudflare.crt";
            sslCertificateKey = "/etc/ssl/cloudflare.key";
            extraConfig = ''
              location / {
                try_files $uri /index.php$is_args$args;
              }

              location ~ \.php$ {
                try_files $uri $uri/ =404;
                fastcgi_split_path_info ^(.+\.php)(/.+)$;

                include ${pkgs.nginx}/conf/fastcgi.conf;
                fastcgi_param APP_ENV    "prod";
                fastcgi_param APP_SECRET "${builtins.getEnv "APP_SECRET"}";
                fastcgi_param APP_URL "https://packages.friendsofshopware.de";
                fastcgi_param HTTP_PROXY "";
                fastcgi_buffers 8 16k;
                fastcgi_buffer_size 32k;
                client_max_body_size 24M;
                client_body_buffer_size 128k;
                fastcgi_pass unix:${config.services.phpfpm.pools.packages.socket};
              }
            '';
          };
        };
      };

      systemd.services."packages-setup" = {
        after = [ "mysql.service" ];
        wantedBy = [ "multi-user.target" ];
        before = [ "phpfpm-packages.service" ];
        script = ''
          # Clear cache
          if [[ -e /var/lib/packages/var/cache/ ]]; then
            rm -rf /var/lib/packages/var/cache/*
          fi

          ${symfony_cmd}/bin/symfony-console cache:warmup

        '';
        serviceConfig.Type = "oneshot";
      };

      services.redis.enable = true;
    };
}
