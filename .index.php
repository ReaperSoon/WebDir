<?php
$uri_array = explode('/',$_SERVER['REQUEST_URI']);

if (count($uri_array) >= 2 && $uri_array[1] == 'rss') {
   include ".rss.php";
}else {
   include ".list.php";
}
?>
