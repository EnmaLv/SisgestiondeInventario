<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Orden de compra Nro: {{ $compra->id }}</title>
</head>

<body>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            color: #333333;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #007bff;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 24px;
            margin: 0;
            color: #007bff;
        }

        .container p {
            line-height: 1.5;
            margin: 0 0 10px;
        }

        .info-section {
            background-color: #f9f9f9;
            padding: 15px;
            border-left: 4px solid #007bff;
            margin-bottom: 20px;
        }

        .info-section p {
            margin: 0;
            font-size: 14px;
        }

        .info-section p span {
            font-weight: bold;
        }

        .product-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .product-table th,
        .product-table td {
            border: 1px solid #dddddd;
            padding: 10px;
            text-align: left;
            font-size: 14px;
        }

        .product-table th {
            background-color: #007bff;
            color: #ffffff;
            text-transform: uppercase;
        }

        .total-row {
            font-weight: bold;
            background-color: #e9f5ff;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #888;
            border-top: 1px solid #dddddd;
            padding-top: 20px;
            margin-top: 20px;
        }
    </style>


    <div class="container">
        <div class="header">
            <h1>Detalles de la Compra Nro: {{ $compra->id }}</h1>
        </div>

        <div class="info-section">
            <p><strong>Proveedor: </strong>{{ $proveedor->nombre }}</p>
            <p><strong>Fecha de la Orden: </strong>{{ \Carbon\Carbon::parse($compra->fecha)->format('d/m/Y') }}</p>
        </div>

        <div class="content">
            <p>Estimado/a Proveedor: {{ $proveedor->nombre }}</p>
            <p>Adjuntamos los detalles de la orden Nro: {{ $compra->id }}</p>
        </div>

        <div class="product-table">
            <table>
                <thead>
                    <tr>
                        <th>Nro</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($detalleCompras as $detalle)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $detalle->producto->nombre }}</td>
                            <td>{{ $detalle->cantidad }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="content">
            <p>Gracias por su colaboración.</p>
            <p>Atentamente, Comedor Universitario UPTP JJ Montilla</p>
        </div>
    </div>
</body>

</html>
