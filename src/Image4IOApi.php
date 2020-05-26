<?php

namespace Image4IO;

class Image4IOApi{

    public $api;
	public $apiKey;

	private $endpoint="https://api.image4.io/v1.0/";

    public function __construct($_api,$_apiKey) {
		$this->api = $_api;
		$this->apiKey = $_apiKey;
	}
	
	public function query($uri, $method, $data=null, $curl_headers=array(), $curl_options=array()) {
		$default_curl_options = array(
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_HEADER => true,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_TIMEOUT => 3,
		);
		$default_headers = array();
		if(!isset($method)){
			throw new \Exception("Method cannot be null");
		}
		$method = trim($method);
		$allowed_methods = array('GET', 'POST', 'PUT', 'PATCH', 'DELETE');

		if(!in_array($method, $allowed_methods))
			throw new \Exception("'$method' is not valid cURL HTTP method.");

		if(!empty($data) && !is_array($data))
			throw new \Exception("Invalid data for cURL request '$method $uri'");

		$curl = curl_init($uri);
		curl_setopt_array($curl, $default_curl_options);
		switch($method) {
			case 'GET':
				/*if($data){
					curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'GET' );
					curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
				}*/
				break;
			case 'POST':
				if(!is_array($data))
					throw new \Exception("Invalid data for cURL request '$method $uri'");
				curl_setopt($curl, CURLOPT_POST, true);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
				break;
			case 'PUT':
				if(!is_array($data))
					throw new \Exception("Invalid data for cURL request '$method $uri'");
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
				break;
			case 'PATCH':
				if(!is_array($data))
					throw new \Exception("Invalid data for cURL request '$method $uri'");
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
			case 'DELETE':
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
				break;
		}
		curl_setopt_array($curl, $curl_options);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array_merge($default_headers, $curl_headers));
		$raw = rtrim(curl_exec($curl));
		$lines = explode("\r\n", $raw);
		$headers = array();
		$content = '';
		$write_content = false;
		if(count($lines) > 3) {
			foreach($lines as $h) {
				if($h == '')
					$write_content = true;
				else {
					if($write_content)
						$content .= $h."\n";
					else
						$headers[] = $h;
				}
			}
		}
		$error = curl_error($curl);
		curl_close($curl);
		return array(
			'raw' => $raw,
			'headers' => $headers,
			'content' => $content,
			'error' => $error
		);
	}
	
	public function connect() {
		$headers = array(
			'Content-Type: application/json',
			'Authorization: Basic '. base64_encode($this->api.":".$this->apiKey)
		);
		$query = $this->query($this->endpoint . 'listfolder','GET','',$headers);
		return $query;
	}
	
	public function listFolder($path='',$token='') {
		$headers = array(
			'Content-Type: application/json',
			'Authorization: Basic '. base64_encode($this->api.":".$this->apiKey)
		);
		$qs = http_build_query(array('path' => $path, 'continuationToken' => $token));
		$query = $this->query($this->endpoint . 'listFolder?' . $qs, 'GET', '', $headers);
		return $query;
	}

	public function getImage($name) {
		$headers = array(
			'Content-Type: application/json',
			'Authorization: Basic '. base64_encode($this->api.":".$this->apiKey)
		);
		$query = $this->query($this->endpoint . 'image?name=' . $name, 'GET', '', $headers);
		return $query;
	}

	public function getStream($name) {
		$headers = array(
			'Content-Type: application/json',
			'Authorization: Basic '. base64_encode($this->api.":".$this->apiKey)
		);
		$query = $this->query($this->endpoint . 'stream?name=' . $name, 'GET', '', $headers);
		return $query;
	}
	
	public function createFolder($path='') {
		$headers = array(
			'Content-Type: application/json',
			'Authorization: Basic '. base64_encode($this->api.":".$this->apiKey)
		);
		$query = $this->query($this->endpoint .'createFolder','POST',array('path' => $path),$headers);
		return $query;
	}

	public function startUploadStream($path='', $filename='') {
		$headers = array(
			'Content-Type: application/json',
			'Authorization: Basic '. base64_encode($this->api.":".$this->apiKey)
		);
		$query = $this->query($this->endpoint .'uploadStream','POST',array('path' => $path,'filename'=>$filename),$headers);
		return $query;
	}
	
	public function uploadImage($file,$folder='',$useFilename=false,$overwrite=true) {
		$headers = array(
			'Content-Type: multipart/form-data',
			'Authorization: Basic '. base64_encode($this->api.":".$this->apiKey)
		);
		$data=array(
			'folder'=>$folder,
			'useFilename'=>$useFilename,
			'overwrite'=>$overwrite,
			'file'=>new CurlFile( $file['tmp_name'], $file['type'],$file['name'])
		);
		
		$query = $this->query($this->endpoint . 'uploadImage', 'POST', $data, $headers);
		return $query;
	}
	
	public function fetchImage($from='',$targetPath='',$useFilename=false) {
		$headers = array(
			'Content-Type: application/json',
			'Authorization: Basic '. base64_encode($this->api.":".$this->apiKey)
		);
		$query = $this->query($this->endpoint . 'fetchImage','POST',array('from' => $from,'targetPath'=>$targetPath,'useFilename'=>$useFilename),$headers);
		return $query;
	}

	public function fetchStream($from='',$targetPath='',$filename='') {
		$headers = array(
			'Content-Type: application/json',
			'Authorization: Basic '. base64_encode($this->api.":".$this->apiKey)
		);
		$query = $this->query($this->endpoint . 'fetchStream','POST',array('from' => $from,'targetPath'=>$targetPath,'filename'=>$filename),$headers);
		return $query;
	}

	public function deleteImage($name='') {
		$headers = array(
			'Content-Type: application/json',
			'Authorization: Basic '. base64_encode($this->api.":".$this->apiKey)
		);
		$query = $this->query($this->endpoint .'deleteImage','DELETE',array('name'=>$name),$headers);
		return json_decode($query);
	}

	public function deleteStream($name='') {
		$headers = array(
			'Content-Type: application/json',
			'Authorization: Basic '. base64_encode($this->api.":".$this->apiKey)
		);
		$query = $this->query($this->endpoint .'deleteStream','DELETE',array('name'=>$name),$headers);
		return json_decode($query);
	}

	public function deleteFolder($path='') {
		$headers = array(
			'Content-Type: application/json',
			'Authorization: Basic '. base64_encode($this->api.":".$this->apiKey)
		);
		$query = $this->query($this->endpoint . 'deleteFolder','DELETE',array('path'=>$path),$headers);
		return $query;
	}

	public function copyImage($source='',$targetPath='',$name='',$useFilename=false,$overwrite=false) {
		$headers = array(
			'Content-Type: application/json',
			'Authorization: Basic '. base64_encode($this->api.":".$this->apiKey)
		);
		$body=array(
			'source'=>$source,
			'targetPath'=>$targetPath,
			'name'=>$name,
			'useFilename'=>$useFilename,
			'overwrite'=>$overwrite
		);
		$query = $this->query($this->endpoint . 'copy','PUT',$body,$headers);
		return $query;
	}
	
	public function moveImage($source='',$target='') {
		$headers = array(
			'Content-Type: application/json',
			'Authorization: Basic '. base64_encode($this->api.":".$this->apiKey)
		);
		$body=array(
			'source'=>$source,
			'targetPath'=>$targetPath
		);
		$query = $this->query($this->endpoint .'moveImage','PUT',$body,$headers);
		return $query;
	}

	public function uploadStreamPart($part,$partId,$filename,$token) {
		$headers = array(
			'Content-Type: multipart/form-data',
			'Authorization: Basic '. base64_encode($this->api.":".$this->apiKey)
		);
		$data=array(
			'partId'=>$partId,
			'filename'=>$filename,
			'token'=>$token,
			'part'=>new CurlFile( $part['tmp_name'], $part['type'],$part['name'])
		);
		
		$query = $this->query($this->endpoint . 'uploadStream', 'PATCH', $data, $headers);
		return $query;
	}

	public function finalizeStreamUpload($filename='',$token='') {
		$headers = array(
			'Content-Type: application/json',
			'Authorization: Basic '. base64_encode($this->api.":".$this->apiKey)
		);
		$query = $this->query($this->endpoint .'finalizeStream','POST',array('filename' => $filename,'token'=>$token),$headers);
		return $query;
	}

	public function getSubscription() {
		$headers = array(
			'Content-Type: application/json',
			'Authorization: Basic '. base64_encode($this->api.":".$this->apiKey)
		);
		$query = $this->query($this->endpoint . 'subscription','GET',null,$headers);
		return $query;
	}

}