<?php

namespace tester;

require_once __DIR__  . '/tests_common.php';

class mnemonic_wordsets extends tests_common {
    
    static $map = [
        'english_old' =>['seed'     => '202c3b67726c8611ddd2ac22314b32852e94a1d37750898480bee8d2158bb772',
                         'mnemonic' => 'worse barely guilt gain button dove skip insult delicate rise belong former poet core sadness cup ghost again jealous poem soul moment maybe swim former'],
        'english' =>    ['seed'     => '202c3b67726c8611ddd2ac22314b32852e94a1d37750898480bee8d2158bb772',
                         'mnemonic' => 'gained huddle software lively reef sieve seismic somewhere vessel different intended village pelican oars inquest language luggage agreed tufts either arrow befit aplomb long intended'],
        'japanese' =>   ['seed'     => '202c3b67726c8611ddd2ac22314b32852e94a1d37750898480bee8d2158bb772',
                         'mnemonic' => 'けいれき こさめ てまきずし しなもの たんか ていさつ つのる てれび のっく がいこう こゆび のりゆき たあい ぜんあく こやく さんすう しみん あらためる にっせき きくらげ いっぽう うけとる いたりあ しはつ いっぽう'],
        'portuguese' => ['seed'     => '202c3b67726c8611ddd2ac22314b32852e94a1d37750898480bee8d2158bb772',
                         'mnemonic' => 'faquir golpista rolo jegue pajem reivindicavel radonio rorqual urologo dablio heterossexual utero obituario mofo heptassilabo iota jorro agentes teatrologo dulija aresta barulho anzol jetom dablio'],
        'spanish' =>    ['seed'     => '202c3b67726c8611ddd2ac22314b32852e94a1d37750898480bee8d2158bb772',
                         'mnemonic' => 'dental esfuerzo ogro goma mina nuera nieto ola pulga calma familia pupila lombriz justo falso gaita grúa ágil petróleo chuleta andar asado amapola gráfico mina'],
    ];
    
    public function runtests() {
        $this->test_mnemonic_decode();
        $this->test_mnemonic_encode();
        $this->test_all_wordsets();
    }
    
    protected function test_all_wordsets() {
        
        // obtain wordset list
        $cmd = sprintf('php %s/../subaddress-derive-xmr --help-wordsets --format=list', escapeshellarg(__DIR__) );
        $output = $this->exec_cmd($cmd, 1, '--help-wordsets');
        $wordsets = explode("\n", trim($output));
        
        foreach($wordsets as $ws) {
            // generate mnemonic from seed for this wordset
            $params = ['seed' => self::$map['english']['seed'],
                       'wordset' => $ws,
                       'wallet-keys' => null,
            ];
            $result1 = $this->derive_params( $params );

            $params = ['mnemonic' => @$result1['mnemonic'],
                       'wallet-keys' => null,
            ];
            $result2 = $this->derive_params( $params );
            
            $this->eq(@$result2['seed'], @self::$map['english']['seed'], "verify decode(encode(seed)) == seed for $ws");
        }
    }
    
    protected function test_mnemonic_decode() {
        
        foreach(self::$map as $wordset => $values) {

            // check xprv derivation results in correct addresses.
            $params = ['mnemonic' => $values['mnemonic'],
                       'wallet-keys' => null,
            ];
            
            // note: the wordset (lang) is detected automatically from the mnemonic.
        
            // tests default value for --key-type
            $result = $this->derive_params( $params );
            $expect = $values['seed'];
            $this->eq( @$result['seed'], $expect, "mnemonic decode ($wordset)" );
        }
    }

    protected function test_mnemonic_encode() {
        
        foreach(self::$map as $wordset => $values) {

            // check xprv derivation results in correct addresses.
            $params = ['seed' => $values['seed'],
                       'wordset' => $wordset,  // must be specified when generating a mnemonic.
                       'wallet-keys' => null,
            ];
            
            // tests default value for --key-type
            $result = $this->derive_params( $params );
            $expect = $values['mnemonic'];
            $this->eq( @$result['mnemonic'], $expect, "mnemonic encode ($wordset)" );
        }
    }
    
    
    
}
