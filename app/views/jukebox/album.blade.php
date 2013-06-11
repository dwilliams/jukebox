<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>Jukebox</title>
	<meta name="viewport" content="width=device-width">
    <?php echo HTML::style('css/style.css'); ?>
    <?php echo HTML::script('js/jquery-1.9.1.js'); ?>
    <?php echo HTML::script('js/jquery-ui.js'); ?>
<script>
$(function() {
$( "#accordion" ).accordion({ active: false, collapsible: true, heightStyle: "content" });
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
			    <img src="/jukebox/pic/<?php echo $album_ids[$album]; ?>" />
			    <div>
			        <ul class="artistList">
<?php foreach ($song_list as $song_name) { ?>		    
                                    <li><?php echo HTML::link('jukebox/add/'.$artist_name.'/'.$album.'/'.$song_name, $song_name); ?></li>
<?php } ?>
			        </ul>
			    </div>
			</div>
<?php } ?>
		</div>
	</div>
</body>
</html>


