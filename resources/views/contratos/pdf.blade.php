<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contrato {{ $contrato->numero_contrato }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #1e293b;
            line-height: 1.6;
        }

        /* ===== MEMBRETE ===== */
        .membrete {
            background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 100%);
            color: white;
            padding: 25px 35px;
            margin-bottom: 0;
        }

        .membrete-inner {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .empresa-nombre {
            font-size: 22px;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .empresa-subtitulo {
            font-size: 11px;
            color: #bfdbfe;
            margin-top: 3px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .contrato-num-box {
            text-align: right;
        }

        .contrato-num-label {
            font-size: 9px;
            color: #bfdbfe;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .contrato-num {
            font-size: 16px;
            font-weight: bold;
            color: white;
        }

        /* Banda azul decorativa */
        .banda {
            background: #1d4ed8;
            height: 6px;
        }

        .banda-gold {
            background: #f59e0b;
            height: 3px;
        }

        /* ===== CONTENIDO ===== */
        .contenido {
            padding: 30px 35px;
        }

        .titulo-contrato {
            text-align: center;
            margin-bottom: 25px;
        }

        .titulo-contrato h2 {
            font-size: 16px;
            font-weight: bold;
            color: #1e3a5f;
            text-transform: uppercase;
            letter-spacing: 2px;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 8px;
            display: inline-block;
            padding-left: 20px;
            padding-right: 20px;
        }

        /* ===== PÁRRAFOS ===== */
        .parrafo {
            margin-bottom: 15px;
            text-align: justify;
            line-height: 1.8;
        }

        .parrafo strong {
            color: #1e3a5f;
        }

        /* ===== SECCIÓN DE DATOS ===== */
        .seccion-titulo {
            background: #1e3a5f;
            color: white;
            padding: 6px 12px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 0;
            margin-top: 20px;
        }

        .datos-tabla {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }

        .datos-tabla td {
            padding: 7px 12px;
            border: 1px solid #e2e8f0;
            font-size: 10.5px;
        }

        .datos-tabla td:first-child {
            background: #f0f4ff;
            color: #1e3a5f;
            font-weight: bold;
            width: 35%;
        }

        .datos-tabla td:last-child {
            color: #1e293b;
        }

        /* ===== CLÁUSULAS ===== */
        .clausulas {
            margin-top: 20px;
        }

        .clausula {
            margin-bottom: 12px;
        }

        .clausula-titulo {
            font-weight: bold;
            color: #1e3a5f;
            font-size: 10.5px;
            margin-bottom: 3px;
        }

        .clausula-texto {
            text-align: justify;
            line-height: 1.7;
            color: #334155;
        }

        /* ===== MONTO DESTACADO ===== */
        .monto-box {
            background: linear-gradient(135deg, #eff6ff, #dbeafe);
            border: 2px solid #2563eb;
            border-radius: 6px;
            padding: 12px 20px;
            text-align: center;
            margin: 15px 0;
        }

        .monto-label {
            font-size: 9px;
            color: #3b82f6;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .monto-valor {
            font-size: 22px;
            font-weight: bold;
            color: #1e3a5f;
        }

        .monto-periodo {
            font-size: 10px;
            color: #64748b;
        }

        /* ===== FIRMAS ===== */
        .firmas {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }

        .firma-box {
            width: 42%;
            text-align: center;
        }

        .firma-linea {
            border-top: 1.5px solid #1e3a5f;
            margin-bottom: 6px;
            padding-top: 6px;
        }

        .firma-nombre {
            font-weight: bold;
            color: #1e3a5f;
            font-size: 10.5px;
        }

        .firma-cargo {
            font-size: 9px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* ===== PIE ===== */
        .pie {
            background: #1e3a5f;
            color: #bfdbfe;
            text-align: center;
            padding: 10px;
            font-size: 9px;
            margin-top: 30px;
            letter-spacing: 1px;
        }

        .sello-pagado {
            position: absolute;
            top: 350px;
            right: 50px;
            border: 4px solid #16a34a;
            color: #16a34a;
            font-size: 28px;
            font-weight: bold;
            padding: 10px 20px;
            transform: rotate(-20deg);
            opacity: 0.15;
            text-transform: uppercase;
            letter-spacing: 3px;
        }
    </style>
</head>
<body>

    <!-- MEMBRETE -->
    <div class="membrete">
        <div class="membrete-inner">
            <div>
                <div class="empresa-nombre">🏭 Mini Bodegas Asercom</div>
                <div class="empresa-subtitulo">Soluciones en almacenamiento — Renta de bodegas</div>
            </div>
            <div class="contrato-num-box">
                <div class="contrato-num-label">N° de Contrato</div>
                <div class="contrato-num">{{ $contrato->numero_contrato }}</div>
                <div style="font-size:9px; color:#bfdbfe; margin-top:3px;">
                    Fecha: {{ now()->format('d/m/Y') }}
                </div>
            </div>
        </div>
    </div>
    <div class="banda"></div>
    <div class="banda-gold"></div>

    <div class="contenido">

        <!-- Título -->
        <div class="titulo-contrato">
            <h2>Contrato de Arrendamiento de Bodega</h2>
        </div>

        <!-- Párrafo introductorio -->
        <div class="parrafo">
            Conste por el presente documento el <strong>CONTRATO DE ARRENDAMIENTO DE BODEGA</strong>
            que celebran de una parte <strong>MINI BODEGAS ASERCOM</strong>, en calidad de
            <strong>ARRENDANTE</strong>; y de la otra parte el/la señor(a)
            <strong>{{ strtoupper($contrato->cliente->nombre . ' ' . $contrato->cliente->apellido) }}</strong>,
            quien se identifica con DPI número
            <strong>{{ $contrato->cliente->dpi ?: '______________________' }}</strong>
            y NIT <strong>{{ $contrato->cliente->nit ?: '______________________' }}</strong>,
            en calidad de <strong>ARRENDATARIO</strong>; quienes acuerdan celebrar el presente
            contrato de arrendamiento de conformidad con las cláusulas y condiciones siguientes:
        </div>

        <!-- Datos del Arrendatario -->
        <div class="seccion-titulo">👤 Datos del Arrendatario</div>
        <table class="datos-tabla">
            <tr>
                <td>Nombre Completo</td>
                <td>{{ $contrato->cliente->nombre }} {{ $contrato->cliente->apellido }}</td>
            </tr>
            <tr>
                <td>DPI</td>
                <td>{{ $contrato->cliente->dpi ?: 'No registrado' }}</td>
            </tr>
            <tr>
                <td>NIT</td>
                <td>{{ $contrato->cliente->nit ?: 'No registrado' }}</td>
            </tr>
            <tr>
                <td>Teléfono</td>
                <td>{{ $contrato->cliente->telefono ?: 'No registrado' }}</td>
            </tr>
            <tr>
                <td>Dirección</td>
                <td>{{ $contrato->cliente->direccion ?: 'No registrado' }}</td>
            </tr>
        </table>

        <!-- Datos de la Bodega -->
        <div class="seccion-titulo">🏭 Datos de la Bodega</div>
        <table class="datos-tabla">
            <tr>
                <td>Número de Bodega</td>
                <td><strong>{{ $contrato->bodega->numero }}</strong></td>
            </tr>
            @if($contrato->bodega->nombre)
            <tr>
                <td>Nombre</td>
                <td>{{ $contrato->bodega->nombre }}</td>
            </tr>
            @endif
            @if($contrato->bodega->tamanio_m2)
            <tr>
                <td>Tamaño</td>
                <td>{{ $contrato->bodega->tamanio_m2 }} m²</td>
            </tr>
            @endif
            @if($contrato->bodega->descripcion)
            <tr>
                <td>Descripción</td>
                <td>{{ $contrato->bodega->descripcion }}</td>
            </tr>
            @endif
        </table>

        <!-- Datos del Contrato -->
        <div class="seccion-titulo">📄 Condiciones del Contrato</div>
        <table class="datos-tabla">
            <tr>
                <td>Fecha de Inicio</td>
                <td>{{ \Carbon\Carbon::parse($contrato->fecha_inicio)->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td>Fecha de Vencimiento</td>
                <td>{{ \Carbon\Carbon::parse($contrato->fecha_fin)->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td>Periodicidad de Pago</td>
                <td>{{ ucfirst($contrato->periodicidad) }}</td>
            </tr>
            <tr>
                <td>Día de Pago</td>
                <td>Día {{ $contrato->dia_pago }} de cada período</td>
            </tr>
        </table>

        <!-- Monto destacado -->
        <div class="monto-box">
            <div class="monto-label">Monto de Renta</div>
            <div class="monto-valor">
                Q {{ number_format($contrato->monto, 2) }}
            </div>
            <div class="monto-periodo">Pago {{ $contrato->periodicidad }}</div>
        </div>

        <!-- Cláusulas -->
        <div class="clausulas">
            <div class="clausula">
                <div class="clausula-titulo">PRIMERA — Del objeto del contrato</div>
                <div class="clausula-texto">
                    MINI BODEGAS ASERCOM da en arrendamiento al señor(a)
                    <strong>{{ $contrato->cliente->nombre }} {{ $contrato->cliente->apellido }}</strong>
                    la bodega número <strong>{{ $contrato->bodega->numero }}</strong>, para uso exclusivo
                    de almacenamiento de bienes muebles lícitos. Queda expresamente prohibido el uso
                    de la bodega para fines distintos al almacenamiento, así como el subarrendamiento
                    sin autorización escrita del arrendante.
                </div>
            </div>

            <div class="clausula">
                <div class="clausula-titulo">SEGUNDA — Del plazo</div>
                <div class="clausula-texto">
                    El presente contrato tendrá una vigencia desde el
                    <strong>{{ \Carbon\Carbon::parse($contrato->fecha_inicio)->format('d \d\e F \d\e Y') }}</strong>
                    hasta el
                    <strong>{{ \Carbon\Carbon::parse($contrato->fecha_fin)->format('d \d\e F \d\e Y') }}</strong>,
                    pudiendo ser renovado por acuerdo mutuo entre las partes con al menos 15 días
                    de anticipación al vencimiento.
                </div>
            </div>

            <div class="clausula">
                <div class="clausula-titulo">TERCERA — Del precio y forma de pago</div>
                <div class="clausula-texto">
                    El arrendatario se compromete a pagar la cantidad de
                    <strong>Q {{ number_format($contrato->monto, 2) }} ({{ $contrato->periodicidad }})</strong>
                    en concepto de renta, pagadero el día <strong>{{ $contrato->dia_pago }}</strong>
                    de cada período. El incumplimiento en el pago podrá dar lugar a la rescisión
                    del contrato y al cobro de penalizaciones correspondientes.
                </div>
            </div>

            <div class="clausula">
                <div class="clausula-titulo">CUARTA — De las obligaciones del arrendatario</div>
                <div class="clausula-texto">
                    El arrendatario se obliga a: a) Pagar puntualmente la renta convenida;
                    b) Usar la bodega únicamente para el fin pactado; c) Mantener la bodega
                    en buen estado de conservación; d) No realizar modificaciones sin autorización;
                    e) Devolver la bodega en las mismas condiciones al finalizar el contrato;
                    f) Respetar el reglamento interno de Mini Bodegas Asercom.
                </div>
            </div>

            <div class="clausula">
                <div class="clausula-titulo">QUINTA — De la rescisión</div>
                <div class="clausula-texto">
                    Cualquiera de las partes podrá rescindir el presente contrato con un aviso
                    mínimo de <strong>30 días de anticipación</strong>. En caso de incumplimiento
                    de pago por más de dos períodos consecutivos, MINI BODEGAS ASERCOM podrá
                    rescindir el contrato de forma inmediata.
                </div>
            </div>

            @if($contrato->observaciones)
            <div class="clausula">
                <div class="clausula-titulo">SEXTA — Observaciones y condiciones especiales</div>
                <div class="clausula-texto">{{ $contrato->observaciones }}</div>
            </div>
            @endif
        </div>

        <!-- Aceptación -->
        <div class="parrafo" style="margin-top: 20px;">
            Leído el presente contrato y enteradas las partes de su contenido, efectos y validez
            legal, lo firman de conformidad en la ciudad de Guatemala, el día
            <strong>{{ now()->format('d \d\e F \d\e Y') }}</strong>.
        </div>

        <!-- Firmas -->
        <div class="firmas">
            <div class="firma-box">
                <br><br><br>
                <div class="firma-linea"></div>
                <div class="firma-nombre">Mini Bodegas Asercom</div>
                <div class="firma-cargo">Arrendante</div>
            </div>
            <div class="firma-box">
                <br><br><br>
                <div class="firma-linea"></div>
                <div class="firma-nombre">{{ $contrato->cliente->nombre }} {{ $contrato->cliente->apellido }}</div>
                <div class="firma-cargo">Arrendatario — DPI: {{ $contrato->cliente->dpi ?: '_______________' }}</div>
            </div>
        </div>

    </div>

    <!-- PIE DE PÁGINA -->
    <div class="pie">
        MINI BODEGAS ASERCOM &nbsp;|&nbsp;
        Contrato N° {{ $contrato->numero_contrato }} &nbsp;|&nbsp;
        Generado el {{ now()->format('d/m/Y H:i') }} &nbsp;|&nbsp;
        Documento de carácter legal — Conserve este documento
    </div>

</body>
</html>