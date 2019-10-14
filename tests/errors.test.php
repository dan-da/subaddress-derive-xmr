<?php

namespace tester;

require_once __DIR__  . '/tests_common.php';

class errors extends tests_common {
    
    public function runtests() {
        $this->test_required_args();
        $this->test_key_args_together();
        $this->test_mutually_exclusive_args();
    }
    
    protected function test_required_args() {
        $expect_str = '(--view-priv and --spend-pub) or --mnemonic or --gen-wallet or --seed must be specified.';
        $expect_rc = 1;        
        
        // check xprv derivation results in correct addresses.
        $params = ['format' => 'list'];
        $this->exec_params_expect_error( $params, $expect_rc, $expect_str, 'missing required arg' );
    }
    
    protected function test_key_args_together() {
        $expect_str = '--view-priv and --spend-pub must be used together';
        $expect_rc = 1;        
        
        $params = ['format' => 'list', 'view-priv' => 'foo'];
        $this->exec_params_expect_error( $params, $expect_rc, $expect_str, 'view-priv and spend-pub must be used together' );
    }    
    
    protected function test_mutually_exclusive_args() {
        $expect_str = 'These flags are mutually exclusive: --mnemonic, --gen-wallet, --seed, and (--view-priv, --spend-pub)';
        $expect_rc = 1;        
        
        $params = ['format' => 'list', 'gen-wallet' => null, 'view-priv' => 1, 'spend-pub' => 1];
        $this->exec_params_expect_error( $params, $expect_rc, $expect_str, 'mutually exclusive args' );
        
        $params = ['format' => 'list', 'gen-wallet' => null, 'mnemonic' => 1];
        $this->exec_params_expect_error( $params, $expect_rc, $expect_str, 'mutually exclusive args' );
        
        $params = ['format' => 'list', 'gen-wallet' => null, 'seed' => 1];
        $this->exec_params_expect_error( $params, $expect_rc, $expect_str, 'mutually exclusive args' );
        
        $params = ['format' => 'list', 'mnemonic' => 1, 'seed' => 1];
        $this->exec_params_expect_error( $params, $expect_rc, $expect_str, 'mutually exclusive args' );
    }
}
