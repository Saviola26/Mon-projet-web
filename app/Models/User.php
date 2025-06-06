<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    
    public function seminaires()
    {
        return $this->hasMany(Seminaire::class);
    }

    public function participations()
    {
        return $this->hasMany(Participation::class);
    }

    public function fichiers()
    {
        return $this->hasMany(Fichier::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function seminairesParticipations()
    {
        return $this->belongsToMany(Seminaire::class, 'participations', 'user_id', 'seminaire_id')
                    ->withPivot('date_inscription')
                    ->withTimestamps();
    }

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        // ajoute d'autres champs si nécessaire
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

}
