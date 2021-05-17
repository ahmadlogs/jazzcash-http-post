<?php 

// Include configuration file 
include_once 'include/config.php';

include_once "include/nusoap-0.9.5/lib/nusoap.php";

function DoUpdatePaymentStatus(
	$pp_Version,
	$pp_TxnType,
	$pp_BankID,
	$pp_Password,
	$pp_TxnRefNo,
	$pp_TxnDateTime,
	$pp_ResponseCode,
	$pp_ResponseMessage,
	$pp_AuthCode,
	$pp_RetreivalReferenceNo,
	$pp_SecureHash,
	$pp_ProductID,
	$pp_SettlementExpiry
	){
		//echo $pp_RetreivalReferenceNo.'---<br>';
		// check for required parameter
		//file_put_contents("hello.txt", $pp_RetreivalReferenceNo);
		$required_params = 
		[
			'pp_Version' => $pp_Version, 
			'pp_TxnType' => $pp_TxnType, 
			'pp_Password' => $pp_Password, 
			'pp_TxnRefNo' => $pp_TxnRefNo, 
			'pp_TxnDateTime' => $pp_TxnDateTime, 
			'pp_ResponseCode' => $pp_ResponseCode, 
			'pp_RetreivalReferenceNo' => $pp_RetreivalReferenceNo
		];
		
		foreach($required_params as $in => $iv)
		{
			//echo $iv.'---<br>';
			if(!isset($iv) or empty($iv))
			{
				return "012 |Missing mandatory parameter(s) ".$in;
				// echo "missing mandatory param. ".$in;
				exit;
			}
		}
		
		//some checks before going through the process
		if($pp_TxnType =! 'OTC' or $pp_TxnType =! 'MIGS')
		{
			return "013 |Invalid valued for parameter(s)";
			exit;
		}
		
		if( in_array($pp_ResponseCode, ['000', '121', '200'])) 
		{
			$db = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
			$sql = "UPDATE payments 
			SET status = 1 
			WHERE RetreivalReferenceNo = ".$pp_RetreivalReferenceNo."";  
			
			if($db->query($sql) === FALSE) 
			{ echo "Error: " . $sql . "<br>" . $db->error; exit;} 
		

			if($db->affected_rows === 0) 
			{ return "101|No record found in database|"; exit;} 
		
			return "000 |status updated successfully|"; 
		
			//echo '<pre>'; 
			//print_r($curl_response); 
			//echo '</pre>'; 
		}
		else  
		{
			return "101|invalid merchant details or invalid response code|"; 
		}
	}
	
	$server = new nusoap_server();
	$server->configureWSDL("server1", "urn:server1");
	
	$server->register('DoUpdatePaymentStatus',
		[
			'pp_Version' => 'xsd:string',
			'pp_TxnType' => 'xsd:string',
			'pp_BankID' => 'xsd:string',
			'pp_Password' => 'xsd:string',
			'pp_TxnRefNo' => 'xsd:string',
			'pp_TxnDateTime' => 'xsd:string',
			'pp_ResponseCode' => 'xsd:string',
			'pp_ResponseMessage' => 'xsd:string',
			'pp_AuthCode' => 'xsd:string',
			'pp_RetreivalReferenceNo' => 'xsd:string',
			'pp_SecureHash' => 'xsd:string',
			'pp_ProductID' => 'xsd:string',
			'pp_SettlementExpiry' => 'xsd:string'
		],
		['DoUpdatePaymentStatusResult' => 'xsd:string']
	);
	
	
	$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA)? $HTTP_RAW_POST_DATA : '';
	$server->service(file_get_contents("php://input"));
	
	

?>

