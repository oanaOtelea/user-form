<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
	protected $table = 'countries';

	public $name;

	public $sortname;

	public $timestamps = false;

	protected $fillable = [
		'name',
		'code'
	];

	public function setName($name)
	{
		$this->name = $name;
	}

	public function getName($name)
	{
		return $this->name;
	}

	public function setCode($code)
	{
		$this->code = $code;
	}

	public function getCode()
	{
		return $this->code;
	}

	public function users()
    {
        return $this->hasMany('App\Model\User');
    }
}