<?php
namespace App\Service;

class MessageGenerator {
    private string $messageHash;
    public array $data = ['a' => 1, 'b' => 2];
    public string $tmp = 'Publiczne tmp';

    public function __construct(
        callable $generateMessageHash,
    ) {
        $this->messageHash = $generateMessageHash();
    }
    
    public function __get($name)
    {
        if (array_key_exists($name, $this->data)) {
            echo '__get('.$name.')';
            return $this->data[$name];
        }
        if( $name=='messageHashPrivate' )
            echo '__get('.$name.')';
            return $this->messageHash;

        $trace = debug_backtrace();
        trigger_error(
            'Undefined property via __get(): ' . $name .
            ' in ' . $trace[0]['file'] .
            ' on line ' . $trace[0]['line'],
            E_USER_NOTICE);
        return null;
    }
}
