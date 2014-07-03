<?php
	include("./../_includes/class.db.php");
	include("./../_includes/class.utils.php");
	
	// dbhandler
	
	class DBHandler
	{
		var $util;
		var $dbcon;
		function __construct()
		{
			$db			 = new Db();
			$db->setDBCon();
			$this->dbcon = $db->getDBCon();
			$this->util  = new Utils();
		}
		function insertWhatappMessages($whatsapp_msgid, $whatsapp_sender, $whatsapp_date, $whatsapp_message)
		{                                                                                                                                                                                           
			$db = $this->dbcon;
                        $sentStatus     = 1;
                        $seenStatus = 0;
                        $doc_name = " ";
			$timestamp 	= $this->util->generateTimestamp($whatsapp_date);
                        echo $timestamp;
			$stmt = $db->prepare("INSERT INTO _tblwhatsappmessage (msg_sender, msg_timestamp, msg_body, msg_doc_name, msg_status, msg_seen_status, msg_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
			$stmt->bind_param("sssssss", $whatsapp_sender, $timestamp, $whatsapp_message, $doc_name, $sentStatus, $seenStatus, $whatsapp_msgid);

			$stmt->execute();			
			if($stmt->affected_rows > 0)
			{
				echo "Insert ok";
			}
			else
			{
				echo "Error executing::".mysqli_errno();
			}
		}
		function insertWhatappMedia($table_name, $whatsapp_media_id, $whatsapp_media_date, $whatsapp_media_name, $whatsapp_media_sender)
		{
			$db = $this->dbcon;
                        $status = 0;
			$timestamp 	= $this->util->generateTimestamp($whatsapp_media_date);
                        echo $timestamp;
                        if($table_name == "_tblwhatsappimage")
                        {
			$stmt = $db->prepare("INSERT INTO _tblwhatsappimage (image_id, image_timestamp, image_name, image_sender, image_status) VALUES (?, ?, ?, ?, ?)");
                        }
                        if($table_name == "_tblwhatsappaudio")
                        {
			$stmt = $db->prepare("INSERT INTO _tblwhatsappaudio (audio_id, audio_timestamp, audio_name, audio_sender, audio_status) VALUES (?, ?, ?, ?, ?)");
                        print_r($stmt);
                        }
                        if($table_name == "_tblwhatsappvideo")
                        {
                            echo "am called";
			$stmt = $db->prepare("INSERT INTO _tblwhatsappvideo (video_id, video_timestamp, video_name, video_sender, video_status) VALUES (?, ?, ?, ?, ?)");
                        print_r($stmt);
                        }
			$stmt->bind_param("sssss", $whatsapp_media_id, $timestamp, $whatsapp_media_name, $whatsapp_media_sender, $status);
			$stmt->execute();			
			if($stmt->affected_rows > 0)
			{
				echo "Insert ok";
			}
			else
			{
				echo "Error executing::".mysqli_errno();
			}
		}

	}
	//$test = new DBHandler();
	//$test->insertData("456DI", "254711714306", "30/07/2010 13:24", "Hello there");
        //$test->insertWhatappMedia("_tblwhatsappvideo","D34567-25", "30/07/2010 10:22", "kdf_12-23-33.avi", "254711714306");
		