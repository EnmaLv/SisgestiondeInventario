@extends('adminlte::page')

@section('title', 'Preguntas de seguridad')

@section('content')
<div class="container" style="padding:40px;max-width:720px;">
    <h3>Preguntas de seguridad</h3>
    <p>Por seguridad, por favor seleccione dos preguntas personales fáciles de recordar y sus respuestas.</p>

    <form method="POST" action="{{ route('security.questions.save') }}" id="sq-form">
        @csrf

        @php
            $questions = [
                '¿Cuál es el nombre de tu primera mascota?',
                '¿Cuál es el nombre de tu madre?',
                '¿En qué ciudad naciste?',
                '¿Cuál es tu comida favorita?',
                '¿Cuál fue tu primer colegio?',
                '¿Cuál es el segundo nombre de tu padre?',
                'Otro',
            ];
        @endphp

        <div class="form-group">
            <label>Pregunta 1</label>
            <select name="security_questions[0][question_type]" class="form-control" id="q0_type" required>
                <option value="">-- Seleccione una pregunta --</option>
                @foreach($questions as $q)
                    <option value="{{ $q }}">{{ $q }}</option>
                @endforeach
            </select>
            <input type="text" name="security_questions[0][question]" id="q0_custom" class="form-control mt-2" placeholder="Escribe tu pregunta personal" style="display:none">
        </div>

        <div class="form-group">
            <label>Respuesta 1</label>
            <input class="form-control" name="security_questions[0][answer]" required>
        </div>

        <hr>

        <div class="form-group">
            <label>Pregunta 2</label>
            <select name="security_questions[1][question_type]" class="form-control" id="q1_type" required>
                <option value="">-- Seleccione una pregunta --</option>
                @foreach($questions as $q)
                    <option value="{{ $q }}">{{ $q }}</option>
                @endforeach
            </select>
            <input type="text" name="security_questions[1][question]" id="q1_custom" class="form-control mt-2" placeholder="Escribe tu pregunta personal" style="display:none">
        </div>

        <div class="form-group">
            <label>Respuesta 2</label>
            <input class="form-control" name="security_questions[1][answer]" required>
        </div>

        <button class="btn btn-primary">Guardar preguntas</button>
    </form>
</div>
@endsection

@section('js')
<script>
    function toggleCustom(selectEl, inputId){
        var val = selectEl.value;
        var input = document.getElementById(inputId);
        if(val === 'Otro'){
            input.style.display = 'block';
            input.required = true;
        } else {
            input.style.display = 'none';
            input.required = false;
            input.value = '';
        }
    }

    document.addEventListener('DOMContentLoaded', function(){
        var q0 = document.getElementById('q0_type');
        var q1 = document.getElementById('q1_type');
        q0.addEventListener('change', function(){ toggleCustom(q0, 'q0_custom'); });
        q1.addEventListener('change', function(){ toggleCustom(q1, 'q1_custom'); });

        // When submitting, if a custom question field is visible, move its value to the question key
        document.getElementById('sq-form').addEventListener('submit', function(e){
            var q0Type = q0.value;
            var q1Type = q1.value;
            var q0Custom = document.getElementById('q0_custom');
            var q1Custom = document.getElementById('q1_custom');

            // set hidden inputs for actual questions
            var q0Actual = q0Type === 'Otro' ? q0Custom.value : q0Type;
            var q1Actual = q1Type === 'Otro' ? q1Custom.value : q1Type;

            // create or set the question fields
            var existing0 = document.querySelector('input[name="security_questions[0][question]"]');
            if(!existing0){
                var i0 = document.createElement('input');
                i0.type = 'hidden';
                i0.name = 'security_questions[0][question]';
                i0.value = q0Actual;
                this.appendChild(i0);
            } else { existing0.value = q0Actual; }

            var existing1 = document.querySelector('input[name="security_questions[1][question]"]');
            if(!existing1){
                var i1 = document.createElement('input');
                i1.type = 'hidden';
                i1.name = 'security_questions[1][question]';
                i1.value = q1Actual;
                this.appendChild(i1);
            } else { existing1.value = q1Actual; }
        });
    });
</script>
@endsection
