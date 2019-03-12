<?php
function format_bytes($size, $delimiter = '') {
    $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
    for ($i = 0; $size >= 1024 && $i < 5; $i++) $size /= 1024;
    return round($size, 2) . $delimiter . $units[$i];
}
function format_links($urls){
	$array = json_decode($urls);
	foreach ($array as $key => $value) {
		echo $value."\n";
	}
}