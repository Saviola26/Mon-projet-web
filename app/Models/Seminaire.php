<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seminaire extends Model
{
    
    
    public function presentateur()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function fichiers()
    {
        return $this->hasMany(Fichier::class);
    }

    public function participations()
    {
        return $this->hasMany(Participation::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function participants()
    {
        return $this->belongsToMany(User::class, 'participations')
                ->withPivot('date_inscription')
                ->withTimestamps();
    }

    public function peutAccepterPlusDeParticipants(): bool
    {
        return $this->nombre_participants < $this->nombre_max_participants;
    }

    public function incrementerParticipants(): void
    {
        $this->increment('nombre_participants');
    }

    public function decrementerParticipants(): void
    {
        $this->decrement('nombre_participants');
    }

    protected $fillable = [
        'theme',
        'user_id',
        'date_proposee',
        'date_validee',
        'statut',
        'nombre_max_participants',
        'nombre_participants'
    ];

}
