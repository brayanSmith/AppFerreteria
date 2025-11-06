<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pedido FACTURADO #{{ $pedido->id }}</title>
    <style>
    body {
      font-family: Arial, sans-serif;
      font-size: 12px;
      margin: 30px;
      color: #000;
    }

    .header,
    .cliente-info {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
    }

    .centered {
      text-align: center;
    }

    .bold {
      font-weight: bold;
    }

    .red {
      color: red;
    }

    .table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
    }

    .table th,
    .table td {
      border: 1px solid #000;
      padding: 5px;
      text-align: left;
    }

    .table th {
      background-color: #f2f2f2;
    }

    .totals td {
      text-align: right;
    }

    .logo {
      width: 180px;
    }

    .section {
      margin-top: 20px;
    }

    .observacion {
      margin-top: 30px;
    }

    .separator {
      border-top: 2px dashed #333;
      margin: 15px 0;
    }
  </style>
</head>
<body>

    <!-- Logo centrado -->
  <div style="text-align: center; margin-bottom: 20px;">
    <img class="logo" src="data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEAYABgAAD/4QLaRXhpZgAATU0AKgAAAAgABAE7AAIAAAAFAAABSodpAAQAAAABAAABUJydAAEAAAAKAAACyOocAAcAAAEMAAAAPgAAAAAc6gAAAAEAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAVXNlcgAAAAWQAwACAAAAFAAAAp6QBAACAAAAFAAAArKSkQACAAAAAzEzAACSkgACAAAAAzEzAADqHAAHAAABDAAAAZIAAAAAHOoAAAABAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAADIwMjU6MDc6MzAgMDQ6MjE6MjgAMjAyNTowNzozMCAwNDoyMToyOAAAAFUAcwBlAHIAAAD/4QQXaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLwA8P3hwYWNrZXQgYmVnaW49J++7vycgaWQ9J1c1TTBNcENlaGlIenJlU3pOVGN6a2M5ZCc/Pg0KPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyI+PHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj48cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0idXVpZDpmYWY1YmRkNS1iYTNkLTExZGEtYWQzMS1kMzNkNzUxODJmMWIiIHhtbG5zOmRjPSJodHRwOi8vcHVybC5vcmcvZGMvZWxlbWVudHMvMS4xLyIvPjxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PSJ1dWlkOmZhZjViZGQ1LWJhM2QtMTFkYS1hZDMxLWQzM2Q3NTE4MmYxYiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIj48eG1wOkNyZWF0ZURhdGU+MjAyNS0wNy0zMFQwNDoyMToyOC4xMzE8L3htcDpDcmVhdGVEYXRlPjwvcmRmOkRlc2NyaXB0aW9uPjxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PSJ1dWlkOmZhZjViZGQ1LWJhM2QtMTFkYS1hZDMxLWQzM2Q3NTE4MmYxYiIgeG1sbnM6ZGM9Imh0dHA6Ly9wdXJsLm9yZy9kYy9lbGVtZW50cy8xLjEvIj48ZGM6Y3JlYXRvcj48cmRmOlNlcSB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPjxyZGY6bGk+VXNlcjwvcmRmOmxpPjwvcmRmOlNlcT4NCgkJCTwvZGM6Y3JlYXRvcj48L3JkZjpEZXNjcmlwdGlvbj48L3JkZjpSREY+PC94OnhtcG1ldGE+DQogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPD94cGFja2V0IGVuZD0ndyc/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" alt="Logo Distribuidor">
  </div>

  <!-- Información general centrada -->
<div style="text-align: center; margin-bottom: 10px;">
  <div class="bold" style="font-size: 24px;">{{ $empresa->nombre_empresa ?? 'DISTRIGUERRERO' }}</div>
  <div>Distribuidora de Ferreterías</div>
  <div>{{ $empresa->direccion_empresa ?? 'CALLE 7 NUMERO 5-63' }}</div>
  <div>{{ $empresa->telefono_empresa ?? '3105568244' }}</div> 
  <div>{{ $empresa->nit_empresa ?? '1087644203-1' }}</div>
</div>

