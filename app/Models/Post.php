<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $hidden = ['created_at' , 'updated_at'];

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'content'
    ];


    public function category(){
        return $this->belongsTo(category::class);
    }

    public function user(){
        return $this->belongsTo(User::class );
    }

}
