<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = [
        'title', 'description', 'datetime', 'checked', 'shared', 'user_id'
    ];
    protected $attributes = [
        'checked' => false,
        'shared' => false
    ];

    protected $hidden = [
        'user_id'
    ];

    // protected $with = ['user'];
    protected $with = ['shared_users'];

    // public function user(){
    //     return $this->belongsTo(User::class);
    // }

    public function shared_users(){
        return $this->belongsToMany(User::class, 'shared_tasks', 'task_id', 'user_id');
    }
}
