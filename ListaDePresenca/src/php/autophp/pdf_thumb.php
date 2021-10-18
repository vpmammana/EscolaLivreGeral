<?php



if(isset($_GET["pdf"])){
  $pdf = $_GET["pdf"];
}


$im=new imagick($pdf."[0]");
$im->setImageBackgroundColor("#ffffff");
$im->setImageFormat("png");
$im->thumbnailImage(200,0);
header("Content-Type: image/jpg");
echo $im;

?>
