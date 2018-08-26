<?php
$uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2);
$uri = $uri_parts[0];
$uri_array = explode('/',$uri);

$configJson = file_get_contents(".config");
$config = json_decode($configJson);

if (count($uri_array) >= 2 && file_exists(".".$uri_array[1].".php")) {
   include ".".$uri_array[1].".php";
}else {
   include ".list.php";
}
?>
