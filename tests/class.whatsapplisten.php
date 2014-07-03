<meta http-equiv="refresh" content="30" />
<?php

require_once('whatsapp_whatsapi_config.php');
require_once('class.dbhandler.php');

class WhatsappListen {

    // Define whatsapp media folders
    const MEDIA_PATH   = '../_whatsapp/';
    const IMAGE_FOLDER = '_images/targeting_images/'; 
    const AUDIO_FOLDER = '_audio/targeting_audio/'; 
    const VIDEO_FOLDER = '_video/targeting_video/'; 
    // Define class variables
    var $w;
    var $db;
    function __construct($userPhoneNo, $userIMEI, $userProfileName, $userPassword, $debug) {
        $this->w = new WhatsProt($userPhoneNo, $userIMEI, $userProfileName, $debug);
        $this->w->connect();
        $this->w->loginWithPassword($userPassword);
        $this->db = new DBHandler();
    }

    protected function myCopy($url, $path) {
        copy($url, $path);
    }

    protected function getFileExtension($file_name) {
        return substr(strrchr($file_name, '.'), 1);
    }
    protected function processMessage($msgNode, $from, $id, $time) {
        $messagebody = $msgNode->getChild("body")->getData();
        $this->db->insertWhatappMessages($id, substr($from, 0, 12), $time, $messagebody);
    }

    protected function processImage($msgNode, $from, $id, $time, $date) {
        echo ":::IMAGE DETECTED:::<br>";
        $mediaUrl  = $msgNode->getChild("media")->getAttribute('url');
        $mediaName = $msgNode->getChild("media")->getAttribute("file");
        $mediaType = $msgNode->getChild("media")->getAttribute("type");
        $fileName  = "kdf_" . $date . "." . $this->getFileExtension($mediaName);
        $mediaPath = static::MEDIA_PATH . static::IMAGE_FOLDER . $fileName;
        echo "<br>image::" . $mediaUrl . "<br>file name::" . $mediaName . "<br>type::" . $mediaType . "<br>::new name" . $fileName;
        $this->myCopy($mediaUrl, $mediaPath);
        $this->db->insertWhatappMedia("_tblwhatsappimage", $id, $time, $fileName, substr($from, 0, 12));
    }
    protected function processAudio($msgNode, $from, $id, $time, $date) {
        echo ":::AUDIO DETECTED:::<br>";
        $mediaUrl  = $msgNode->getChild("media")->getAttribute('url');
        $mediaName = $msgNode->getChild("media")->getAttribute("file");
        $mediaType = $msgNode->getChild("media")->getAttribute("type");
        $fileName  = "kdf_" . $date . "." . $this->getFileExtension($mediaName);
        $mediaPath = static::MEDIA_PATH . static::AUDIO_FOLDER . $fileName;
        echo "<br>image::" . $mediaUrl . "<br>file name::" . $mediaName . "<br>type::" . $mediaType;
        $this->myCopy($mediaUrl, $mediaPath);
        $this->db->insertWhatappMedia("_tblwhatsappaudio", $id, $time, $fileName, substr($from, 0, 12));
    }
    protected function processVideo($msgNode, $from, $id, $time, $date) {
        echo ":::VIDEO DETECTED:::<br>";
        $mediaUrl  = $msgNode->getChild("media")->getAttribute('url');
        $mediaName = $msgNode->getChild("media")->getAttribute("file");
        $mediaType = $msgNode->getChild("media")->getAttribute("type");
        $fileName  = "kdf_" . $date . "." . $this->getFileExtension($mediaName);
        $mediaPath = static::MEDIA_PATH . static::VIDEO_FOLDER . $fileName;
        echo "<br>image::" . $mediaUrl . "<br>file name::" . $mediaName . "<br>type::" . $mediaType;
        $this->myCopy($mediaUrl, $mediaPath);
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
                    echo "----------------------------------------</br>";
                    echo "From: " . $from . "</br>";
                    echo "ID: " . $id . "</br>";
                    echo "Time: " . $time . "</br>";
                    echo "Body: " . $messagebody . "</br>";
                    echo "----------------------------------------</br>";
                }
            }
//
            exit(0);
        }
    }

}

$test = new WhatsappListen($userPhone, $userIdentity, $userName, $password, $debug);
$test->getWhatsappMessages();
