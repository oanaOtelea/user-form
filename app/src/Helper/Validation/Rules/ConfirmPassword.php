<?php

namespace App\Helper\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;

class ConfirmPassword extends AbstractRule {

    protected $password;

    public function __construct( $password) {
        $this->password= $password;
    }

    public function validate( $input ) {
        return $input === $this->password;
    }
}