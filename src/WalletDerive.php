<?php

namespace App;

require_once __DIR__  . '/../vendor/autoload.php';


use Exception;
use App\Utils\MyLogger;

// For HD-Wallet Key Derivation
use MoneroIntegrations\MoneroPhp;


/* A class that implements HD wallet key/address derivation
 */
class WalletDerive
{

    // Contains options we care about.
    protected $params;
    
    public function __construct($params)
    {
        $this->params = $params;
    }

    /* Getter for params
     */
    private function get_params()
    {
        return $this->params;
    }    
    
    public function derive_keys()
    {
        $params = $this->get_params();
        $addrs = array();        

        $start = $params['startindex'];
        $end = $params['startindex'] + $params['numderive'];
        $numderive = $params['numderive'];
        
        $major_index = $params['majorindex'];
        
        $view_secret_key = $params['view-key'];
        $spend_public_key = $params['spend-key'];
        
        MyLogger::getInstance()->log( "Deriving keys", MyLogger::info );
        
        $count = 0;
        $period_start = time();
        for($i = $start; $i < $end; $i++)
        {
            $minor_index = $i;
            $this->derive_key_worker($addrs, $major_index, $minor_index, $view_secret_key, $spend_public_key);
            
            $count = $i + 1;
            if(time() - $period_start > 10)
            {
                $pct = round($count / $numderive * 100, 2);
                MyLogger::getInstance()->log( "Derived $count of $numderive keys.  ($pct%)", MyLogger::specialinfo );
                $period_start = time();
            }
        }
        MyLogger::getInstance()->log( "Derived $count keys", MyLogger::info );

        return $addrs;
    }
    
    private function derive_key_worker(&$addrs, $major_index, $minor_index, $view_secret_key, $spend_public_key) {

        $params = $this->get_params();
        
        $sub = new MoneroPHP\subaddress();
        $address = $sub->generate_subaddress( $major_index, $minor_index, $view_secret_key, $spend_public_key);

        $addrs[] = [
            'view_secret_key' => $view_secret_key,
            'spend_public_key' => $spend_public_key,
            'major_index' => $major_index,
            'minor_index' => $minor_index,
            'subaddress' => $address,
        ];
    }

    protected function genRandomSeed($password=null) {
        $params = $this->get_params();
        
        $cn = new MoneroPHP\Cryptonote();
        $seed = $cn->gen_new_hex_seed();
        
        $data = [
            'seed' => $seed,
            'mnemonic' => 'unimplemented',   // $mnemonic,
        ];
        
        return $data;
    }
        
    protected function genKeysFromSeed($seedinfo) {
        
        $cn = new MoneroPHP\Cryptonote();
        $priv = $cn->gen_private_keys($seedinfo['seed']);
        
        $data = ['seed' => $seedinfo['seed'],
                 'mnemonic' => $seedinfo['mnemonic'],
                 'view-key-private' => $priv['viewKey'],
                 'view-key-public' => $cn->pk_from_sk($priv['viewKey']),
                 'spend-key-private' => $priv['spendKey'],
                 'spend-key-public' => $cn->pk_from_sk($priv['spendKey']),
        ];
            
        return $data;
    }

    public function genRandomKeys() {
        $seedinfo = $this->genRandomSeed();
        return $this->genKeysFromSeed($seedinfo);
    }
    
    /* Returns all columns available for reports
     */
    static public function all_cols()
    {
        return [ 'view_secret_key',
                 'spend_public_key',
                 'major_index',
                 'minor_index',
                 'subaddress',
        ];
    }

    /* Returns all columns available for reports when using --gen-key
     */
    static public function all_cols_genkey()
    {
        return ['seed', 'mnemonic', 'view-key-private', 'view-key-public', 'spend-key-private', 'spend-key-public'];
    }
    
    
    /* Returns default reporting columns
     */
    static public function default_cols()
    {
        return ['major_index', 'minor_index', 'subaddress'];
    }
    
    /* Returns default reporting columns when using --gen-key
     */
    static public function default_cols_genkey()
    {
        return ['seed', 'mnemonic', 'view-key-private', 'view-key-public', 'spend-key-private', 'spend-key-public'];
    }
    
}