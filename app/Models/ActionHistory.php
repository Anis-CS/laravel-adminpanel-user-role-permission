<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActionHistory extends Model
{
    use HasFactory;

    protected $table = 'action_histories';
    protected $fillable = ["action_type", "action_data", "title", "by_user_id", "by_user_name"];
}
