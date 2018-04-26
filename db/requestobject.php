<?php
//credits to: http://www.lornajane.net/posts/2012/building-a-restful-php-server-understanding-the-request
class RequestObject 
{
	public $method;
	public $accept;
	public $mime;
	public $path;
	public $parameters;

	public function __construct() {
		$this->method = $_SERVER['REQUEST_METHOD'];
		$this->accept = $_SERVER['HTTP_ACCEPT'];
		if (isset($_SERVER['PATH_INFO'])) {
			$this->path = explode('/', ltrim($_SERVER['PATH_INFO'], '/'));
		} else {
			$this->path = null;
		}
		if(isset($_SERVER['CONTENT_TYPE'])) {
			$this->mime = $_SERVER['CONTENT_TYPE'];
		}
		$this->parameters = array();
		$this->parameterMerge($_GET);
		$this->parameterMerge($_POST);
		//$this->paramterMerge($_COOKIE);
		$this->loadBody(file_get_contents("php://input"));
	}
	
	private function parameterMerge($arr) {
		$temp = $this->scrub($arr);
		foreach($temp as $key => $value) {
			$this->parameters[$key] = $value;
		}
	}
	
	private function loadBody($body) {
		if (empty($body)) {
			return;
		}
		$type = explode(';', $this->mime, 2)[0];
		switch($type) {
			case "application/json":
				$body_params = json_decode($body);
				if($body_params) {
					foreach($body_params as $param_name => $param_value) {
						$this->parameters[$param_name] = $param_value;
					}
				}
				break;
			case "application/x-www-form-urlencoded":
				if ($this->method === 'POST') {
					//already picked up via $_POST
				} else {
					parse_str($body, $postvars);
					foreach($postvars as $field => $value) {
						$this->parameters[$field] = $value;
					}
				}
				break;
			default:
				debug("unexpected content type: ".$this->mime);
				break;
		}
	}
	private function scrub($input) {
		if(is_array($input)) {
			foreach ($input as $key => $value) {
				$input[$key] = $this->scrub($value);
			}
		} else {
			$input = htmlentities($input,ENT_QUOTES,"UTF-8");
		}
		return $input;
	}
}
?>


