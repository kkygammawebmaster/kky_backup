<?php
	$name = $_POST["name"];
	$nickname = $_POST["nickname"];
	$hometown = $_POST["hometown"];
	$major = $_POST["major"];
	$minor = $_POST["minor"];
	$class = $_POST["class"];
	$family = $_POST["family"];
	$instrument = $_POST["instrument"];
	$hobby = $_POST["hobby"];
	$favmom = $_POST["favmom"];
	$leadership = $_POST["leadership"];
	$attributes = array('Name' => $name, 'Nickname' => $nickname, 'Hometown' => $hometown,
			'Major' => $major, 'Minor' => $minor, 'Class' => $class, 'Family' => $family, 
			'Instrument' => $instrument, 'Hobby' => $hobby,  'FavMom' => $favmom, 'Leadership' => $leadership);
	foreach ($attributes as $title => $att) {
		file_put_contents($name . ".html", "<strong> {$title}: </strong> $att <br /> ", FILE_APPEND);
	}
	header('Location: complete.html');
	die();
?>
