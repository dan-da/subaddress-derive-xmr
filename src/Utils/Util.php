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
            'spend-key:',
            'view-key:',
            'majorindex:',
            'mnemonic:',
            'mnemonic-pw:',
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

        $params['cols'] = @$params['cols'] ?: null;
        $params['cols'] = static::getCols( $params );
        $params['format'] = @$params['format'] ?: 'txt';
        
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

        $params['gen-key'] = isset($params['gen-key']) || isset($params['gen-words']);
        $view_key = @$params['view-key'];
        $spend_key = @$params['spend-key'];
        $mnemonic = @$params['mnemonic'];
        
        if(@$params['mnemonic']) {
            throw new Exception("Sorry, --mnemonic has not been implemented yet.");
        }
        if(@$params['gen-key']) {
            throw new Exception("Sorry, --gen-key has not been implemented yet.");
        }
        if(@$params['gen-words']) {
            throw new Exception("Sorry, --gen-words has not been implemented yet.");
        }

        if( !($view_key && $spend_key) && !$mnemonic && !$params['gen-key']) {
            throw new Exception( "(--view-key and --spend_key) or --mnemonic or --gen-key must be specified." );
        }
        $params['mnemonic-pw'] = @$params['mnemonic-pw'] ?: null;
        $params['majorindex'] = @$params['majorindex'] ?: 0;
        $params['numderive'] = isset($params['numderive']) ? $params['numderive'] : 10;
        $params['startindex'] = @$params['startindex'] ?: 0;
        $params['includeroot'] = isset($params['includeroot'] );
        
        $gen_words = (int)(@$params['gen-words'] ?: 24);
        $allowed = self::allowed_numwords();
        if(!in_array($gen_words, $allowed)) {
            throw new Exception("--gen-words must be one of " . implode(', ', $allowed));
        }
        $params['gen-words'] = $gen_words;

        return [$params, $success];
    }
    
    public static function allowed_numwords() {
        $allowed = [];
        for($i = 12; $i <= 48; $i += 3) {
            $allowed[] = $i;
        }
        return $allowed;
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
    --gen-key           generates a new key. (unimplemented)
    --gen-words=<n>     num words to generate. implies --gen-key.
                           one of: [$allowed_numwords]
                           default = 24.
    
    --logfile=<file>    path to logfile. if not present logs to stdout.
    --loglevel=<level>  $loglevels
                          default = info
    


END;

        fprintf( STDERR, $buf );

    }
    
    /* parses the --cols argument and returns an array of columns.
     */
    public static function getCols( $params )
    {
        $arg = static::stripWhitespace( @$params['cols'] ?: null );

        $allcols = [];
        if( isset($params['gen-key'])) {
            $allcols = WalletDerive::all_cols_genkey();
        }
        else {
            $allcols = WalletDerive::all_cols();
        }

        if( $arg == 'all' ) {
            $cols = $allcols;
        }
        else if( !$arg ) {
            $cols = @$params['gen-key'] ? WalletDerive::default_cols_genkey() : WalletDerive::default_cols();
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
}
