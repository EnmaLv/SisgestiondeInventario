<div id="minimalist-design" class="history-design">
    <hr>
    @if (count($registro) > 0)
        <div class="history-container" id="minimalist-container">
            @foreach ($registro as $item)
                <div class="minimalist-item">
                    <div class="minimalist-info">
                        <div class="minimalist-name">{{ $item['nombre'] }}</div>
                        <div class="minimalist-details">
                            <span><strong>Cédula:</strong> {{ $item['cedula'] }}</span>
                            <span><strong>Fecha:</strong> {{ $item['fecha'] }}</span>
                            <span><strong>Hora:</strong> {{ $item['hora'] }}</span>
                            <span><strong>Observación:</strong> {{ $item['observacion'] }}</span>
                        </div>
                    </div>
                    <div class="minimalist-status {{ $item['estado'] === 'Aprobado' ? 'success' : 'error' }}">
                        {{ $item['estado'] }}</div>
                </div>
            @endforeach
        </div>
    @else
        <div class="history-container" id="minimalist-container">
            <div class="minimalist-item">
                <div class="minimalist-info">
                    <div class="minimalist-name text-center ">No hay registros en el historial</div>
                </div>
            </div>
        </div>
    @endif
</div>
