<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Turno extends Model
{
    use HasFactory;

    protected $table = "turnos";
    protected $primaryKey = "id";

    protected $fillable=[
        'user_id',
        'cola',
        'estado',
    ];

    public function users(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
