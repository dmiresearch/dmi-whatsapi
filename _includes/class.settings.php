<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Settings {

    var $settingsXML;
    var $xmlFile;
    var $whatsappNo;
    var $whatsappIMEI;
    var $whatsappPass;

    function __construct() {
        $this->xmlFile = "settings.xml";
        $this->settingsXML = simplexml_load_file($this->xmlFile);
    }

    public function getSettingsXML() {
        return $this->settingsXML;
    }

    public function getWhatsappNo() {
        $this->whatsappNo = $this->settingsXML->settings[0]->whatsapp[0]->whatsappnumber;
        return $this->whatsappNo;
    }
    public function getWhatsappIMEI() {
        $this->whatsappIMEI = $this->settingsXML->settings[0]->whatsapp[0]->whatsappimei;
        return $this->whatsappIMEI;
    }

    public function getWhatsappPass() {
        $this->whatsappPass = $this->settingsXML->settings[0]->whatsapp[0]->whatsapppass;
        return $this->whatsappPass;
    }

}

$test = new Settings();
echo $test->getWhatsappNo();
echo $test->getWhatsappIMEI();
echo $test->getWhatsappPass();
echo "<pre>";
print_r($test->getSettingsXML())."<br/>";
echo "</pre>";
