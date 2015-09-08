<?php
if(!isset($_GET["url"])
|| !isset($_GET["qs"])) {
	if(strstr($_SERVER["REQUEST_URI"], "&amp;")) {
		$fixed = html_entity_decode($_SERVER["REQUEST_URI"]);
		header("Location: $fixed");exit;
	}
	die("Invalid format");
}

$tmp = sys_get_temp_dir();
$uniq = uniqid("webrender-");

$filePath = "$tmp/$uniq.png";

chdir(__DIR__);
exec("./node_modules/phantomjs/bin/phantomjs render.js "
	. "$_GET[url] $_GET[qs] $filePath");

sleep(3);
$fileSize = filesize($filePath);

header("Content-Type: image/png");
header("Content-Length: $fileSize");

$im = imagecreatefrompng($filePath);
$size = getimagesize($filePath);
$h = $size[1];

if(isset($_GET["ratio"])) {
	$col = imagecolorallocate($im, 9, 17, 53);
	$h = $size[0] / $_GET["ratio"];
	$canvas = imagecreatetruecolor($size[0], $h);
	imagefill($canvas, 10, 10, $col);
}
else {
	$canvas = imagecreatetruecolor($size[0], $size[1]);
}

$y = ($h / 2) - ($size[1] / 2);
imagecopy($canvas, $im, 0, $y, 0, 0, $size[0], $size[1]);

imagepng($canvas);
imagedestroy($im);
imagedestroy($canvas);

unlink($filePath);

exit;
