<?php

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'download':
            $dirtodl = urldecode($_GET['dir']);
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
            $dir = urldecode($_GET['dir']);
            $size = pretty_filesize($dir, true);
            echo $size;
        break;
        default:
            header("/");
        break;
    }
} else {
    header("/");
}
?>
