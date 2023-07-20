<?php
//Department.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = "department";

	public function transactionTypes()
    {
        return $this->hasMany(TransactionType::class);
    }

	public function user()
	{
	    return $this->belongsTo('App\Models\User');
	}
}