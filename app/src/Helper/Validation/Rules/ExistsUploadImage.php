<?php

namespace App\Helper\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;
use Respect\Validation\Rules;
use Respect\Validation\Validator as V;

class ExistsUploadImage extends AbstractRule {

	protected $uploadedImage;

    public function __construct( $uploadedImage) {
        $this->uploadedImage = $uploadedImage;
    }

    public function validate( $input ) {
        return V::image()->validate($this->uploadedImage->file);
    }
}