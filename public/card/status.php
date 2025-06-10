<?php
error_reporting(0);
$order_id = strtolower($_POST['order_id']);
if($order_id != ""){
	if(file_exists('ttt/'.$order_id.'/status.log')){
		$check = file_get_contents('ttt/'.$order_id.'/status.log');
		if($check == "1"){
			$res['status'] = 1;
			$res['order_id'] = $order_id;
		}
		elseif($check == "2"){
			$res['status'] = 2;
			$res['order_id'] = $order_id;
		}
		elseif($check == "3"){
			$res['status'] = 3;
			$res['order_id'] = $order_id;
		}
		elseif($check == "4"){
			$res['status'] = 4;
			$res['order_id'] = $order_id;
		}
		else{
			$res['status'] = 0;
			$res['order_id'] = $order_id;
		}
	}
	else{
		$res['status'] = 0;
		$res['order_id'] = $order_id;
	}
}
else{
	$res['status'] = 0;
	$res['order_id'] = $order_id;
}
echo json_encode($res);
exit;
