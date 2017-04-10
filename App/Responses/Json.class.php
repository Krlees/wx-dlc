<?php
namespace App\Responses;

class Json extends Response {
	public function callback($code, $message = '', $data = array()) {
		if(!(is_numeric($code))) {
			return '';
		}

        $message = $message ? $message : $GLOBALS['cfg']['code'][$code];
		
		//记录错误日志
		if( $code != 0 ){
			//error_logs($code,$message);
		}
		

		$result = array(
			'code'    => $code,
			'message' => $message,
			'data'    => $data
		);

		echo json_encode($result);
		exit;
	}
}