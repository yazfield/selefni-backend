<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = ['name', 'details', 'amount', 'type', 'return_at',
        'borrowed_to', 'borrowed_from', ];
    protected $hidden = ['deleted_at'];

    public function borrowedFrom()
    {
        return $this->belongsTo('App\User', 'borrowed_from');
    }

    public function borrowedTo()
    {
        return $this->belongsTo('App\User', 'borrowed_to');
    }
}
