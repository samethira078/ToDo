<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'table_id',
        'task_name',
        'items',
    ];
    public function options(){
        return $this->hasMany(Option::class);
    }
}
