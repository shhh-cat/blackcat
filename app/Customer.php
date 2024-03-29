<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Passport\HasApiTokens;
use App\Components\Helper\ImageProcessing;

class Customer extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     *  Generate Api_Token
     */
    public function generateToken()
    {
        $this->api_token = Str::random(60);
        $this->save();

        return $this->api_token;
    }

    /**
     * Find the user instance for the given username.
     *
     * @param  string  $username
     * @return \App\User
     */
    public function findForPassport($username)
    {
        return $this->where('phone', $username)->first();
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname', 'lastname', 'gender', 'email', 'password', 'phone','avatar', 'dob', 'username','block',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','firebase_uid',  'api_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getFullName()
    {
        return ($this->firstname && $this->lastname ? "{$this->firstname} {$this->lastname}" : null);
    }

    public function getAvatar($size = "sm") {
        return !$this->avatar ? \Avatar::create($this->getFullName())->toBase64() : ImageProcessing::getURL($this->avatar,$size);
    }

    public function cartItems()
    {
        return $this->hasMany('App\CartItem');
    }

    public function addresses()
    {
        return $this->hasMany('App\Address');
    }

    public function orders()
    {
        return $this->hasMany('App\Order');
    }
}
