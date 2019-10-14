**subaddress-derive-xmr is a command-line tool that generates a monero wallet and derives addresses**

# About

This tool can be used to generate a monero wallet and/or derive addresses offline
from keys or from a mnemonic without installing full monero software.

Derivation reports show major index (account ID), address index, and address.

Input must be provided with these flags:

    --mnemonic : a mnemonic phrase to use an existing wallet
    --view-priv and --spend-pub : a private view key and a public spend key
    --gen-wallet : generates a new wallet seed and keys.


Reports are available in json, plaintext, and html. Columns can be changed or
re-ordered via command-line.

This tool was adapted from: [hd-wallet-derive](https://github.com/dan-da/hd-wallet-derive) -- a tool for
deriving Bitcoin HD-Wallet addresses.



# Let see some examples

## Generate new wallet keys and master address

```
$ ./subaddress-derive-xmr --gen-wallet  -g
{
    "seed": "66dcbb7490ee34dad1b04fa316b90ba1795ce70586298e2cc09455de1ae95273",
    "mnemonic": "focus aquarium luxury etched video smidgen sidekick because rounded cigar befit ritual layout visited wetsuit tobacco oars setup mystery insult females dauntless yodel jeopardy rounded",
    "mnemonic-wordset": "english",
    "view-key-private": "25d014a444fb7a1e6836c680d3ec1b6eed628a29c3c85e0379fb89f53c4c610a",
    "view-key-public": "603ebe3bc1b2590c8a5e4caa90ee807cada4f881ad4f21f6c3653459781034c0",
    "spend-key-private": "eb1003ead738b471f5668a2e00e4f20e795ce70586298e2cc09455de1ae95203",
    "spend-key-public": "dce90ff7304d8b648bfbac69624b4c6562340c5c748a8a6d2c84bad3b76fe974",
    "address": "49zf2PF7nLSHpRwWcPG8ePHxYnR6eFmYuKG8Akpq5vFALTzZzMdv3kC36fCSP3UfFdMrY51QAs5NGiGuwXK6YMa3Nk7549x"
}
```

## Generate new wallet using alternative wordset

Here we specify --mnemonic-ws=japanese to generate a mnemonic in Japanese.

```
$ ./subaddress-derive-xmr --gen-wallet --mnemonic-ws=japanese -g
{
    "seed": "a6d5b4007d6b05d3a46b8bae199c8eef702c05f4fa219dbc80c84913da1c6a7e",
    "mnemonic": "すくう あらわす あんい だいじょうぶ ちしりょう ずっと おもちゃ てはい さんこう そんみん てんかい ちひょう ちたん かかえる おおどおり けわしい きかい はめつ すれちがう はっかく いきおい けねん しんか あらためる ちしりょう",
    "mnemonic-wordset": "japanese",
    "view-key-private": "48381703f1354d371678d1e8a63d2eef6cde8e6b406ff466902583024fe93c0a",
    "view-key-public": "a528500e7bf5ea90e2da21c7e1dcb8bc2d23c1fca912dbcf050f26fa760fa3ea",
    "spend-key-private": "2b0afc75c4b5846ac821c63903c7755d702c05f4fa219dbc80c84913da1c6a0e",
    "spend-key-public": "900a3a401f61cea0cc9e9df0cc08c193a356a63c500a1c53bcc61be12dd2b808",
    "address": "475hYFjWisFTtxNEvr8qTNRhGtXAJUzd9F1Mk9TznAYP2SsPUPXMcsjREaUE6GVhDqYUYg3gzne42bdMMq5ZuvxSTVQUiGG"
}
```


## Derive addresses

For deriving addresses we use the private view key and the public spend key.  In the examples below, we use the
keys that were generated above.

If you want to use keys from your wallet, then in the official Monero GUI, they are available in the
Settings->Seed & Keys area. In the CLI, open your wallet and use the commands spendkey and viewkey
to find the keys.

```
./subaddress-derive-xmr --view-priv=25d014a444fb7a1e6836c680d3ec1b6eed628a29c3c85e0379fb89f53c4c610a --spend-pub=dce90ff7304d8b648bfbac69624b4c6562340c5c748a8a6d2c84bad3b76fe974 --numderive=3 -g

+-------------+-------------+-------------------------------------------------------------------------------------------------+
| major_index | minor_index | address                                                                                         |
+-------------+-------------+-------------------------------------------------------------------------------------------------+
|           0 |           0 | 49zf2PF7nLSHpRwWcPG8ePHxYnR6eFmYuKG8Akpq5vFALTzZzMdv3kC36fCSP3UfFdMrY51QAs5NGiGuwXK6YMa3Nk7549x |
|           0 |           1 | 87i7kA61fNvMboXiYWHVygPAggKJPETFqLXXcdH4mQTrECvrTxZMtt6e6owj1k8jUVjNR11eBuBMWHFBtxAwEVcm9dcSUxr |
|           0 |           2 | 8A9XmWsATrhfedtNhTMNKELwfCwMVAk2iVTdUJdFRb2AC4tV4VeBjsCLYR9cSQTwnvLo4MAuQFMLP6Si4xp6t6BS788db3t |
+-------------+-------------+-------------------------------------------------------------------------------------------------+
```

Note that the first address (0,0) is the same address that was generated with --gen-wallet.  This is the master address
for the Monero wallet and is generated slightly differently from the other subaddresses.

## Same keys, different account

For this, we use the --majorindex flag.

```
./subaddress-derive-xmr --majorindex=1 --view-priv=25d014a444fb7a1e6836c680d3ec1b6eed628a29c3c85e0379fb89f53c4c610a --spend-pub=dce90ff7304d8b648bfbac69624b4c6562340c5c748a8a6d2c84bad3b76fe974 --numderive=3 -g

+-------------+-------------+-------------------------------------------------------------------------------------------------+
| major_index | minor_index | address                                                                                         |
+-------------+-------------+-------------------------------------------------------------------------------------------------+
|           1 |           0 | 87G36SRd8Ru9YG9xXjq3i7hAiHTEpVfRdjZEqn5KcWk28oFbEaNWDCiJTcL9BqrZQ8cFtonzJY3zz3LsTT95wdunQhRqW5g |
|           1 |           1 | 88jg9HNvkisAYFz9J3gr9H4jsz4kMA1yu4Pm8qrwoieuRtarWNX5a2ac5pAwxz3Kphgn1391RgKPe5oZ1uuWmbnwMiVkkaZ |
|           1 |           2 | 86V9FP5VWUc3dSrAKuJHp1AotL6CU41z3fjBUDetGzpGK8jDW7bPeVL6BJNjK8SVrf1795oPMmw78HbK1JoH1cqtKoQuPyj |
+-------------+-------------+-------------------------------------------------------------------------------------------------+
```


## We can easily change up the columns in whatever order we want.

Just use the --cols parameter.

```
./subaddress-derive-xmr --cols=address,minor_index --majorindex=1 --view-priv=25d014a444fb7a1e6836c680d3ec1b6eed628a29c3c85e0379fb89f53c4c610a --spend-pub=dce90ff7304d8b648bfbac69624b4c6562340c5c748a8a6d2c84bad3b76fe974 --numderive=3 -g

+-------------------------------------------------------------------------------------------------+-------------+
| address                                                                                         | minor_index |
+-------------------------------------------------------------------------------------------------+-------------+
| 87G36SRd8Ru9YG9xXjq3i7hAiHTEpVfRdjZEqn5KcWk28oFbEaNWDCiJTcL9BqrZQ8cFtonzJY3zz3LsTT95wdunQhRqW5g |           0 |
| 88jg9HNvkisAYFz9J3gr9H4jsz4kMA1yu4Pm8qrwoieuRtarWNX5a2ac5pAwxz3Kphgn1391RgKPe5oZ1uuWmbnwMiVkkaZ |           1 |
| 86V9FP5VWUc3dSrAKuJHp1AotL6CU41z3fjBUDetGzpGK8jDW7bPeVL6BJNjK8SVrf1795oPMmw78HbK1JoH1cqtKoQuPyj |           2 |
+-------------------------------------------------------------------------------------------------+-------------+
```

## Derive addresses from a mnemonic

The --mnemonic flag makes it happen.

```
$ ./subaddress-derive-xmr --mnemonic="school bunch godfather school umbrella criminal mowing payment himself tacit tawny dagger phrases blender depth sayings antics bagpipe gels ability ablaze mugged balding apology sayings" -g --numderive=3

+-------------+-------------+-------------------------------------------------------------------------------------------------+
| major_index | minor_index | address                                                                                         |
+-------------+-------------+-------------------------------------------------------------------------------------------------+
|           0 |           0 | 46pbLJeN7ns2LxWGqvfYL5dEJ42UXKogi6E9kkY6hTZVM8u7FVN2egrH2mxUGTxRmd1RKhfzXU6dRDWyuub6m7QZDztrVEc |
|           0 |           1 | 8BtVfzdXSZ6TbxjGPLsWwjcmWvYduXu23UAAeHPhPu91QF9h54Vg6dn6sGMA4e35WV5cw2YByEsdfHfhMstHHBwi7PNhETF |
|           0 |           2 | 86XGETJkKkoN2dcD9sv2Sx1XHby1XC9dFaYvM6hnRr1NHjnH6sd6ZQydSJUy9xqrPQiqWtFZ6FLuQiRdwGpc7LMb7BLNmTv |
+-------------+-------------+-------------------------------------------------------------------------------------------------+
```

## Get seed and wallet keys from a mnemonic

Just use --wallet-keys in addition to --mnemonic.  Note that --wallet-keys defaults to jsonpretty output, but that can
be changed with the -format flag.

```
$ ./subaddress-derive-xmr --wallet-keys --mnemonic="school bunch godfather school umbrella criminal mowing payment himself tacit tawny dagger phrases blender depth sayings antics bagpipe gels ability ablaze mugged balding apology sayings" -g
{
    "seed": "e5f8bd36b5d9174725aca5bc88a8ac567a0d1614a840d109af84430001fd56f7",
    "mnemonic": "school bunch godfather school umbrella criminal mowing payment himself tacit tawny dagger phrases blender depth sayings antics bagpipe gels ability ablaze mugged balding apology sayings",
    "view-key-private": "6b4236e3446c290ed5df7cee7389925796759e6ad464318fed2f6c159cadc405",
    "view-key-public": "62e7eb0777e9b35fd739e7ec43cc540281d42ccd1ef82c4ad57182eb06f49e73",
    "spend-key-private": "028e56c4290b041e967b23307d049c1d790d1614a840d109af84430001fd5607",
    "spend-key-public": "892aacab12ca34080927d5fda5772ed899abb1f6e904c71f3fa6c4ee51d13478",
    "address": "46pbLJeN7ns2LxWGqvfYL5dEJ42UXKogi6E9kkY6hTZVM8u7FVN2egrH2mxUGTxRmd1RKhfzXU6dRDWyuub6m7QZDztrVEc"
}
```

## Use a mnemonic with alternative wordset.

Presently, it is necessary to specify the wordset with the --mnemonic-ws flag.

```
$ ./subaddress-derive-xmr --wallet-keys --mnemonic-ws=japanese --mnemonic="すくう あらわ す あんい だいじょうぶ ちしりょう ずっと おもちゃ てはい さんこう そんみん てんかい ちひょう ちたん かかえる おおどおり けわしい きかい はめつ すれちがう はっかく いきおい けねん しんか あらためる ちしりょう" -g
{
    "seed": "a6d5b4007d6b05d3a46b8bae199c8eef702c05f4fa219dbc80c84913da1c6a7e",
    "mnemonic": "すくう あらわす あんい だいじょうぶ ちしりょう ずっと おもちゃ てはい さんこう そんみん てんかい ちひょう ちたん かかえる おおどおり けわしい きかい はめつ すれちがう はっかく いきおい けねん しんか あらためる ちしりょう",
    "mnemonic-wordset": "japanese",
    "view-key-private": "48381703f1354d371678d1e8a63d2eef6cde8e6b406ff466902583024fe93c0a",
    "view-key-public": "a528500e7bf5ea90e2da21c7e1dcb8bc2d23c1fca912dbcf050f26fa760fa3ea",
    "spend-key-private": "2b0afc75c4b5846ac821c63903c7755d702c05f4fa219dbc80c84913da1c6a0e",
    "spend-key-public": "900a3a401f61cea0cc9e9df0cc08c193a356a63c500a1c53bcc61be12dd2b808",
    "address": "475hYFjWisFTtxNEvr8qTNRhGtXAJUzd9F1Mk9TznAYP2SsPUPXMcsjREaUE6GVhDqYUYg3gzne42bdMMq5ZuvxSTVQUiGG"
}
```


## Derive addresses from a seed

We use the --seed flag

```
$ ./subaddress-derive-xmr --seed="66dcbb7490ee34dad1b04fa316b90ba1795ce70586298e2cc09455de1ae95273" -g --numderive=3

+-------------+-------------+-------------------------------------------------------------------------------------------------+
| major_index | minor_index | address                                                                                         |
+-------------+-------------+-------------------------------------------------------------------------------------------------+
|           0 |           0 | 49zf2PF7nLSHpRwWcPG8ePHxYnR6eFmYuKG8Akpq5vFALTzZzMdv3kC36fCSP3UfFdMrY51QAs5NGiGuwXK6YMa3Nk7549x |
|           0 |           1 | 87i7kA61fNvMboXiYWHVygPAggKJPETFqLXXcdH4mQTrECvrTxZMtt6e6owj1k8jUVjNR11eBuBMWHFBtxAwEVcm9dcSUxr |
|           0 |           2 | 8A9XmWsATrhfedtNhTMNKELwfCwMVAk2iVTdUJdFRb2AC4tV4VeBjsCLYR9cSQTwnvLo4MAuQFMLP6Si4xp6t6BS788db3t |
+-------------+-------------+-------------------------------------------------------------------------------------------------+
```

## Get mnemonic and wallet keys from a seed

Again we add the --wallet-keys flag.

```
$ ./subaddress-derive-xmr --wallet-keys --seed="66dcbb7490ee34dad1b04fa316b90ba1795ce70586298e2cc09455de1ae95273" -g --numderive=3
{
    "seed": "66dcbb7490ee34dad1b04fa316b90ba1795ce70586298e2cc09455de1ae95273",
    "mnemonic": "focus aquarium luxury etched video smidgen sidekick because rounded cigar befit ritual layout visited wetsuit tobacco oars setup mystery insult females dauntless yodel jeopardy rounded",
    "view-key-private": "25d014a444fb7a1e6836c680d3ec1b6eed628a29c3c85e0379fb89f53c4c610a",
    "view-key-public": "603ebe3bc1b2590c8a5e4caa90ee807cada4f881ad4f21f6c3653459781034c0",
    "spend-key-private": "eb1003ead738b471f5668a2e00e4f20e795ce70586298e2cc09455de1ae95203",
    "spend-key-public": "dce90ff7304d8b648bfbac69624b4c6562340c5c748a8a6d2c84bad3b76fe974",
    "address": "49zf2PF7nLSHpRwWcPG8ePHxYnR6eFmYuKG8Akpq5vFALTzZzMdv3kC36fCSP3UfFdMrY51QAs5NGiGuwXK6YMa3Nk7549x"
}
```


# How address derivation works

For background, please read [Monero's documentation](https://monerodocs.org/public-address/subaddress/).

# Mnemonic wordsets

As of this writing, the following mnemonic wordsets are supported:

    english, electrum, japanese, spanish, portuguese
    
This list is available in the usage help.

A particular wordset can be specified using the --mnemonic-ws flag
which is recognized when generating a wallet, deriving subaddresses
or displaying wallet info.

The default is english.



# Privacy and Security implications

This tool runs locally and does not make any requests to a server.
This eliminates many forms of leaks and privacy issues.

That said, any time when you are working with private keys you should
take serious security precautions.

When you run this tool in a terminal, the executed command(s) will
usually be saved to a history file -- including your keys used as command arguments.
You should be very careful to either expunge the command(s), or move the funds to
another wallet, or be certain untrusted parties cannot access your machine.

Finally, this tool depends on libraries written by other authors and they
have not been carefully audited for security.  So use at your own risk.


# Use at your own risk.

The author makes no claims or guarantees of correctness.

By using this software you agree to take full responsibility for any losses
incurred before, during, or after the usage, whatsoever the cause, and not to
hold the software author liable in any manner.


# Output formats

The report may be printed in the following formats:
* plain      - an ascii formatted table, as above.  intended for humans.
* csv        - CSV format.  For spreadsheet programs.
* json       - raw json format.  for programs to read easily.
* jsonpretty - pretty json format.  for programs or humans.
* list       - single column list. for easy cut/paste.  uses first col.


# Usage

```
   subaddress-derive-xmr.php

   This script derives Monero addresses

   Options:

    -g                   go!  ( required )
        
    --spend-pub=<key>    public spend key
    --view-priv=<key>    private view key
    
    --mnemonic=<words>   seed words
                           note: either key or nmemonic is required.
                           
    --mnemonic-ws=<ws>   mnemonic wordset. default=english.
                          [english, electrum, japanese, spanish, portuguese]
                          
    --mnemonic-pw=<pw>   optional password for mnemonic. (unimplemented)
    
    --seed=<seed>        wallet seed in hex  
    
    --wallet-keys        display seed+keys and do not derive.
                          applies to --mnemonic and --seed.

    --majorindex         identifies an account.  default=0
    
    --startindex=<n>     Index to start deriving keys from.  default=0

    --numderive=<n>      Number of keys to derive.  default=10
                            
    --cols=<cols>        a csv list of columns, or "all"
                         all:
                          (view_secret_key,spend_public_key,major_index,minor_index,address)
                         default:
                          (major_index,minor_index,address)

    --outfile=<path>     specify output file path.
    --format=<format>    txt|md|csv|json|jsonpretty|html|list|all   default=txt
    
                         if 'all' is specified then a file will be created
                         for each format with appropriate extension.
                         only works when outfile is specified.
                         
                         'list' prints only the first column. see --cols

    --includeroot       include root key as first element of report.
    --gen-wallet        generates keys and mnemonic for a new wallet.
    --gen-words=<n>     num words to generate. implies --gen-wallet.
                           (unimplemented)
                           one of: [13, 25]
                           default = 25.
    
    --logfile=<file>    path to logfile. if not present logs to stdout.
    --loglevel=<level>  debug,info,specialinfo,warning,exception,fatalerror
                          default = info
```


# Installation and Running.

Linux Ubuntu 16.04 requirements:
```
apt-get install php php-gmp php-mbstring php-mcrypt
```


Try an example
```
$ ./subaddress-derive-xmr --view-priv='55cdeaa9a36c83a130e42934fcc7bb7761945fa266f026bf85a4056beafb390f' --spend-pub='9b593fbcdc7a13f95b8cf51274bd3ffc9ef8374ee1fa14400857e927b3f2f6f4' -g
```


# Thanks

A big thank-you to the author(s) of [monero-integrations/monerophp](https://github.com/monero-integrations/monerophp/).
This library does much of the heavy lifting.


# Todos

* implement --mnemonic-pw (mnemonic password)
* auto detect mnemonic wordset from mnemonic words.
* implement --gen-words (variable length mnemonics).
* implement --network  (support testnet)
* add more test cases
* <strike>support mnemonics in other languages</strike>
* <strike>implement --gen-wallet</strike>
* <strike>implement --mnemonic</strike>
* <strike>implement --seed</strike>
* <strike>implement --wallet-keys</strike>



