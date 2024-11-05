<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>{{ get_section_content('project', 'site_title') }}</title>
</head>
<body>
	<div class="content">
		<h3>Hello {{ $maildata['name'] }},</h3>
		<span>{{$maildata['body']}} .</span><br>
		<span>Thank you!</span><br>

        <span>Regards, <br></span>
        <span><a href="{{ url('/') }}">{{ get_section_content('project', 'site_title') }} </a><br></span>
	</div>
</body>
</html>
