<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pedido FACTURADO #{{ $pedido->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
        h1, h3 { margin: 0; padding: 0; }
    </style>
</head>
<body>
    <h1>Pedido Facturado #{{ $pedido->id }}</h1>
    <p><strong>Cliente:</strong> {{ $cliente->razon_social ?? 'N/A' }}</p>
    <p><strong>Fecha:</strong> {{ $pedido->created_at->format('d/m/Y H:i') }}</p>
    <p><strong>Método de Pago:</strong> {{ $pedido->metodo_pago }}</p>
    <p><strong>Tipo de Precio:</strong> {{ $pedido->tipo_precio }}</p>

    <h3>Productos Pedido Facturado</h3>
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Código</th>
                <th>Cantidad</th>
                <th>Precio Unit.</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($detalles as $detalle)
                <tr>
                    <td>{{ $detalle->producto->nombre_producto ?? 'N/A' }}</td>
                    <td>{{ $detalle->producto->codigo_producto ?? 'N/A' }}</td>
                    <td>{{ $detalle->cantidad }}</td>
                    <td>${{ number_format($detalle->precio_unitario, 2) }}</td>
                    <td>${{ number_format($detalle->subtotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3 style="margin-top:20px;">Total: ${{ number_format($pedido->subtotal, 2) }}</h3>
</body>
</html>
