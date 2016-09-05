<?php
namespace weidizhang\PHPCurlWrapper;

class Curl
{
	private $curl;
	private $behavior = Behavior::CLEAR_HEADERS;
	
	private $tempHeaders = array();
	
	public function __construct() {
		$this->curl = curl_init();
		
		$this->setOption("RETURNTRANSFER", true);		
		$this->setOption("CONNECTTIMEOUT", 10);
		$this->setOption("FOLLOWLOCATION", true);
		
		$this->setUserAgent("weidizhang/PHP-Curl-Wrapper (Available on GitHub)");
	}
	
	public function __destruct() {
		curl_close($this->curl);
	}
	
	public function request($type, $url, $query = "", $options = array()) {
		$type = strtoupper($type);
		
		if (is_array($query)) {
			$query = http_build_query($query);
		}
		
		if (!empty($options)) {
			foreach ($options as $name => $value) {
				$this->setOption($name, $value);
			}
		}
		
		$this->setOption("URL", $url);
		
		if ($type == "GET" || $type == "HEAD") {
			if ($type == "GET") {
				$this->setOption("HTTPGET", true);
			}
			else {
				$this->setOption("NOBODY", true);
			}
			
			$separator = "";
			if (!empty($query)) {
				$separator = "?";
				
				$urlQuery = parse_url($url, PHP_URL_QUERY);
				if ($urlQuery != null) {
					$separator = "&";
				}
			}
			
			$this->setOption("URL", $url . $separator . $query);
		}
		elseif ($type == "POST" || $type == "PUT") {
			$this->setOption($type, true);
			$this->setOption("POSTFIELDS", $query);
		}
		else {
			$this->setOption("CUSTOMREQUEST", $type);
			$this->setOption("POSTFIELDS", $query);
		}
		
		$response = new Response();
		$this->setOption("HEADERFUNCTION", array($response, "setHeader"));		
		
		$data = curl_exec($this->curl);
		$response->setBody($data);
		
		if (curl_errno($this->curl)) {
			$err = curl_error($this->curl);
			$response->setError($err);
		}
		else {
			$info = curl_getinfo($this->curl);
			$response->setInfo($info);
		}
		
		if ($this->behavior == Behavior::CLEAR_HEADERS) {
			foreach ($this->tempHeaders as $header) {
				$this->unsetHeader($header);
			}			
			$this->unsetHeader("Referer");			
			
			$this->tempHeaders = array();
		}
		
		return $response;
	}
	
	public function setBehavior($value) {
		$this->behavior = $value;
	}
	
	public function setOption($name, $value) {
		$getOption = $name;
		if (is_string($name)) {
			if ((strlen($name) <= 8) || (substr($name, 0, 8) != "CURLOPT_")) {
				$name = "CURLOPT_" . $name;
			}
			
			$getOption = constant(strtoupper($name));
		}
		
		if ($getOption != null) {
			curl_setopt($this->curl, $getOption, $value);
		}
	}
	
	public function setCookieFile($name) {
		if (!file_exists($name)) {
			touch($name);
		}
		$file = realpath($name);
		
		$this->setOption("COOKIEFILE", $file);
		$this->setOption("COOKIEJAR", $file);
	}
	
	public function setReferer($value) {
		$this->setOption("REFERER", $value);
	}
	
	public function setUserAgent($value) {
		$this->setOption("USERAGENT", $value);
	}
	
	public function setHeader($name, $value) {
		if ($this->behavior == Behavior::CLEAR_HEADERS) {
			$this->tempHeaders[] = $name;
		}
		
		$this->setOption("HTTPHEADER", array($name . ": " . $value));
	}
	
	public function setHeaders($value) {
		if ($this->behavior == Behavior::CLEAR_HEADERS) {
			foreach ($value as $header) {
				$name = explode(":", $header);
				
				if (count($name) >= 2) {
					$this->tempHeaders[] = $name;
				}
			}
		}
		
		$this->setOption("HTTPHEADER", $value);
	}
	
	public function unsetHeader($name) {
		$this->setOption("HTTPHEADER", array($name . ":"));
	}
	
	public function enableSSLVerify() {
		$this->setOption("SSL_VERIFYHOST", 2);
		$this->setOption("SSL_VERIFYPEER", true);
	}
	
	public function disableSSLVerify() {
		$this->setOption("SSL_VERIFYHOST", false);
		$this->setOption("SSL_VERIFYPEER", false);
	}
	
	public function getHandle() {
		return $this->curl;
	}
}
?>