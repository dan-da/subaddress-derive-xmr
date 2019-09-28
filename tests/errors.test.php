<?php

namespace tester;

require_once __DIR__  . '/tests_common.php';

class errors extends tests_common {
    
    public function runtests() {
        $this->test_required_args();
    }
    
    protected function test_required_args() {
        $expect_str = '(--view-priv and --spend-pub) or --mnemonic or --gen-key must be specified.';
        $expect_rc = 1;        
        
        // check xprv derivation results in correct addresses.
        $params = ['format' => 'list'];
        $this->exec_params_expect_error( $params, $expect_rc, $expect_str, 'missing --key or --nmenonic' );
    }    
}
