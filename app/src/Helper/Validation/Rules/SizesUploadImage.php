<?php

namespace App\Helper\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;
use Respect\Validation\Rules;
use Respect\Validation\Validator as V;

class SizesUploadImage extends AbstractRule {

	protected $uploadedImage;
	protected $imageSizes;

    public function __construct( $uploadedImage, $imageSizes) {
        $this->uploadedImage = $uploadedImage;
        $this->imageSizes = $imageSizes;
    }

    public function validate( $input ) {

    	if (empty($this->uploadedImage->file)) {
    		return false;
    	}

    	$imageSizes = getimagesize($this->uploadedImage->file);

    	if (isset($imageSizes) && $imageSizes[0] >= $this->imageSizes['width'] && $imageSizes[1] >= $this->imageSizes['height']) {
    		return true;
    	}
        
        return false;
    }
}