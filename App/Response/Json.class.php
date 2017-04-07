<?php
/**
 * 按xml方式输出通信数据
*/
class Json extends Response {
	public function callback($code, $message = '', $data = array()) {
		if(!(is_numeric($code))) {
			return '';
		}
		
		//记录错误日志
		if( $code != 0 ){
			error_logs($code,$message);
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