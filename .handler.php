<?php

echo "<pre>";

class FlxZipArchive extends ZipArchive {
    /** Add a Dir with Files and Subdirs to the archive;;;;; @param string $location Real Location;;;;  @param string $name Name in Archive;;; @author Nicolas Heimann;;;; @access private  **/
    public function addDir($location, $name) {
        $this->addEmptyDir($name);
        $this->addDirDo($location, $name);
     } // EO addDir;

     /**  Add Files & Dirs to archive;;;; @param string $location Real Location;  @param string $name Name in Archive;;;;;; @author Nicolas Heimann * @access private   **/
     private function addDirDo($location, $name) {
        $name .= '/';         $location .= '/';
      // Read all Files in Dir
        $dir = opendir ($location);
        while ($file = readdir($dir))    {
            if ($file == '.' || $file == '..') continue;
          // Rekursiv, If dir: FlxZipArchive::addDir(), else ::File();
            $do = (filetype( $location . $file) == 'dir') ? 'addDir' : 'addFile';
            $this->$do($location . $file, $name . $file);
        }
    } 
}

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'download':
            $dirtodl = urldecode($_GET['dir']);
            $parts = explode('/', $dirtodl);
            $zipname = '/tmp/webzir-zip/'.$parts[count($parts)-1].".zip";

            $za = new FlxZipArchive;
            $res = $za->open($zipname, ZipArchive::CREATE);
            if ($res === TRUE)    {
                $za->addDir($dirtodl, basename($dirtodl)); $za->close();
                header('Content-Type: application/zip');
                header("Content-Disposition: attachment; filename = $zipname");
                header('Content-Length: ' . filesize($zipname));
                header("Location: $zipname");
            }
            else  { echo 'Could not create a zip archive';}
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
        default:
            header("/");
        break;
    }
} else {
    header("/");
}
?>
