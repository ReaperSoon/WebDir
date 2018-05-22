<!doctype html>
<?php
$uri_array = explode('/',$_SERVER['REQUEST_URI']);
array_pop($uri_array);

$pageName = urldecode(end($uri_array));
?>
<html>
<head>
   <meta charset="UTF-8">
   <link rel="shortcut icon" href="/.favicon.ico">
   <title>
     <?php
	if($pageName != "") {
	  echo($pageName);
	} else {
	  echo "Main directory";
	}
	?>
   </title>

   <link rel="stylesheet" href="/.style.css">
   <script src="/.sorttable.js"></script>
   <script src="/.video.js"></script>
</head>

<body>
<div id="container">
  <h1>
    <?php
       if($pageName != "") {
       echo($pageName);
       } else {
       echo "Main directory";
       }
               ?>
  </h1>

	
	<?php if($pageName != "") echo "<a class=\"back\" href=\"../\">Back</a>" ?>
	<table class="sortable">
	    <thead>
		<tr>
			<th>Filename</th>
			<th>Type</th>
			<th>Size</th>
			<th>Date Modified</th>
			<th>Embeded player</th>
		</tr>
	    </thead>
	    <tbody><?php

	// Adds pretty filesizes
	function pretty_filesize($file) {
		$size=filesize("." . urldecode($_SERVER['REQUEST_URI']) . $file);
		if($size<1024){$size=$size." Bytes";}
		elseif(($size<1048576)&&($size>1023)){$size=round($size/1024, 1)." KB";}
		elseif(($size<1073741824)&&($size>1048575)){$size=round($size/1048576, 1)." MB";}
		else{$size=round($size/1073741824, 1)." GB";}
		return $size;
	}

 	// Checks to see if veiwing hidden files is enabled
	if($_SERVER['QUERY_STRING']=="hidden")
	{$hide="";
	 $ahref="./";
	 $atext="Hide";}
	else
	{$hide=".";
	 $ahref="./?hidden";
	 $atext="Show";}

	 // Opens directory
	 $myDirectory=opendir("./".urldecode($_SERVER['REQUEST_URI']));

	while($entryName=readdir($myDirectory)) {
	   $dirArray[]=$entryName;
	} 
					     

	// Closes directory
	closedir($myDirectory);

	// Counts elements in array
	$indexCount=count($dirArray);

	// Sorts files
	sort($dirArray);

	// Loops through the array of files
	for($index=0; $index < $indexCount; $index++) {

	// Decides if hidden files should be displayed, based on query above.
	    if(substr("$dirArray[$index]", 0, 1)!=$hide) {

	// Resets Variables
		$favicon="";
		$class="file";

	// Gets File Names
		$name=$dirArray[$index];
		$namehref=$dirArray[$index];
		$modtime = date("M j Y g:i A", filemtime("." . urldecode($_SERVER['REQUEST_URI']) . $dirArray[$index]));
		$timekey=date("YmdHis", filemtime("." . urldecode($_SERVER['REQUEST_URI']) . $dirArray[$index]));


       // Array containing all embeddable videos exts
		$embeddableVideoExts = array( "ogv", "mov", "mp4", "mkv", "webm" );
       // Array containing all embeddable audio exts
	        $embeddableAudioExts = array( "mp3", "wav", "ogg" );
	// Separates directories, and performs operations on those directories
		if(is_dir("." . urldecode($_SERVER['REQUEST_URI']) . $dirArray[$index]))
		{
			       $extname="&lt;Directory&gt;";
			       $extn="&lt;Directory&gt;";
				$size="&lt;Directory&gt;";
				$sizekey="0";
				$class="dir";

			// Gets favicon.ico, and displays it, only if it exists.
				if(file_exists("$namehref/favicon.ico"))
					{
						$favicon=" style='background-image:url($namehref/favicon.ico);'";
						$extn="&lt;Website&gt;";
					}

			// Cleans up . and .. directories
				if($name=="."){$name=". (Current Directory)"; $extn="&lt;System Dir&gt;"; $favicon=" style='background-image:url($namehref/.favicon.ico);'";}
				if($name==".."){$name=".. (Parent Directory)"; $extn="&lt;System Dir&gt;";}
		}

	// File-only operations
		else{
			// Gets file extension
			$extn=pathinfo("." . urldecode($_SERVER['REQUEST_URI']) . $dirArray[$index], PATHINFO_EXTENSION);
			// Prettifies file type
			switch ($extn){
				case "png": $extname="PNG Image"; break;
				case "jpg": $extname="JPEG Image"; break;
				case "jpeg": $extname="JPEG Image"; break;
				case "svg": $extname="SVG Image"; break;
				case "gif": $extname="GIF Image"; break;
				case "ico": $extname="Windows Icon"; break;

				case "txt": $extname="Text File"; break;
				case "log": $extname="Log File"; break;
				case "htm": $extname="HTML File"; break;
				case "html": $extname="HTML File"; break;
				case "xhtml": $extname="HTML File"; break;
				case "shtml": $extname="HTML File"; break;
				case "php": $extname="PHP Script"; break;
				case "js": $extname="Javascript File"; break;
				case "css": $extname="Stylesheet"; break;

				case "pdf": $extname="PDF Document"; break;
				case "xls": $extname="Spreadsheet"; break;
				case "xlsx": $extname="Spreadsheet"; break;
				case "doc": $extname="Microsoft Word Document"; break;
				case "docx": $extname="Microsoft Word Document"; break;

				case "zip": $extname="ZIP Archive"; break;
				case "htaccess": $extname="Apache Config File"; break;
			        case "exe": $extname="Windows Executable"; break;

				default: if($extn!=""){$extname=strtoupper($extn)." File";} else{$extname="Unknown";} break;
			}

			// Gets and cleans up file size
				$size=pretty_filesize($dirArray[$index]);
				$sizekey=filesize("." . urldecode($_SERVER['REQUEST_URI']) . $dirArray[$index]);
		}

	// Output
			       echo("<tr class='$class'>");
			       echo("		<td><a href='./" . rawurlencode($namehref) . "' $favicon class='name'>$name</a></td>");
			       echo("		<td><a href='./" . rawurlencode($namehref) . "'>$extname</a></td>");
			       echo("		<td sorttable_customkey='$sizekey'><a href='./$namehref'>$size</a></td>");
			       echo("		<td sorttable_customkey='$timekey'><a href='./$namehref'>$modtime</a></td>");
			       echo("           <td>");
			       if (in_array($extn, $embeddableVideoExts)) {
			          echo("	   <a onclick='playVideo(\"./$namehref\")'>Watch now</a>");
			       }else if (in_array($extn, $embeddableAudioExts)) {
			          echo("           <a onclick='playAudio(\"./$namehref\")'>Listen now</a>");
			       }
		               echo("           </td>");
			       echo("	</tr>");
		}
	}
	?>

	    </tbody>
	</table>
	<div class="media-container">
	  <div id="video-name"></div>
	  <video id="video-player" src="" controls>
	    Votre navigateur n'est pas compatible avec les lecteurs HTML5, merci de télécharger un navigateur décent...
	  </video>
	  <audio id="audio-player" src="" controls>
	    Votre navigateur n'est pas compatible avec les lecteurs HTML5, merci de télécharger un navigateur décent...
	  </audio>
	<div>
	<!--<h2><?php echo("<a href='$ahref'>$atext hidden files</a>"); ?></h2>-->
</div>
</body>
</html>
