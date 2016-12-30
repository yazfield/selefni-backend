<?php

namespace App;

use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class User model
 * @package App
 */
class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password', 'phone_number', 'active',];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token', 'deleted_at',];

    /**
     * Cast to Carbon dates
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Casting fields to php types
     * @var array
     */
    protected $casts = ['active' => 'boolean',];

    /**
     * User constructor.
     * @param array $attributes
     * @param Hasher $hasher
     */
    public function __construct(array $attributes = [], Hasher $hasher)
    {
        parent::__construct($attributes);
        $this->hasher = $hasher;
    }

    /**
     * Hashing password value before saving
     * @param String $value Password
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = $this->hasher->make($value);
    }

    public function scopeActive($query) {
        return $query->where('active', true);
    }
}
