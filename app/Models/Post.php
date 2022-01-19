<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    const BORRADOR = 1;
    const PUBLICADO = 2;

    //Relación uno a muchos inversa
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    //Relación muchos a muchos
    public function tags(){
        return $this->belongsToMany(Tag::class);
    }

    //Relación uno a muchos polimórfica
    public function images(){
        return $this->morphMany(Image::class, 'imageable'); 
    }
}
