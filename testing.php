<?php

function downloadRemote($url, $path) {
    $newfilename = $path;
    $file = fopen($url, "rb");
    if ($file) {
        $newfile = fopen($newfilename, "wb");

        if ($newfile)
            while (!feof($file)) {
                fwrite($newfile, fread($file, 1024 * 8), 1024 * 8);
            }
    }

    if ($file) {
        fclose($file);
    }
    if ($newfile) {
        fclose($newfile);
    }
}

function writeContent($file, $content) {
    $fp = fopen($file, 'w');
    //chmod($fp, 0777);
    fwrite($fp, $content);
    fclose($fp);

    return true;
}

function myCopy($url, $path) {
    copy($url, $path);
}

function test() {

    $host = gethostbyname('www.example.com');
    $hostip = @gethostbyname($host);
    $ip = ( $hostip == $host ) ? $host : long2ip(ip2long($hostip));
    echo sprintf("Resolved %s to %s", $host, $ip);
    return $ip;
}

function replaceHostnameByIp($url) {
    $newUrl = str_replace("mms886.whatsapp.net", "173.193.205.6", $url);
    return $newUrl;
}

function mam() {
   $x = gethostbyname('www.electrictoolbox.com');
var_dump($x);
// outputs string(13) "120.138.20.39"

}
function get_file_extension($file_name) {
	return substr(strrchr($file_name,'.'),1);
}
$url = "https://mms886.whatsapp.net/d/UtsV58qwQnMSbPNq_kTtuComuYEABPuPQf59Yw/Ajdj1JQaZDm93RQBIHuXi61-yJc-_yksLTbacwRuUxCg.jpg";
//$path = "test/test.jpg";
//$url = "D:/Databases/htdocs/whatsapp/EVENTS.md";
//downloadRemote($url, "D:/Databases/htdocs/whatsapp/test");
//myCopy($url, $path);
//writeContent($path, $url);
//test();
//echo "::".replaceHostnameByIp($url);

//echo get_file_extension("yksLTbacwRuUxCg.owg");   
echo realpath($_SERVER['DOCUMENT_ROOT'].'');
