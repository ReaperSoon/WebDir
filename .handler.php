<?php

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'download':
            $dirtodl = urldecode($_GET['path']);
            $parts = explode('/', $dirtodl);
            $filename = $parts[count($parts)-1].".zip";
            $mime = "application/zip";
            header("Content-Type: " . $mime);
            header("Content-length: " . GetDirectorySize($dirtodl));
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            $cmd = "zip -qr - \"" . $dirtodl . "\"";

            passthru($cmd);
        break;
        case 'rename':
            $oldname = urldecode($_GET['oldname']);
            $newname = urldecode($_GET['newname']);
            rename($oldname, $newname);
        break;
        case 'delete':
            $path = urldecode($_GET['path']);
            $file = urldecode($_GET['file']);
            if (!is_dir('./.Trash')) {
                mkdir('./.Trash');
            }
            rename($path.'/'.$file, './.Trash/'.$file);
            /*unlink($file);*/
        break;
        case 'dirsize':
            $dir = urldecode($_GET['path']);
            $size = pretty_filesize($dir, true);
            echo $size;
        break;
        case 'm3u':
            $path = urldecode($_GET['path']);
            $parts = explode('/', $path);
            $name = $parts[count($parts)-1];

            if (is_dir($path)) {
                $files = getDirContents($path, true, array_merge($videoExts, $audioExts));
            }else {
                $files = [$path];
            }

            header("Content-type: audio/x-mpegurl");
            header("Content-Disposition: inline; filename=".$name.".m3u");
            foreach ($files as $file) {
                $parts = explode('/', $file);
                $name = $parts[count($parts)-1];
                echo "#EXTINF:-1,$name".PHP_EOL;
                echo $hostname.substr($file, 1).PHP_EOL;
            }
        break;
        default:
            header("/");
        break;
    }
} else {
    header("/");
}
?>