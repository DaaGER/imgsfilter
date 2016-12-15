<?php

$request = parse_url($_SERVER['REQUEST_URI']);
$filename = basename($request['path']);
$filepath = $request['path'];

$arr=$_GET;
unset($arr['h']);
$hash=$filename.implode($arr)."hash";
if (!isset($_COOKIE['imgsfilter']) AND md5($hash) != $_GET['h']) {
    exit('bad hash, bitch');
}

$image = "/var/www/content/" . $filepath;
$im = new Imagick();
$im->readImage($image);

if (!empty($_GET['bg'])) {
    $bg = $_GET['bg'] ? "#".$_GET['bg']: '#fff';
    $im->setImageBackgroundColor($bg);
}

if (!empty($_GET['q'])) {
    $im->setImageCompression(Imagick::COMPRESSION_LZW);
    $im->setImageCompressionQuality($_GET['q']);
}

if (!empty($_GET['s'])) {
    $size = explode("x", $_GET['s']);
    $width = $size[0];
    $height = $size[1];
    $fill = ($width == 0 OR $height == 0) ? false : true;
    $im->thumbnailImage($width, $height, $fill, $fill);
}else if($_GET['sc']){
	$size = explode("x", $_GET['sc']);
	$width = $size[0];
	$height = $size[1];
	$bestfit = !isset($size[2]);
	$im->scaleImage($width, $height, $bestfit);

}else if($_GET['c']){
	$implode = explode('x', $_GET['c']);
	if(count($implode) < 2) $implode[1] = $implode[0];
	$im->cropThumbnailImage( $implode[0], $implode[1] );
}

if($_GET['f']){
	$filters = explode(",", $_GET['f']);
	foreach ($filters as $filter){
		switch ($filter){
			case 'gray':
				$im->modulateImage(100,0,100);
				break;
		}
	}
}

//if (!empty($_GET['c'])) {
//    $size = explode("x", $_GET['s']?:$_GET['c']);
//    $width = $size[0];
//    $height = $size[1];
//    $fill = ($width == 0 OR $height == 0) ? false : true;
//    $im->thumbnailImage($width, $height, $fill, $fill);
//}

header('Content-Type: image/' . $im->getImageFormat());
echo $im;
$im->destroy();


?>
