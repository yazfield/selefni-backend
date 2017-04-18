<?php

namespace App;

use Carbon\Carbon;
use Config;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;

/**
 * User model class.
 */
class User extends Authenticatable implements HasMedia
{
    use Notifiable, SoftDeletes, HasApiTokens, HasMediaTrait;
    use SingleMediaTrait {
        setSingleMedia as setAvatar;
        getSingleMediaUrl as getAvatarUrl;
    }

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
    protected $fillable = ['name', 'email', 'password', 'phone_number', 'active',
        'activation_code',
        'activation_code_created_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token', 'deleted_at', 'activation_code',
        'activation_code_expires_at', 'active', ];

    /**
     * Cast to Carbon dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at', 'activation_code_expires_at'];

    /**
     * Casting fields to php types.
     *
     * @var array
     */
    protected $casts = ['active' => 'boolean'];

    /**
     * The accessors to append to the model's array form.
     * @var array
     */
    protected $appends = [ 'avatar' ];

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

    public function getAvatarAttribute()
    {
        return $this->getAvatarUrl();
    }

    public function getDefaultMediaUrl(): string
    {
        // FIXME: probably add female / male avatars
        return config('selefni.user.avatar.default');
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
        // TODO: ensure that username is a unique field
        return $this->active()->where(username_field($username), $username)->first();
    }

    // TODO: take out to a trait
    public function activate(int $code)
    {
        if (is_null($this->activation_code_expires_at)) {
            return false;
        }
        if (new Carbon > $this->activation_code_expires_at) {
            return false;
        }
        if ($this->activation_code != $code) {
            return false;
        }
        $this->active = true;
        $this->activation_code = null;
        $this->activation_code_expires_at = null;

        return true;
    }

    public function requestActivation()
    {
        $this->activation_code = rand(1000, 9999);
        $this->activation_code_expires_at = (new Carbon)
                ->addHours(Config::get('selefni.activation_code.lifetime'));
        $this->active = false;

        return $this;
    }

    public function borrowed()
    {
        return $this->hasMany('App\Item', 'borrowed_to');
    }

    public function lent()
    {
        return $this->hasMany('App\Item', 'borrowed_from');
    }
}
