<!doctype html>
<?php
$uri_array = explode('/',$_SERVER['REQUEST_URI']);
array_pop($uri_array);

$pageName = urldecode(end($uri_array));

$background = $config->background->url;
if ($config->background->random == true) {
	$ch = curl_init("https://api.unsplash.com/photos/random?query=landscape&featured&orientation=landscape");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Authorization: Client-ID e1c4ece99d2ca64e5f541de11c16d66529394c3084ae2f6e988ca1b86212fee6'
	));
	$unsplash = json_decode(curl_exec($ch));
	curl_close($ch);
	$background = $unsplash->urls->full;
}

?>

<html>
<head>
	<meta charset="UTF-8">
	<link rel="shortcut icon" href="/.favicon.ico">
	<link rel="preload" as="image" href="<?php echo $background ?>">
	</style>
	<title>
		<?php
		if($pageName != "") {
			echo($pageName);
		} else {
			echo "WebDir";
		}
		?>
	</title>

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.4.0/min/dropzone.min.css">
	<link rel="stylesheet" href="/.style.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>   
	<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.4.0/min/dropzone.min.js"></script>
	<script src="/.sorttable.js"></script>
	<script src="/.script.js"></script>
</head>

<body style="background-image: url('<?php echo $background; ?>')">
	<div id="container">
		<h1>
			<?php
			if($pageName != "") {
				echo($pageName);
			} else {
				echo "Alibaba Directory";
			}
			?>
		</h1>
		<div id="menu">
			<?php
			foreach($config->menu as $menu){
				echo '<a href="'.$menu->url.'" target="'.($menu->tab == true ? "_blank" : "parent").'" class="'.($menu->iframe == true ? "oniframe" : "").'"><img src="'.$menu->image.'"></a>';
			}
			?>
		</div>
		<?php
		if ($config->enableRSS == true) {
			echo '<a href="/rss"><img class="rss" src="/.images/rss.png" height="16" width="16"></a>';
		}
		?>


		<?php if($pageName != "") echo "<a class=\"back\" href=\"../\">Back</a>" ?>
		<table id="uploadFile" class="sortable dropzone">
			<thead>
				<tr>
					<th>Filename</th>
					<th>Type</th>
					<th>Size</th>
					<th>Date Modified</th>
					<th></th>
				</tr>
			</thead>
			<tbody class=""><?php

			$hide=".";
			$myDirectory=opendir("./".urldecode($_SERVER['REQUEST_URI']));
			$dirArray = [];

			while($entryName=readdir($myDirectory)) {
				$dirArray[]=$entryName;
			} 


			closedir($myDirectory);

			$indexCount=count($dirArray);

			sort($dirArray);

			for($index=0; $index < $indexCount; $index++) {
				$filePath = "." . urldecode($_SERVER['REQUEST_URI']) . $dirArray[$index];
				$isDir = is_dir($filePath);

				if(substr("$dirArray[$index]", 0, 1)!=$hide) {

					$favicon="";
					$class="file";

					$name=$dirArray[$index];
					$namehref=$dirArray[$index];
					$modtime = date("M j Y g:i A", filemtime($filePath));
					$timekey=date("YmdHis", filemtime($filePath));


					$embeddableVideoExts = array( "ogv", "mov", "mp4", "mkv", "webm" );
					$embeddableAudioExts = array( "mp3", "wav", "ogg" );
					if($isDir)
					{
						$extname="&lt;Directory&gt;";
						$extn="&lt;Directory&gt;";
						$size="show";//pretty_filesize($filePath, true);
						$sizekey="0";
						$class="dir";

						if(file_exists("$namehref/favicon.ico"))
						{
							$favicon=" style='background-image:url($namehref/favicon.ico);'";
							$extn="&lt;Website&gt;";
						}

					} 
					else
					{
						$extn=pathinfo($filePath, PATHINFO_EXTENSION);
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

						$size=pretty_filesize($filePath);
						$sizekey=filesize($filePath);
					}

					echo("<tr class='$class'>");
					/* Name */
					echo("<td><a href='./" . rawurlencode($namehref) . "' $favicon class='name'>$name</a></td>");
					/* Filetype */
					echo("<td><span class='ext'>$extname</span></td>");
					/* Size */
					echo("<td sorttable_customkey='$sizekey'><span class='size" . ($size === 'show' ? " show" : "")  . "'" . ($size === 'show' ? " onclick='showDirSize(\"".rawurlencode($filePath)."\", this)'" : "") . ">$size</span></td>");
					/* Date */
					echo("<td sorttable_customkey='$timekey'><span class='time'>$modtime</span></td>");
					/* Menu */
					echo '<td class="item-menu">'.
					/* Menu - VLC */
					((($isDir && containsAudioOrVideo($filePath)) || in_array($extn, array_merge($videoExts, $audioExts))) ? '<a href="/handler?action=m3u&path=' . rawurlencode($filePath) . '" class="vlc"></a>' : '').
					/* Menu - Embed */
					(
						(in_array($extn, $embeddableVideoExts)) ?
						'<a class="embed" onclick="playVideo(\'./'.$namehref.'\')"></a>' :
						((in_array($extn, $embeddableAudioExts)) ?
							'<a class="embed" onclick="playAudio(\'./'.$namehref.'\')"></a>' : '')
					).
					/* Menu - Download */
					(($isDir) ? '<a href="/handler?action=download&path=' . rawurlencode($filePath) . '" class="download"></a>' : '').
					/* Menu - Rename */
					'<a onclick="renameFile(\'' . "." . urldecode($_SERVER['REQUEST_URI'] . '\', \'' . $dirArray[$index]) . '\')" class="edit"></a>'.
					/* Menu - Delete */
					'<a onclick="deleteFile(\'' . "." . urldecode($_SERVER['REQUEST_URI'] . '\', \'' . $dirArray[$index]) . '\')" class="delete"></a>'.
					"</td>";
					echo("</tr>");
				}
			}
			?>

		</tbody>
	</table>
	<div id="upload-preview" class="dropzone"></div>
	<div class="media-container">
		<div id="video-name"></div>
		<video id="video-player" src="" controls>
			Votre navigateur n'est pas compatible avec les lecteurs HTML5, merci de télécharger un navigateur décent...
		</video>
		<audio id="audio-player" src="" controls>
			Votre navigateur n'est pas compatible avec les lecteurs HTML5, merci de télécharger un navigateur décent...
		</audio>
	</div>
</div>

<?php
if ($config->background->random == true && $config->background->showCopyright == true) {
	echo "<span class=\"bg-copyright\"><a href=\"".$unsplash->links->html."\">Photo</a> by <a href=\"".$unsplash->user->links->html."\">".$unsplash->user->name."</a> on <a href=\"https://unsplash.com\">Unsplash</a></span>";
}
?>
</body>
</html>
