{composerEnv, fetchurl, fetchgit ? null, fetchhg ? null, fetchsvn ? null, noDev ? false}:

let
  packages = {
    "algolia/algoliasearch-client-php" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "algolia-algoliasearch-client-php-602748161cecbf9d329379a9949d63e1dc683852";
        src = fetchurl {
          url = https://api.github.com/repos/algolia/algoliasearch-client-php/zipball/602748161cecbf9d329379a9949d63e1dc683852;
          sha256 = "0w0r88j6di5zpdx26hvpwb0mxj1sasdah3z9v90j5rm4jxkypmr6";
        };
      };
    };
    "clue/stream-filter" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "clue-stream-filter-5a58cc30a8bd6a4eb8f856adf61dd3e013f53f71";
        src = fetchurl {
          url = https://api.github.com/repos/clue/php-stream-filter/zipball/5a58cc30a8bd6a4eb8f856adf61dd3e013f53f71;
          sha256 = "1n3l5grmw47b2nrnsr8v9jzf9w7g2b6jlh3ax90i350nhklg8vnx";
        };
      };
    };
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
    "guzzlehttp/promises" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "guzzlehttp-promises-a59da6cf61d80060647ff4d3eb2c03a2bc694646";
        src = fetchurl {
          url = https://api.github.com/repos/guzzle/promises/zipball/a59da6cf61d80060647ff4d3eb2c03a2bc694646;
          sha256 = "1kpl91fzalcgkcs16lpakvzcnbkry3id4ynx6xhq477p4fipdciz";
        };
      };
    };
    "guzzlehttp/psr7" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "guzzlehttp-psr7-239400de7a173fe9901b9ac7c06497751f00727a";
        src = fetchurl {
          url = https://api.github.com/repos/guzzle/psr7/zipball/239400de7a173fe9901b9ac7c06497751f00727a;
          sha256 = "0mfq93x7ayix6l3v5jkk40a9hnmrxaqr9vk1r26q39d1s6292ma7";
        };
      };
    };
    "http-interop/http-factory-guzzle" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "http-interop-http-factory-guzzle-34861658efb9899a6618cef03de46e2a52c80fc0";
        src = fetchurl {
          url = https://api.github.com/repos/http-interop/http-factory-guzzle/zipball/34861658efb9899a6618cef03de46e2a52c80fc0;
          sha256 = "0zy3dzrd6b8094ckvd6ax3si8i2b36pz4ww7qqym2cilcf5bdqwl";
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
    "jean85/pretty-package-versions" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "jean85-pretty-package-versions-75c7effcf3f77501d0e0caa75111aff4daa0dd48";
        src = fetchurl {
          url = https://api.github.com/repos/Jean85/pretty-package-versions/zipball/75c7effcf3f77501d0e0caa75111aff4daa0dd48;
          sha256 = "1n9n8a9wkvna4h4iary4cdj5v335h4qxswq8crvw386azp7frxyi";
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
    "php-http/client-common" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "php-http-client-common-2b8aa3c4910afc21146a9c8f96adb266e869517a";
        src = fetchurl {
          url = https://api.github.com/repos/php-http/client-common/zipball/2b8aa3c4910afc21146a9c8f96adb266e869517a;
          sha256 = "12i39s7qnr6bkf86548h8hrvxcc72n9hqd44wfdamh0mliiwfh3w";
        };
      };
    };
    "php-http/curl-client" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "php-http-curl-client-e7a2a5ebcce1ff7d75eaf02b7c85634a6fac00da";
        src = fetchurl {
          url = https://api.github.com/repos/php-http/curl-client/zipball/e7a2a5ebcce1ff7d75eaf02b7c85634a6fac00da;
          sha256 = "1cnafiqcrn48qc4lbav4x28dy8ihbvafcwpnfda1dkcn5x2hcynf";
        };
      };
    };
    "php-http/discovery" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "php-http-discovery-e822f86a6983790aa17ab13aa7e69631e86806b6";
        src = fetchurl {
          url = https://api.github.com/repos/php-http/discovery/zipball/e822f86a6983790aa17ab13aa7e69631e86806b6;
          sha256 = "0nbncj3b7zvw1mcbqghf8c95vayn2sjscdcg1ip458jd57nrdib3";
        };
      };
    };
    "php-http/httplug" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "php-http-httplug-b3842537338c949f2469557ef4ad4bdc47b58603";
        src = fetchurl {
          url = https://api.github.com/repos/php-http/httplug/zipball/b3842537338c949f2469557ef4ad4bdc47b58603;
          sha256 = "0n4cvk4birhkx8y94wqny9ady94bcqmjycx1cyis2c5n4pkqpa2y";
        };
      };
    };
    "php-http/message" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "php-http-message-ce8f43ac1e294b54aabf5808515c3554a19c1e1c";
        src = fetchurl {
          url = https://api.github.com/repos/php-http/message/zipball/ce8f43ac1e294b54aabf5808515c3554a19c1e1c;
          sha256 = "1717jp0a6vkip98vn8pr28zjzwhpp47fn8c0zf0z05qz63rvy1di";
        };
      };
    };
    "php-http/message-factory" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "php-http-message-factory-a478cb11f66a6ac48d8954216cfed9aa06a501a1";
        src = fetchurl {
          url = https://api.github.com/repos/php-http/message-factory/zipball/a478cb11f66a6ac48d8954216cfed9aa06a501a1;
          sha256 = "13drpc83bq332hz0b97whibkm7jpk56msq4yppw9nmrchzwgy7cs";
        };
      };
    };
    "php-http/promise" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "php-http-promise-dc494cdc9d7160b9a09bd5573272195242ce7980";
        src = fetchurl {
          url = https://api.github.com/repos/php-http/promise/zipball/dc494cdc9d7160b9a09bd5573272195242ce7980;
          sha256 = "1vcf3s8mlyrhchyl43piizph3g2zyw5n9qi99mm0in0j2g3xql5l";
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
    "psr/http-client" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "psr-http-client-496a823ef742b632934724bf769560c2a5c7c44e";
        src = fetchurl {
          url = https://api.github.com/repos/php-fig/http-client/zipball/496a823ef742b632934724bf769560c2a5c7c44e;
          sha256 = "1da5f0jax9i738kdrv91mbkp57s3mhw52w3ls0bmwlqwpzim8qiv";
        };
      };
    };
    "psr/http-factory" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "psr-http-factory-12ac7fcd07e5b077433f5f2bee95b3a771bf61be";
        src = fetchurl {
          url = https://api.github.com/repos/php-fig/http-factory/zipball/12ac7fcd07e5b077433f5f2bee95b3a771bf61be;
          sha256 = "0inbnqpc5bfhbbda9dwazsrw9xscfnc8rdx82q1qm3r446mc1vds";
        };
      };
    };
    "psr/http-message" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "psr-http-message-f6561bf28d520154e4b0ec72be95418abe6d9363";
        src = fetchurl {
          url = https://api.github.com/repos/php-fig/http-message/zipball/f6561bf28d520154e4b0ec72be95418abe6d9363;
          sha256 = "195dd67hva9bmr52iadr4kyp2gw2f5l51lplfiay2pv6l9y4cf45";
        };
      };
    };
    "psr/log" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "psr-log-446d54b4cb6bf489fc9d75f55843658e6f25d801";
        src = fetchurl {
          url = https://api.github.com/repos/php-fig/log/zipball/446d54b4cb6bf489fc9d75f55843658e6f25d801;
          sha256 = "04baykaig5nmxsrwmzmcwbs60ixilcx1n0r9wdcnvxnnj64cf2kr";
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
    "ralouphie/getallheaders" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "ralouphie-getallheaders-120b605dfeb996808c31b6477290a714d356e822";
        src = fetchurl {
          url = https://api.github.com/repos/ralouphie/getallheaders/zipball/120b605dfeb996808c31b6477290a714d356e822;
          sha256 = "1bv7ndkkankrqlr2b4kw7qp3fl0dxi6bp26bnim6dnlhavd6a0gg";
        };
      };
    };
    "ramsey/uuid" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "ramsey-uuid-d09ea80159c1929d75b3f9c60504d613aeb4a1e3";
        src = fetchurl {
          url = https://api.github.com/repos/ramsey/uuid/zipball/d09ea80159c1929d75b3f9c60504d613aeb4a1e3;
          sha256 = "1hgnf32xy2cxfwihncmsndnxgkf2hhs6zjqnhyxdhwjyhv4apb67";
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
    "sentry/sdk" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "sentry-sdk-4c115873c86ad5bd0ac6d962db70ca53bf8fb874";
        src = fetchurl {
          url = https://api.github.com/repos/getsentry/sentry-php-sdk/zipball/4c115873c86ad5bd0ac6d962db70ca53bf8fb874;
          sha256 = "0ffm5fr1qp97jbpbk4rpmy30w7fv4yl37b8ibwiffzshgh0smxgi";
        };
      };
    };
    "sentry/sentry" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "sentry-sentry-71abca33b75f6bafb8b3829a620ff9ea5e5c1d9b";
        src = fetchurl {
          url = https://api.github.com/repos/getsentry/sentry-php/zipball/71abca33b75f6bafb8b3829a620ff9ea5e5c1d9b;
          sha256 = "0rkhb407awdkmdpi4yaknmk9wza9swn7zrx7dk0gfhhviapsv51l";
        };
      };
    };
    "sentry/sentry-symfony" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "sentry-sentry-symfony-62d5c9d280044283d898d262bdae4062d02039bc";
        src = fetchurl {
          url = https://api.github.com/repos/getsentry/sentry-symfony/zipball/62d5c9d280044283d898d262bdae4062d02039bc;
          sha256 = "0yjjpknd4f4cihbhdmy9242f4yrgqgx7841ajq3whl8ga46dbl8w";
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
        name = "symfony-cache-30a51b2401ee15bfc7ea98bd7af0f9d80e26e649";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/cache/zipball/30a51b2401ee15bfc7ea98bd7af0f9d80e26e649;
          sha256 = "19a09gvnsck4a37h2cgjlal04l7yqymncn9vfzwrb6cic7yzali4";
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
        name = "symfony-config-f4ee0ebb91b16ca1ac105aa39f9284f3cac19a15";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/config/zipball/f4ee0ebb91b16ca1ac105aa39f9284f3cac19a15;
          sha256 = "17yhmnnsnfpzlz2vcrs8mdlnj0d1csyqwlsxn4qk4qhkc23r29i1";
        };
      };
    };
    "symfony/console" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-console-136c4bd62ea871d00843d1bc0316de4c4a84bb78";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/console/zipball/136c4bd62ea871d00843d1bc0316de4c4a84bb78;
          sha256 = "1qlp32wkn1xyyx4c0lbqdclvv39jzvqba9v4p4j9xab47dbwk9rw";
        };
      };
    };
    "symfony/debug" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-debug-5ea9c3e01989a86ceaa0283f21234b12deadf5e2";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/debug/zipball/5ea9c3e01989a86ceaa0283f21234b12deadf5e2;
          sha256 = "12p9g0yhrjx1bvqx9bfg5i1mn0pgdgwvxkls1h908r6b85kazazr";
        };
      };
    };
    "symfony/dependency-injection" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-dependency-injection-fc036941dfafa037a7485714b62593c7eaf68edd";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/dependency-injection/zipball/fc036941dfafa037a7485714b62593c7eaf68edd;
          sha256 = "1q3s98rr94gig8m5qhkm693v6wxril9c8wbdkg1w4ggqwxs87q13";
        };
      };
    };
    "symfony/doctrine-bridge" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-doctrine-bridge-2c1397c1ec5b0112e78aec8ef8325d449eb414d7";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/doctrine-bridge/zipball/2c1397c1ec5b0112e78aec8ef8325d449eb414d7;
          sha256 = "0c8kddp83ky0nmjax955yghydjanhad7zmfykfr30j7l7yq26qak";
        };
      };
    };
    "symfony/dotenv" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-dotenv-62d93bf07edd0d76f033d65a7fd1c1ce50d28b50";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/dotenv/zipball/62d93bf07edd0d76f033d65a7fd1c1ce50d28b50;
          sha256 = "0h7j7vr5sa9srfyqnc7vgflpr1jm11s2ip8n6dc9spmy78viimx4";
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
        name = "symfony-finder-72a068f77e317ae77c0a0495236ad292cfb5ce6f";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/finder/zipball/72a068f77e317ae77c0a0495236ad292cfb5ce6f;
          sha256 = "15ck61vpgpb73x1f1sicfafnxxvl05f9xl436z2ny7n2dj20jx36";
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
        name = "symfony-framework-bundle-f93b4202207a85822d4ee2cb62e9805e4ea95006";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/framework-bundle/zipball/f93b4202207a85822d4ee2cb62e9805e4ea95006;
          sha256 = "1f937hqwcxqm8bqyilbgilzciabx8fysmpbs408ikbsdxm4ybkw6";
        };
      };
    };
    "symfony/http-client" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-http-client-9aea44c00dee78844f18c345009ef3f0fb3e1d0f";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/http-client/zipball/9aea44c00dee78844f18c345009ef3f0fb3e1d0f;
          sha256 = "0ka54d0ld66yf1hz2j775j6qcki45lnpc20javv9gldskf25af6q";
        };
      };
    };
    "symfony/http-client-contracts" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-http-client-contracts-353b2a3e907e5c34cf8f74827a4b21eb745aab1d";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/http-client-contracts/zipball/353b2a3e907e5c34cf8f74827a4b21eb745aab1d;
          sha256 = "0gbhf0afjmpll6l7fy132iva32b536qmsd99bahiiwb6sqbbhsmy";
        };
      };
    };
    "symfony/http-foundation" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-http-foundation-38f63e471cda9d37ac06e76d14c5ea2ec5887051";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/http-foundation/zipball/38f63e471cda9d37ac06e76d14c5ea2ec5887051;
          sha256 = "0av9wafy4n32hipm2il1avcdpw32fkfpyf23i2xb1plwg4x27q17";
        };
      };
    };
    "symfony/http-kernel" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-http-kernel-56acfda9e734e8715b3b0e6859cdb4f5b28757bf";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/http-kernel/zipball/56acfda9e734e8715b3b0e6859cdb4f5b28757bf;
          sha256 = "0f633h8bmgrw60s46ff7hi6n6misk5c1j8frga28vn43692hqv80";
        };
      };
    };
    "symfony/inflector" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-inflector-fc488a52c79b2bbe848fa9def35f2cccb47c4798";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/inflector/zipball/fc488a52c79b2bbe848fa9def35f2cccb47c4798;
          sha256 = "1l8cpwqzrj0wp1j5xydryj0f29iv585dh55fl0a5s3hfdvfgr9lc";
        };
      };
    };
    "symfony/mime" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-mime-3c0e197529da6e59b217615ba8ee7604df88b551";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/mime/zipball/3c0e197529da6e59b217615ba8ee7604df88b551;
          sha256 = "13pxygsk6wppd6d80kdwbczbzf0zpcgp29harlvrzc4jkg87j77w";
        };
      };
    };
    "symfony/options-resolver" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-options-resolver-f46c7fc8e207bd8a2188f54f8738f232533765a4";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/options-resolver/zipball/f46c7fc8e207bd8a2188f54f8738f232533765a4;
          sha256 = "1pbrif4hba8ma8m3hfz7wq5aprssx33apxih0l0nz7xn9x5hb3gp";
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
    "symfony/profiler-pack" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-profiler-pack-99c4370632c2a59bb0444852f92140074ef02209";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/profiler-pack/zipball/99c4370632c2a59bb0444852f92140074ef02209;
          sha256 = "12xisnrqq6q5l0v8bric0p23bsaxh50x43fq7wn2adnsz24nv9pi";
        };
      };
    };
    "symfony/property-access" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-property-access-bb0c302375ffeef60c31e72a4539611b7f787565";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/property-access/zipball/bb0c302375ffeef60c31e72a4539611b7f787565;
          sha256 = "1zxha9dz9vhldnmgn1pjq9kcdw6kahcschp4yl9zignwlrj4lsnj";
        };
      };
    };
    "symfony/routing" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-routing-63a9920cc86fcc745e5ea254e362f02b615290b9";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/routing/zipball/63a9920cc86fcc745e5ea254e362f02b615290b9;
          sha256 = "1qaqs7b2fngj655jpplmjz4sjirnd0alccnz6m3pmbb8i9z4hnvp";
        };
      };
    };
    "symfony/security-bundle" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-security-bundle-d295626ee5294dde825efa4ef5be5c65a37f21b3";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/security-bundle/zipball/d295626ee5294dde825efa4ef5be5c65a37f21b3;
          sha256 = "125x5lkj0jp45ryaw7l5v9arsrz8bgpw7czj5mxfw8phnmcx3fy3";
        };
      };
    };
    "symfony/security-core" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-security-core-8c46ea77fe0738f2495eacc08fa34e1e19ff0b0d";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/security-core/zipball/8c46ea77fe0738f2495eacc08fa34e1e19ff0b0d;
          sha256 = "0qn0hpaanvl2vpf3rj4bsardjrr5b3zv1v5752gv4r87nzki3xaf";
        };
      };
    };
    "symfony/security-csrf" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-security-csrf-0760ec651ea8ff81e22097780337e43f3a795769";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/security-csrf/zipball/0760ec651ea8ff81e22097780337e43f3a795769;
          sha256 = "01w17n699mjf4wpxf1pbx8yjlmizyqszdbaj3gk94s2ddrq9g08a";
        };
      };
    };
    "symfony/security-guard" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-security-guard-62cc82a384f2c1c75c58189fcf713032f6fef1e9";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/security-guard/zipball/62cc82a384f2c1c75c58189fcf713032f6fef1e9;
          sha256 = "0xsy4xpmdy5hxdqwjgsza5klqh1xv86xznsdwkfyrmihr3lxcp72";
        };
      };
    };
    "symfony/security-http" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-security-http-a2f67dfe0ecfb713734847f4ada0f4231e28ae71";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/security-http/zipball/a2f67dfe0ecfb713734847f4ada0f4231e28ae71;
          sha256 = "1bf18wsf1dq32jfl5ij8ngjwam7gvdki1fd5qm8gw9l95vfabxza";
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
        name = "symfony-twig-bridge-67fdb93de3361bcf1ab02bd8275af8c790bae900";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/twig-bridge/zipball/67fdb93de3361bcf1ab02bd8275af8c790bae900;
          sha256 = "01npalwjbphalb5n9z0b9bqmg8lsf0r2z4z8hjpxix4kqgcsiji3";
        };
      };
    };
    "symfony/twig-bundle" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-twig-bundle-869ebf144acafd19fb9c8c386808c26624f28572";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/twig-bundle/zipball/869ebf144acafd19fb9c8c386808c26624f28572;
          sha256 = "13gwkmkacbm4b5h94vl801m0wpzfn09r28yzpj6lbhmcx6ngg8a6";
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
    "symfony/var-dumper" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-var-dumper-ea4940845535c85ff5c505e13b3205b0076d07bf";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/var-dumper/zipball/ea4940845535c85ff5c505e13b3205b0076d07bf;
          sha256 = "13y555yn62226kx10f74glcwc4b74fpps8gn9gaygv3c6i2skk6f";
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
    "symfony/web-profiler-bundle" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-web-profiler-bundle-6ce12ffe97d9e26091b0e7340a9d661fba64655e";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/web-profiler-bundle/zipball/6ce12ffe97d9e26091b0e7340a9d661fba64655e;
          sha256 = "15k7is5dmdk1x5g12j3ziygkrrxz44gdak0a8adq6bcb242cr0hi";
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
        name = "symfony-yaml-324cf4b19c345465fad14f3602050519e09e361d";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/yaml/zipball/324cf4b19c345465fad14f3602050519e09e361d;
          sha256 = "0c5xs3pk25chj5sp22h01j26fg9mvvgxsgrvppl8splsk59dvsyb";
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
    "zendframework/zend-diactoros" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "zendframework-zend-diactoros-6dcf9e760a6b476f3e9d80abbc9ce9c4aa921f9c";
        src = fetchurl {
          url = https://api.github.com/repos/zendframework/zend-diactoros/zipball/6dcf9e760a6b476f3e9d80abbc9ce9c4aa921f9c;
          sha256 = "0xjpb0f3cb02sx93s66542b4jv4xjr8hqc8jg861l3dkj81n72k0";
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
    "composer/semver" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "composer-semver-46d9139568ccb8d9e7cdd4539cab7347568a5e2e";
        src = fetchurl {
          url = https://api.github.com/repos/composer/semver/zipball/46d9139568ccb8d9e7cdd4539cab7347568a5e2e;
          sha256 = "11nq81abq684v12xfv6xg2y6h8fhyn76s50hvacs51sqqs926i0d";
        };
      };
    };
    "composer/xdebug-handler" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "composer-xdebug-handler-46867cbf8ca9fb8d60c506895449eb799db1184f";
        src = fetchurl {
          url = https://api.github.com/repos/composer/xdebug-handler/zipball/46867cbf8ca9fb8d60c506895449eb799db1184f;
          sha256 = "0y4axhr65ygd2a619xrbfd3yr02jxnazf3clfxrzd63m1jwc5mmx";
        };
      };
    };
    "friendsofphp/php-cs-fixer" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "friendsofphp-php-cs-fixer-705490b0f282f21017d73561e9498d2b622ee34c";
        src = fetchurl {
          url = https://api.github.com/repos/FriendsOfPHP/PHP-CS-Fixer/zipball/705490b0f282f21017d73561e9498d2b622ee34c;
          sha256 = "0jwanz5qi2fza8gzl2pcbhk89m12hbdwr0hn675bxchfjk2vs7yy";
        };
      };
    };
    "nette/bootstrap" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "nette-bootstrap-b45a1e33b6a44beb307756522396551e5a9ff249";
        src = fetchurl {
          url = https://api.github.com/repos/nette/bootstrap/zipball/b45a1e33b6a44beb307756522396551e5a9ff249;
          sha256 = "1ii00pzx757fffdxyfghpsj67gwbla74y1jq8bgjcc2x0la5484r";
        };
      };
    };
    "nette/di" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "nette-di-4aff517a1c6bb5c36fa09733d4cea089f529de6d";
        src = fetchurl {
          url = https://api.github.com/repos/nette/di/zipball/4aff517a1c6bb5c36fa09733d4cea089f529de6d;
          sha256 = "1bwh3sl3hfvghy84709839d55wzk6zw1zm38h3zp69hjpixfnkms";
        };
      };
    };
    "nette/finder" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "nette-finder-14164e1ddd69e9c5f627ff82a10874b3f5bba5fe";
        src = fetchurl {
          url = https://api.github.com/repos/nette/finder/zipball/14164e1ddd69e9c5f627ff82a10874b3f5bba5fe;
          sha256 = "1cwmqhf0ph645mqnl88kv3w3x9l9ds7ay1hqirj5ihnq2rdphnm2";
        };
      };
    };
    "nette/neon" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "nette-neon-cbff32059cbdd8720deccf9e9eace6ee516f02eb";
        src = fetchurl {
          url = https://api.github.com/repos/nette/neon/zipball/cbff32059cbdd8720deccf9e9eace6ee516f02eb;
          sha256 = "1fvz3bfg3v6gs9sb8cgqxqsmnzjsb66nd16wf854lh2svxj4zk7m";
        };
      };
    };
    "nette/php-generator" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "nette-php-generator-aea6e81437bb238e5f0e5b5ce06337433908e63b";
        src = fetchurl {
          url = https://api.github.com/repos/nette/php-generator/zipball/aea6e81437bb238e5f0e5b5ce06337433908e63b;
          sha256 = "0d2fvyh2xjjn8s5fb9h09jibf5v22q8myc7fh9zyib9z8fxjp0l2";
        };
      };
    };
    "nette/robot-loader" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "nette-robot-loader-0712a0e39ae7956d6a94c0ab6ad41aa842544b5c";
        src = fetchurl {
          url = https://api.github.com/repos/nette/robot-loader/zipball/0712a0e39ae7956d6a94c0ab6ad41aa842544b5c;
          sha256 = "12xyrdrfvckf08zgp68s55p9vcj4wcsnmc25naxak43gamswg5f3";
        };
      };
    };
    "nette/schema" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "nette-schema-337117df1dade22e2ba1fdc4a4b832c1e9b06b76";
        src = fetchurl {
          url = https://api.github.com/repos/nette/schema/zipball/337117df1dade22e2ba1fdc4a4b832c1e9b06b76;
          sha256 = "1cyjhw7jdzzh04ralk3li1z04ni3hgrnrf91gxbr2igq16df4hrm";
        };
      };
    };
    "nette/utils" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "nette-utils-c133e18c922dcf3ad07673077d92d92cef25a148";
        src = fetchurl {
          url = https://api.github.com/repos/nette/utils/zipball/c133e18c922dcf3ad07673077d92d92cef25a148;
          sha256 = "0405fwadr4s4118cwnl7vh69wh8nxjvdvvq6rhh33m1i4a7pjyhb";
        };
      };
    };
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
    "php-cs-fixer/diff" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "php-cs-fixer-diff-78bb099e9c16361126c86ce82ec4405ebab8e756";
        src = fetchurl {
          url = https://api.github.com/repos/PHP-CS-Fixer/diff/zipball/78bb099e9c16361126c86ce82ec4405ebab8e756;
          sha256 = "082w79q2bipw5iibpw6whihnz2zafljh5bgpfs4qdxmz25n8g00l";
        };
      };
    };
    "phpstan/extension-installer" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "phpstan-extension-installer-295656793c53b5eb73a38486032ad1bd650264bc";
        src = fetchurl {
          url = https://api.github.com/repos/phpstan/extension-installer/zipball/295656793c53b5eb73a38486032ad1bd650264bc;
          sha256 = "11s1vm0wp2chw240fzq2n1080hbcqklbh1b9ikimchkiys75hnm9";
        };
      };
    };
    "phpstan/phpdoc-parser" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "phpstan-phpdoc-parser-8c4ef2aefd9788238897b678a985e1d5c8df6db4";
        src = fetchurl {
          url = https://api.github.com/repos/phpstan/phpdoc-parser/zipball/8c4ef2aefd9788238897b678a985e1d5c8df6db4;
          sha256 = "1j4pkaxv1psh7ff36nv1prmj02idm7p96zi5rg0qng0d38pplr9d";
        };
      };
    };
    "phpstan/phpstan" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "phpstan-phpstan-63cc502f6957b7f74efbac444b4cf219dcadffd7";
        src = fetchurl {
          url = https://api.github.com/repos/phpstan/phpstan/zipball/63cc502f6957b7f74efbac444b4cf219dcadffd7;
          sha256 = "1cc20kgdnj6fn6yrkarw0rnjnqamsyqh870rs5rcxaa1axd9k5j8";
        };
      };
    };
    "phpstan/phpstan-doctrine" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "phpstan-phpstan-doctrine-77592865e167b32c7dcb4f39a35210e909a8854c";
        src = fetchurl {
          url = https://api.github.com/repos/phpstan/phpstan-doctrine/zipball/77592865e167b32c7dcb4f39a35210e909a8854c;
          sha256 = "1gmxfwwh9lw0m9s0bmglq0q589abhjh4jn2wz39zwv8pry29xm92";
        };
      };
    };
    "roave/security-advisories" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "roave-security-advisories-f8c8349a4b12a26edfa8b21d07d3dbeb6dcedcfa";
        src = fetchurl {
          url = https://api.github.com/repos/Roave/SecurityAdvisories/zipball/f8c8349a4b12a26edfa8b21d07d3dbeb6dcedcfa;
          sha256 = "007b18vcjg57jx3a0zv1vhbsvwj8p2f9rdwyrfgvfv4v32d14i8j";
        };
      };
    };
    "symfony/maker-bundle" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-maker-bundle-f7dd84691dbf399137a5caafc90f419801d09e5f";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/maker-bundle/zipball/f7dd84691dbf399137a5caafc90f419801d09e5f;
          sha256 = "0vh97wnk9yqfjl34ipjgqwv9dwsl1sy82j2792sglp9sjk3cnbyd";
        };
      };
    };
    "symfony/process" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-process-3b2e0cb029afbb0395034509291f21191d1a4db0";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/process/zipball/3b2e0cb029afbb0395034509291f21191d1a4db0;
          sha256 = "1mkqayyafnnx521kdgvhc6wnq10vb1n30vf7899wdrjj4v3ndg2j";
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
    rm -rf storage ssl var node_modules .env
    ln -s /var/lib/packages/ssl ssl
    ln -s /var/lib/packages/var var
    '';
}
