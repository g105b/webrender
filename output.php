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

$fileSize = filesize($filePath);
$fh = fopen($filePath, "r");

header("Content-Type: image/png");
header("Content-Length: $fileSize");
echo fread($fh, $fileSize);

fclose($fh);
unlink($filePath);

exit;
