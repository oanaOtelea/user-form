<?php

namespace App\Helper\Validation;

use Respect\Validation\Validator as Respect;
use Respect\Validation\Exceptions\NestedValidationException;

class Validator
{
	protected $errors;

	public function constructor()
	{
        Respect::with('App\\Helper\\Validation\\Exceptions');
	}

	public function validate($request, array $rules)
	{
		foreach ($rules as $field => $rule) {
			try {
				$rule->setName(ucfirst(str_replace('_',' ',$field)))->assert($request->getParam($field));
			} catch (NestedValidationException $e) {
				if (!isset($this->errors)) {
					$this->errors = (object)[];
				}
				$this->errors->{$field} = $e->getMessages();
			}
		}

		$_SESSION['errors'] = $this->errors;
		return $this;
	}

	public function failed()
	{
		return !empty($this->errors);
	}
}