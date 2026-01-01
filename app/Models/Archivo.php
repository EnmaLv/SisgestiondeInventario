<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Archivo extends Model
{
    use HasFactory;
    protected $table = 'archivos';

    protected $fillable = [
        'info_estudiantes',
        'fecha',
        'estado',
    ];
}
