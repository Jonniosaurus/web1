<?php

namespace web1;

use Illuminate\Database\Eloquent\Model;
use web1\Type;

class Page extends Model
{
	protected $fillable = ['title', 'slug', 'type_id'];
	public function contents() {
		return $this->hasMany('web1\Content');
	}
	public function types() {
		return $this->belongsTo('web1\Type');
	}
	public function scopeofType($query, $type) {
		return $query->where('type_id', Type::wheretype($type)->first()->id);
	}    
}
