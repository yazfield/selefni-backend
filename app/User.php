<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * User model class.
 */
class User extends Authenticatable
{
    use Notifiable, SoftDeletes, HasApiTokens;

    /**
     * Hasher class for password hashing.
     *
     * @var Hasher|null
     */
    public $_hasher = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password', 'phone_number', 'active'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token', 'deleted_at'];

    /**
     * Cast to Carbon dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Casting fields to php types.
     *
     * @var array
     */
    protected $casts = ['active' => 'boolean'];

    /**
     * User constructor.
     *
     * @param array  $attributes
     * @param Hasher $hasher
     */
    public function __construct(array $attributes = [])
    {
        $this->_hasher = app(Hasher::class);
        parent::__construct($attributes);
    }

    /**
     * Hashing password value before saving.
     *
     * @param string $value Password
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = $this->_hasher->make($value);
    }

    /**
     * Active users scope.
     *
     * @param $query
     *
     * @return mixed
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Tells \Passport how to find a user.
     *
     * @param mixed $username
     *
     * @return mixed
     */
    public function findForPassport($username)
    {
        return $this->active()->where(username_field($username), $username)->first();
    }
}
