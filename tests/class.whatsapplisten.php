<html>
<meta/><!-- http-equiv="refresh" content="30" />-->
 <head>
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<?php
//add this as the first line of the entry file may it is the index.php or config.php
//stream_context_set_default(['http'=>['proxy'=>'192.168.101.10:3128']]);
require_once('whatsapp_whatsapi_config.php');
//require_once('class.dbhandler.php');
include('class.generatedocs.php');

class WhatsappListen {

    // Define whatsapp media folders
    const MEDIA_PATH   = '../_whatsapp/';
    const IMAGE_FOLDER = '_images/targetingimages/'; 
    const AUDIO_FOLDER = '_audio/targetingaudio/'; 
    const VIDEO_FOLDER = '_video/targetingvideo/'; 
    // Define class variables
    var $w;
    var $db;
    function __construct($userPhoneNo, $userIMEI, $userProfileName, $userPassword, $debug) {
        $this->w = new WhatsProt($userPhoneNo, $userIMEI, $userProfileName, $debug);
        $this->w->connect();
        $this->w->loginWithPassword($userPassword);
        $this->db = new DBHandler();
		echo "am mhere";
    }

    protected function myCopy($url, $path) {
        copy($url, $path);
    }
    protected function chunkedCopy($from, $to) {
        # 1 meg at a time, you can adjust this.
        $buffer_size = 1048576; 
        $ret = 0;
        $fin = fopen($from, "rb");
        $fout = fopen($to, "w");
        while(!feof($fin)) {
            $ret += fwrite($fout, fread($fin, $buffer_size));
        }
        fclose($fin);
        fclose($fout);
        return $ret; # return number of bytes written
    }
    protected function getFileExtension($file_name) {
        return substr(strrchr($file_name, '.'), 1);
    }
    protected function processMessage($msgNode, $from, $id, $time) {
        $messagebody = $msgNode->getChild("body")->getData();
        $this->db->insertWhatappMessages($id, substr($from, 0, 12), $time, $messagebody);
        date_default_timezone_set('Africa/Nairobi');
        $date = date('Y-m-d H.i.s');
        $targetFile = "template.doc";
        $targetCopy = "../_whatsapp/_text/targetingmessages/kdf[text]".$date.".doc";
        $test = new GenerateWhatsappDoc($targetFile, $targetCopy);
        $msg = substr($from, 0, 12).PHP_EOL.$time.PHP_EOL.$messagebody;
        $test->generateDoc($msg);

    }

    protected function processImage($msgNode, $from, $id, $time, $date) {
        echo ":::IMAGE DETECTED:::<br>";
        $mediaUrl  = $msgNode->getChild("media")->getAttribute('url');
        $mediaName = $msgNode->getChild("media")->getAttribute("file");
        $mediaType = $msgNode->getChild("media")->getAttribute("type");
        $fileName  = "kdf[imagefile]-" . $date . "." . $this->getFileExtension($mediaName);
        $mediaPath = static::MEDIA_PATH . static::IMAGE_FOLDER . $fileName;
        echo "<br>image::" . $mediaUrl . "<br>file name::" . $mediaName . "<br>type::" . $mediaType . "<br>::new name" . $fileName;
        $this->chunkedCopy($mediaUrl, $mediaPath);
        $this->db->insertWhatappMedia("_tblwhatsappimage", $id, $time, $fileName, substr($from, 0, 12));
    }
    protected function processAudio($msgNode, $from, $id, $time, $date) {
        echo ":::AUDIO DETECTED:::<br>";
        $mediaUrl  = $msgNode->getChild("media")->getAttribute('url');
        $mediaName = $msgNode->getChild("media")->getAttribute("file");
        $mediaType = $msgNode->getChild("media")->getAttribute("type");
        $fileName  = "kdf[audiofile]" . $date . "." . $this->getFileExtension($mediaName);
        $mediaPath = static::MEDIA_PATH . static::AUDIO_FOLDER . $fileName;
        echo "<br>image::" . $mediaUrl . "<br>file name::" . $mediaName . "<br>type::" . $mediaType;
        $this->chunkedCopy($mediaUrl, $mediaPath);
        $this->db->insertWhatappMedia("_tblwhatsappaudio", $id, $time, $fileName, substr($from, 0, 12));
    }
    protected function processVideo($msgNode, $from, $id, $time, $date) {
        echo ":::VIDEO DETECTED:::<br>";
        $mediaUrl  = $msgNode->getChild("media")->getAttribute('url');
        $mediaName = $msgNode->getChild("media")->getAttribute("file");
        $mediaType = $msgNode->getChild("media")->getAttribute("type");
        $fileName  = "kdf[videofile]" . $date . "." . $this->getFileExtension($mediaName);
        $mediaPath = static::MEDIA_PATH . static::VIDEO_FOLDER . $fileName;
        echo "<br>image::" . $mediaUrl . "<br>file name::" . $mediaName . "<br>type::" . $mediaType;
        $this->chunkedCopy($mediaUrl, $mediaPath);
        $this->db->insertWhatappMedia("_tblwhatsappvideo", $id, $time, $fileName, substr($from, 0, 12));
    }

     function getWhatsappMessages() {
        while (TRUE) {
            $this->w->pollMessages();
            $data = $this->w->getMessages();
            if (!empty($data)) {
                foreach ($data as $message) {
                    date_default_timezone_set('Africa/Nairobi');
                    $date = date('Y-m-d H-i-s');
                    $from = $message->getAttribute("from");
                    $id   = $message->getAttribute("id");
                    $time = date("m/d/Y H:i", $message->getAttribute("t"));
                    $messagebody = "";
                    if ($message->getChild("body")) {
                        $this->processMessage($message, $from, $id, $time);
                    }
                    elseif ($message->getChild("media")->getAttribute("type") == "image") {
                        $this->processImage($message, $from, $id, $time, $date);
                    }
                    elseif ($message->getChild("media")->getAttribute("type") == "audio") {
                        $this->processAudio($message, $from, $id, $time, $date);
                    }
                    elseif ($message->getChild("media")->getAttribute("type") == "video") {
                        $this->processVideo($message, $from, $id, $time, $date);
                    }
                    else
                    {
                        echo "Unsupported format";
                    }
                    echo '<table class="gridtable">';
                    echo "<tr><td>From:</td><td> " . $from . "</td></tr>";
                    echo "<tr><td>ID:</td><td>" . $id . "</td></tr>";
                    echo "<tr><td>Time:</td><td>" . $time . "</td></tr>";
                    echo "<tr><td>Body:</td><td>" . $messagebody . "</td></tr>";
                    echo "</table>";
                }
            }
//
            exit(0);
        }
    }

}

$test = new WhatsappListen($userPhone, $userIdentity, $userName, $password, $debug);
$test->getWhatsappMessages();
