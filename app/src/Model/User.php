<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
	protected $table = 'users';

	public $name;

	public $email;

	public $first_address;

	public $second_address;

	public $eircode;

	// public $country;

	public $profile_picture;

	protected $fillable = [
		'email',
		'name',
		'first_address',
		'second_address',
		'eircode',
		'country_id',
		'password',
	];

	public function setPassword($password)
	{
		$this->update([
			'password' => password_hash($password, PASSWORD_DEFAULT)
		]);
	}

	public function setName($name)
	{
		$this->name = $name;
	}

	public function getName($name)
	{
		return $this->name;
	}

	public function setEmail($email)
	{
		$this->email = $email;
	}

	public function getEmail()
	{
		return $this->email;
	}

	public function setFirstAddress($first_address)
	{
		$this->first_address = $first_address;
	}

	public function getFirstAddress($first_address)
	{
		return $this->first_address;
	}

	public function setSecondAddress($second_address)
	{
		$this->second_address = $second_address;
	}

	public function getSecondAddress($second_address)
	{
		return $this->second_address;
	}

	public function getFullAddress()
	{
		return "$this->first_address $this->second_address";
	}

	public function setEircode($eircode)
	{
		$this->eircode = $eircode;
	}

	public function getEircode($eircode)
	{
		return $this->eircode;
	}

	public function country()
	{
		return $this->hasOne('App\Model\Country');
	}

	public function images()
    {
        return $this->belongsToMany('App\Model\Image');
    }

}