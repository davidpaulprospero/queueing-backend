<?php
//TransactionTypes
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionType extends Model
{
    protected $table = 'transaction_types';
    
    protected $fillable = [
        'department_id',
        'name',
        'key'
    ];
    
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}