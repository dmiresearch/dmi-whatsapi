<?php

$randarray = array();
for($i = 0; $i<5; $i++){
	$rand = rand(1, 100);
	$randarray[$i] = $rand;
}
echo "<pre>";
print_r($randarray);
echo "</pre>";
//echo $rand;
?>