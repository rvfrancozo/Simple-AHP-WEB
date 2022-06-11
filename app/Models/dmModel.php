<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class dmModel extends Model
{
    public $id;
    public $name;
    public $email;

    use HasFactory;
}
