<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Http\Requests\CategoriaRequest;
use Illuminate\Http\Request;

/**
 * Controlador para gestionar las operaciones relacionadas con las categorías de productos.
 * 
 * Este controlador maneja las operaciones CRUD para las categorías, incluyendo la visualización,
 * creación, edición y eliminación de categorías, así como la gestión de productos asociados.
 */
class CategoriaController extends Controller
{
    /**
     * Muestra un listado paginado de categorías con opciones de búsqueda y filtrado.
     *
     * @param  \Illuminate\Http\Request  $request  Solicitud HTTP que puede contener parámetros de búsqueda
     * @return \Illuminate\View\View  Vista que muestra la lista de categorías
     */
    public function index(Request $request)
    {
        // Obtener las categorías con los filtros aplicados
        $categorias = Categoria::listarCategorias(
            $request->input('buscar'),  // Término de búsqueda opcional
            $request->input('estado', 1)   // Filtro de estado opcional (activo/inactivo)
        );

        return view('admin.maestros.categorias.index', compact('categorias'));
    }

    /**
     * Muestra el formulario para crear una nueva categoría.
     *
     * @return \Illuminate\View\View  Vista con el formulario de creación
     */
    public function create()
    {
        return view('admin.maestros.categorias.create');
    }

    /**
     * Almacena una nueva categoría en la base de datos.
     *
     * @param  \App\Http\Requests\CategoriaRequest  $request  Solicitud HTTP con los datos validados
     * @return \Illuminate\Http\RedirectResponse  Redirección a la lista de categorías con mensaje de éxito
     */
    public function store(CategoriaRequest $request)
    {
        // Validar los datos del formulario
        $validated = $request->validated();

        // Crear la nueva categoría
        Categoria::crearCategoria($validated);

        // Redirigir con mensaje de éxito
        return redirect()
            ->route('admin.maestros.categorias.index')->with('success', 'Categoría creada exitosamente.');
    }

    /**
     * Muestra los detalles de una categoría específica, incluyendo sus productos asociados.
     *
     * @param  int  $id  ID de la categoría a mostrar
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse  
     *         Vista con los detalles de la categoría o redirección si no se encuentra
     */
    public function show($id)
    {
        // Obtener la categoría con sus productos asociados
        $categoria = Categoria::obtenerCategoriaConProductos($id);

        // Verificar si la categoría existe
        if (!$categoria) {
            return redirect()
                ->route('admin.maestros.categorias.index')
                ->with('mensaje', 'Categoría no encontrada.')
                ->with('icono', 'error');
        }

        // Mostrar la vista de detalles de la categoría
        return view('admin.maestros.categorias.show', compact('categoria'));
    }

    /**
     * Muestra el formulario para editar una categoría existente.
     *
     * @param  int  $id  ID de la categoría a editar
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse  
     *         Formulario de edición o redirección si no se encuentra la categoría
     */
    public function edit($id)
    {
        // Obtener la categoría por su ID
        $categoria = Categoria::obtenerCategoria($id);

        // Verificar si la categoría existe
        if (!$categoria) {
            return redirect()
                ->route('admin.maestros.categorias.index')
                ->with('mensaje', 'Categoría no encontrada.')
                ->with('icono', 'error');
        }

        // Mostrar el formulario de edición con los datos de la categoría
        return view('admin.maestros.categorias.edit', compact('categoria'));
    }

    /**
     * Actualiza una categoría existente en la base de datos.
     *
     * @param  \App\Http\Requests\CategoriaRequest  $request  Datos validados del formulario
     * @param  int  $id  ID de la categoría a actualizar
     * @return \Illuminate\Http\RedirectResponse  Redirección con mensaje de éxito
     */
    public function update(CategoriaRequest $request, $id)
    {
        // Validar los datos del formulario
        $validated = $request->validated();

        // Actualizar la categoría
        Categoria::actualizarCategoria($id, $validated);

        // Redirigir con mensaje de éxito
        return redirect()
            ->route('admin.maestros.categorias.index')->with('success', 'Categoría actualizada exitosamente.');
    }

    /**
     * Elimina una categoría de la base de datos.
     *
     * @param  int  $id  ID de la categoría a eliminar
     * @return \Illuminate\Http\RedirectResponse  Redirección con mensaje de éxito o error
     */
    public function destroy($id)
    {
        // Verificar si la categoría tiene productos asociados
        if (Categoria::tieneProductos($id)) {
            // Contar la cantidad de productos asociados
            $cantidad = Categoria::contarProductos($id);

            // Redirigir con mensaje de error si hay productos asociados
            return redirect()
                ->route('admin.maestros.categorias.index')
                ->with('error', "No se puede eliminar la categoría porque tiene {$cantidad} producto(s) asociado(s).")
                ->with('icono', 'error');
        }

        // Eliminar la categoría si no tiene productos asociados
        Categoria::eliminarCategoria($id);

        // Redirigir con mensaje de éxito
        return redirect()
            ->route('admin.maestros.categorias.index')
            ->with('success', 'Categoría eliminada exitosamente.');
    }

    public function activar($id)
    {
        Categoria::activarCategoria($id);
        return redirect()->route('admin.maestros.categorias.index')->with('success', 'Categoria activada exitosamente.');
    }
}
