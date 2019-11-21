{composerEnv, fetchurl, fetchgit ? null, fetchhg ? null, fetchsvn ? null, noDev ? false}:

let
  packages = {
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
        name = "doctrine-cache-89a5c76c39c292f7798f964ab3c836c3f8192a55";
        src = fetchurl {
          url = https://api.github.com/repos/doctrine/cache/zipball/89a5c76c39c292f7798f964ab3c836c3f8192a55;
          sha256 = "017n5cim226iyq44vzcj5spl4b2makzy9dnmi4xjk43bzvaw77by";
        };
      };
    };
    "doctrine/collections" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "doctrine-collections-6b1e4b2b66f6d6e49983cebfe23a21b7ccc5b0d7";
        src = fetchurl {
          url = https://api.github.com/repos/doctrine/collections/zipball/6b1e4b2b66f6d6e49983cebfe23a21b7ccc5b0d7;
          sha256 = "1q8zrday7mdsncss6mqnp73j71603q48jx0ynwi2w7ca80bk5rdy";
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
        name = "doctrine-dbal-0c9a646775ef549eb0a213a4f9bd4381d9b4d934";
        src = fetchurl {
          url = https://api.github.com/repos/doctrine/dbal/zipball/0c9a646775ef549eb0a213a4f9bd4381d9b4d934;
          sha256 = "1m94d4c2xi1pdl0rzn5rp8ckf4q5cxhfp7jg1911cm92z8icc7qp";
        };
      };
    };
    "doctrine/doctrine-bundle" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "doctrine-doctrine-bundle-82826278bb88ae8c20aee3af3191430dcbcca63a";
        src = fetchurl {
          url = https://api.github.com/repos/doctrine/DoctrineBundle/zipball/82826278bb88ae8c20aee3af3191430dcbcca63a;
          sha256 = "0x1mnvlkz9ch8vrwfmkasr60s4q7avdhw3pbxmkqin2gy72pkil9";
        };
      };
    };
    "doctrine/doctrine-migrations-bundle" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "doctrine-doctrine-migrations-bundle-856437e8de96a70233e1f0cc2352fc8dd15a899d";
        src = fetchurl {
          url = https://api.github.com/repos/doctrine/DoctrineMigrationsBundle/zipball/856437e8de96a70233e1f0cc2352fc8dd15a899d;
          sha256 = "1dvhjjwhcx29zmsjgzs5kdgr8bzkxhc7qnink4sn0jxwf7gx2mc4";
        };
      };
    };
    "doctrine/event-manager" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "doctrine-event-manager-629572819973f13486371cb611386eb17851e85c";
        src = fetchurl {
          url = https://api.github.com/repos/doctrine/event-manager/zipball/629572819973f13486371cb611386eb17851e85c;
          sha256 = "02zglsk2zfnpabs83an7zg18h2k31h00vzk6qpawvmy35r1vmrfn";
        };
      };
    };
    "doctrine/inflector" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "doctrine-inflector-ec3a55242203ffa6a4b27c58176da97ff0a7aec1";
        src = fetchurl {
          url = https://api.github.com/repos/doctrine/inflector/zipball/ec3a55242203ffa6a4b27c58176da97ff0a7aec1;
          sha256 = "18i6zyd5bh5zazgqr3c9bwi7s5vhm9wpnn2hd8vp8vgdp9x7f4hb";
        };
      };
    };
    "doctrine/instantiator" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "doctrine-instantiator-ae466f726242e637cebdd526a7d991b9433bacf1";
        src = fetchurl {
          url = https://api.github.com/repos/doctrine/instantiator/zipball/ae466f726242e637cebdd526a7d991b9433bacf1;
          sha256 = "1dzx7ql2qjkk902g02salvz0yarf1a17q514l3y6rqg53i3rmxp7";
        };
      };
    };
    "doctrine/lexer" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "doctrine-lexer-5242d66dbeb21a30dd8a3e66bf7a73b66e05e1f6";
        src = fetchurl {
          url = https://api.github.com/repos/doctrine/lexer/zipball/5242d66dbeb21a30dd8a3e66bf7a73b66e05e1f6;
          sha256 = "1sxadyv4b6i75v46dzc7jqqbshwx9smyj5j2gwg5j6jf2h6l10hm";
        };
      };
    };
    "doctrine/migrations" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "doctrine-migrations-8e124252d2f6be1124017d746d5994dd4095d66f";
        src = fetchurl {
          url = https://api.github.com/repos/doctrine/migrations/zipball/8e124252d2f6be1124017d746d5994dd4095d66f;
          sha256 = "1hz9nbpl5cvxai4x3l5q9ky5509jrvhvysriwxazg64gkw6razg3";
        };
      };
    };
    "doctrine/orm" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "doctrine-orm-4d763ca4c925f647b248b9fa01b5f47aa3685d62";
        src = fetchurl {
          url = https://api.github.com/repos/doctrine/orm/zipball/4d763ca4c925f647b248b9fa01b5f47aa3685d62;
          sha256 = "1i583lm2gb0ifdb5y7yl3vw51kdscyi4gvb0jy8whgqayrh82nvz";
        };
      };
    };
    "doctrine/persistence" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "doctrine-persistence-43526ae63312942e5316100bb3ed589ba1aba491";
        src = fetchurl {
          url = https://api.github.com/repos/doctrine/persistence/zipball/43526ae63312942e5316100bb3ed589ba1aba491;
          sha256 = "14zgxfaz0xm743zaaw5p00z8wz9g79apspi2h6zp3189hd05gryg";
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
    "elasticsearch/elasticsearch" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "elasticsearch-elasticsearch-830a6f6fdb4a6b7005ff914fe761e1a92ebb5cb8";
        src = fetchurl {
          url = https://api.github.com/repos/elastic/elasticsearch-php/zipball/830a6f6fdb4a6b7005ff914fe761e1a92ebb5cb8;
          sha256 = "1z49j3px8k4qiky1hznwkk3i7kp03ak34iwcbqjhr0s4sn2xl0vd";
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
    "guzzlehttp/ringphp" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "guzzlehttp-ringphp-5e2a174052995663dd68e6b5ad838afd47dd615b";
        src = fetchurl {
          url = https://api.github.com/repos/guzzle/RingPHP/zipball/5e2a174052995663dd68e6b5ad838afd47dd615b;
          sha256 = "09n1znwxawmsidyq6zk94mg85hibsg8kxm1j0bi795pa55fiqzj9";
        };
      };
    };
    "guzzlehttp/streams" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "guzzlehttp-streams-47aaa48e27dae43d39fc1cea0ccf0d84ac1a2ba5";
        src = fetchurl {
          url = https://api.github.com/repos/guzzle/streams/zipball/47aaa48e27dae43d39fc1cea0ccf0d84ac1a2ba5;
          sha256 = "1ax2b61l31vsx5814iak7l35rmh9yk0rbps5gndrkwlf27ciq9jy";
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
    "ongr/elasticsearch-dsl" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "ongr-elasticsearch-dsl-b86153ce7a4192ff7caf8d93f59c2e7c151cba1f";
        src = fetchurl {
          url = https://api.github.com/repos/ongr-io/ElasticsearchDSL/zipball/b86153ce7a4192ff7caf8d93f59c2e7c151cba1f;
          sha256 = "0grxpdcdlkymlrrgpbixzh2kva93j58shwarjim8rkrcqj00q4zn";
        };
      };
    };
    "php-http/client-common" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "php-http-client-common-a8b29678d61556f45d6236b1667db16d998ceec5";
        src = fetchurl {
          url = https://api.github.com/repos/php-http/client-common/zipball/a8b29678d61556f45d6236b1667db16d998ceec5;
          sha256 = "15x5zsnxi28wrpdsxahrhybd65lv2kgy5hb9swdy5s7is5dfbrds";
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
    "react/promise" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "react-promise-31ffa96f8d2ed0341a57848cbb84d88b89dd664d";
        src = fetchurl {
          url = https://api.github.com/repos/reactphp/promise/zipball/31ffa96f8d2ed0341a57848cbb84d88b89dd664d;
          sha256 = "12pz35wc1zy4djkigqikg74fxi88s46fk22hzp5qkyjy829bbj42";
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
        name = "sentry-sentry-a74999536b9119257cb1a4b1aa038e4a08439f67";
        src = fetchurl {
          url = https://api.github.com/repos/getsentry/sentry-php/zipball/a74999536b9119257cb1a4b1aa038e4a08439f67;
          sha256 = "0cgww0fiykw4g3q59pzm1b9l1y3apyghqj1kyilp8bjnyz0gr9wx";
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
        name = "symfony-asset-7ec5fc653dab63d7519a6f411982ee224a696d66";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/asset/zipball/7ec5fc653dab63d7519a6f411982ee224a696d66;
          sha256 = "08v9miw0jqsap8kaqxg9gcapzbx1qy5vlwz42m27bhh5h2bzqz92";
        };
      };
    };
    "symfony/cache" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-cache-72d5cdc6920f889290beb65fa96b5e9d4515e382";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/cache/zipball/72d5cdc6920f889290beb65fa96b5e9d4515e382;
          sha256 = "1wxyqbqrvqgayvzw72qbz5gbsf6b1yj5s54gkir3xwg2dlzs0qlq";
        };
      };
    };
    "symfony/cache-contracts" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-cache-contracts-a91281de82119a7a07481b892f709d88da592cd3";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/cache-contracts/zipball/a91281de82119a7a07481b892f709d88da592cd3;
          sha256 = "0916fgmf9w62qrr6cldw9mv4qxmblk9hqjc1n0h9w550x9iv50dq";
        };
      };
    };
    "symfony/config" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-config-f08e1c48e1f05d07c32f2d8599ed539e62105beb";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/config/zipball/f08e1c48e1f05d07c32f2d8599ed539e62105beb;
          sha256 = "0mamyy4g5q3pssrn012wwc0sirjkwk36lvwvvk5f0nssyc240z7s";
        };
      };
    };
    "symfony/console" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-console-35d9077f495c6d184d9930f7a7ecbd1ad13c7ab8";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/console/zipball/35d9077f495c6d184d9930f7a7ecbd1ad13c7ab8;
          sha256 = "13zj2gpll8smnxxsjpjxyk3k6gwp16pq98svs65h88h4qj8y1d43";
        };
      };
    };
    "symfony/debug" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-debug-b24b791f817116b29e52a63e8544884cf9a40757";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/debug/zipball/b24b791f817116b29e52a63e8544884cf9a40757;
          sha256 = "0ngzhc0wqh853rmkbd13339bi09rcnjpj40fcl3wynb7xswwj34j";
        };
      };
    };
    "symfony/dependency-injection" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-dependency-injection-d4439814135ed1343c93bde998b7792af8852e41";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/dependency-injection/zipball/d4439814135ed1343c93bde998b7792af8852e41;
          sha256 = "0r60hgn429iba2s8dszcc4gc1xgsw0a9v2g3xjvq3w1rgln1fi2j";
        };
      };
    };
    "symfony/doctrine-bridge" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-doctrine-bridge-cfbd5233162a5d01060ae696e7c8e52f91e6028b";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/doctrine-bridge/zipball/cfbd5233162a5d01060ae696e7c8e52f91e6028b;
          sha256 = "1ccrf68haqhfinmwman038yf4s3q5jkh74rzxvkgi9wq4nk1nqbp";
        };
      };
    };
    "symfony/dotenv" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-dotenv-1a9cad0daa3d0726124d7bec2419591ed0f03bc3";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/dotenv/zipball/1a9cad0daa3d0726124d7bec2419591ed0f03bc3;
          sha256 = "1gw53rza8vj8dz0140djdq6gkxmnkkdh5m95y9lrcnpaqjhk2hl1";
        };
      };
    };
    "symfony/error-handler" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-error-handler-e1acb58dc6a8722617fe56565f742bcf7e8744bf";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/error-handler/zipball/e1acb58dc6a8722617fe56565f742bcf7e8744bf;
          sha256 = "0mfk58j10qs5xppw4jc2qgpinx99z2jbny3rgq0i13b38adrrd8h";
        };
      };
    };
    "symfony/event-dispatcher" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-event-dispatcher-ab1c43e17fff802bef0a898f3bc088ac33b8e0e1";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/event-dispatcher/zipball/ab1c43e17fff802bef0a898f3bc088ac33b8e0e1;
          sha256 = "04kl6wjim3rqsk60x970gqg9nvi5rnxlq13z6n9vipj2zv1w9wps";
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
        name = "symfony-filesystem-d12b01cba60be77b583c9af660007211e3909854";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/filesystem/zipball/d12b01cba60be77b583c9af660007211e3909854;
          sha256 = "08414vzy7amyr3x2daxh9fviga6wgz81w7yv60gw33a1135l6q44";
        };
      };
    };
    "symfony/finder" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-finder-ce8743441da64c41e2a667b8eb66070444ed911e";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/finder/zipball/ce8743441da64c41e2a667b8eb66070444ed911e;
          sha256 = "0s17v8jxznjccjlmmbrdk1qv9w0974102v0bbgp204rz2l228frn";
        };
      };
    };
    "symfony/flex" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-flex-f5bfc79c1f5bed6b2bb4ca9e49a736c2abc03e8f";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/flex/zipball/f5bfc79c1f5bed6b2bb4ca9e49a736c2abc03e8f;
          sha256 = "1hr4hb4p647jpdk8nn0dkxvzj5zh0pv08543976bvs0xw633h4xz";
        };
      };
    };
    "symfony/framework-bundle" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-framework-bundle-dbb892fce45acb9c2191147db674ab8a78a3cc98";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/framework-bundle/zipball/dbb892fce45acb9c2191147db674ab8a78a3cc98;
          sha256 = "1f5kswx7fgw2rm31j9v2fx0m6301yyl7s9hfgphnk50f7hqhcj94";
        };
      };
    };
    "symfony/http-client" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-http-client-233f73babb157deac7289119aa3d0e871bac8def";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/http-client/zipball/233f73babb157deac7289119aa3d0e871bac8def;
          sha256 = "0km4qzxzk11hf5w2wl6dlr1avk03a0j4whbphd15jvm2za5cmw7q";
        };
      };
    };
    "symfony/http-client-contracts" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-http-client-contracts-f1aa62591e44a737d4589a0913ac49b84313c19d";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/http-client-contracts/zipball/f1aa62591e44a737d4589a0913ac49b84313c19d;
          sha256 = "1qrrbnbbh8wklap5va8yh5kbc4n23409rvg59s81h8kwyinhlbif";
        };
      };
    };
    "symfony/http-foundation" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-http-foundation-502040dd2b0cf0a292defeb6145f4d7a4753c99c";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/http-foundation/zipball/502040dd2b0cf0a292defeb6145f4d7a4753c99c;
          sha256 = "081sk8ps4rdjlq158r1bf9jhyiwc5hkhyk300ssj3a31h2lwblai";
        };
      };
    };
    "symfony/http-kernel" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-http-kernel-5a5e7237d928aa98ff8952050cbbf0135899b6b0";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/http-kernel/zipball/5a5e7237d928aa98ff8952050cbbf0135899b6b0;
          sha256 = "17c7pm4v2baqbzg63xcj68v71z7wdss7ksrzj7hcpl627i1gkljp";
        };
      };
    };
    "symfony/inflector" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-inflector-98581481d9ddabe4db3a66e10202fe1fa08d791b";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/inflector/zipball/98581481d9ddabe4db3a66e10202fe1fa08d791b;
          sha256 = "0nfy2rva6gnh9a71x81b67382ibi88dpy93p85bbfgkwgpzv99ja";
        };
      };
    };
    "symfony/mime" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-mime-89da7b68b7149aab065c09b97f938753ab52831f";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/mime/zipball/89da7b68b7149aab065c09b97f938753ab52831f;
          sha256 = "0ssh031vyp009f2533zlcrps631hcd73p0zbzprcs5p9nc7hw68b";
        };
      };
    };
    "symfony/options-resolver" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-options-resolver-2be23e63f33de16b49294ea6581f462932a77e2f";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/options-resolver/zipball/2be23e63f33de16b49294ea6581f462932a77e2f;
          sha256 = "0g33afhwhaz2jxvinpmr5c461g0z9iax6wdg7fw8cc1s9l8akz5c";
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
        name = "symfony-property-access-4df120cbe473d850eb59f75c341915955e45f25b";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/property-access/zipball/4df120cbe473d850eb59f75c341915955e45f25b;
          sha256 = "1n1249y6k84zyqkhq0jpd48pzrisq9vf11l1z6iqf21jza1ihr8q";
        };
      };
    };
    "symfony/routing" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-routing-cf6d72cf0348775f5243b8389169a7096221ea40";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/routing/zipball/cf6d72cf0348775f5243b8389169a7096221ea40;
          sha256 = "1rdyxpkznvlb315y3rid3shcibwx6yjxrwkmys8i8gkji2zp7j0p";
        };
      };
    };
    "symfony/security-bundle" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-security-bundle-c9a867d69dd77be33ff0c008a43aa6dc90ec91a5";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/security-bundle/zipball/c9a867d69dd77be33ff0c008a43aa6dc90ec91a5;
          sha256 = "05pl6h18s4gjkn7vpp49gkq7148356prl4cn5cmxl5sk0ajz8c3w";
        };
      };
    };
    "symfony/security-core" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-security-core-312c91f90786fd7add89e8542cfc98543f0e60db";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/security-core/zipball/312c91f90786fd7add89e8542cfc98543f0e60db;
          sha256 = "09z32fx150xyymlcwjyfybxkljki15l8yday8gynfawfxsn9pa23";
        };
      };
    };
    "symfony/security-csrf" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-security-csrf-aeed1a2315019b5a090f5ad34c01f1935ea9b757";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/security-csrf/zipball/aeed1a2315019b5a090f5ad34c01f1935ea9b757;
          sha256 = "1jj0ks75bfim1ydbzh7ajfjswff8ndp1a3mf50vgq79r9wfngy34";
        };
      };
    };
    "symfony/security-guard" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-security-guard-367e7d49648113279baddceb296ffd90c621414a";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/security-guard/zipball/367e7d49648113279baddceb296ffd90c621414a;
          sha256 = "07r8058m3p720hms1v45ngavzk35vh9sc9f8mddz1cjxnjpywxws";
        };
      };
    };
    "symfony/security-http" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-security-http-e49361b75e9acbc029b35ae4ba957e712137286b";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/security-http/zipball/e49361b75e9acbc029b35ae4ba957e712137286b;
          sha256 = "053wf8l89kb2k8pf3i0rw6amaayw3iba7g8a7f4w7msr2gxryvpl";
        };
      };
    };
    "symfony/serializer" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-serializer-57116a962eb0c5e165009535f1e1d2e7024e78de";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/serializer/zipball/57116a962eb0c5e165009535f1e1d2e7024e78de;
          sha256 = "18r2h60r6xmvg40030iqwqlgl15d548y85pk9bc60lb1jv8wcwv2";
        };
      };
    };
    "symfony/service-contracts" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-service-contracts-9d99e1556417bf227a62e14856d630672bf10eaf";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/service-contracts/zipball/9d99e1556417bf227a62e14856d630672bf10eaf;
          sha256 = "0rzxycshayxzin62yggyrfwqqgi1q3xf3hrcf2286psnfasjy926";
        };
      };
    };
    "symfony/stopwatch" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-stopwatch-5745b514fc56ae1907c6b8ed74f94f90f64694e9";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/stopwatch/zipball/5745b514fc56ae1907c6b8ed74f94f90f64694e9;
          sha256 = "0w07vgjrwl2v6c68h38rdgryds33l2r89lz1pxffls4f4kwcgcfy";
        };
      };
    };
    "symfony/translation-contracts" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-translation-contracts-8feb81e6bb1a42d6a3b1429c751d291eb6d05297";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/translation-contracts/zipball/8feb81e6bb1a42d6a3b1429c751d291eb6d05297;
          sha256 = "0sp0gjc17aw7zdrmbs68lhz0nzwnd3d6rls9g5pnm16ihijpjprp";
        };
      };
    };
    "symfony/twig-bridge" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-twig-bridge-5f0baa30985e8423ac7927170d23504a018ce83d";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/twig-bridge/zipball/5f0baa30985e8423ac7927170d23504a018ce83d;
          sha256 = "09b9xy04rlv0f5lrd7wid9hqk51b17pcsg69m40nvc4np2bvksv6";
        };
      };
    };
    "symfony/twig-bundle" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-twig-bundle-9a4a8ddbb36598ed0b1f4cc80be867456409bc01";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/twig-bundle/zipball/9a4a8ddbb36598ed0b1f4cc80be867456409bc01;
          sha256 = "16p2cp6alk5bswlcjfv62j26d6hx2qx07nys0la8ayapg7p2336c";
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
        name = "symfony-var-dumper-eade2890f8b0eeb279b6cf41b50a10007294490f";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/var-dumper/zipball/eade2890f8b0eeb279b6cf41b50a10007294490f;
          sha256 = "011xgjvmcklhq5wd5w6j7yyzbzalvhxmf43a0nqvkm99bqr6xd6q";
        };
      };
    };
    "symfony/var-exporter" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-var-exporter-72feb69a33def8f761e612360588e40bac98caad";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/var-exporter/zipball/72feb69a33def8f761e612360588e40bac98caad;
          sha256 = "04k1jwrmafpf4fh16y1ncalypyhhlmisx92kg9w08i9q31n3i3k9";
        };
      };
    };
    "symfony/web-profiler-bundle" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-web-profiler-bundle-92453ec17c365c561d9e65b06050b9e2a65e9306";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/web-profiler-bundle/zipball/92453ec17c365c561d9e65b06050b9e2a65e9306;
          sha256 = "1k70xwg0g6fbicqaswq0j6zr1wwjpz9gygkfmiqd61p4y1fiqhlc";
        };
      };
    };
    "symfony/webpack-encore-bundle" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-webpack-encore-bundle-8a5ba96bbec60bf04e2a70e45d2e953c94c4edc5";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/webpack-encore-bundle/zipball/8a5ba96bbec60bf04e2a70e45d2e953c94c4edc5;
          sha256 = "09lh55caxgav4a38xw5sg70gv1w4p57slgw9a2ra9npani7c77ap";
        };
      };
    };
    "symfony/yaml" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-yaml-76de473358fe802578a415d5bb43c296cf09d211";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/yaml/zipball/76de473358fe802578a415d5bb43c296cf09d211;
          sha256 = "0xcqj5ikfj46gcqr1w5c26l0kfvf00brkgwdmzmz071rdws34s91";
        };
      };
    };
    "twig/extra-bundle" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "twig-extra-bundle-c56821429490e351003a09b7ed0c917feec2355f";
        src = fetchurl {
          url = https://api.github.com/repos/twigphp/twig-extra-bundle/zipball/c56821429490e351003a09b7ed0c917feec2355f;
          sha256 = "1si3n1zhhal7m8rihylrpbqjngzpk6dywcyp514j73bwx1m4mkjg";
        };
      };
    };
    "twig/twig" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "twig-twig-9b58bb8ac7a41d72fbb5a7dc643e07923e5ccc26";
        src = fetchurl {
          url = https://api.github.com/repos/twigphp/Twig/zipball/9b58bb8ac7a41d72fbb5a7dc643e07923e5ccc26;
          sha256 = "0yiy6m618p7hijyzahgp8jgybj110f48xldxplf5ww4xvdj36m6l";
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
        name = "zendframework-zend-diactoros-de5847b068362a88684a55b0dbb40d85986cfa52";
        src = fetchurl {
          url = https://api.github.com/repos/zendframework/zend-diactoros/zipball/de5847b068362a88684a55b0dbb40d85986cfa52;
          sha256 = "1na43rs2ak42vjvimajq56zpfwkbnvf3n6wd711vh31r5jvjw1x5";
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
    "composer/xdebug-handler" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "composer-xdebug-handler-cbe23383749496fe0f373345208b79568e4bc248";
        src = fetchurl {
          url = https://api.github.com/repos/composer/xdebug-handler/zipball/cbe23383749496fe0f373345208b79568e4bc248;
          sha256 = "0shf0q79fkzqvwpb8gn8fgwpm5bhzj5acwayilgdsasr506jsr2l";
        };
      };
    };
    "friendsofphp/php-cs-fixer" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "friendsofphp-php-cs-fixer-ceaff36bee1ed3f1bbbedca36d2528c0826c336d";
        src = fetchurl {
          url = https://api.github.com/repos/FriendsOfPHP/PHP-CS-Fixer/zipball/ceaff36bee1ed3f1bbbedca36d2528c0826c336d;
          sha256 = "0nsfgxfnyf663i1nnc3f54nik6vsndrvbqfb7bljxlipqvgh6fsj";
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
        name = "nikic-php-parser-9a9981c347c5c49d6dfe5cf826bb882b824080dc";
        src = fetchurl {
          url = https://api.github.com/repos/nikic/PHP-Parser/zipball/9a9981c347c5c49d6dfe5cf826bb882b824080dc;
          sha256 = "1qk8g51sxh8vm9b2w98383045ig20g71p67izw7vrsazqljmxxyb";
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
        name = "roave-security-advisories-40fb2c205dd261ab6bb42ec29545934f0db7026f";
        src = fetchurl {
          url = https://api.github.com/repos/Roave/SecurityAdvisories/zipball/40fb2c205dd261ab6bb42ec29545934f0db7026f;
          sha256 = "15g65aw1ss0d0aji3xby4frv7p0ibg8mx55jk416ldwyjvhy647m";
        };
      };
    };
    "symfony/maker-bundle" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-maker-bundle-c864e7f9b8d1e1f5f60acc3beda11299f637aded";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/maker-bundle/zipball/c864e7f9b8d1e1f5f60acc3beda11299f637aded;
          sha256 = "1a5klmgx9h4k6a1sww5hla4w1xdx7c25210g8inpgvndf3yvvpp1";
        };
      };
    };
    "symfony/process" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-process-75ad33d9b6f25325ebc396d68ad86fd74bcfbb06";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/process/zipball/75ad33d9b6f25325ebc396d68ad86fd74bcfbb06;
          sha256 = "0a455fbfs5nmqaivq8kpyw2maws8y9my7znq9nqv4jiy71a38zkr";
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
    ln -s /var/lib/packages/secrets config/secrets/prod
    '';
}
