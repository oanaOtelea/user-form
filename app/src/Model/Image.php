<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
	protected $table = 'images';

	public $filename;

	public $profile_picture;

	protected $fillable = [
		'filename',
		'profile_picture'
	];

	public function setFilename($filename)
	{
		$this->filename = $filename;
	}

	public function getFilename($filename)
	{
		return $this->filename;
	}

	public function setProfilePicture($profile_picture)
	{
		$this->profile_picture = $profile_picture;
	}

	public function getProfilePicture()
	{
		return $this->profile_picture;
	}

    public function users()
    {
        return $this->belongsToMany('App\User');
    }
}