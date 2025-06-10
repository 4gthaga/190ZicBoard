<?php
	error_reporting(0);
	include('config.php');
	
	if(!isset($_GET['sig']) or $_GET['sig'] == ""){
		header("Location: https://".CONFIG['DOMAINV2B']);
		exit;
	}
	$sig = $_GET['sig'];
	
	function decrypt($crypted_token,$enc_key){
		$crypted_token = hex2bin($crypted_token);
		list($crypted_token, $enc_iv) = explode("::", $crypted_token);
		$cipher_method = 'aes-128-ctr';
		$token = openssl_decrypt($crypted_token, $cipher_method, $enc_key, 0, hex2bin($enc_iv));
		unset($crypted_token, $cipher_method, $enc_key, $enc_iv);
		
		return $token;
	}
	$decrypt_string = decrypt($sig,CONFIG['TOKEN']);
	
	if($decrypt_string == ""){
		header("Location: https://".CONFIG['DOMAINV2B']);
		exit;
	}
	
	$order = json_decode($decrypt_string);
	
	if(!isset($order->total_amount) or !isset($order->trade_no) or !isset($order->order_id) or !isset($order->return_url) ){
		header("Location: https://".CONFIG['DOMAINV2B']);
		exit;
	}
	
	$amount = (int)$order->total_amount/100;
	$return_url = $order->return_url;
	$notify_url = $order->notify_url;
	$trade_no = $order->trade_no;
	$order_id = $order->order_id;
	$gate = $order->gate;
	
	
	//print_r($order);
	
	@mkdir('ttt/'.CONFIG['KEYWORD'].''.$order_id);
	
	if(!file_exists('ttt/'.CONFIG['KEYWORD'].''.$order_id.'/status.log')){
		file_put_contents('ttt/'.CONFIG['KEYWORD'].''.$order_id.'/status.log',0);
	}
	if(!file_exists('ttt/'.CONFIG['KEYWORD'].''.$order_id.'/trade_no.log')){
		file_put_contents('ttt/'.CONFIG['KEYWORD'].''.$order_id.'/trade_no.log',$trade_no);
	}
	
	if(!file_exists('ttt/'.CONFIG['KEYWORD'].''.$order_id.'/price.log')){
		file_put_contents('ttt/'.CONFIG['KEYWORD'].''.$order_id.'/price.log',$amount);
	}
	
	if(!file_exists('ttt/'.CONFIG['KEYWORD'].''.$order_id.'/time.log')){
		file_put_contents('ttt/'.CONFIG['KEYWORD'].''.$order_id.'/time.log',time());
	}
	
	$time = file_get_contents('ttt/'.CONFIG['KEYWORD'].''.$order_id.'/time.log');
	$status = file_get_contents('ttt/'.CONFIG['KEYWORD'].''.$order_id.'/status.log');
	if($status == "1"){
		header("Location: ".$return_url);
		exit;
	}
	
	if($gate == "bank"){
	    include('gate/bank.php');
	}
	elseif ($gate == "card") {
	    include('gate/card.php');
	}
	else{
	    include('gate/card.php');
	}
?>