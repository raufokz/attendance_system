<?php
namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'pin_code' , 'profile_picture',
    ];

    protected $hidden = [
        'pin_code', 'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getRouteKeyName()
    {
        return 'name';
    }



// Add this accessor to get full URL of profile picture
public function getProfilePictureUrlAttribute()
{
    if ($this->profile_picture) {
        return Storage::url($this->profile_picture);
    }
    return asset('assets/images/profile-icon.png');
}

    public function hasAnyRole($roles)
    {
        if (is_array($roles)) {
            foreach ($roles as $role) {
                if ($this->hasRole($role)) {
                    return true;
                }
            }
        } else {
            if ($this->hasRole($roles)) {
                return true;
            }
        }

        return false;
    }
public function roles()
{
    return $this->belongsToMany(Role::class, 'role_users', 'user_id', 'role_id');
}

    public function hasRole($role)
    {
        $firstRole = $this->roles()->first();
        if ($firstRole && $firstRole->slug === $role) {
            return true;
        }
        return false;
    }
}
