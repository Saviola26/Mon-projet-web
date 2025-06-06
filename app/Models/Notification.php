<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $guarded = [];
    
    public $incrementing = false;

    protected $keyType = 'string';
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function seminaire()
    {
        return $this->belongsTo(Seminaire::class);
    }


}
