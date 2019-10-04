<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'role', 'date_in_position', 'specialties', 'notes', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * For the User/Roles relation
     */
    public function roles() {
        return $this->belongsToMany('App\Role');
    }

    /**
     * To calidating user/roles level
     */
    public function hasAnyRoles($roles) {
        return null !== $this->roles()->whereIn('name', $roles)->first();
    }

    /**
     * To validate user/roles level
     */
    public function hasAnyRole($role) {
        return null !== $this->roles()->where('name', $role)->first();
    }


    /**
     * For the User/Specialty relation (user belongs to many roles)
     */
    public function specialties() {
        return $this->belongsToMany('App\Specialty');
    }

    /**
     * To validate user/specialty level
     */
    public function hasAnySpecialty($specialty) {
        return null !== $this->specialties()->where('name', $specialty)->first();
    }

    /**
     * For the User/Officer relation (officer belongs to one user)
     */
    public function officer()
    {
        $this->belongsTo('App\Models\Officer');
    }

    /**
     * Get the bidding queue that owns the user.
     */
    public function biddingqueue()
    {
        return $this->belongsTo('App\Models\BiddingQueue');
    }

}
