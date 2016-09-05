<?php
namespace weidizhang\PHPCurlWrapper;

class Response
{
	private $headers = array();
	private $body;
	private $error;
	private $info = array();	
	
	public function getHeader($name, $caseSensitive = true) {
		foreach ($this->headers as $header) {
			$headerParts = explode(":", $header);
			
			$matches = ($headerParts[0] == $name);
			if (!$caseSensitive) {
				$matches = (strcasecmp($headerParts[0], $name) == 0);
			}
			
			if ($matches) {
				return substr($header, strlen($name . ": "));
			}
		}
		
		return null;
	}
	
	public function getHeaders() {
		return $this->headers;
	}
	
	public function getBody() {
		return $this->body;
	}
	
	public function getInfo() {
		return $this->info;
	}
	
	public function getError() {
		return $this->error;
	}
	
	public function hasError() {
		return ($this->error != null);
	}
	
	public function setInfo($data) {
		$this->info = $data;
	}
	
	public function setError($data) {
		$this->error = $data;
	}
	
	public function setBody($data) {
		$this->body = $data;
	}
	
	public function setHeader($ch, $line) {
		$origLength = strlen($line);
		
		$line = trim($line);
		if (!empty($line)) {
			$this->headers[] = $line;
		}
		
		return $origLength;
	}
	
	public function __toString() {
		return $this->body;
	}
}
?>