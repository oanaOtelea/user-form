<?php

namespace App\Helper\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

class SizesUploadImageException extends ValidationException {

	public static $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => 'The minimum sizes for {{name}} are 250x250px.'
        ]
    ];
    
}