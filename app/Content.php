<?php

namespace web1;

use Illuminate\Database\Eloquent\Model;
use web1\Page;

class Content extends Model
{
	protected $fillable = ['wrapper_id', 'wrapper_class', 'order', 'content', 'page_id', 'def_id'];
	public $timestamps = false;	
	public function pages() {
		return $this->belongsTo('web1\Page');
	}
	public function def() {
		return $this->belongsTo('web1\Def');
	}
	public function scopeofUri($query, $uri) {
		return $query
			->where('page_id', Page::whereslug($uri)->first()->id)
			->orderBy('order');
	}
}
