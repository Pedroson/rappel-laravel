<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TodoList extends Model
{
    use SoftDeletes;
    protected $fillable = ['user_id', 'name', 'slug'];

    public function User()
    {
        $this->belongsTo('App\User');
    }

    public function setSlugAttribute($value)
    {
        return $this->attributes['slug'] = strtolower(str_replace(' ', '-', $value));
    }
}
