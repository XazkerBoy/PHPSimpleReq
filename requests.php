<?php
/**
 * Simple PHP requests library, that looks like a Python one
 * @author XazkerBoy (https://github.com/XazkerBoy/)
*/

class Response{		//Response class, that gets returned
	public $text;	//Response text
	public $cookies;	//Response cookies array
	public $headers;	//Response headers array
	public function __construct($ch, $resp){	//Constructor with curl object and response
		if(!isset($ch, $resp)){
			throw new Exception('Some parameter in Response class not set!');
		}
		$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);		//Get header size
		$headers = explode("\n", substr($resp, 0, $header_size));	//Create an array with header lines
		foreach($headers as $header){
		    if($header == '' || !strpos($header, ':')){ continue;}
		    $headerarr = explode(': ', $header);
		    if($headerarr[0] == 'Set-Cookie'){	//If header is cookie, append it to cookies array
		        $cookiearr = explode('; ', $headerarr[1]);
		        $cookie = explode('=', $cookiearr[0]);
		        $this->cookies[$cookie[0]] = $cookie[1];
		    }
		    else{
		        $this->headers[$headerarr[0]] = $headerarr[1];	//Append headers key:value to an array
		    }
		}
		$this->text = substr($resp, $header_size);	//Set response text to everything after header
	}
	public function json(){	//JSON parser
		if(!isset($this->text) || $this->text == ''){
			throw new Exception('Response not set');
		}
		return json_decode($this->text, true);
	}
}

class Session{
	private $cookies;
	private $ch;

	private function randomString($length = 10) {	//Random string generator
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}

	private function initCurl(){	//Init curl
		$this->cookies = $this->randomString().'.txt';	//Select a cookie file
		$this->ch = curl_init();
		curl_setopt ($this->ch, CURLOPT_COOKIEJAR, $this->cookies); 	//Set cookie file
		curl_setopt ($this->ch, CURLOPT_COOKIEFILE, $this->cookies); 
		curl_setopt($this->ch, CURLOPT_COOKIESESSION, true);
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($this->ch, CURLOPT_HEADER, true);	//Also return headers
	}

	public function __construct(){
		$this->initCurl();
	}
	
	public function __destruct() {
		curl_close($this->ch);
		if(file_exists($this->cookies)) unlink($this->cookies);
	}

	public function Get($url, $headers = null){		//GET-Request
		if(!isset($url)){
			throw new Exception('URL not set!');
		}
		curl_setopt($this->ch, CURLOPT_URL, $url);	//Set URL
		curl_setopt($this->ch, CURLOPT_POST, 0);	//Disable POST
		if(isset($headers)) curl_setopt($this->ch, CURLOPT_HTTPHEADER, $headers);	//Set headers, if available
		$resp = curl_exec($this->ch);	//Execute request
		return new Response($this->ch, $resp);	//Return response
	}

	public function Post($url, $data = null, $headers = null){	//POST-Request
		if(!isset($url)){
			throw new Exception('URL not set!');
		}
		curl_setopt($this->ch, CURLOPT_URL, $url);
		curl_setopt($this->ch, CURLOPT_POST, 1);
		if(isset($headers)) curl_setopt($this->ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data);
		$resp = curl_exec($this->ch);
		return new Response($this->ch, $resp);
	}

	public function Reset(){	//Reset session
		curl_close($this->ch);
		if(file_exists($this->cookies)) unlink($this->cookies);
		$this->initCurl();
	}
}
?>