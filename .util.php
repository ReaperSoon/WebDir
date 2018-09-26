<?php

$videoExts = ["3g2","3gp","aaf","asf","avchd","avi","drc","flv","m2v","m4p","m4v","mkv","mng","mov","mp2","mp4","mpe","mpeg","mpg","mpv","mxf","nsv","ogg","ogv","qt","rm","rmvb","roq","svi","vob","webm","wmv","yuv"];
$audioExts = ["wav","bwf","raw","aiff","flac","m4a","pac","tta","wv","ast","aac","mp2","mp3","mp4","amr","s3m","3gp","act","au","dct","dss","gsm","m4p","mmf","mpc","ogg","oga","opus","ra","sln","vox"];

$hostname = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";

function GetDirectorySize($path){
	$bytestotal = 0;
	$path = realpath($path);
	if($path!==false && $path!='' && file_exists($path)){
		foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)) as $object){
			$bytestotal += $object->getSize();
		}
	}
	return $bytestotal;
}

function pretty_filesize($file, $directory = false) {
	$size = 0;
	if ($directory) {
		$size = GetDirectorySize($file);
	}else {
		$size=filesize($file);
	}
	if($size<1024){$size=$size." Bytes";}
	elseif(($size<1048576)&&($size>1023)){$size=round($size/1024, 1)." KB";}
	elseif(($size<1073741824)&&($size>1048575)){$size=round($size/1048576, 1)." MB";}
	else{$size=round($size/1073741824, 1)." GB";}
	return $size;
}

function getDirContents($dir, $onlyFiles = false, $extFilter = array(), &$results = array()){
    $files = scandir($dir);

    foreach($files as $key => $value){
        $path = $dir.DIRECTORY_SEPARATOR.$value;
        if(!is_dir($path)) {
        	if (empty($extFilter) || in_array(pathinfo($path, PATHINFO_EXTENSION), $extFilter))
            $results[] = $path;
        } else if($value != "." && $value != "..") {
            getDirContents($path, $onlyFiles, $extFilter, $results);
            if (!$onlyFiles) $results[] = $path;
        }
    }
    return $results;
}

function containsAudioOrVideo($dir) {
	$files = scandir($dir);
	$videoExts = ["3g2","3gp","aaf","asf","avchd","avi","drc","flv","m2v","m4p","m4v","mkv","mng","mov","mp2","mp4","mpe","mpeg","mpg","mpv","mxf","nsv","ogg","ogv","qt","rm","rmvb","roq","svi","vob","webm","wmv","yuv"];
    $audioExts = ["wav","bwf","raw","aiff","flac","m4a","pac","tta","wv","ast","aac","mp2","mp3","mp4","amr","s3m","3gp","act","au","dct","dss","gsm","m4p","mmf","mpc","ogg","oga","opus","ra","sln","vox"];

    foreach($files as $key => $value){
        $path = $dir.DIRECTORY_SEPARATOR.$value;
        if(!is_dir($path)) {
        	if (in_array(pathinfo($path, PATHINFO_EXTENSION), $videoExts) || in_array(pathinfo($path, PATHINFO_EXTENSION), $audioExts)) {
        	    return true;
        	}
        } else if($value != "." && $value != "..") {
            if (containsAudioOrVideo($path)) {
                return true;
            }
        }
    }
    return false;
}
?>
