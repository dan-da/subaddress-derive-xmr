<?php

namespace tester;

require_once __DIR__  . '/tests_common.php';

class subaddresses extends tests_common {
    
    public function runtests() {
        $this->test_derive_account_0();
        $this->test_derive_account_1();
    }
    
    protected function test_derive_account_0() {

        $params = [
            'view-priv'  => '55cdeaa9a36c83a130e42934fcc7bb7761945fa266f026bf85a4056beafb390f',
            'spend-pub' => '9b593fbcdc7a13f95b8cf51274bd3ffc9ef8374ee1fa14400857e927b3f2f6f4',
        ];
        $addrs = [
            '47WZB9eJnnNii5iPNYS1nNjFjv2zADboHBiCPWh3dQD3huowVYQxLDF4nPsqrAYuo3aY6V6XRemfn9VEk1z4i791T7oex28',
            '88AAbbFDZruJkAyAZu1hXoK2sDXkm1MEMZhfx5DnqYjS1QNyvcCSxmCPY64pnD853V9gNJenKyDL6Nt37vx5jgBKKg6S9bc',
        ];
        
        // check xprv derivation results in correct addresses.
        $results = $this->derive_params( $params );
        $this->eq( @$results[0]['address'], $addrs[0], '0,0' );
        $this->eq( @$results[1]['address'], $addrs[1], '0,1' );
    }
    
    protected function test_derive_account_1() {

        $params = [
            'view-priv'  => '55cdeaa9a36c83a130e42934fcc7bb7761945fa266f026bf85a4056beafb390f',
            'spend-pub' => '9b593fbcdc7a13f95b8cf51274bd3ffc9ef8374ee1fa14400857e927b3f2f6f4',
            'majorindex' => 1,
        ];
        $addrs = [
            '881oaqSBkr81GtAGvoyJ6k7phLRmjChcrLWXVGPfYf6ebGazbUf6BoCXNAENBU2uKSg7F8579SMTFNe48V8G4KdxLaU9zXh',
            '8854ic9fQM4K2FxZs8amkZcmktM6MnRGhTM3Mfoho8DPT63xNKt8gZsbXKoquC7zfGMAAJTzkhbRR52fGAPTkjyd91Ua4z2',
        ];
        
        // check xprv derivation results in correct addresses.
        $results = $this->derive_params( $params );
        $this->eq( @$results[0]['address'], $addrs[0], '1,0' );
        $this->eq( @$results[1]['address'], $addrs[1], '1,1' );
    }
    
}
