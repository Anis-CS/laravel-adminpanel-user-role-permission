<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActionData extends Model
{
    protected $table = 'action_data';

    protected $fillable = [
        'user_id',
        'action_type',
        'model_type',
        'model_id',
        'before_data',
        'after_data',
        'changes',
        'route',
    ];

    protected $casts = [
        'before_data' => 'array',
        'after_data' => 'array',
        'changes' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function model()
    {
        return $this->morphTo();
    }
}
