<?php

namespace tester;

require_once __DIR__  . '/tests_common.php';

class mnemonic_wordsets extends tests_common {
    
    static $map = [
        'electrum' =>   ['seed'     => '202c3b67726c8611ddd2ac22314b32852e94a1d37750898480bee8d2158bb772',
                         'mnemonic' => 'worse barely guilt gain button dove skip insult delicate rise belong former poet core sadness cup ghost again jealous poem soul moment maybe swim former'],
        'english' =>    ['seed'     => '1b4f17884d2c5175f93b9d5ca9781eb337c0aa57eaf05de59d1046ddd7bfc04a',
                         'mnemonic' => 'threaten jerseys wildly ghetto sneeze emerge reinvest inactive shocking jester audio sidekick altitude goldfish puzzled cause pact memoir axle dude awning loyal torch code pact'],
        'japanese' =>   ['seed'     => '47b9f54157261ce87fc866ff7abff4b103df36a1389b219705ba442acc3013fc',
                         'mnemonic' => 'とくしゅう こうりつ そよかぜ ためいき せりふ しなもの にんむ せまい せつりつ たなばた ばあい そぼろ きかく きちょう にんい ぎじゅつしゃ きんかくじ にしき なにもの いもうと きくばり こもち あこがれる はんめい しなもの'],
        'portuguese' => ['seed'     => 'c65fd303b46ae9f97612a4e48949c59790e9276cc1db020867ed4f9a7b63f2d2',
                         'mnemonic' => 'peixinho xodo acautelar licoroso guaxinim gaviao ornitologo mausoleu induzir helena jingle barulho jeova seborreia ebano usbeque refutavel ruela jaqueta patua exsudar motriz damista afluir refutavel'],
        'spanish' =>    ['seed'     => 'fb4961db71b8e01953cf9660e6ed861ecba25fe3ebf6c23c6e7f9bf82eb31032',
                         'mnemonic' => 'brisa cripta baúl loción pecho razón banda amparo fiel ancho nutria plaza cómodo atún ábaco guion árido desvío preso grano futuro llover disfraz hocico atún'],
    ];
    
    public function runtests() {
        $this->test_mnemonic_decode();
        $this->test_mnemonic_encode();
    }
    
    protected function test_mnemonic_decode() {
        
        foreach(self::$map as $wordset => $values) {

            // check xprv derivation results in correct addresses.
            $params = ['mnemonic' => $values['mnemonic'],
                       'mnemonic-ws' => $wordset,
                       'wallet-keys' => null,
            ];
        
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
                       'mnemonic-ws' => $wordset,
                       'wallet-keys' => null,
            ];
        
            // tests default value for --key-type
            $result = $this->derive_params( $params );
            $expect = $values['mnemonic'];
            $this->eq( @$result['mnemonic'], $expect, "mnemonic encode ($wordset)" );
        }
    }
    
    
    protected function test_mnemonic_btc_testnet() {
        
        // check xprv derivation results in correct addresses.
        $params = ['mnemonic' => self::mnemonic,
                   'coin' => 'BTC-test',
                   'numderive' => 1,
                   'cols' => 'address',
                   'format' => 'list',
                   ];
        
        $address = $this->derive_params( $params );
        $addr_correct = 'mxcgHPeZvhQYypoFqr5GRzQeogjc16dWya';
        $this->eq( $address, $addr_correct, 'btc testnet xprv addr' );
        
        $params['key-type'] = 'y';
        $address = $this->derive_params( $params );
        $addr_correct = '2NAGHgSwxHufJVmvB2ZncTqFvnmk9R7nX6e';
        $this->eq( $address, $addr_correct, 'btc testnet yprv addr' );
        
        $params['key-type'] = 'z';
        $address = $this->derive_params( $params );
        $addr_correct = 'tb1q086tqpwxkk4xdz3jw7pxwgluap0vu7hrvz0f9t';
        $this->eq( $address, $addr_correct, 'btc testnet zprv addr' );        
    }

    protected function test_mnemonic_btc_regtest() {
        
        // check xprv derivation results in correct addresses.
        $params = ['mnemonic' => self::mnemonic,
                   'coin' => 'BTC-regtest',
                   'numderive' => 1,
                   'cols' => 'address',
                   'format' => 'list',
                   ];
        
        $address = $this->derive_params( $params );
        $addr_correct = 'mxcgHPeZvhQYypoFqr5GRzQeogjc16dWya';
        $this->eq( $address, $addr_correct, 'btc regtest xprv addr' );

/* y,z prefix values not yet known for regtest.       
        $params['key-type'] = 'y';
        $address = $this->derive_params( $params );
        $addr_correct = '2NAGHgSwxHufJVmvB2ZncTqFvnmk9R7nX6e';
        $this->eq( $address, $addr_correct, 'btc regtest yprv addr' );
        
        $params['key-type'] = 'z';
        $address = $this->derive_params( $params );
        $addr_correct = 'bcrt1q086tqpwxkk4xdz3jw7pxwgluap0vu7hrwtkyjz';
        $this->eq( $address, $addr_correct, 'btc regtest zprv addr' );
 */
    }
    
}
