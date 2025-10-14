<!DOCTYPE html>
<html>
	<head>
	    <title>Just Convenience</title>
	</head>
	<body>
	    <p>Hola {{ $params['name'] }},</p>
	    <p>{{ isset($data['homeData']['email_template']['welcome_email_text']) ? $data['homeData']['email_template']['welcome_email_text'] : 'Thank you for connecting.' }}</p>
	</body>
</html>