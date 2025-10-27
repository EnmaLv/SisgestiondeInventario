<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pnf extends Model
{
    protected $table = 'pnf';
    protected $id = 'id_pnf';
    protected $fillable = [
        'id_pnf',
        'nombre_pnf',
    ];



    public function personaPnf()
    {
        return $this->hasMany(PersonaPnf::class, 'id_pnf');
    }
}
