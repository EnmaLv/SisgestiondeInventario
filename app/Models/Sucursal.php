<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    /** @use HasFactory<\Database\Factories\SucursalFactory> */
    use HasFactory;

    protected $table = 'sucursals';

    protected $fillable = [
        'nombre',
        'direccion',
        'telefono',
        'activo',
    ];

    public function inventarioSucursalLotes()
    {
        return $this->hasMany(InventarioSucursalLote::class);
    }

    public function movimientos()
    {
        return $this->hasMany(MovimientoInventario::class);
    }

    public function exportCsv(Request $request)
    {
        $query = Sucursal::query();
        // aplica mismos filtros que index
        if ($request->buscar) { /* ... */ }
        if ($request->activo !== null && $request->activo !== '') { /* ... */ }

        $rows = $query->get(['id','nombre','direccion','telefono','activo']);

        $filename = 'sucursales_'.date('Ymd_His').'.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['ID','Nombre','Direccion','Telefono','Activo']);
            foreach ($rows as $r) {
                fputcsv($out, [$r->id, $r->nombre, $r->direccion, $r->telefono, $r->activo ? 'Activo' : 'Inactivo']);
            }
            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }

}
