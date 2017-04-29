<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;

class Item extends Model implements HasMedia
{
    use HasMediaTrait;
    use SingleMediaTrait {
        setSingleMedia as setImage;
        getSingleMediaUrl as getImageUrl;
    }

    protected $fillable = ['name', 'details', 'amount', 'type', 'return_at',
        'borrowed_to', 'borrowed_from', 'returned_at', 'borrowed_at', 'owner_id',
    ];
    protected $hidden = ['deleted_at'];

    protected $dates = [ 'returned_at', 'borrowed_at', 'return_at', ];

    protected $appends = [ 'image' ];

    public function getDefaultMediaUrl(): string
    {
        return config('selefni.item.images.'.$this->type, function () {
            return config('selefni.item.images.default');
        });
    }

    public function getImageAttribute()
    {
        return $this->getImageUrl();
    }

    // FIXME: maybe use a pivot table because if one user deletes the item the other will
    // have it deleted too
    public function borrowedFrom()
    {
        return $this->belongsTo('App\User', 'borrowed_from');
    }

    public function borrowedTo()
    {
        return $this->belongsTo('App\User', 'borrowed_to');
    }

    public function scopeOfUser($query, int $id)
    {
        return $query->where(function ($query) use ($id) {
            $query->where('borrowed_to', $id)
                  ->orWhere('borrowed_from', $id);
        });
    }

    public function scopeReturned($query)
    {
        return $query->whereNotNull('returned_at');
    }

    public function scopeNotReturned($query)
    {
        return $query->whereNull('returned_at');
    }

    public function belongsToUser($id)
    {
        return $this->borrowed_from == $id || $this->borrowed_to == $id;
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

}
