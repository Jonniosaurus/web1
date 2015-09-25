<?php

namespace web1;

use Illuminate\Database\Eloquent\Model;

class Def extends Model
{
	public function contents() {
		return $this->hasMany('web1\Content');
	}
}
