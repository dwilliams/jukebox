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
			<div class="songbutton"><?php echo HTML::link('jukebox', 'Back to Now Playing'); ?></div>
			<h2>Pick a Song</h2>
		    <div class="clearboth"></div>
		</header>
		<div class="main" id="accordion">
<?php foreach ($songs as $album => $song_list) { ?>
			<h3 class="addLetter"><?php echo $album; ?></h3>
			<div class="addWrapper" id="<?php echo $album; ?>">
			    <img src="" />
			    <ul class="artistList">
<?php foreach ($song_list as $song_name) { ?>		    
                    <li><?php echo HTML::link('jukebox/add/'.$artist_name.'/'.$album.'/'.$song_name, $song_name); ?></li>
<?php } ?>
			    </ul>
			</div>
<?php } ?>
		</div>
	</div>
</body>
</html>


