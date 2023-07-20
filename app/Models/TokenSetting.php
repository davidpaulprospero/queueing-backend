<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class TokenSetting extends Model
{
    protected $table = "token_setting";

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
}