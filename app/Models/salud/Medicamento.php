<?php

namespace App\Models\salud;

use App\Models\Unidad;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Traits\ConvierteAMayusculasNoEloquent;
use App\Models\salud\PrecioMedicamento;


class Medicamento extends Model
{
    use ConvierteAMayusculasNoEloquent;
    protected $table = 'medicamentos';

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'imagen',
        'precio_compra',
        'stock_minimo',
        'stock_maximo',
        'peso_contenido',
        'unidad_id',
        'envase_primario_id',
        'categoria_medicamento_id',
        'estado',
        'costo_usd',
    ];

    public function unidad()
    {
        return $this->belongsTo(Unidad::class);
    }

    public function envasePrimario()
    {
        return $this->belongsTo(EnvasePrimario::class);
    }

    public function categoriaMedicamento()
    {
        return $this->belongsTo(CategoriaMedicamento::class);
    }

/*     public function precioMedicamento()
    {
        return $this->hasOne(PrecioMedicamento::class);
    } */

    public static function listar($buscar = null, $estado = null)
    {
        $query = self::with(['categoriaMedicamento', 'unidad']);

        if ($buscar) {
            $query->where('nombre', 'like', "%{$buscar}%");
        }

        if ($estado !== null && $estado !== '') {
            $query->where('estado', (int)$estado);
        }

        return $query->orderBy('id', 'desc')->paginate(10);
    }

    public static function getDatosFormulario()
    {
        return [
            'categorias' => DB::table('categoria_medicamentos')->select('id', 'nombre')->get(),
            'unidades'   => DB::table('unidades')->select('id', 'nombre', 'abreviatura')->get(),
            'envases'    => DB::table('envase_primarios')->select('id', 'nombre')->get(),
        ];
    }

    public static function crear(array $data)
    {
        $helper = new self();

        $data = $helper->convertirCamposAMayusculas($data, ['nombre', 'descripcion']);
        return DB::transaction(function () use ($data) {

            if (empty($data['codigo'])) {
                $categoriaNombre = DB::table('categoria_medicamentos')
                    ->where('id', $data['categoria_medicamento_id'])
                    ->value('nombre') ?? 'CAT';

                $data['codigo'] = self::generarCodigoProducto(
                    $categoriaNombre,
                    $data['nombre']
                );
            } else {
                $data['codigo'] = strtoupper($data['codigo']);
            }
            $unidad = DB::table('unidades')
                ->where('id', $data['unidad_id'])
                ->first();

            $pesoBase = $data['peso_contenido'] * ($unidad->factor_a_base ?? 1);

            $medicamentoId = DB::table('medicamentos')->insertGetId([
                'categoria_medicamento_id'  => $data['categoria_medicamento_id'],
                'envase_primario_id'  => $data['envase_primario_id'],
                'codigo'        => $data['codigo'],
                'nombre'        => $data['nombre'],
                'descripcion'   => $data['descripcion'] ?? null,
                'imagen'        => $data['imagen'] ?? 'imagenes/productos/product-defect.webp',
                'precio_compra' => 0,
                'stock_minimo'  => $data['stock_minimo'] ?? 0,
                'stock_maximo'  => $data['stock_maximo'] ?? 0,
                'peso_contenido' => $pesoBase,
                'unidad_id'     => $data['unidad_id'] ?? null,
                'estado'        => isset($data['estado']) ? (int)$data['estado'] : 1,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);

            PrecioMedicamento::create([
                'medicamento_id' => $medicamentoId,
                'costo_usd'  => $data['costo_usd'],
                'margen'      => $data['margen'] ?? 0
            ]);

            return $medicamentoId;
        });
    }

    protected static function generarCodigoProducto($categoriaNombre, $nombreProducto)
    {
        $cat = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $categoriaNombre), 0, 3) ?: 'CAT');
        $prod = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $nombreProducto), 0, 3) ?: 'PRD');
        $date = now()->format('Ymd');
        $base = "{$cat}-{$prod}";
        $suf = 1;
        do {
            $codigo = "{$base}-" . str_pad($suf, 3, '0', STR_PAD_LEFT);
            $exists = DB::table('medicamentos')->where('codigo', $codigo)->exists();
            $suf++;
        } while ($exists && $suf < 999);

        if ($exists) {
            $codigo = "{$base}-" . strtoupper(substr(uniqid(), -4));
        }

        return $codigo;
    }
}
