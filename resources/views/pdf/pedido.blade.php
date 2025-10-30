<!DOCTYPE html> 
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pedido #{{ $pedido->id }}</title>
    <style>
    body {
      font-family: Arial, sans-serif;
      font-size: 8px;
      margin: 30px;
    }
    .section {
      margin-bottom: 15px;
    }
    .flex {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
    }
    .flex > div {
      margin: 2px 0;
    }
    .bold {
      font-weight: bold;
    }
    .separator {
      border-top: 1px solid black;
      margin: 10px 0;
    }
    .table, .table th, .table td {
      border: 1px solid black;
      border-collapse: collapse;
    }
    .table {
      width: 100%;
      margin-top: 10px;
    }
    .table th, .table td {
      padding: 4px;
      text-align: left;
    }
    .section-title {
      font-size: 16px;
      font-weight: bold;
      margin-top: 30px;
    }
    .observation {
      margin: 10px 0;
    }
    .flex.centered {
    justify-content: center;
    text-align: center;
  }

  </style>
</head>
<body>

    <!-- Encabezado -->
  <div class="section">
    <div class="bold">REMISIÓN N°: {{ $pedido->id }}</span></div>
  </div>

  <div class="separator"></div>
  <div class="section flex centered">
    <div>
      <strong>Fecha Venta:</strong> {{ $pedido->created_at->format('d/m/Y H:i') }}
      <br>
      <strong>Vendedor:</strong> {{ $pedido->user->name ?? 'N/A' }}
      <br>
      <strong>Forma de pago:</strong> {{ $pedido->metodo_pago }}
    </div>
  </div>

  <div class="separator"></div>

  <!-- Cliente -->
  <div class="section">
    <div><strong>CLIENTE:</strong> {{ $pedido->cliente->razon_social ?? 'N/A' }}</div>
    <br>
    <div><strong>CIUDAD:</strong> {{ $pedido->ciudad ?? 'N/A' }}</div>
  </div>

    {{--<h1>Pedido #{{ $pedido->id }}</h1>
    <p><strong>Cliente:</strong> {{ $cliente->razon_social ?? 'N/A' }}</p>
    <p><strong>Fecha:</strong> {{ $pedido->created_at->format('d/m/Y H:i') }}</p>
    <p><strong>Método de Pago:</strong> {{ $pedido->metodo_pago }}</p>
    <p><strong>Tipo de Precio:</strong> {{ $pedido->tipo_precio }}</p>--}}

    <!-- Productos -->
  <table class="table">
    <thead>
      <tr>
        <th style="width: 10%;">CÓDIGO</th>
        <th style="width: 10%;">UBICACIÓN</th>
        <th style="width: 40%;">NOMBRE</th>
        <th style="width: 5%;">CANT</th>
        <th style="width: 10%;">AJUSTE</th>
        <th style="width: 10%;">UND</th>
        <th style="width: 10%;">TOTAL</th>
        <th style="width: 5%;">EN ALMACEN</th>
      </tr>
    </thead>
    <tbody>
            @foreach($detalles as $detalle)
                <tr>
                    <td>{{ $detalle->producto->codigo_producto ?? 'N/A' }}</td>
                    <td>{{ $detalle->producto->ubicacion ?? 'N/A' }}</td>
                    <td>{{ $detalle->producto->nombre_producto ?? 'N/A' }}</td>
                    <td>{{ $detalle->cantidad }}</td>
                    <td></td>
                    <td>${{ number_format($detalle->precio_unitario, 2) }}</td>
                    <td>${{ number_format($detalle->subtotal, 2) }}</td>
                    <td></td>
                </tr>
            @endforeach
        </tbody>
  </table>

    
    <table align="right" style="width: 23%" class="table">
      <tr>
        
        <td class="bold" style="width: 15%">TOTAL</td>
        <td class="bold" style="width: 85%; font-size: 10px" id="subTotal"> ${{ number_format($pedido->subtotal, 2) }}</td>
       
      </tr>
  </table>

  <div class="separator"></div>

  <!-- Remisionado -->
  <div class="section-title"><span id="tipoCliente"></span></div>

  <div class="section observation">
    <div><strong>OBSERVACIÓN 1:</strong></span>{{ $pedido->primer_comentario }}</div>
    <br>
    <div><strong>OBSERVACIÓN 2:</strong>{{ $pedido->segundo_comentario }}</div>
  </div>

  <table class="table">
    <tr>
      <th>TIPO</th><th>CANTIDAD</th>
      <th>TIPO</th><th>CANTIDAD</th>
      <th>TIPO</th><th>CANTIDAD</th>
      <th>TIPO</th><th>CANTIDAD</th>
      <th>TIPO</th><th>CANTIDAD</th>
    </tr>
    <tr>
      <td>T. SANITARIO</td><td></td>
      <td>CAJA</td><td></td>
      <td>PIRAGUAS</td><td></td>
      <td>CABLE</td><td></td>
      <td>ESPUMAS</td><td></td>
    </tr>
    <tr>
      <td>T. CONDUIT</td><td></td>
      <td>BULTO</td><td></td>
      <td>ACRONAL</td><td></td>
      <td>ALAMBRE</td><td></td>
      <td>PALADRAGAS</td><td></td>
    </tr>
    <tr>
      <td>T. CORTINERO</td><td></td>
      <td>PAQUETE</td><td></td>
      <td>P. GRADAS</td><td></td>
      <td>PLATÓN</td><td></td>
      <td>CANALETAS</td><td></td>
    </tr>
    <tr>
      <td>T. PRESIÓN</td><td></td>
      <td>LIOS</td><td></td>
      <td>BALDES</td><td></td>
      <td>CODAL</td><td></td>
      <td></td><td></td>
    </tr>
    <tr>
      <td>ESQUINERO</td><td></td>
      <td>CABOS</td><td></td>
      <td>MANGUERAS</td><td></td>
      <td>LAVAPLATOS</td><td></td>
      <td></td><td></td>
    </tr>
  </table>

  <div class="separator"></div>

  <!-- Firma y ciclo -->
  <table class="table">
    <tr>
      <th style="width: 10%;">FECHA</th>
      <th style="width: 5%;">HORA INICIAL</th>
      <th style="width: 5%;">HORA FINAL</th>
      <th style="width: 50%;">ACTORES QUE INTERVIENEN EN EL CICLO DE ALISTAMIENTO Y DESPACHO</th>
      <th style="width: 30%;">OBSERVACIONES</th>
    </tr>
    <tr>
      <td></td><td></td><td></td>
      <td>SEPARADO POR:</td>
      <td></td>
    </tr>
    <tr>
      <td></td><td></td><td></td>
      <td>EMPACADO POR:</td>
      <td></td>
    </tr>
    <tr>
      <td></td><td></td><td></td>
      <td>AUDITADO POR:</td>
      <td></td>
    </tr>
  </table>
    
</body>
</html>
