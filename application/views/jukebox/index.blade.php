<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>Jukebox</title>
	<meta name="viewport" content="width=device-width">
    <?php echo Asset::styles(); ?>
    <?php echo Asset::scripts(); ?>
<script>
$(function() {
$( "#accordion" ).accordion({ active: false, collapsible: true });
});
</script>
</head>
<body>
	<div class="wrapper">
		<header>
			<h1>Jukebox</h1>
			<div class="songbutton"><?php echo HTML::link('jukebox/add', 'Add a song'); ?></div>
			<h2>Now Playing</h2>
		    <div class="clearboth"></div>
		</header>
		<div class="main">
		  <div class="nowplaying">
		    <img src="" />
		    <div class="songtitle"><?php echo $currentsong[0]; ?></div>
		    <div class="artistalbum"><?php echo $currentsong[1]; ?></div>
		    <div class="artistalbum"><?php echo $currentsong[2]; ?></div>
		    <div class="clearboth"></div>
		  </div>
		  <?php foreach ($oldsongs as $oldsong) { ?>
		  <div class="lastsong">
		    <img src="" />
		    <div class="songtitle"><?php echo $oldsong[0]; ?></div>
		    <div class="artistalbum"><?php echo $oldsong[1]; ?></div>
		    <div class="artistalbum"><?php echo $oldsong[2]; ?></div>
		    <div class="clearboth"></div>
		  </div>
		  <?php } ?>
		</div>
	</div>
</body>
</html>
