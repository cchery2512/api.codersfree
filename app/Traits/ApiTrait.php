<?php

namespace App\Traits;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rule;

trait ApiTrait{
    
    protected $allowFilter = ['id', 'name', 'slug'];
    protected $allowSort = ['id', 'name', 'slug'];

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
        if(empty($this->allowFilter) || empty(request('filter'))){
            return;
        }

        $filters = request('filter');
        $allowFilter = collect($this->allowFilter);

        foreach ($filters as $filter => $value) {
            if($allowFilter->contains($filter)){
                $query->where($filter, 'like', '%'.$value.'%');
            }
        }
    }


    public function scopeSort(Builder $query){
        if(empty($this->allowSort) || empty(request('sort'))){
            return;
        }

        $sortFields = explode(',', request('sort'));
        $allowSort = collect($this->allowSort);
        foreach ($sortFields as $sortField) {
            $direction = 'asc';
            if(substr($sortField, 0, 1) == '-'){
                $direction = 'desc';
                $sortField = substr($sortField, 1);
            }

            if($allowSort->contains($sortField)){
                $query->orderBy($sortField, $direction);
            }
        }
    }

    public function scopeGetOrPaginate(Builder $query){
        if(request('perPage')){
            $perPage = intval(request('perPage'));

            if($perPage){
                return $query->paginate($perPage);
            }
        }
        return $query->get();
    }
}