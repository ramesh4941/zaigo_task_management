<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'user_id',
        'comment_content',
        'attachment',
        'delete_comment',
    ];

    public function commented_used(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
