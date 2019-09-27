<?php

namespace tester;

require_once __DIR__  . '/tests_common.php';

class subaddresses extends tests_common {
    
    public function runtests() {
        $this->test_derive();
    }
    
    protected function test_derive() {

        $params = [
            'view-key'  => '55cdeaa9a36c83a130e42934fcc7bb7761945fa266f026bf85a4056beafb390f',
            'spend-key' => '9b593fbcdc7a13f95b8cf51274bd3ffc9ef8374ee1fa14400857e927b3f2f6f4',
        ];
        $addrs = [
            '8BFkheDYAXMU7aqrxgBwkNGAxS9bpRYimWcBoVxfhjtkJTugpaV8yYpePzrvEcTdb1KyDnAFk3yf4cs2db4EBfeeSC4sBak',
            '88AAbbFDZruJkAyAZu1hXoK2sDXkm1MEMZhfx5DnqYjS1QNyvcCSxmCPY64pnD853V9gNJenKyDL6Nt37vx5jgBKKg6S9bc',
        ];
        
        // check xprv derivation results in correct addresses.
        $results = $this->derive_params( $params );
        $this->eq( @$results[0]['subaddress'], $addrs[0], '0' );
        $this->eq( @$results[1]['subaddress'], $addrs[1], 'xprv m/1' );
    }
}
