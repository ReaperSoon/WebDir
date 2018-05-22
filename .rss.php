<?php
header('Content-type: text/xml');
/*
	Runs from a directory containing files to provide an
	RSS 2.0 feed that contains the list and modification times for all the
	files.

*/
$feedName = "Steve Alibaba RSS feed";
$feedDesc = "Feed for the my alibaba";
$feedURL = "https://alibaba.stevecohen.fr/rss";
$feedBaseURL = "https://alibaba.stevecohen.fr/"; // must end in trailing forward slash (/).
?><<?= '?'; ?>xml version="1.0"<?= '?'; ?>>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
	<channel>
		<title><?=$feedName?></title>
		<link><?=$feedURL?></link>
		<description><?=$feedDesc?></description>
		<atom:link href="http://gogglesoptional.com/bloopers" rel="self" type="application/rss+xml" />
<?php

function readFiles($path) {
   $files = array();
   $dir=opendir($path);
   while(($file = readdir($dir)) !== false)
   {
	$filepath = $path . '/' . $file;
        $path_info = pathinfo($filepath);

        if ($file[0] !== '.') {
	   if(!is_dir($filepath))                    
           {
		// echo "File: " . $file . PHP_EOL;
                $files[] = array(
			"name" => preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', $path_info['basename']),
			"uri" => implode('/', array_map('urlencode', explode('/', $filepath))),
			"timestamp" => filectime($filepath)	
	        );
           }else {
		//echo "Dir:  " . $file . PHP_EOL;
		//echo "=== Reading dir " . $filepath . " ===" . PHP_EOL;
                $files = array_merge($files, readFiles($filepath));
           }
	}
   }
   closedir($dir);
   return $files;
}
$files = readFiles(".");

//var_dump($files);

for($i=0; $i<count($files); $i++) {
          if (!empty($files[$i]['name'])) {
		echo "	<item>\n";
		echo "		<title>". $files[$i]['name'] ."</title>\n";
		echo "		<link>". $feedBaseURL . substr($files[$i]['uri'], 2) . "</link>\n";
		echo "		<guid>". md5($feedBaseURL . $files[$i]['uri']) . "</guid>\n";
//		echo "		<pubDate>". date("D M j G:i:s T Y", $files[$i]['timestamp']) ."</pubDate>\n";
//		echo "		<pubDate>" . $files[$i]['timestamp'] ."</pubDate>\n";

		echo "    </item>\n";
	  }
}
?>
	</channel>
</rss>
