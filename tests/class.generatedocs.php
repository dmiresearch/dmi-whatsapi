 <?php
 class GenerateWhatsappDoc{
 	var $templateIn;
 	var $templateOut;
 	public function __construct($tempIn, $tempOut){
 		$this->templateIn  = $tempIn;
 		$this->templateOut = $tempOut;

 	}
 	public function generateDoc($content){
	  $fp = fopen($this->templateIn, 'w+'); 
	  fwrite($fp, $content);
	  fclose($fp); 
	  copy($this->templateIn, $this->templateOut);
	  // Use file_exists() to see if it was successfully copied and placed
	  if (file_exists($this->templateOut)) {
	    echo "Success : $this->templateOut has been made";
	  } else {
	    echo "Failure: $this->templateOut does not exist";
	  }
	}
 }

  ?> 