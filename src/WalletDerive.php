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

/***
 *  Obsolete.  From hd-wallet-derive.  using bitwasp/bitcoin-php lib.
 *  needs to be re-implemented for monero.
 *  
    protected function genRandomSeed($password=null) {
        $params = $this->get_params();
        $num_bytes = (int)($params['gen-words'] / 0.75);
        
        // generate random mnemonic
        $random = new Random();
        $bip39 = MnemonicFactory::bip39();
        $entropy = $random->bytes($num_bytes);
        $mnemonic = $bip39->entropyToMnemonic($entropy);

        // generate seed and master priv key from mnemonic
        $seedGenerator = new Bip39SeedGenerator();
        $pw = $password == null ? '' : $password;
        $seed = $seedGenerator->getSeed($mnemonic, $pw);

        $data = [
            'seed' => $seed,
            'mnemonic' => $mnemonic,
        ];
        
        return $data;
    }
        
    protected function genKeysFromSeed($coin, $seedinfo) {
        $networkCoinFactory = new NetworkCoinFactory();
        $network = $networkCoinFactory->getNetworkCoinInstance($coin);
        Bitcoin::setNetwork($network);
        
                    // type   purpose        
        $key_types = ['x'  => 44,
                      'y'  => 49,
                      'z'  => 84,
//                      'Y'  => ??,    // multisig
//                      'Z'  => ??,    // multisig
                     ];
        $keys = [];
        
        $rows = [];
        foreach($key_types as $key_type => $purpose) {
            if( !$this->networkSupportsKeyType($network, $key_type, $coin) ) {
                // $data[$key_type] = null;
                continue;
            }
            $row = ['coin' => $this->normalizeCoin($coin),
                    'seed' => $seedinfo['seed']->getHex(),
                    'mnemonic' => $seedinfo['mnemonic']
                   ];
            
            $k = $key_type;
            $pf = '';
            
            $scriptFactory = $this->getScriptDataFactoryForKeyType($key_type);  // xpub

            $xkey = $this->hkf->fromEntropy($seedinfo['seed'], $scriptFactory);
            $masterkey = $this->toExtendedKey($coin, $xkey, $network, $key_type);
            $row[$pf . 'root-key'] = $masterkey;
    
            // determine bip32 path for ext keys, which requires a bip44 ID for coin.
            $bip32path = $this->getCoinBip44ExtKeyPathPurpose($coin, $purpose);
            if($bip32path) {
                // derive extended priv/pub keys.
                $prv = $xkey->derivePath($bip32path);
                $pub = $prv->withoutPrivateKey();
                $row[$pf . 'path'] = $bip32path;
                $row['xprv'] = $this->toExtendedKey($coin, $prv, $network, $key_type);
                $row['xpub'] = $this->toExtendedKey($coin, $pub, $network, $key_type);
                $row['comment'] = null;
            }
            else {
                $row[$pf . 'path'] = null;
                $row['xprv'] = null;
                $row['xpub'] = null;
                $row['comment'] = "Bip44 ID is missing for this coin, so extended keys not derived.";
            }
            $rows[] = $row;
        }
        return $rows;
    }
    
    public function genRandomKeyForNetwork($coin) {
        $seedinfo = $this->genRandomSeed();
        return $this->genKeysFromSeed($coin, $seedinfo);
    }
    
    public function genRandomKeyForAllChains() {
        $seedinfo = $this->genRandomSeed();
        
        $allcoins = NetworkCoinFactory::getNetworkCoinsList();
        $rows = [];
        echo "Deriving keys... ";
        foreach($allcoins as $coin => $data) {
            echo "$coin, ";
            $rows = array_merge( $rows, $this->genKeysFromSeed($coin, $seedinfo));
        }
        echo "\n\n";
        return $rows;
    }
    
*/
    
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
        return ['seed', 'mnemonic', 'root-key', 'path', 'xprv', 'xpub', 'comment'];
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
        return ['coin', 'seed', 'mnemonic', 'root-key', 'path', 'xprv', 'xpub', 'comment'];
    }
    
}