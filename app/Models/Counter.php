<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Counter extends Model
{
    protected $table = 'counter';

    public function tokens()
    {
        return $this->hasMany(Token::class, 'counter_id');
    }
}