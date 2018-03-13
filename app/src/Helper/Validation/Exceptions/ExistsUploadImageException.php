<?php

namespace App\Helper\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

class ExistsUploadImageException extends ValidationException {

	public static $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => '{{name}} is missing or is invalid file.'
        ]
    ];
    
}