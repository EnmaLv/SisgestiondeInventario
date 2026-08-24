<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use \App\Models\Sucursal;
use \App\Models\Categoria;
use \App\Models\Producto;
use \App\Models\Proveedor;
use \App\Models\Compra;
use \App\Models\Lote;
use \App\Models\ExchangeRates;
use \App\Models\Rol;
use App\Models\salud\EnvasePrimario;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
// MÓDULO TRANSPORTE — Abdias
use \App\Models\BusMarca;
use \App\Models\BusModelo;
use \App\Models\BusTipoCombustible;
use \App\Models\BusVehiculo;
use \App\Models\BusRuta;
use \App\Models\BusParada;
use \App\Models\BusMantenimiento;
use \App\Models\BusViaje;
use \App\Models\BusCargaCombustible;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $hoy = Carbon::now();
        $limite = Carbon::now()->addDays(7);
        $sucursalId = Auth::user()->persona?->sucursal_id ?? 1;
        $user = Auth::user();

        $roleName = $user->role ?? null;
        if (empty($roleName) && method_exists($user, 'roles')) {
            $firstRole = $user->roles()->first();
            $roleName = $firstRole?->nombre ?? null;
        }

        if (is_null(session('modulos_permitidos')) || is_null(session('menu_permissions_user'))) {
            (new \App\AdminLTE\Filters\ModuleFilter)->transform(['key' => 'init_check']);
        }

        if ($roleName && strtolower($roleName) === 'obrero') {
            return redirect()->route('admin.movimientos.registro_comida.index');
        }
        if (!session()->has('modulo_activo') || is_null(session('modulo_activo'))) {
            $sessionPermissions = session('menu_permissions_user', []);

            if (in_array('admin/modulos/seleccionar', $sessionPermissions)) {
                return redirect('admin/modulos/seleccionar');
            }
        }

        $rol = $roleName ? Rol::where('nombre', $roleName)->first() : null;
        $menuPermissions = $rol?->menu_permissions ?? [];
        $isAdministrator = $roleName && strtolower($roleName) === 'administrador';
        $isSecretaria = $roleName && strtolower($roleName) === 'secretaria de bienestar';

        // ── Conteos generales ──────────────────────────────────────────
        $total_envases_primarios = EnvasePrimario::count();
        $total_sucursales        = Sucursal::count();
        $total_categorias        = Categoria::count();
        $total_productos         = Producto::count();
        $total_proveedores       = Proveedor::count();
        $total_compras           = Compra::count();
        
        

        $total_lotes_vencidos = Lote::whereDate('fecha_vencimiento', '<=', $hoy)
            ->where('estado', 1)
            ->count();

        $total_lotes_por_vencer = Lote::whereBetween(
            'fecha_vencimiento',
            [$hoy, $limite]
        )->count();

        $ultimaTasa = ExchangeRates::latest()->first();

        $productos_stock_minimo = Producto::select(
            'productos.id',
            'productos.nombre',
            'productos.stock_minimo',
            DB::raw('COALESCE(SUM(inventario_sucursal_lotes.cantidad), 0) as stock_actual')
        )
            ->join('lotes', 'lotes.producto_id', '=', 'productos.id')
            ->join('inventario_sucursal_lotes', function ($join) use ($sucursalId) {
                $join->on('inventario_sucursal_lotes.lote_id', '=', 'lotes.id')
                    ->where('inventario_sucursal_lotes.sucursal_id', '=', $sucursalId);
            })
            ->where('productos.estado', 1)
            ->groupBy('productos.id', 'productos.nombre', 'productos.stock_minimo')
            ->havingRaw('SUM(inventario_sucursal_lotes.cantidad) <= productos.stock_minimo')
            ->havingRaw('SUM(inventario_sucursal_lotes.cantidad) > 0')
            ->orderBy('stock_actual', 'asc')
            ->get();

        $total_productos_stock_minimo = $productos_stock_minimo->count();

        // MÓDULO TRANSPORTE — Abdias
        $total_bus_marcas  = BusMarca::count();
        $total_bus_modelos = BusModelo::count();
        $total_bus_tipo_combustibles = BusTipoCombustible::count();
        $total_bus_vehiculos = BusVehiculo::count();
        $total_bus_rutas = BusRuta::count();
        $total_bus_paradas = BusParada::count();
        $total_bus_mantenimientos = BusMantenimiento::count();
        $total_bus_viajes = BusViaje::count();
        $total_bus_cargas = BusCargaCombustible::count();

        $menuConfig = config('adminlte.menu');

        $findKeyForUrl = function ($items, $targetUrl) use (&$findKeyForUrl) {
            foreach ($items as $item) {
                if (isset($item['url']) && trim($item['url'], '/') === trim($targetUrl, '/')) {
                    return $item['key'] ?? null;
                }
                if (isset($item['submenu']) && is_array($item['submenu'])) {
                    $found = $findKeyForUrl($item['submenu'], $targetUrl);
                    if ($found !== null) return $found;
                }
            }
            return null;
        };

        $modules = [
            // ── Salud ──────────────────────────────────────────────────
            'envases_primarios' => 'admin/salud/maestros/envases_primarios',
            // ── Comedor ────────────────────────────────────────────────
            'sucursales'        => 'admin/maestros/sucursales',
            'categorias'        => 'admin/maestros/categorias',
            'productos'         => 'admin/maestros/productos',
            'proveedores'       => 'admin/maestros/proveedores',
            'compras'           => 'admin/movimientos/compras',
            'comidas'           => 'admin/maestros/recetas',
            'por_vencer'        => 'admin/movimientos/lotes',
            'registro_comida'   => 'admin/movimientos/registro_comida',
            // ── Transporte — Abdias ───────────────────────────
            'bus_marcas'        => 'admin/transporte/maestros/bus_marcas',
            'bus_modelos'       => 'admin/transporte/maestros/bus_modelos',
            'bus_tipo_combustibles' => 'admin/transporte/maestros/bus_tipo_combustibles',
            'bus_vehiculos'     => 'admin/transporte/maestros/bus_vehiculos',
            'bus_rutas'         => 'admin/transporte/maestros/bus_rutas',
            'bus_paradas'       => 'admin/transporte/maestros/bus_paradas',
            'bus_mantenimientos'=> 'admin/transporte/maestros/bus_mantenimientos',
            'bus_viajes'        => 'admin/transporte/maestros/bus_viajes',
            'bus_carga_combustibles' => 'admin/transporte/maestros/bus_carga_combustibles',
        ];

        $visibleModules = [];
        foreach ($modules as $key => $url) {
            $menuKey = $findKeyForUrl($menuConfig, $url);
            $visible = $isAdministrator || $isSecretaria || ($menuKey && in_array($menuKey, $menuPermissions));
            $visibleModules[$key] = $visible;
        }

        return view('home', [
            'variacion_dolar' => $ultimaTasa?->variacion,
            'tasa_actual'     => $ultimaTasa?->tasa,
            'visibleModules'  => $visibleModules,
        ], compact(
            'total_sucursales',
            'total_categorias',
            'total_productos',
            'total_proveedores',
            'total_compras',
            'total_lotes_vencidos',
            'total_lotes_por_vencer',
            'productos_stock_minimo',
            'total_productos_stock_minimo',
            'total_envases_primarios',
            // ── Transporte — Abdias ────────────────────────────
            'total_bus_marcas',
            'total_bus_modelos',
            'total_bus_tipo_combustibles',
            'total_bus_vehiculos',
            'total_bus_rutas',
            'total_bus_paradas',
            'total_bus_mantenimientos',
            'total_bus_viajes',
            'total_bus_cargas',
        ));
    }
}