<?php

namespace web1;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
	public $timestamps = false;
    public function pages() {
		return $this->hasMany('web1\Page');
	}
}
