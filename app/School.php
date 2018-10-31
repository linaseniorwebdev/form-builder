<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{

	//RELATIONSHIPS
    public function forms() {
    	return $this->hasMany('App\Form');
    }

    protected $fillable = ['name'];
}
