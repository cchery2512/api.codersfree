<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    //Relación uno a muchos
    public function posts(){
        return $this->hasMany(Post::class);
    }

    public function scopeIncluded(Builder $query){   

        if(empty(request('included'))){
            return;
        }
        
        request()->validate([
            'included' => [
                'array',
                Rule::in(['posts','posts.user'])
            ]
        ]);
        $query->with(request('included'));
    }

    public function scopeFilter(Builder $query){
        if(empty(request('filter'))){
            return;
        }


    }
}
