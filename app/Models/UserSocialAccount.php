<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSocialAccount extends Model
{ 
	protected $fillable = ['user_id', 'provider_name', 'provider_id'];
	
    public $timestamps = true;

	public function user()
	{
	    return $this->belongsTo('App\Models\User');
	}
}
