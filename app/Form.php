<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{

	//RELATIONSHIPS
    public function school() {
    	return $this->belongsTo('App\School');
    }

    protected $fillable = ['name', 'steps', 'school_id', 'hidden_props', 'access_code', 'form_options'];

    protected $casts = [
        'steps' => 'array',
        'form_options' => 'array'
    ];

}
