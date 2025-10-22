@extends('adminlte::page')

@section('content_header')
    <h1>Productos</h1>
    <p>Bienvenido {{ auth()->user()->name }}.</p>
@stop

@section('content')
    <input type="hidden" id="FormOpen" value="0">
    <div id="content-wrapper">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h5><i class="icon fas fa-check"></i> ¡Éxito!</h5>
                {{ session('success') }}
            </div>
        @endif
        @include('admin.maestros.productos.indexContent')
        </div>
    </div>
@stop

@section('css')
    <style>
        .ck.ck-editor {
            width: 100% !important;
        }

        .ck.ck-editor__editable {
            width: 100% !important;
            min-height: 300px;
            box-sizing: border-box;
        }

        @media (max-width: 768px) {
            .ck.ck-editor__editable {
                min-height: 250px;
                padding: 10px;
            }
        }
    </style>
@stop

@section('js')
<script>    

    // Inicializa CKEditor en el textarea #descripcion si aún no está inicializado
    function initEditors(){
        try {
            if (typeof ClassicEditor === 'undefined') return;
            const el = document.querySelector('#descripcion');
            if (!el) return;
            // Evitar doble inicialización: si ya existe un contenedor .ck-editor cerca
            if (el.closest('.ck-editor')) return;

            ClassicEditor
                .create(el, {
                    toolbar: {
                        items: [
                            'heading', '|',
                            'bold', 'italic', 'underline', 'strikethrough', 'subscript', '|',
                            'link', 'bulletedList', 'numberedList', '|',
                            'outdent', 'indent', '|',
                            'blockQuote', 'insertTable', 'mediaEmbed', '|',
                            'undo', 'redo', '|',
                            'footBackgroundColor', 'fontColor', 'fontSize', 'fontFamily', '|',
                            'code', 'codeBlock', 'htmlEmbed', '|',
                            'sourceEditing'
                        ],
                        shouldNotGroupWhenFull: true
                    },
                    language: 'es'
                })
                .then(editor => {
                    const editorEl = editor.ui.view.element;
                    if (editorEl) {
                        editorEl.style.width = '100%';
                        const editable = editorEl.querySelector('.ck-editor__editable');
                        if (editable) editable.style.width = '100%';
                    }
                })
                .catch(error => { console.error(error); });
        } catch (e) { console.error(e); }
    }

    // Muestra un alert de éxito (estilo similar al session('success'))
    function showSuccessAlert(message){
        const container = document.querySelector('.col-md-12') || document.body
        // Si ya hay un alert de éxito, actualizar el mensaje y no crear otro
        const existing = container.querySelector('.alert.alert-success.alert-dismissible[data-js-success]')
        if (existing) {
            const msgEl = existing.querySelector('.js-success-message')
            if (msgEl) msgEl.textContent = message
            return
        }

        const wrapper = document.createElement('div')
        wrapper.innerHTML = `
            <div class="alert alert-success alert-dismissible" data-js-success="1">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h5><i class="icon fas fa-check"></i> ¡Éxito!</h5>
                <span class="js-success-message">${message}</span>
            </div>
        `
        // Insertar al inicio del contenedor
        container.insertBefore(wrapper.firstElementChild, container.firstChild)
    }

    function reloadTable(){
        const table = document.querySelector('#example1')
        if(!table) return

        fetch(window.location.href, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.text())
        .then(html => {
            const parser = new DOMParser()
            const doc = parser.parseFromString(html, 'text/html')
            const newTbody = doc.querySelector('#example1 tbody')
            const oldTbody = table.querySelector('tbody')
            if (newTbody && oldTbody) {
                oldTbody.innerHTML = newTbody.innerHTML
            }
        })
        .catch(err => console.error('Error al recargar la tabla:', err))
        
    }
    

    $(document).on('submit', '#editForm', function(e){
        e.preventDefault();
        var $form = $(this);
        var formData = new FormData(this);
        var csrf = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: $form.attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
        }).done(function(resp){
            fetchAndSwap('/admin/maestros/productos', function(){
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'success', title: 'Éxito', text: (resp && resp.message) ? resp.message : 'Producto actualizado correctamente.' });
                }
            });
        }).fail(function(xhr){
            let msg = 'Error al actualizar el producto.';
            if (xhr && xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
            if (typeof Swal !== 'undefined') {
                Swal.fire({ icon: 'error', title: 'Error', text: msg });
            } else {
                alert(msg);
            }
        });
    });



    function injectContent(html){
        const target = document.getElementById('content-wrapper');
        if (target) target.innerHTML = html;
    }

    function fetchAndSwap(url, onDone){
        $.ajax({
            url: url,
            method: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        }).done(function(data){
            injectContent(data);
            // Inicializar plugins que dependan del DOM inyectado
            initEditors();
            history.pushState({urlPath: window.location.pathname}, document.title, url);
            // Marcar estado de formulario según URL (create o edit)
            if (/\/admin\/maestros\/productos\/create(\b|\/|\?|$)/.test(url) || /\/admin\/maestros\/productos\/(\d+|[^\/]+)\/edit(\b|\/|\?|$)/.test(url)) {
                $('#FormOpen').val(1);
            } else {
                $('#FormOpen').val(0);
            }
            if (typeof onDone === 'function') onDone();
        }).fail(function(){
            if (typeof Swal !== 'undefined') {
                Swal.fire('Error', 'No se pudo cargar el contenido.', 'error');
            } else {
                alert('No se pudo cargar el contenido.');
            }
        });
    }

    function GoTo(url){
        var formOpenVal = $('#FormOpen').val();
        if (formOpenVal == 1 && typeof Swal !== 'undefined'){
            Swal.fire({
                title: '¿Quiere salir de esta pantalla?',
                text: 'Esta acción causará que se pierdan los datos del formulario!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, salir!',
                cancelButtonText: 'No, cancelar!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetchAndSwap(url);
                    $('#FormOpen').val(0);
                }
            });
        } else {
            fetchAndSwap(url);
        }
    }

    window.addEventListener('popstate', function (event) {
        if (event.state && event.state.urlPath) {
            // Recargar el contenido para la URL actual
            $.ajax({
                url: location.pathname + location.search,
                method: 'GET',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            }).done(function(data){ 
                injectContent(data);
                initEditors();
            });
        }
    });

    // Envío AJAX de creación: sin recargar, mostrar alerta y volver al listado
    $(document).on('submit', '#createForm', function(e){
        e.preventDefault();
        var $form = $(this);
        var formData = new FormData(this);
        var csrf = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: $form.attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
        }).done(function(resp){
            // Volver al listado y mostrar alerta de éxito
            fetchAndSwap('/admin/maestros/productos', function(){
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'success', title: 'Éxito', text: (resp && resp.message) ? resp.message : 'Producto creado correctamente.' });
                }
            });
        }).fail(function(xhr){
            let msg = 'Error al crear el producto.';
            if (xhr && xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
            if (typeof Swal !== 'undefined') {
                Swal.fire({ icon: 'error', title: 'Error', text: msg });
            } else {
                alert(msg);
            }
        });
    });

    // Inicialización en carga inicial
    document.addEventListener('DOMContentLoaded', initEditors);

</script>
@endsection 
