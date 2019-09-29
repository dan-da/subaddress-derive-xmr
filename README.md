**subaddress-derive-xmr is a command-line tool that generates and derives monero addresses**

# About

This tool can be used to generate a monero address and/or derive addresses offline
without installing full monero software.

Derivation reports show major index (account ID), address index, and address.

Input must be a private view key and a public spend key.

Reports are available in json, plaintext, and html. Columns can be changed or
re-ordered via command-line.

This tool was adapted from: [hd-wallet-derive](https://github.com/dan-da/hd-wallet-derive) -- a tool for
deriving Bitcoin HD-Wallet addresses.



# Let see some examples

## Generate new keys and master address

```
$ ./subaddress-derive-xmr --gen-key  -g
{
    "seed": "66dcbb7490ee34dad1b04fa316b90ba1795ce70586298e2cc09455de1ae95273",
    "mnemonic": "unimplemented",
    "view-key-private": "25d014a444fb7a1e6836c680d3ec1b6eed628a29c3c85e0379fb89f53c4c610a",
    "view-key-public": "603ebe3bc1b2590c8a5e4caa90ee807cada4f881ad4f21f6c3653459781034c0",
    "spend-key-private": "eb1003ead738b471f5668a2e00e4f20e795ce70586298e2cc09455de1ae95203",
    "spend-key-public": "dce90ff7304d8b648bfbac69624b4c6562340c5c748a8a6d2c84bad3b76fe974",
    "address": "49zf2PF7nLSHpRwWcPG8ePHxYnR6eFmYuKG8Akpq5vFALTzZzMdv3kC36fCSP3UfFdMrY51QAs5NGiGuwXK6YMa3Nk7549x"
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

Note that the first address (0,0) is the same address that was generated with --gen-key.  This is the master address
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


# How address derivation works

For background, please read [Monero's documentation](https://monerodocs.org/public-address/subaddress/).


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
subaddress-derive-xmr 

   subaddress-derive-xmr.php

   This script derives Monero addresses

   Options:

    -g                   go!  ( required )
        
    --spend-pub=<key>    public spend key
    --view-priv=<key>    private view key
    
    --mnemonic=<words>   seed words  (unimplemented)
                           note: either key or nmemonic is required.
                           
    --mnemonic-pw=<pw>   optional password for mnemonic.

    --majorindex         identifies an account.  default=0
    
    --startindex=<n>     Index to start deriving keys from.  default=0

    --numderive=<n>      Number of keys to derive.  default=10
                            
    --cols=<cols>        a csv list of columns, or "all"
                         all:
                          (view_secret_key,spend_public_key,major_index,minor_index,subaddress)
                         default:
                          (major_index,minor_index,subaddress)

    --outfile=<path>     specify output file path.
    --format=<format>    txt|md|csv|json|jsonpretty|html|list|all   default=txt
    
                         if 'all' is specified then a file will be created
                         for each format with appropriate extension.
                         only works when outfile is specified.
                         
                         'list' prints only the first column. see --cols

    --includeroot       include root key as first element of report.
    --gen-key           generates a new key. (unimplemented)
    --gen-words=<n>     num words to generate. implies --gen-key.
                           one of: [12, 15, 18, 21, 24, 27, 30, 33, 36, 39, 42, 45, 48]
                           default = 24.
    
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
This library does all the heavy lifting.


# Todos

* implement --gen-key
* implement --mnemonic
* implement --gen-words
* add more test cases
