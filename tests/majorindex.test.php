<?php

namespace tester;

require_once __DIR__  . '/tests_common.php';

class majorindex extends tests_common {
    
    public function runtests() {
        $this->test_derive();
    }
    
    protected function test_derive() {

        $params = [
            'view-key'  => '55cdeaa9a36c83a130e42934fcc7bb7761945fa266f026bf85a4056beafb390f',
            'spend-key' => '9b593fbcdc7a13f95b8cf51274bd3ffc9ef8374ee1fa14400857e927b3f2f6f4',
            'majorindex' => 1,
        ];
        $addrs = [
            '881oaqSBkr81GtAGvoyJ6k7phLRmjChcrLWXVGPfYf6ebGazbUf6BoCXNAENBU2uKSg7F8579SMTFNe48V8G4KdxLaU9zXh',
            '8854ic9fQM4K2FxZs8amkZcmktM6MnRGhTM3Mfoho8DPT63xNKt8gZsbXKoquC7zfGMAAJTzkhbRR52fGAPTkjyd91Ua4z2',
        ];
        
        // check xprv derivation results in correct addresses.
        $results = $this->derive_params( $params );
        $this->eq( @$results[0]['major_index'], 1, 'major index' );
        $this->eq( @$results[0]['subaddress'], $addrs[0], 'subaddress 0' );
        $this->eq( @$results[1]['major_index'], 1, 'major index' );
        $this->eq( @$results[1]['subaddress'], $addrs[1], 'subaddress 1' );
    }
}
