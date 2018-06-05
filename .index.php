<?php
$uri_array = explode('/',$_SERVER['REQUEST_URI']);

$configJson = file_get_contents(".config");
$config = json_decode($configJson);

if (count($uri_array) >= 2 && file_exists(".".$uri_array[1].".php")) {
   include ".".$uri_array[1].".php";
}else {
   include ".list.php";
}
?>
