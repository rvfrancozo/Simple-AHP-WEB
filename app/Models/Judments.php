<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Judments extends Model
{
    use HasFactory;

    protected $table = 'judments';

    protected $fillable = ['id', 'id_node', 'id_node1', 'id_node2', 'score', 'user_id'];
}
