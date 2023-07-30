<?php
class authObj
{
	var $token = "Y29kZXg6cmVzdF9hcGlfdGVzdAo=";
	function authenticate()
	{
		$headers = getallheaders();
		if (!array_key_exists('Authorization', $headers)) {
			header("HTTP/1.0 400");
			echo json_encode(["error" => "Authorization header is missing"]);
			exit;
		}
		
		if (substr($headers['Authorization'], 0, 7) !== 'Bearer ') {
			header("HTTP/1.0 400");
			echo json_encode(["error" => "Bearer keyword is missing"]);
			exit;
		}
		
		$bearerToken = trim(substr($headers['Authorization'], 7));
		return $bearerToken === $this->token;
	}
}
?>