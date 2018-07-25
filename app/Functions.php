<?php 
	function responseJson($status, $msg = '', $data = '')
	{
	    /*if (!empty($data)) {
	        $data = keyToCamel($data);
	    }*/

	    /* if (empty($msg)) {
	         $msg = errorName($status);
	     }*/

	    return response()->json(['status' => $status, 'msg' => $msg, 'data' => $data], 200);
	}

	function log_error($filename,$data){
		if(!file_exists('..'.DIRECTORY_SEPARATOR.'log')){
			mkdir('..'.DIRECTORY_SEPARATOR.'log');
		}

		if(!file_exists('..'.DIRECTORY_SEPARATOR.'log'.DIRECTORY_SEPARATOR.date('Y-m-d'))){
			mkdir('..'.DIRECTORY_SEPARATOR.'log'.DIRECTORY_SEPARATOR.date('Y-m-d'));
		}

		file_put_contents('..'.DIRECTORY_SEPARATOR.'log'.DIRECTORY_SEPARATOR.date('Y-m-d').DIRECTORY_SEPARATOR.$filename.'.txt', $data , FILE_APPEND);
	}
 ?>