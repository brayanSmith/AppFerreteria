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

  <div style="text-align: center; margin-bottom: 40px;">
    @if($empresa && $empresa->logo_empresa)
        @php
            $logoPath = storage_path('app/public/' . $empresa->logo_empresa);
            $logoBase64 = '';
            if (file_exists($logoPath)) {
                $logoData = file_get_contents($logoPath);
                $logoBase64 = 'data:image/' . pathinfo($logoPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode($logoData);
            }
        @endphp
        @if($logoBase64)
            <img 
                src="{{ $logoBase64 }}" 
                alt="Logo Empresa"
                style="width: 120px; height: auto; max-height: 80px; object-fit: contain;"
            />
        @endif
    @endif
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