<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupDecision extends Model
{
    use HasFactory;

    protected $table = 'groupdecision';

    protected $fillable = ['id', 'node', 'email'];

}
