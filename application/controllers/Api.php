<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		$this->load->model('api_model');
		$this->load->model('checkout_model');
		$this->load->library('mpesa');
	}
	function index(){
	    $CONSUMER_KEY = '';
	    $CONSUMER_SECRET = '';
	    $ENVIRONMENT = '';
	    $SHORTCODE = '';

	    $mpesa_settings = $this->api_model->get_mpesa_settings();
	    foreach($mpesa_settings as $row){
	    	$CONSUMER_KEY = $row->consumer_key;
	    	$CONSUMER_SECRET = $row->consumer_secret;
	    	$ENVIRONMENT = $row->environment;
	    	$SHORTCODE = $row->short_code;
	    }

		$mpesa= new Mpesa();
		echo ($mpesa->generateLiveToken($CONSUMER_KEY, $CONSUMER_SECRET));
	}

	function registerurl(){
	    $CONSUMER_KEY = '';
	    $CONSUMER_SECRET = '';
	    $ENVIRONMENT = '';
	    $SHORTCODE = '';

	    $mpesa_settings = $this->api_model->get_mpesa_settings();
	    foreach($mpesa_settings as $row){
	    	$CONSUMER_KEY = $row->consumer_key;
	    	$CONSUMER_SECRET = $row->consumer_secret;
	    	$ENVIRONMENT = $row->environment;
	    	$SHORTCODE = $row->short_code;
	    }

		$mpesa= new Mpesa();
		echo($mpesa->c2b_registerurl($SHORTCODE, 'Completed', 'https://bethanyhouse.co.ke/api/confirmation', 'https://bethanyhouse.co.ke/api/validation', $ENVIRONMENT, $CONSUMER_KEY, $CONSUMER_SECRET));
	}

	function validation(){
		$resultArray=[
            "ResultDesc"=>"Confirmation Service request accepted successfully",
            "ResultCode"=>"0"
        ];

		header('Content-Type: application/json');

        echo json_encode($resultArray);
	}

	function confirmation(){

		$callbackJSONData=file_get_contents('php://input');
        $callbackData=json_decode($callbackJSONData);

        $transactionType=$callbackData->TransactionType;
        $transID=$callbackData->TransID;
        $transTime=$callbackData->TransTime;
        $transAmount=$callbackData->TransAmount;
        $businessShortCode=$callbackData->BusinessShortCode;
        $billRefNumber=$callbackData->BillRefNumber;
        $invoiceNumber=$callbackData->InvoiceNumber;
        $orgAccountBalance=$callbackData->OrgAccountBalance;
        $thirdPartyTransID=$callbackData->ThirdPartyTransID;
        $MSISDN=$callbackData->MSISDN;
        $firstName=$callbackData->FirstName;
        $middleName=$callbackData->MiddleName;
        $lastName=$callbackData->LastName;

		$callback_data = array(
			'transaction_type' => $transactionType,
			'transaction_id' => $transID,
			'transaction_time' => date("Y-m-d H:i:s", strtotime($transTime)),
			'transaction_amount' => $transAmount,
			'business_short_code' => $businessShortCode,
			'bill_reference_number' => $billRefNumber,
			'invoice_number' => $invoiceNumber,
			'organization_account_balance' => $orgAccountBalance,
			'third_party_transaction_id' => $thirdPartyTransID,
			'MSISDN' => $MSISDN,
			'first_name' => $firstName,
			'middle_name' => $middleName,
			'last_name' => $lastName
		);
		
		$this->api_model->confirmation($callback_data);

		$resultArray=[
            "ResultDesc"=>"Confirmation Service request accepted successfully",
            "ResultCode"=>"0"
        ];

		header('Content-Type: application/json');

        echo json_encode($resultArray);

	}

	function stk_cb(){
		$response = json_decode(file_get_contents('php://input'), true);
		$response = isset($response['Body']) ? $response['Body'] : array();
		if (isset($response['Body'])) {

            if (isset($response['Body']['stkCallback']['CallbackMetadata'])) {

                $parsed = array();
                foreach ($response['Body']['stkCallback']['CallbackMetadata']['Item'] as $item) {
                    $parsed[$item['Name']] = $item['Value'];
                }

                $this->api_model->update_contact($parsed['MpesaReceiptNumber'],$parsed['PhoneNumber']);

                // $parsed['PhoneNumber']
                // $parsed['MpesaReceiptNumber']

            }

        }
        
		// $callbackJSONData=file_get_contents('php://input');
        // $callbackData=json_decode($callbackJSONData);
        // $resultCode=$callbackData->Body->stkCallback->ResultCode;
        // $resultDesc=$callbackData->Body->stkCallback->ResultDesc;
        // $merchantRequestID=$callbackData->Body->stkCallback->MerchantRequestID;
        // $checkoutRequestID=$callbackData->Body->stkCallback->CheckoutRequestID;
        // $amount=$callbackData->stkCallback->Body->CallbackMetadata->Item[0]->Value;
        // $mpesaReceiptNumber=$callbackData->Body->stkCallback->CallbackMetadata->Item[1]->Value;
        // $balance=$callbackData->stkCallback->Body->CallbackMetadata->Item[2]->Value;
        // $b2CUtilityAccountAvailableFunds=$callbackData->Body->stkCallback->CallbackMetadata->Item[3]->Value;
        // $transactionDate=$callbackData->Body->stkCallback->CallbackMetadata->Item[4]->Value;
        // $phoneNumber=$callbackData->Body->stkCallback->CallbackMetadata->Item[5]->Value;
        // $result=[
        //     "resultDesc"=>$resultDesc,
        //     "resultCode"=>$resultCode,
        //     "merchantRequestID"=>$merchantRequestID,
        //     "checkoutRequestID"=>$checkoutRequestID,
        //     "amount"=>$amount,
        //     "mpesaReceiptNumber"=>$mpesaReceiptNumber,
        //     "balance"=>$balance,
        //     "b2CUtilityAccountAvailableFunds"=>$b2CUtilityAccountAvailableFunds,
        //     "transactionDate"=>$transactionDate,
        //     "phoneNumber"=>$phoneNumber
        // ];
        // return json_encode($result);        
	}

	function pesapal_callback() {

		$callback_success = false;

		$this->load->view('fe/OAuth/OAuth'); 

		$CONSUMER_KEY = '';
	    $CONSUMER_SECRET = '';
	    $ENVIRONMENT = '';

	    $pesapal_settings = $this->api_model->get_pesapal_settings();
	    foreach($pesapal_settings as $row){
	    	$CONSUMER_KEY = $row->consumer_key;
	    	$CONSUMER_SECRET = $row->consumer_secret;
	    	$ENVIRONMENT = $row->environment;
	    }

		$consumer_key=$CONSUMER_KEY;
		$consumer_secret=$CONSUMER_SECRET;

		if ($ENVIRONMENT == 'LIVE') {
			$statusrequestAPI = 'https://www.pesapal.com/api/querypaymentdetails';
		} else {
			$statusrequestAPI = 'https://demo.pesapal.com/api/querypaymentdetails';
		}

		// Parameters sent to you by PesaPal IPN
		$pesapalNotification=$_GET['pesapal_notification_type'];
		$pesapalTrackingId=$_GET['pesapal_transaction_tracking_id'];
		$pesapal_merchant_reference=$_GET['pesapal_merchant_reference'];

		if($pesapalNotification=="CHANGE" && $pesapalTrackingId!=''){
		   $token = $params = NULL;
		   $consumer = new OAuthConsumer($consumer_key, $consumer_secret);
		   $signature_method = new OAuthSignatureMethod_HMAC_SHA1();

		   //get transaction status
		   $request_status = OAuthRequest::from_consumer_and_token($consumer, $token, "GET", $statusrequestAPI, $params);
		   $request_status->set_parameter("pesapal_merchant_reference", $pesapal_merchant_reference);
		   $request_status->set_parameter("pesapal_transaction_tracking_id",$pesapalTrackingId);
		   $request_status->sign_request($signature_method, $consumer, $token);

		   $ch = curl_init();
		   curl_setopt($ch, CURLOPT_URL, $request_status);
		   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		   curl_setopt($ch, CURLOPT_HEADER, 1);
		   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		   if(defined('CURL_PROXY_REQUIRED')) if (CURL_PROXY_REQUIRED == 'True')
		   {
		      $proxy_tunnel_flag = (defined('CURL_PROXY_TUNNEL_FLAG') && strtoupper(CURL_PROXY_TUNNEL_FLAG) == 'FALSE') ? false : true;
		      curl_setopt ($ch, CURLOPT_HTTPPROXYTUNNEL, $proxy_tunnel_flag);
		      curl_setopt ($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
		      curl_setopt ($ch, CURLOPT_PROXY, CURL_PROXY_SERVER_DETAILS);
		   }

		   $response = curl_exec($ch);

		   $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		   $raw_header  = substr($response, 0, $header_size - 4);
		   $headerArray = explode("\r\n\r\n", $raw_header);
		   $header      = $headerArray[count($headerArray) - 1];

		   //transaction status
		   //$elements = preg_split("/=/",substr($response, $header_size));
		   //$status = $elements[1];

		   $resStatus = '';

   			//transaction status
			if ($response != ''){
				$elements = preg_split("/=/",substr($response, $header_size));
				$status = explode (",", $elements[1]);//$elements[1];

				if ($status[2] == 'COMPLETED'){
					$order = $this->checkout_model->get_order($status[3]);
			        foreach($order as $row){
			        	$transaction_amount = $row->ord_total;
			        	$ord_order_number = $row->ord_order_number;
			            $customer_id = $row->ord_customer_id;
			        }

					$data = array(
		                'payment_method' => $status[1],
		                'transaction_tracking_id' => $status[0],
		                'merchant_reference_id' => $status[3],
		                'transaction_amount' => $transaction_amount,
		                'ord_order_number' => $ord_order_number,
		                'customer_id' => $customer_id,
		                'transaction_completed' => 1
		            );

		            $q = $this->api_model->submit_pesapal_payment($data, $status[0]);
					if ($q == true){
						$callback_success = true;
					}
				}
			}

		   curl_close ($ch);

		   if($callback_success == true){
		      $resp="pesapal_notification_type=$pesapalNotification&pesapal_transaction_tracking_id=$pesapalTrackingId&pesapal_merchant_reference=$pesapal_merchant_reference";
		      ob_start();
		      echo $resp;
		      ob_flush();
		      exit;
		   }
		}



	}


}