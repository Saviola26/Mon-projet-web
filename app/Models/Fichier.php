<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fichier extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'seminaire_id',
        'type',         
        'nom',          
        'chemin',       
        'type_mime',
        'taille',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function seminaire()
    {
        return $this->belongsTo(Seminaire::class);
    }

}
