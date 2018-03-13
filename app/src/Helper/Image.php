<?php

namespace App\Helper;

use Intervention\Image\ImageManager;
use Slim\Http\UploadedFile;
use Monolog\Logger;

class Image
{
	private $imageManager;
	public $imageSettings;
	private $logger;

	public function __construct(ImageManager $imageManager, $imageSettings, Logger $logger)
	{
		$this->imageManager = $imageManager;
		$this->imageSettings = $imageSettings;
		$this->logger = $logger;
	}

	public function saveNewImage(UploadedFile $uploadedImage)
	{
		$newName = $this->generateNewName($uploadedImage);
		$newPath = $this->imageSettings['uploads_path'] . DIRECTORY_SEPARATOR . $newName;

		try {

			$img = $this->imageManager->make($uploadedImage->file);
			$uploadedImageSizes = getimagesize($uploadedImage->file);

			if ($uploadedImageSizes[0] > $this->imageSettings['image_sizes']['width'] && $uploadedImageSizes[1] > $this->imageSettings['image_sizes']['height']) {
				$img->resize($this->imageSettings['image_sizes']['width'], $this->imageSettings['image_sizes']['height']);
			}

			$img->save($newPath);

			return $newName;

		} catch (\Exception $e) {
			$this->logger->info($e->getMessage());
		}
	
		return null;
	}

	private function generateNewName($uploadedImage)
	{
		$extension = pathinfo($uploadedImage->getClientFilename(), PATHINFO_EXTENSION);
        $basename = bin2hex(random_bytes(8));
        $newName = sprintf('%s.%0.8s', $basename, $extension);
        
        return $newName;
	}
}