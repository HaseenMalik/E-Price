<?php session_start();
set_time_limit(90);
error_reporting(E_ALL ^ E_NOTICE);
//include_once("api_class.php");

$con = mysql_connect("localhost", "root", "");
mysql_select_db("e-price", $con);




//$status = $object_smile_api->get_session();
if(isset($_SESSION["sid"])){
	$sess = file_get_contents("http://api.smilesn.com/session?username=4&password=Moonbeam8333");
	$sess = json_decode($sess);
	$sess = $sess->sessionid;
	echo $sess;
	$_SESSION["sid"] = $sess;
	//print_r($sess);
	#exit;
}


$receive = json_decode(file_get_contents("http://api.smilesn.com/receivesms?sid=" . $_SESSION["sid"]));

foreach($receive as $value){
		
	$single = $value[0];	
	var_dump($single);
	echo $sender =  $single->sender_num;
	echo $text   = $single->text;
	$qry = mysql_query("SELECT price FROM priceList WHERE variation LIKE '%".$text."%'");
	$res = mysql_fetch_array($qry);
	echo $price = $res[0];	
	$status = json_decode(file_get_contents("http://api.smilesn.com/sendsms?sid="
											 . $_SESSION["sid"]
											 . "&sendernum=8333"
											 . "&receivenum=". str_replace("+92", "0", $sender)
											 . "&textmessage=" . urlencode($text . " price is Rs." . $price)));
	if($status){
		var_dump($status);
	}
}


	$url=$_SERVER['REQUEST_URI'];
	header("Refresh: 5; URL=\"" . $url . "\""); // redirect in 5 seconds



?>
