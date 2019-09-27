**subaddress-derive-xmr is a command-line tool that derives monero subaddresses**

# About

Derivation reports show major index (account ID), subaddress index, and subaddress.

Input must be a private view key and a public spend key.

This tool can be used to derive monero subaddresses offline and without installing
full monero software.

Reports are available in json, plaintext, and html. Columns can be changed or
re-ordered via command-line.

This tool was adapted from: [hd-wallet-derive](https://github.com/dan-da/hd-wallet-derive) -- a tool for
deriving Bitcoin HD-Wallet addresses.



# Let see some examples

First, you need to obtain your private view key and public spend key.  Once you have them:

## Derive subaddresses


```
$ subaddress-derive-xmr --view-key='55cdeaa9a36c83a130e42934fcc7bb7761945fa266f026bf85a4056beafb390f' --spend-key='9b593fbcdc7a13f95b8cf51274bd3ffc9ef8374ee1fa14400857e927b3f2f6f4' --numderive=3 -g

+-------------+-------------+-------------------------------------------------------------------------------------------------+
| major_index | minor_index | subaddress                                                                                      |
+-------------+-------------+-------------------------------------------------------------------------------------------------+
|           0 |           0 | 8BFkheDYAXMU7aqrxgBwkNGAxS9bpRYimWcBoVxfhjtkJTugpaV8yYpePzrvEcTdb1KyDnAFk3yf4cs2db4EBfeeSC4sBak |
|           0 |           1 | 88AAbbFDZruJkAyAZu1hXoK2sDXkm1MEMZhfx5DnqYjS1QNyvcCSxmCPY64pnD853V9gNJenKyDL6Nt37vx5jgBKKg6S9bc |
|           0 |           2 | 8B1nzov38yZfJozwLjf7NzPTUARcyBh7d4WQprLMQnBrcxBf6rhwXdpUS9dNyqCXpVMrRxbN2aE23EiUDHz8zR1YTXUAwcR |
+-------------+-------------+-------------------------------------------------------------------------------------------------+
```

## Same keys, different account

For this, we use the --majorindex flag.

```
$ subaddress-derive-xmr --majorindex=1 --view-key='55cdeaa9a36c83a130e42934fcc7bb7761945fa266f026bf85a4056beafb390f' --spend-key='9b593fbcdc7a13f95b8cf51274bd3ffc9ef8374ee1fa14400857e927b3f2f6f4' --numderive=3 -g

+-------------+-------------+-------------------------------------------------------------------------------------------------+
| major_index | minor_index | subaddress                                                                                      |
+-------------+-------------+-------------------------------------------------------------------------------------------------+
|           1 |           0 | 881oaqSBkr81GtAGvoyJ6k7phLRmjChcrLWXVGPfYf6ebGazbUf6BoCXNAENBU2uKSg7F8579SMTFNe48V8G4KdxLaU9zXh |
|           1 |           1 | 8854ic9fQM4K2FxZs8amkZcmktM6MnRGhTM3Mfoho8DPT63xNKt8gZsbXKoquC7zfGMAAJTzkhbRR52fGAPTkjyd91Ua4z2 |
|           1 |           2 | 83PWdoFpvz8iFfXX7HnG9Q3xuqddMnhAg3amhH1TmfZcYcS6Q62dXU5aXkiHEhp79mVcN83bs7o7D3E8Yo9aNE7fD6h6Ttf |
+-------------+-------------+-------------------------------------------------------------------------------------------------+
```


## We can easily change up the columns in whatever order we want.

Just use the --cols parameter.

```
$ subaddress-derive-xmr --majorindex=1 --view-key='55cdeaa9a36c83a130e42934fcc7bb7761945fa266f026bf85a4056beafb390f' --spend-key='9b593fbcdc7a13f95b8cf51274bd3ffc9ef8374ee1fa14400857e927b3f2f6f4' --numderive=3 --cols=subaddress,minor_index -g

+-------------------------------------------------------------------------------------------------+-------------+
| subaddress                                                                                      | minor_index |
+-------------------------------------------------------------------------------------------------+-------------+
| 881oaqSBkr81GtAGvoyJ6k7phLRmjChcrLWXVGPfYf6ebGazbUf6BoCXNAENBU2uKSg7F8579SMTFNe48V8G4KdxLaU9zXh |           0 |
| 8854ic9fQM4K2FxZs8amkZcmktM6MnRGhTM3Mfoho8DPT63xNKt8gZsbXKoquC7zfGMAAJTzkhbRR52fGAPTkjyd91Ua4z2 |           1 |
| 83PWdoFpvz8iFfXX7HnG9Q3xuqddMnhAg3amhH1TmfZcYcS6Q62dXU5aXkiHEhp79mVcN83bs7o7D3E8Yo9aNE7fD6h6Ttf |           2 |
+-------------------------------------------------------------------------------------------------+-------------+```
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

   This script derives Monero subaddresses

   Options:

    -g                   go!  ( required )
        
    --spend-key=<key>    public spend key
    --view-key=<key>     private view key
    
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
$ ./subaddress-derive-xmr --view-key='55cdeaa9a36c83a130e42934fcc7bb7761945fa266f026bf85a4056beafb390f' --spend-key='9b593fbcdc7a13f95b8cf51274bd3ffc9ef8374ee1fa14400857e927b3f2f6f4' -g
```


# Thanks

A big thank-you to the author(s) of [monero-integrations/monerophp](https://github.com/monero-integrations/monerophp/).
This library does all the heavy lifting.


# Todos

* implement --gen-key
* implement --mnemonic
* implement --gen-words
* add more test cases
