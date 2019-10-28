{composerEnv, fetchurl, fetchgit ? null, fetchhg ? null, fetchsvn ? null, noDev ? false}:

let
  packages = {
    "doctrine/annotations" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "doctrine-annotations-904dca4eb10715b92569fbcd79e201d5c349b6bc";
        src = fetchurl {
          url = https://api.github.com/repos/doctrine/annotations/zipball/904dca4eb10715b92569fbcd79e201d5c349b6bc;
          sha256 = "0sskldckhf7dfd8r6dz1jla660kyj9zlxp3giv2a7g8g758pwy06";
        };
      };
    };
    "doctrine/cache" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "doctrine-cache-d4374ae95b36062d02ef310100ed33d78738d76c";
        src = fetchurl {
          url = https://api.github.com/repos/doctrine/cache/zipball/d4374ae95b36062d02ef310100ed33d78738d76c;
          sha256 = "159yq8f35rm4h376w82agjayld117z3f2ycryirphf7xi1v5d94m";
        };
      };
    };
    "doctrine/collections" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "doctrine-collections-c5e0bc17b1620e97c968ac409acbff28b8b850be";
        src = fetchurl {
          url = https://api.github.com/repos/doctrine/collections/zipball/c5e0bc17b1620e97c968ac409acbff28b8b850be;
          sha256 = "1d51w3ci5ypx7rfg08f3724xwqjz7i1wm7bgicdxixxmbhyn9xiz";
        };
      };
    };
    "doctrine/common" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "doctrine-common-b8ca1dcf6b0dc8a2af7a09baac8d0c48345df4ff";
        src = fetchurl {
          url = https://api.github.com/repos/doctrine/common/zipball/b8ca1dcf6b0dc8a2af7a09baac8d0c48345df4ff;
          sha256 = "0gb67qssa5dhv5vm1agwvqzvrqdxdhn3p6icm3wxycw1swy7ykg2";
        };
      };
    };
    "doctrine/dbal" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "doctrine-dbal-22800bd651c1d8d2a9719e2a3dc46d5108ebfcc9";
        src = fetchurl {
          url = https://api.github.com/repos/doctrine/dbal/zipball/22800bd651c1d8d2a9719e2a3dc46d5108ebfcc9;
          sha256 = "0kbahs699jd8pxf512dgg7arv49dc7qzi3mx8snxqm4h15n5brnj";
        };
      };
    };
    "doctrine/doctrine-bundle" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "doctrine-doctrine-bundle-28101e20776d8fa20a00b54947fbae2db0d09103";
        src = fetchurl {
          url = https://api.github.com/repos/doctrine/DoctrineBundle/zipball/28101e20776d8fa20a00b54947fbae2db0d09103;
          sha256 = "0bwljrj7v5gpa4mlhqdmj8rcpsrwy1i1r4gcynvbliriscvrsjn2";
        };
      };
    };
    "doctrine/doctrine-cache-bundle" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "doctrine-doctrine-cache-bundle-5514c90d9fb595e1095e6d66ebb98ce9ef049927";
        src = fetchurl {
          url = https://api.github.com/repos/doctrine/DoctrineCacheBundle/zipball/5514c90d9fb595e1095e6d66ebb98ce9ef049927;
          sha256 = "04njrfhw4fc2ifacd9h0wd9i14l7ycv3hanbqrw5ilsai02j6asa";
        };
      };
    };
    "doctrine/doctrine-migrations-bundle" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "doctrine-doctrine-migrations-bundle-4c9579e0e43df1fb3f0ca29b9c20871c824fac71";
        src = fetchurl {
          url = https://api.github.com/repos/doctrine/DoctrineMigrationsBundle/zipball/4c9579e0e43df1fb3f0ca29b9c20871c824fac71;
          sha256 = "19m4hrdhwf1fdp9zl51f5ffzkiiac118ykhj2n7rlqjpvz1812sk";
        };
      };
    };
    "doctrine/event-manager" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "doctrine-event-manager-a520bc093a0170feeb6b14e9d83f3a14452e64b3";
        src = fetchurl {
          url = https://api.github.com/repos/doctrine/event-manager/zipball/a520bc093a0170feeb6b14e9d83f3a14452e64b3;
          sha256 = "165cxvw4idqj01l63nya2whpdb3fz6ld54rx198b71bzwfrydl88";
        };
      };
    };
    "doctrine/inflector" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "doctrine-inflector-5527a48b7313d15261292c149e55e26eae771b0a";
        src = fetchurl {
          url = https://api.github.com/repos/doctrine/inflector/zipball/5527a48b7313d15261292c149e55e26eae771b0a;
          sha256 = "0ng6vlwjr8h6hqwa32ynykz1mhlfsff5hirjidlk086ab6njppa5";
        };
      };
    };
    "doctrine/instantiator" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "doctrine-instantiator-a2c590166b2133a4633738648b6b064edae0814a";
        src = fetchurl {
          url = https://api.github.com/repos/doctrine/instantiator/zipball/a2c590166b2133a4633738648b6b064edae0814a;
          sha256 = "1d75i3rhml0amm7wvgb3ixzlkn97x4hmdb9xcr6m8dbqhnzjqy24";
        };
      };
    };
    "doctrine/lexer" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "doctrine-lexer-e17f069ede36f7534b95adec71910ed1b49c74ea";
        src = fetchurl {
          url = https://api.github.com/repos/doctrine/lexer/zipball/e17f069ede36f7534b95adec71910ed1b49c74ea;
          sha256 = "1amc4245djbw822fwkmwssgl6a0991i1l8g3k8501xc6383mjkz1";
        };
      };
    };
    "doctrine/migrations" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "doctrine-migrations-a89fa87a192e90179163c1e863a145c13337f442";
        src = fetchurl {
          url = https://api.github.com/repos/doctrine/migrations/zipball/a89fa87a192e90179163c1e863a145c13337f442;
          sha256 = "03lvyg9qripka4hzbw2sbka2fjk3b2pjb2fywjw2d6i7c5w203j6";
        };
      };
    };
    "doctrine/orm" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "doctrine-orm-b52ef5a1002f99ab506a5a2d6dba5a2c236c5f43";
        src = fetchurl {
          url = https://api.github.com/repos/doctrine/orm/zipball/b52ef5a1002f99ab506a5a2d6dba5a2c236c5f43;
          sha256 = "17rqnrmd4ixa5xk7k7xs8kzf6fvnxrqx7bimafzyfafnwcyfdinn";
        };
      };
    };
    "doctrine/persistence" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "doctrine-persistence-3da7c9d125591ca83944f477e65ed3d7b4617c48";
        src = fetchurl {
          url = https://api.github.com/repos/doctrine/persistence/zipball/3da7c9d125591ca83944f477e65ed3d7b4617c48;
          sha256 = "0yswkdkhclgwnhkxmwisv4d5v133di7czsi750p5y0nqfwchxaab";
        };
      };
    };
    "doctrine/reflection" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "doctrine-reflection-02538d3f95e88eb397a5f86274deb2c6175c2ab6";
        src = fetchurl {
          url = https://api.github.com/repos/doctrine/reflection/zipball/02538d3f95e88eb397a5f86274deb2c6175c2ab6;
          sha256 = "12n9zik4lxb9lx1jf0nbvg9vl9nv958a7z1yjx48scfxd1d1sxjy";
        };
      };
    };
    "jdorn/sql-formatter" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "jdorn-sql-formatter-64990d96e0959dff8e059dfcdc1af130728d92bc";
        src = fetchurl {
          url = https://api.github.com/repos/jdorn/sql-formatter/zipball/64990d96e0959dff8e059dfcdc1af130728d92bc;
          sha256 = "1dnmkm8mxylvxjwi0bdkzrlklncqx92fa4fwqp5bh2ypj8gaagzi";
        };
      };
    };
    "ocramius/package-versions" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "ocramius-package-versions-1d32342b8c1eb27353c8887c366147b4c2da673c";
        src = fetchurl {
          url = https://api.github.com/repos/Ocramius/PackageVersions/zipball/1d32342b8c1eb27353c8887c366147b4c2da673c;
          sha256 = "1bdi6lfb8l4aa9161a2wa72hcqg8j33irv748sbqgz6rpd88m6ns";
        };
      };
    };
    "ocramius/proxy-manager" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "ocramius-proxy-manager-4d154742e31c35137d5374c998e8f86b54db2e2f";
        src = fetchurl {
          url = https://api.github.com/repos/Ocramius/ProxyManager/zipball/4d154742e31c35137d5374c998e8f86b54db2e2f;
          sha256 = "0444f7rcac7y1cs0kw44mjyqg7ac7dmv7cabl595riv82svdvwj7";
        };
      };
    };
    "psr/cache" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "psr-cache-d11b50ad223250cf17b86e38383413f5a6764bf8";
        src = fetchurl {
          url = https://api.github.com/repos/php-fig/cache/zipball/d11b50ad223250cf17b86e38383413f5a6764bf8;
          sha256 = "06i2k3dx3b4lgn9a4v1dlgv8l9wcl4kl7vzhh63lbji0q96hv8qz";
        };
      };
    };
    "psr/container" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "psr-container-b7ce3b176482dbbc1245ebf52b181af44c2cf55f";
        src = fetchurl {
          url = https://api.github.com/repos/php-fig/container/zipball/b7ce3b176482dbbc1245ebf52b181af44c2cf55f;
          sha256 = "0rkz64vgwb0gfi09klvgay4qnw993l1dc03vyip7d7m2zxi6cy4j";
        };
      };
    };
    "psr/log" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "psr-log-bf73deb2b3b896a9d9c75f3f0d88185d2faa27e2";
        src = fetchurl {
          url = https://api.github.com/repos/php-fig/log/zipball/bf73deb2b3b896a9d9c75f3f0d88185d2faa27e2;
          sha256 = "0w232rxmafmzr4acy2bbfky3ay5zxrqvjfdh5b4ph66akwjzbndz";
        };
      };
    };
    "psr/simple-cache" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "psr-simple-cache-408d5eafb83c57f6365a3ca330ff23aa4a5fa39b";
        src = fetchurl {
          url = https://api.github.com/repos/php-fig/simple-cache/zipball/408d5eafb83c57f6365a3ca330ff23aa4a5fa39b;
          sha256 = "1djgzclkamjxi9jy4m9ggfzgq1vqxaga2ip7l3cj88p7rwkzjxgw";
        };
      };
    };
    "sensio/framework-extra-bundle" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "sensio-framework-extra-bundle-dfc2c4df9f7d465a65c770e9feb578fe071636f7";
        src = fetchurl {
          url = https://api.github.com/repos/sensiolabs/SensioFrameworkExtraBundle/zipball/dfc2c4df9f7d465a65c770e9feb578fe071636f7;
          sha256 = "00s70vjr6dsg3hl9mb475bd6zakl89rj5wpcc7igvmn4xxc3jpnf";
        };
      };
    };
    "symfony-bundles/json-request-bundle" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-bundles-json-request-bundle-ebf0121609d4b449dd2cc52d39074312734e31dc";
        src = fetchurl {
          url = https://api.github.com/repos/symfony-bundles/json-request-bundle/zipball/ebf0121609d4b449dd2cc52d39074312734e31dc;
          sha256 = "1p5l4sbhxz2813q75gshq9nybl4py4xsmz8msp0d4idaxyijm5mb";
        };
      };
    };
    "symfony/asset" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-asset-3f97e57596884f7b9158d564a533112a0d19dbdd";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/asset/zipball/3f97e57596884f7b9158d564a533112a0d19dbdd;
          sha256 = "1v3wi3608ys74ryvzw30341iayf7m8y74dpyycbwl3yymsllcjzg";
        };
      };
    };
    "symfony/cache" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-cache-40c62600ebad1ed2defbf7d35523d918a73ab330";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/cache/zipball/40c62600ebad1ed2defbf7d35523d918a73ab330;
          sha256 = "1n7j67lj36nzsm7l8ngyc9j6cn3jjmgalf4wlw25lp0a8d0sj3ch";
        };
      };
    };
    "symfony/cache-contracts" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-cache-contracts-af50d14ada9e4e82cfabfabdc502d144f89be0a1";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/cache-contracts/zipball/af50d14ada9e4e82cfabfabdc502d144f89be0a1;
          sha256 = "0pjh24vmrpn2wg08r0qn7q9ngzwcmdp47p8wlnz792q5y4rxvh94";
        };
      };
    };
    "symfony/config" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-config-0acb26407a9e1a64a275142f0ae5e36436342720";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/config/zipball/0acb26407a9e1a64a275142f0ae5e36436342720;
          sha256 = "0gqi0m0w2dkyslv1693j8814vawqma6vhf6vgv6chm8sf5yvw5d5";
        };
      };
    };
    "symfony/console" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-console-929ddf360d401b958f611d44e726094ab46a7369";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/console/zipball/929ddf360d401b958f611d44e726094ab46a7369;
          sha256 = "08f7gqkf0fmzpz7lm64vl76gzkm7xd3s4cwjb7i8hx8ychyif8y6";
        };
      };
    };
    "symfony/debug" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-debug-cc5c1efd0edfcfd10b354750594a46b3dd2afbbe";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/debug/zipball/cc5c1efd0edfcfd10b354750594a46b3dd2afbbe;
          sha256 = "0i4jxwbxq5hd2nassr693h9m23r7cy5zixiwhg8a601zgpx641g4";
        };
      };
    };
    "symfony/dependency-injection" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-dependency-injection-e1e0762a814b957a1092bff75a550db49724d05b";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/dependency-injection/zipball/e1e0762a814b957a1092bff75a550db49724d05b;
          sha256 = "1bnsv14w1d2p2z2zd8dyb3cazfrwp1cmcg27shhc0m5lcx8flcm4";
        };
      };
    };
    "symfony/doctrine-bridge" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-doctrine-bridge-486fa65a74692d84f250087c79d0b89d30d655a8";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/doctrine-bridge/zipball/486fa65a74692d84f250087c79d0b89d30d655a8;
          sha256 = "1ysr6fd3m9q2y9ypgnnammc9x880bn90qqcs07zb4sdmd3m01dir";
        };
      };
    };
    "symfony/dotenv" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-dotenv-1785b18148a016b8f4e6a612291188d568e1f9cd";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/dotenv/zipball/1785b18148a016b8f4e6a612291188d568e1f9cd;
          sha256 = "12g35fanylwafvwrz2n2fdghmrjb03f4hwhhz8ym6pd7zzk4xbdd";
        };
      };
    };
    "symfony/event-dispatcher" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-event-dispatcher-6229f58993e5a157f6096fc7145c0717d0be8807";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/event-dispatcher/zipball/6229f58993e5a157f6096fc7145c0717d0be8807;
          sha256 = "1dd0d17mk9v5njlwcck4nrz7c1w63v6i9gg3628hzg6537h1vkg1";
        };
      };
    };
    "symfony/event-dispatcher-contracts" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-event-dispatcher-contracts-c43ab685673fb6c8d84220c77897b1d6cdbe1d18";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/event-dispatcher-contracts/zipball/c43ab685673fb6c8d84220c77897b1d6cdbe1d18;
          sha256 = "0bffyy12ni44ykyrjjdgirmgfh2qvmw2narfl623lqqn7adcam6g";
        };
      };
    };
    "symfony/filesystem" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-filesystem-9abbb7ef96a51f4d7e69627bc6f63307994e4263";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/filesystem/zipball/9abbb7ef96a51f4d7e69627bc6f63307994e4263;
          sha256 = "0c43j7lxjs7ng8a3d3p2vxka8b1701fls5a2fq930cv1vac8czn3";
        };
      };
    };
    "symfony/finder" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-finder-5e575faa95548d0586f6bedaeabec259714e44d1";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/finder/zipball/5e575faa95548d0586f6bedaeabec259714e44d1;
          sha256 = "0k0ycv8c6n9xm9zigf1ib96z9qkp46z0wi1yaakyaf90ad4n3xs8";
        };
      };
    };
    "symfony/flex" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-flex-133e649fdf08aeb8741be1ba955ccbe5cd17c696";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/flex/zipball/133e649fdf08aeb8741be1ba955ccbe5cd17c696;
          sha256 = "1f8xaki2x67qcdyc92zcrfck714af1hjbj0f6y16qhrqhblaa82p";
        };
      };
    };
    "symfony/framework-bundle" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-framework-bundle-fca765488ecea04bf6c1c502d7b0214fa29460d8";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/framework-bundle/zipball/fca765488ecea04bf6c1c502d7b0214fa29460d8;
          sha256 = "0vddnilcfvxb8ppfhqq5xzvvbffcqnjawb1gj8y62f9kz8r7w3gr";
        };
      };
    };
    "symfony/http-foundation" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-http-foundation-76590ced16d4674780863471bae10452b79210a5";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/http-foundation/zipball/76590ced16d4674780863471bae10452b79210a5;
          sha256 = "08lq4n8vg45l9pvrjmr7dys7ss2di6ngmw41vcssr0akfnqwnagm";
        };
      };
    };
    "symfony/http-kernel" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-http-kernel-5f08141850932e8019c01d8988bf3ed6367d2991";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/http-kernel/zipball/5f08141850932e8019c01d8988bf3ed6367d2991;
          sha256 = "16s85afdrlfn8idx6v880ds4n4db4scrz3awi0qr7c5var26ibs1";
        };
      };
    };
    "symfony/mime" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-mime-32f71570547b91879fdbd9cf50317d556ae86916";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/mime/zipball/32f71570547b91879fdbd9cf50317d556ae86916;
          sha256 = "1v1k6jhwjw608pvvzz9cb0g45l5xqqvd5k4klavr3yla91f5y2vf";
        };
      };
    };
    "symfony/orm-pack" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-orm-pack-c57f5e05232ca40626eb9fa52a32bc8565e9231c";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/orm-pack/zipball/c57f5e05232ca40626eb9fa52a32bc8565e9231c;
          sha256 = "05mkawmxfl73xl19d9b8ia3hgg9bnw510kcq2cwcab4fm32wcx0s";
        };
      };
    };
    "symfony/polyfill-intl-idn" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-polyfill-intl-idn-6af626ae6fa37d396dc90a399c0ff08e5cfc45b2";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/polyfill-intl-idn/zipball/6af626ae6fa37d396dc90a399c0ff08e5cfc45b2;
          sha256 = "0lgg226ll63cyd7kbi5pyx13ja5ky5ppd350jib7y6ccx0fkdz2n";
        };
      };
    };
    "symfony/polyfill-mbstring" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-polyfill-mbstring-b42a2f66e8f1b15ccf25652c3424265923eb4f17";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/polyfill-mbstring/zipball/b42a2f66e8f1b15ccf25652c3424265923eb4f17;
          sha256 = "021q6zj07rb2qaia6rvdpbs432313dh4zfq13hmkmpr32wsnv1v8";
        };
      };
    };
    "symfony/polyfill-php72" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-polyfill-php72-04ce3335667451138df4307d6a9b61565560199e";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/polyfill-php72/zipball/04ce3335667451138df4307d6a9b61565560199e;
          sha256 = "17hb4v3g8nwi5sqims5fgsw1fyr71kqrwz7q7xszv1vmfhjj9iqc";
        };
      };
    };
    "symfony/polyfill-php73" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-polyfill-php73-2ceb49eaccb9352bff54d22570276bb75ba4a188";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/polyfill-php73/zipball/2ceb49eaccb9352bff54d22570276bb75ba4a188;
          sha256 = "0w9jbszx4p8n8kwv2hsvn6m38lyadlgvjafanhm8ily9r0nlns9a";
        };
      };
    };
    "symfony/routing" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-routing-3b174ef04fe66696524efad1e5f7a6c663d822ea";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/routing/zipball/3b174ef04fe66696524efad1e5f7a6c663d822ea;
          sha256 = "05wpv5qq72iqyr09kxbyqs76avbhiqgpsiqcgbc9m4nq0jzd5vnr";
        };
      };
    };
    "symfony/service-contracts" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-service-contracts-ffcde9615dc5bb4825b9f6aed07716f1f57faae0";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/service-contracts/zipball/ffcde9615dc5bb4825b9f6aed07716f1f57faae0;
          sha256 = "17mbpxnv1l9dgh82l3qablx5h0qqn0s0gbfg6fh8jq2rj41yfkxy";
        };
      };
    };
    "symfony/stopwatch" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-stopwatch-1e4ff456bd625be5032fac9be4294e60442e9b71";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/stopwatch/zipball/1e4ff456bd625be5032fac9be4294e60442e9b71;
          sha256 = "1083dhra1cwhym3ja3bxmqvn4qxx0mx224r8pz8r3dch9cig30yy";
        };
      };
    };
    "symfony/translation-contracts" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-translation-contracts-364518c132c95642e530d9b2d217acbc2ccac3e6";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/translation-contracts/zipball/364518c132c95642e530d9b2d217acbc2ccac3e6;
          sha256 = "1xyhh4v79w3n9z4rc74v5nb1fn2gcsava4lnjlqvnbr23rf9s7w3";
        };
      };
    };
    "symfony/twig-bridge" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-twig-bridge-499b3f3aedffa44e4e30b476bbd433854afc9bc3";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/twig-bridge/zipball/499b3f3aedffa44e4e30b476bbd433854afc9bc3;
          sha256 = "03drnfyibqxwlvfh9z2qds4gb54xsab20ymzc6bskyvhr12a6h3x";
        };
      };
    };
    "symfony/twig-bundle" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-twig-bundle-c27738bb0d9b314b96a323aebc5f40a20e2a644b";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/twig-bundle/zipball/c27738bb0d9b314b96a323aebc5f40a20e2a644b;
          sha256 = "09sx30aw56qy6arqxv9q0f4d7a2kxmcw0m7awfzvnki333131i6h";
        };
      };
    };
    "symfony/twig-pack" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-twig-pack-8b278ea4b61fba7c051f172aacae6d87ef4be0e0";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/twig-pack/zipball/8b278ea4b61fba7c051f172aacae6d87ef4be0e0;
          sha256 = "18jax01zdad5kbq2jgqqcwjdcx12n7ylxv238ri3dppf642a7ca6";
        };
      };
    };
    "symfony/var-exporter" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-var-exporter-d5b4e2d334c1d80e42876c7d489896cfd37562f2";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/var-exporter/zipball/d5b4e2d334c1d80e42876c7d489896cfd37562f2;
          sha256 = "17ncrjpb1wf7s07gamsqqqx2pas9b5nw1kafyi0mb4lvscv0ph9m";
        };
      };
    };
    "symfony/webpack-encore-bundle" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-webpack-encore-bundle-10acbc01d5653cdaf6169d9ee7034155fd9b731c";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/webpack-encore-bundle/zipball/10acbc01d5653cdaf6169d9ee7034155fd9b731c;
          sha256 = "0q89m139mg2glrlkx08qgr5a6ij68h6mlj2ryr44351yia9wk55a";
        };
      };
    };
    "symfony/yaml" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-yaml-41e16350a2a1c7383c4735aa2f9fce74cf3d1178";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/yaml/zipball/41e16350a2a1c7383c4735aa2f9fce74cf3d1178;
          sha256 = "0hv0gw36bzxa2n2xjb7v5iq1zfkdigwc001csi2b0lxhqrgnfnzc";
        };
      };
    };
    "twig/extra-bundle" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "twig-extra-bundle-331db0bf52680bf13f35c50b17330323fcca04d9";
        src = fetchurl {
          url = https://api.github.com/repos/twigphp/twig-extra-bundle/zipball/331db0bf52680bf13f35c50b17330323fcca04d9;
          sha256 = "1v3g6ickl4mwkxzfg54n2davqd5ggf3hgxbkgq9qcj8q1iwaj4fr";
        };
      };
    };
    "twig/twig" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "twig-twig-ddd4134af9bfc6dba4eff7c8447444ecc45b9ee5";
        src = fetchurl {
          url = https://api.github.com/repos/twigphp/Twig/zipball/ddd4134af9bfc6dba4eff7c8447444ecc45b9ee5;
          sha256 = "19nnlhx6nizbvraad6ng4bysnb1x3kq61aprbj686adkmn5qrckd";
        };
      };
    };
    "zendframework/zend-code" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "zendframework-zend-code-46feaeecea14161734b56c1ace74f28cb329f194";
        src = fetchurl {
          url = https://api.github.com/repos/zendframework/zend-code/zipball/46feaeecea14161734b56c1ace74f28cb329f194;
          sha256 = "1yg5wy11a83cfs4lvzyjplxah79g6436is8zrm6k757q91jdlmcy";
        };
      };
    };
    "zendframework/zend-eventmanager" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "zendframework-zend-eventmanager-a5e2583a211f73604691586b8406ff7296a946dd";
        src = fetchurl {
          url = https://api.github.com/repos/zendframework/zend-eventmanager/zipball/a5e2583a211f73604691586b8406ff7296a946dd;
          sha256 = "08a05gn40hfdy2zhz4gcd3r6q7m7zcaks5kpvb9dx1awgx0pzr8n";
        };
      };
    };
  };
  devPackages = {
    "nikic/php-parser" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "nikic-php-parser-b76bbc3c51f22c570648de48e8c2d941ed5e2cf2";
        src = fetchurl {
          url = https://api.github.com/repos/nikic/PHP-Parser/zipball/b76bbc3c51f22c570648de48e8c2d941ed5e2cf2;
          sha256 = "08grw7vllbg2h4z15m0nji1r7c7prv1q7hfkiih5lxqj14k2q0cg";
        };
      };
    };
    "roave/security-advisories" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "roave-security-advisories-eb59d9f35a47f567ae15e7179d7c666489cd4b85";
        src = fetchurl {
          url = https://api.github.com/repos/Roave/SecurityAdvisories/zipball/eb59d9f35a47f567ae15e7179d7c666489cd4b85;
          sha256 = "0ncvdiznn1yj7qy4mx3ahaplhfsd655xl3c25c5vl3za01pdv9nv";
        };
      };
    };
    "symfony/maker-bundle" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-maker-bundle-31a7597a99d8315bfa8eb0bfd2247ab880cb0c1b";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/maker-bundle/zipball/31a7597a99d8315bfa8eb0bfd2247ab880cb0c1b;
          sha256 = "0mn4dfvjyd9nqphj5krq38rycvl4gv4fwrb2dlk4ky6lq0s0zg4d";
        };
      };
    };
    "symfony/process" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-process-50556892f3cc47d4200bfd1075314139c4c9ff4b";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/process/zipball/50556892f3cc47d4200bfd1075314139c4c9ff4b;
          sha256 = "0vwnbkkiv8a32gh995aj6f1jdkafq6lpy5baxqg25cqa2w7kmjrd";
        };
      };
    };
    "symfony/web-server-bundle" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-web-server-bundle-dc26b980900ddf3e9feade14e5b21c029e8ca92f";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/web-server-bundle/zipball/dc26b980900ddf3e9feade14e5b21c029e8ca92f;
          sha256 = "0byijdmiibrblm1lmq02flz0m165yfgqgizxkzajh8zqyw0jmla9";
        };
      };
    };
  };
in
composerEnv.buildPackage {
  inherit packages devPackages noDev;
  name = "frosh-packages";
  src = ./.;
  executable = false;
  symlinkDependencies = false;
  meta = {
    license = "mit";
  };

  postInstall = ''
    rm -rf storage ssl var node_modules
    ln -s /var/lib/packages/ssl ssl
    ln -s /var/lib/packages/var var
    '';
}