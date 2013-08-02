<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>Jukebox</title>
	<meta name="viewport" content="width=device-width">
        <meta http-equiv="REFRESH" content="2; /jukebox/">
    <?php echo HTML::style('css/style.css'); ?>
    <?php echo HTML::script('js/jquery-1.9.1.js'); ?>
    <?php echo HTML::script('js/jquery-ui.js'); ?>
</head>
<body>
	<div class="wrapper">
		<header>
			<h1>Jukebox</h1>
			<div class="songbutton"><?php echo HTML::link('jukebox/add', 'Add another song'); ?></div>
			<h2>Song Added</h2>
		    <div class="clearboth"></div>
		</header>
		<div class="main">
		</div>
	</div>
</body>
</html>
