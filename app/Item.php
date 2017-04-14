<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = ['name', 'details', 'amount', 'type', 'return_at',
        'borrowed_to',
        'borrowed_from',
        'image',
        'returned_at',
        'borrowed_at',
    ];
    protected $hidden = ['deleted_at'];

    protected $dates = [
        'returned_at',
        'borrowed_at',
        'return_at',
    ];

    public function setTypeAttribute($value)
    {
        $this->attributes[ 'type' ] = $value;
        if ( ! isset($this->attributes[ 'image' ])) {
            $this->setImageAttribute();
        }
    }

    public function setImageAttribute($value = null)
    {
        $this->attributes[ 'image' ] = $value ? $value : 'images/'.$this->attributes[ 'type' ].'.jpg';
    }

    public function borrowedFrom()
    {
        return $this->belongsTo('App\User', 'borrowed_from');
    }

    public function borrowedTo()
    {
        return $this->belongsTo('App\User', 'borrowed_to');
    }
}
