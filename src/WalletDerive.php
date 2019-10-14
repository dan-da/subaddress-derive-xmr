<?php

/**
 * copyright (c) Dan Libby 2019
 * 
 * Use of this software is subject to the terms of the
 * GNU GENERAL PUBLIC LICENSE Version 3.
 * See included LICENSE file or if missing
 * https://www.gnu.org/licenses/gpl-3.0.en.html.
 */

namespace App;

require_once __DIR__  . '/../vendor/autoload.php';


use Exception;
use App\Utils\MyLogger;
use App\mnemonic;

// For HD-Wallet Key Derivation
use MoneroIntegrations\MoneroPhp;
use MoneroIntegrations\MoneroPhp\subaddress;


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
        
        $view_secret_key = $params['view-priv'];
        $spend_public_key = $params['spend-pub'];
        
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
        $cn = new MoneroPHP\Cryptonote();
        
        // Special case, 0/0 is the master address, not a subaddress.
        if($major_index == 0 && $minor_index == 0) {
            $address = $cn->encode_address($spend_public_key, $cn->pk_from_sk($view_secret_key));
        }
        else {
            $address = $cn->generate_subaddress( $major_index, $minor_index, $view_secret_key, $spend_public_key);
        }

        $addrs[] = [
            'view_secret_key' => $view_secret_key,
            'spend_public_key' => $spend_public_key,
            'major_index' => $major_index,
            'minor_index' => $minor_index,
            'address' => $address,
        ];
    }

    protected function genRandomSeed($password=null) {
        $params = $this->get_params();
        
        $cn = new MoneroPHP\Cryptonote();
        $seed = $cn->gen_new_hex_seed();
        
        $data = [
            'seed' => $seed,
            'mnemonic' => implode(' ', mnemonic::encode_with_checksum($seed, $params['mnemonic-ws'])),   // $mnemonic,
            'mnemonic-wordset' => $params['mnemonic-ws'],
        ];
        
        return $data;
    }
        
    public function genKeysFromSeed($seedinfo) {
        
        $cn = new MoneroPHP\Cryptonote();
        $priv = $cn->gen_private_keys($seedinfo['seed']);
        
        $view_key_public = $cn->pk_from_sk($priv['viewKey']);
        $spend_key_public = $cn->pk_from_sk($priv['spendKey']);
        
        $data = ['seed' => $seedinfo['seed'],
                 'mnemonic' => $seedinfo['mnemonic'],
                 'mnemonic-wordset' => $seedinfo['mnemonic-wordset'],
                 'view-key-private' => $priv['viewKey'],
                 'view-key-public' => $view_key_public,
                 'spend-key-private' => $priv['spendKey'],
                 'spend-key-public' => $spend_key_public,
                 'address' => $cn->encode_address($spend_key_public, $view_key_public),
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
                 'address',
        ];
    }

    /* Returns all columns available for reports when using --gen-wallet
     */
    static public function all_cols_genwallet()
    {
        return ['seed', 'mnemonic', 'mnemonic-wordset', 'view-key-private', 'view-key-public', 'spend-key-private', 'spend-key-public', 'address'];
    }
    
    
    /* Returns default reporting columns
     */
    static public function default_cols()
    {
        return ['major_index', 'minor_index', 'address'];
    }
    
    /* Returns default reporting columns when using --gen-wallet
     */
    static public function default_cols_genwallet()
    {
        return ['seed', 'mnemonic', 'mnemonic-wordset', 'view-key-private', 'view-key-public', 'spend-key-private', 'spend-key-public', 'address'];
    }
    
}