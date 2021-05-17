<?php

include_once "nusoap-0.9.5/lib/nusoap.php";
	
$client = new nusoap_client('http://localhost/jazzcash_part_6/soap_ipn.php?wsdl', '');
$client->soap_defencoding = 'utf-8';
$err = $client->getError();
if ($err) {
	echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
}

$person = array(

	'pp_Version' => '1.1',
	'pp_TxnType' => 'OTC',
	'pp_BankID' => 'BNK1',
	'pp_Password' => 'enter password here',
	'pp_TxnRefNo' => '00000000000000000000000000000',
	'pp_TxnDateTime' => '00000000000000000000000000000',
	'pp_ResponseCode' => '000',
	'pp_BillReference' => '4',
	'pp_ResponseMessage' => 'Low Balance',
	'pp_RetreivalReferenceNo' => '00000000000000000000000000000',
	'pp_AuthCode' => '123456',
	'pp_SecureHash' => 'null',
	'pp_ProductID' => 'RETL',
	'pp_SettlementExpiry' => 'null' 

);

$result = $client->call( 'DoUpdatePaymentStatus', $person, '', '', false, true);

// Check for a fault
if ($client->fault) 
{
	echo '<h2>Fault</h2><pre>';
		print_r($result);
	echo '</pre>';
	
} 
else 
{
	// Check for errors
	$err = $client->getError();
	if ($err) {
		// Display the error
		echo '<h2>Error</h2><pre>' . $err . '</pre>';		
	} else {
		// Display the result
		echo '<h2>Result</h2><pre>';
			print_r($result);
		echo '</pre>';
	}
}

echo $client->response;

?>