<!DOCTYPE html>
<html>
	<head>
	    <title>Just Convenience</title>
	</head>
	<body>
	    <p>Hola {{ $params['name'] }}, Welcome to {{ env('APP_NAME') }}</p>
	    <p><a href="{{ url('/') . '/signin' }}">Website</a></p>
	    <p>You are part of the community now.</p>
	    <p>Email: {{ $params['email'] }}</p>
	    <p>Password: {{ $params['password'] }}</p>
	</body>
</html>