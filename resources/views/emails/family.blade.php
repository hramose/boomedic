<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
</head>
<body>
    <img src="{!! $message->embed(public_path() . '/images/logoD.png') !!}" width="170" height="150" style="background-color: gray; align-self: right;" />
    <br>
    <h2>{!! $name !!} te ha añadido como {!! $relationship) !!}</h2>
    

    <p>
    <strong>Detalles del usuario</strong><br/>
    <strong>Nombre de Usuario:</strong> {!! $username !!} <br/>
    <strong>Nombre:</strong> {!! $firstname !!} {!! $lastname !!} <br/>

    <br/><br/>

    <strong>Para aceptar el vinculo familiar has click en el enlace</strong><br/>
        <a href="#" class="btn btn-secondary btn-flat">Test a enlace</a>
    </p>

    
</body>
</html>