<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
</head>
<body>

    <h2>Tú pago no fue procesado, por favor verifica esa forma de pago o cambiala.</h2>
    <strong>Detalles del Pago</strong><br/>
    <strong>Tarjeta:</strong> {!! $provider !!} {!! $card !!} <br/>
    <strong>Monto:</strong> {!! $amount!!} <br/>
    <table>
    <tr>
         <td style="background-color: black;border-color: black;border: 2px solid black;padding: 10px;text-align: center;">
            <a style="display: block;color: #ffffff;font-size: 12px;text-decoration: none;text-transform: uppercase;"  href="{{ url('/payment/index') }}">
                 Cambiar método de pago
            </a>
        </td>
    </tr>
    </table>
    
</body>
</html>