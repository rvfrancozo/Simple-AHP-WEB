<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Node extends Model
{
    use HasFactory;

    protected $table = 'node';

    protected $fillable = ['level', 'descr', 'user_id','user_email'];

    public static function funny()
    {
        echo "Oi eu sou goku";
    }
}
