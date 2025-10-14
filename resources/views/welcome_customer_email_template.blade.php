<!DOCTYPE html>
<html>
	<head>
	    <title>1 Touch Development</title>
	</head>
	<body>
	    <p>Hola {{ $params['name'] }}, Bienvenido a {{ env('APP_NAME') }}</p>
	    <p><a href="{{ url('/') . '/customer-login' }}">Sitio web</a></p>
	    <p>Ahora eres parte de la comunidad.</p>
	    <p>Correo electrónico: {{ $params['email'] }}</p>
	</body>
</html>