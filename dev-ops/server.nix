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
        memory_limit = 512M
        post_max_size = 1M
        upload_max_filesize = 1M
        error_log = stderr
        log_errors = true
        max_execution_time = 300
      '';
      symfony_cmd = pkgs.writeScriptBin "symfony-console" ''
        #! ${pkgs.stdenv.shell}
        cd ${pkgs.packages}
        /run/wrappers/bin/sudo -E -u packages \
          ${phpPackage}/bin/php \
          -c ${pkgs.writeText "php.ini" phpOptions}\
          bin/console $*
      '';
      phpEnv = {
        APP_ENV = "prod";
        APP_SECRET = builtins.getEnv "APP_SECRET";
        ALGOLIA_APP_ID = builtins.getEnv "ALGOLIA_APP_ID";
        ALGOLIA_API_KEY = builtins.getEnv "ALGOLIA_API_KEY";
        SENTRY_DSN = builtins.getEnv "SENTRY_DSN";
        APP_URL = "https://packages.friendsofshopware.com";
        DATABASE_URL = "mysql://root:${builtins.getEnv "MYSQL_PASSWORD"}@localhost/packages";
      };
    in {
      imports = [ ./hardware-configuration.nix ];
      nixpkgs.overlays = import ./overlays.nix;

      boot.loader.grub.enable = true;
      boot.loader.grub.version = 2;

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
        ensureDatabases = [ "packages" ];
      };

      services.phpfpm.pools.packages = {
        user = "packages";
        group = "nginx";
        phpOptions = phpOptions;
        phpPackage = pkgs.php73;
        phpEnv = phpEnv;
        settings = {
          "clear_env" = "no";
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
        };
      };

      services.nginx = {
        enable = true;
        recommendedGzipSettings = true;
        recommendedOptimisation = true;
        recommendedProxySettings = true;
        recommendedTlsSettings = true;
        virtualHosts = {
          "packages.friendsofshopware.com" = {
            root = "${pkgs.packages}/public";
            http2 = true;
            forceSSL = true;
            enableACME = true;
            extraConfig = ''
              location / {
                try_files $uri /index.php$is_args$args;
              }

              location ~ \.php$ {
                try_files $uri $uri/ =404;
                fastcgi_split_path_info ^(.+\.php)(/.+)$;

                include ${pkgs.nginx}/conf/fastcgi.conf;
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
        environment = phpEnv;
        script = ''
          # Clear cache
          if [[ -e /var/lib/packages/var/cache/ ]]; then
            rm -rf /var/lib/packages/var/cache/*
          fi

          ${symfony_cmd}/bin/symfony-console cache:warmup
          ${symfony_cmd}/bin/symfony-console doctrine:migrations:migrate --no-interaction

        '';
        serviceConfig.Type = "oneshot";
      };

      services.redis.enable = true;
    };
}