<!-- Línea con REMISIÓN a la izquierda y FECHA DE VENCIMIENTO a la derecha -->
<div style="display: flex; justify-content: space-between; margin-bottom: 20px;">
  <div><strong>REMISIÓN N°:</strong> <span class="bold">{{ $pedido->id }}</span></div>
  <div><strong>FECHA DE VENCIMIENTO:</strong> <span> {{ $pedido->fecha_vencimiento }}</span> </div>
</div>
<div class="separator"></div>

<div style="text-align: center; margin-top: 10px;">
    <strong>Fecha Venta:</strong> {{ $pedido->fecha->format('d/m/Y H:i') }} <br>
    <strong>Vendedor:</strong> {{ $pedido->user->name ?? 'N/A' }} <br>
    <strong>Forma de pago:</strong> {{ $pedido->metodo_pago }}
  </div>

  <div class="section cliente-info">
    <div>
      <div><strong>CLIENTE:</strong> <span id="nombreCliente">{{ $pedido->cliente->razon_social ?? 'N/A' }}</span></div>
      <div><strong>NIT:</strong> <span id="nDocCliente">{{ $pedido->cliente->numero_documento ?? 'N/A' }}</span></div>
      <div><strong>CIUDAD:</strong> <span id="ciudadCliente">{{ $pedido->cliente->ciudad ?? 'N/A' }}</span></div>
      <div><strong>DIRECCIÓN:</strong> <span id="direccionCliente">{{ $pedido->cliente->direccion ?? 'N/A' }}</span></div>
      <div><strong>TELEFONO:</strong> <span id="telefonoCliente">{{ $pedido->cliente->telefono ?? 'N/A' }}</span></div>
    </div>

    {{--<div style="align-self: flex-start;">
      <strong>SALDO VENCIDO:</strong> <span class="red" id="saldoVencido">{{ $pedido->saldo_vencido ?? 'N/A' }}</span>
    </div>--}}
  </div>

  <div class="section">
    <strong>TIPO </strong> <span id="tipoCliente">{{ $pedido->tipo_venta ?? 'N/A' }}</span>
  </div>

  <h3>Productos Pedido Facturado</h3>
   <!-- Tabla de productos -->
  <table class="table">
    <thead>
      <tr>
        <th>CÓDIGO</th>
        <th>NOMBRE</th>
        <th>CANTIDAD</th>
        <th>UNITARIO</th>
        <th>TOTAL</th>
      </tr>
    </thead>
    @foreach($detalles as $detalle)
                <tr>
                    <td>{{ $detalle->producto->codigo_producto ?? 'N/A' }}</td>
                    <td>{{ $detalle->producto->nombre_producto ?? 'N/A' }}</td>                    
                    <td>{{ $detalle->cantidad }}</td>
                    <td>${{ number_format($detalle->precio_unitario, 2) }}</td>
                    <td>${{ number_format($detalle->subtotal, 2) }}</td>
                </tr>
            @endforeach
    </tbody>
  </table>       

  <!-- Totales -->
  <table class="table totals" style="width: 40%; float: right; margin-top: 10px;">
    <tr>
      <td><strong>Sub Total</strong></td>
      <td><span>{{ number_format($pedido->subtotal, 2) }}</span></td>
    </tr>
    <tr>
      <td><strong>Descuento</strong></td>
      <td><span>{{ number_format($pedido->descuento, 2) }}</span></td>
    </tr>
    <tr>
      <td><strong>Total</strong></td>
      <td><strong id="total">{{ number_format($pedido->total, 2) }}</strong></td>
    </tr>
  </table>
    
 <!-- Observaciones -->
  <div class="observacion">
    <div><strong>OBSERVACIÓN 1:</strong><span id="comentario1">{{ $pedido->comentario1 ?? 'N/A' }}</span></div>
    <div><strong>OBSERVACIÓN 2:</strong><span id="comentario2">{{ $pedido->comentario2 ?? 'N/A' }}</span></div>
  </div>

  <!-- Pie de página -->
<div style="text-align: center; margin-top: 40px; font-size: 12px; border-top: 1px solid #000; padding-top: 10px;">
  PARA CAMBIAR UN PRODUCTO 10 DÍAS CALENDARIO. PARA REPORTAR UN FALTANTE 5 DÍAS CALENDARIO
</div>
    
</body>
</html>
