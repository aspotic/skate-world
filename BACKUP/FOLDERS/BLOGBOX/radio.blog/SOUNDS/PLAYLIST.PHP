<?
$y=1;
$i=1;
$track = '';

$rbsPath = 'sounds/';


if ($dir = opendir('.')) {
    while (false !== ($file = readdir($dir))) {
        if ($file{0} != '=') {
            if ( strrchr($file,'.') == '.rbs' ) {
    			$last_mod = filemtime($file);
    			$track[$last_mod] = utf8_encode($file);
    			$i++;
            }
        }
	}
}
closedir($dir);

header ("Content-type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
echo "<tracks>\n";
if ($track != '') {
	krsort($track);
	while (list ($key,$val) = each ($track)) {
			echo "<track trackMod=\"".$key."\" title=\"".utf8_encode(substr($val,0,strrpos($val,'.')))."\" path=\"".$rbsPath.$val."\"/>\n";
			$y++;
	}
}


//echo "nbTracks=".($i-1)."&";
echo "</tracks>";
?>