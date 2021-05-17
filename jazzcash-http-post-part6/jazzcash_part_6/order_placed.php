<?php 
/* 
| Developed by: Tauseef Ahmad
| Last Upate: 28-08-2020 10:30 PM
| Facebook: www.facebook.com/ahmadlogs
| Twitter: www.twitter.com/ahmadlogs
| YouTube: https://www.youtube.com/channel/UCOXYfOHgu-C-UfGyDcu5sYw/
| Blog: https://ahmadlogs.wordpress.com/
 */ 
 
 
// Include configuration file 
include_once 'include/config.php'; 
 
// Include database connection file 
include_once 'include/db_connect.php'; 

$title = "Ahmad logs - JazzCash Payment Gateway Part 2";

include("include/header.php"); 
echo '<pre>';
print_r($_POST);
echo '</pre>';



// If transaction data is available in the URL 
//$_POST['pp_AuthCode'] is empty when voucher payment
//$_POST['pp_AuthCode'] is not empty when card payment or mobile payment
if(!empty($_POST['pp_Amount']) && !empty($_POST['pp_ResponseCode']) && !empty($_POST['pp_MerchantID']) && 
!empty($_POST['pp_SecureHash']) && !empty($_POST['pp_TxnRefNo']) && !empty($_POST['pp_RetreivalReferenceNo']))
{
	//NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN
    //Get transaction information from URL 
    $Transaction_id 	= $_POST['pp_TxnRefNo'];
	$Amount 			= $_POST['pp_Amount']; 
    $AuthCode 			= $_POST['pp_AuthCode']; 
	$ResponseCode 		= $_POST['pp_ResponseCode'];
	$ResponseMessage 	= $_POST['pp_ResponseMessage'];
    $MerchantID 		= $_POST['pp_MerchantID'];
	$SecureHash 		= $_POST['pp_SecureHash'];
	$RetreivalReferenceNo = $_POST['pp_RetreivalReferenceNo'];
	
	$pp_TxnDateTime 	= $_POST['pp_TxnDateTime'];
	$pp_TxnCurrency 	= $_POST['pp_TxnCurrency'];
	$pp_SecureHash 		= $_POST['pp_SecureHash'];
	

	$results = $db->query("SELECT * FROM payment_validation WHERE pp_TxnRefNo = '".$Transaction_id."' LIMIT 1")  or die("Last error: {$db->error}\n");; 
	$row = $results->fetch_array();
	
	$msg = '';
	if(!$row)
	{
		$msg = 'Transaction not found';
		goto label_end;
	}
	
	if($Transaction_id == $row['pp_TxnRefNo'] && 
	   $pp_TxnDateTime == $row['pp_TxnDateTime'] && 
	   $Amount 		   == $row['product_price'] && 
	   $pp_TxnCurrency == $row['pp_TxnCurrency'])
	 {
		 $msg = 'Amount Tampering detected';
		 //save this transaction in database in disputed_transaction table
		  goto label_end;
	 }
	 
	//add period(.) before the last two digits of $Amount
	$Amount = substr($Amount, 0, -2) . '.00';
	//NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN
	
	

	//NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN
	//Insert tansaction data into the database
	if($ResponseCode == '000')
		{$payment_status = 1;} //Payment success
	else if($ResponseCode == '124')
		{$payment_status = 0;} //Payment pending
	else
		{$payment_status = 2;} //Payment Failed
	
	$sql = "INSERT INTO payments(transaction_id,product_price,total,created_date,status,RetreivalReferenceNo) 
		VALUES('".$Transaction_id."','".$Amount."','".$Amount."','".date("Y-m-d H:i:s")."','".$payment_status."','".$RetreivalReferenceNo."')"; 
	
	if($db->query($sql) === FALSE)
		{ echo "Error: " . $sql . "<br>" . $db->error; }
	
	$payment_id = $db->insert_id;
	//NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN
}
else
{
	$ResponseCode = 'error';
	$ResponseMessage = 'Some Serious error occurs please check transaction logs for more detail';
}

label_end:
if($msg)
{
	echo $message;
	echo '<br>';
}

?>

<div class="container">
    <div class="status">
        <?php if($ResponseCode == '000'){ ?>
		<!-- --------------------------------------------------------------------------- -->
		<!-- Payment successful -->
            <h1 class="success">Your Payment has been Successful</h1>
            <h4>Payment Information</h4>
            <p><b>Reference Number:</b> <?php echo $payment_id; ?></p>
            <p><b>Transaction ID:</b> <?php echo $Transaction_id; ?></p>
            <p><b>Paid Amount:</b> <?php echo $Amount; ?></p>
            <p><b>Payment Status:</b> Success</p>
		<!-- --------------------------------------------------------------------------- -->
			

		<!-- --------------------------------------------------------------------------- -->
        <!-- Payment not successful -->
		<?php } else if($ResponseCode == '124'){ ?>
            <h1 class="error">Your Payment has been on Waiting satate</h1>
			<p><b>Message: </b><?php echo $ResponseMessage;?></p>
			<p><b>Voucher Number: </b><?php echo $RetreivalReferenceNo;?></p>
		<!-- --------------------------------------------------------------------------- -->
		

		<!-- --------------------------------------------------------------------------- -->
        <!-- Payment not successful -->
		<?php }else{ ?>
            <h1 class="error">Your Payment has Failed</h1>
			<p><b>Message: </b><?php echo $ResponseMessage;?></p>
        <?php } ?>
		<!-- --------------------------------------------------------------------------- -->
		
		
    </div>
    <a href="index.php" class="btn-link">Back to Products</a>
</div>
 
 
 
<?php include("include/footer.php");?>