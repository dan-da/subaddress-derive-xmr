<?php

namespace tester;

require_once __DIR__  . '/tests_common.php';

class mnemonic extends tests_common {
    
    static $mnemonic = 'neither saved session sixteen bailed bested oxidant smuggled kisses jockey uptight bogeys towel bias ability voucher gables agenda oust gopher inflamed evenings stacking taboo sixteen';
    static $seed = '7f61a703f35e33030f9b36ada5d18c348cb0cce5d4eb7eb4005b5810497af804';
    
    public function runtests() {
        $this->test_mnemonic_class_encode();
        $this->test_mnemonic_class_decode();
    }
    
    protected function test_mnemonic_class_encode() {
        
        $arg_words = [];
        foreach(explode(' ', self::$mnemonic) as $w) {
            $arg_words[] = escapeshellarg($w);
        }
        $cmd = sprintf('php %s/../src/mnemonic.php %s', escapeshellarg(__DIR__), implode(' ', $arg_words) );
        $seed = $this->exec_cmd($cmd, 0, 'seed from mnemonic cmd');
        
        $this->eq($seed, self::$seed, 'seed from mnemonic');
    }

    protected function test_mnemonic_class_decode() {
        
        $cmd = sprintf('php %s/../src/mnemonic.php %s', escapeshellarg(__DIR__), escapeshellarg(self::$seed) );
        $mnemonic = $this->exec_cmd($cmd, 0, 'mnemonic from seed cmd');
        
        $this->eq($mnemonic, self::$mnemonic, 'mnemonic from seed');
    }    
}
