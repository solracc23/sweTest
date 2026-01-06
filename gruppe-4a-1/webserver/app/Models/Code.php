<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Code extends Model
{
    use HasFactory;

    protected $primaryKey = 'code_id';
    public $timestamps = false;
    protected $fillable = ['code','used','name','role'];
}
