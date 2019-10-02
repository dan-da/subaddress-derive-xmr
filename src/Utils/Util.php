<?php

namespace App\Utils;


use Exception;
use App\WalletDerive;

class Util
{

    // returns the CLI params, exactly as entered by user.
    public static function getCliParams()
    {
        $paramsArray = [
            'spend-pub:',
            'view-priv:',
            'majorindex:',
            'mnemonic:',
            'mnemonic-pw:',
            'seed:',
            'wallet-keys',
            'outfile:',
            'numderive:',
            'startindex:',
            'path:',
            'format:',
            'cols:',
            'logfile:',
            'loglevel:',
            'list-cols',
            'gen-key',
            'gen-words:',
            'version',
            'help',
        ];

        $params = getopt( 'g', $paramsArray);

        return $params;
    }

    /* processes and sanitizes the CLI params. adds defaults
     * and ensure each value is set.
     */
    public static function processCliParams()
    {

        $params = static::getCliParams();

        $success = 0;   // 0 == success.

        if( isset($params['version'])) {
            static::printVersion();
            return [$params, 2];
        }

        $params['gen-key'] = isset($params['gen-key']) || isset($params['gen-words']);
        $params['mnemonic'] = $mnemonic  = self::normalize_whitespace(@$params['mnemonic']);
        $params['wallet-keys'] = isset($params['wallet-keys']);
        $params['seed'] = $seed = @$params['seed'];
        $params['cols'] = @$params['cols'] ?: null;
        $params['cols'] = static::getCols( $params );
        $params['format'] = @$params['format'] ?: (self::report_type($params) == 'keys' ? 'jsonpretty' : 'txt');
        
        if(isset($params['help']) || !isset($params['g'])) {
            static::printHelp();
            return [$params, 1];
        }
        
        // TODO
        if(@$params['logfile']) {
            mylogger()->set_log_file( $params['logfile'] );
            mylogger()->echo_log = false;
        }

        $loglevel = @$params['loglevel'] ?: 'specialinfo';
        MyLogger::getInstance()->set_log_level_by_name( $loglevel );

        $view_key = @$params['view-priv'];
        $spend_key = @$params['spend-pub'];
        
        if(@$params['mnemonic-pw']) {
            throw new Exception("Sorry, --mnemonic-pw has not been implemented yet.");
        }
        if(@$params['gen-words']) {
            throw new Exception("Sorry, --gen-words has not been implemented yet.");
        }

        if( $view_key || $spend_key && !($view_key && $spend_key)) {
            throw new Exception("--view-priv and --spend-pub must be used together");
        }
        
        if( !($view_key && $spend_key) && !$mnemonic && !$params['gen-key'] && !$params['seed']) {
            throw new Exception( "(--view-priv and --spend-pub) or --mnemonic or --gen-key or --seed must be specified." );
        }
        
        // error on mutually exclusive args.
        if( ($view_key && $spend_key) + (bool)$mnemonic + (bool)$params['gen-key'] + (bool)$params['seed'] > 1 ) {
            throw new Exception( "These flags are mutually exclusive: --mnemonic, --gen-key, --seed, and (--view-priv, --spend-pub)" );
        }
        
        $params['mnemonic-pw'] = @$params['mnemonic-pw'] ?: null;
        $params['majorindex'] = @$params['majorindex'] ?: 0;
        $params['numderive'] = isset($params['numderive']) ? $params['numderive'] : 10;
        $params['startindex'] = @$params['startindex'] ?: 0;
        $params['includeroot'] = isset($params['includeroot'] );
        
        $gen_words = (int)(@$params['gen-words'] ?: 25);
        $allowed = self::allowed_numwords();
        if(!in_array($gen_words, $allowed)) {
            throw new Exception("--gen-words must be one of " . implode(', ', $allowed));
        }
        $params['gen-words'] = $gen_words;

        return [$params, $success];
    }
    
    public static function allowed_numwords() {
        return [13,25];
    }

    /**
     * prints program version text
     */
    public static function printVersion()
    {
        $versionFile = __DIR__ . '/../VERSION';

        $version = @file_get_contents($versionFile);
        echo $version ?: 'version unknown' . "\n";
    }


    /* prints CLI help text
     */
    public static function printHelp()
    {

        $levels = MyLogger::getInstance()->get_level_map();
        $allcols = implode(',', WalletDerive::all_cols() );
        $defaultcols = implode(',', WalletDerive::default_cols() );
        $allowed_numwords = implode(', ', self::allowed_numwords());
        
        $loglevels = implode(',', array_values( $levels ));

        $buf = <<< END

   subaddress-derive-xmr.php

   This script derives Monero addresses

   Options:

    -g                   go!  ( required )
        
    --spend-pub=<key>    public spend key
    --view-priv=<key>    private view key
    
    --mnemonic=<words>   seed words
                           note: either key or nmemonic is required.
                           
    --mnemonic-pw=<pw>   optional password for mnemonic. (unimplemented)
    
    --seed=<seed>        wallet seed in hex  
    
    --wallet-keys        display seed+keys and do not derive.
                          applies to --mnemonic and --seed.

    --majorindex         identifies an account.  default=0
    
    --startindex=<n>     Index to start deriving keys from.  default=0

    --numderive=<n>      Number of keys to derive.  default=10
                            
    --cols=<cols>        a csv list of columns, or "all"
                         all:
                          ($allcols)
                         default:
                          ($defaultcols)

    --outfile=<path>     specify output file path.
    --format=<format>    txt|md|csv|json|jsonpretty|html|list|all   default=txt
    
                         if 'all' is specified then a file will be created
                         for each format with appropriate extension.
                         only works when outfile is specified.
                         
                         'list' prints only the first column. see --cols

    --includeroot       include root key as first element of report.
    --gen-key           generates a new key.
    --gen-words=<n>     num words to generate. implies --gen-key.
                           (unimplemented)
                           one of: [$allowed_numwords]
                           default = 25.
    
    --logfile=<file>    path to logfile. if not present logs to stdout.
    --loglevel=<level>  $loglevels
                          default = info
    


END;

        fprintf( STDERR, $buf );

    }
    
    protected static function report_type($params) {
        if( $params['gen-key'] || ( ($params['mnemonic'] || $params['seed']) && $params['wallet-keys']) ) {
            return 'keys';
        }
        return 'derive';
    }
    
    /* parses the --cols argument and returns an array of columns.
     */
    public static function getCols( $params )
    {
        $arg = static::stripWhitespace( @$params['cols'] ?: null );

        $allcols = [];
        
        $report = self::report_type($params);
        if( $report == 'keys' ) {
            $allcols = WalletDerive::all_cols_genkey();
        }
        else {
            $allcols = WalletDerive::all_cols();
        }

        if( $arg == 'all' ) {
            $cols = $allcols;
        }
        else if( !$arg ) {
            $cols = $report == 'keys' ? WalletDerive::default_cols_genkey() : WalletDerive::default_cols();
        }
        else {
            $cols = explode( ',', $arg );
            foreach( $cols as $c ) {
                if( count($allcols) && !in_array($c, $allcols) )
                {
                    throw new Exception( "'$c' is not a known report column.", 2 );
                }
            }
        }
        return $cols;
    }


    /* removes whitespace from a string
     */
    public static function stripWhitespace( $str )
    {
        return preg_replace('/\s+/', '', $str);
    }
    
    /* compress each whitespace to a single space, and trim ends.
     */
    public static function normalize_whitespace( $str )
    {
        return trim(preg_replace('/\s+/', ' ', $str));
    }
    
}
