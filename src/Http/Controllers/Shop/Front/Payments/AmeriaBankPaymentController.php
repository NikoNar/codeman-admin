<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
// use App\Models\OrderItem;
use App\Models\PaymentMethods;
use App\Models\Transaction;

class AmeriaBankPaymentController extends Controller
{

	// Card number:  9051320200000009

	// Cardholder:     TEST VISA V-POS

	// Exp. date:        12/20

	// CVV:               997

	protected $order_id;

    public function __construct(Transaction $transaction, Order $order)
    {
    	$this->transaction = $transaction;
    	$this->order = $order;
    	$this->lang = \App::getLocale();

        $this->gateway_lang = $this->lang == 'hy' ? 'am' : $this->lang;

    	$this->test_mode = env('ameria_bank_test_mode', false);
    	$this->client_id = env('ameria_bank_client_id', 'be652a33-9738-489e-98d5-9d9f9706c7e6');
    	$this->username = env('ameria_bank_username', '19536106_api');
    	$this->password = env('ameria_bank_password', 'e9VuCK972y76r6c');
    	$this->currency = env('ameria_bank_currency', 051);
    	$this->api_url = env('ameria_bank_api_url', 'https://services.ameriabank.am/VPOS/');
    	$this->back_url = env('ameria_bank_back_url', url($this->lang.'/order/transaction/gateway/ameriabank/back'));

    	$this->description = 'Online Purchase';
    }

    public function process_payment($order_id)
    {
    	$order = $this->getOrder($order_id);
    	
    	$transaction = $this->transaction->create([
    		'order_id' => $order->id,
    		'merchant' => 'ameriabank',
    		'amount' => $order->total,
    		'currency' => 'AMD',
    		'currency_code' => $this->currency, //AMD ISO CODE
    		'status_message' => 'Connecting with bank gateway'
    	]);

		$result = $this->requestPaymentId($order, $transaction);
		// dd($result);
		if(isset($result->ResponseCode) && $result->ResponseCode == 1){

			$transaction->update([
				'payment_id' => $result->PaymentID,
				'status_message' => 'Connected with bank gateway, Redirect client to the bank payment page'
			]);

			return redirect()->to($this->api_url.'Payments/Pay?id='.$result->PaymentID.'&lang='.$this->gateway_lang);
		}

		$transaction->update([
			'status' => 'failed',
			'status_message' => 'Unable to connect to bank gateway'
		]);

		$order->update([
    		'status' => 'pending'
    	]);

		return redirect()->to('checkout?order='.$order->id.'&status=transaction-failed')->with('error', $result->ResponseMessage);
    }

    public function return_back()
    {
    	$request = request()->all();
    	$order = $this->getOrder($request['orderID']);
		
		$transaction = $this->transaction
		->where('id', $request['transaction_id'])
		->where('payment_id', $request['paymentID'])
		->where('order_id', $request['orderID'])
		->first();

		if (!$order || !$transaction) {
			return redirect()->to('checkout')->with('error', __('Sorry, something wrong, please contact us by phone or email. Problem with order #').$request['orderID']);
		}

		if(isset($request['paymentID'])){
			$payent_details = $this->getPaymentDetails($request['paymentID']);
            // dd($request, $payent_details);
            if(isset($payent_details->ResponseCode) && $payent_details->ResponseCode == '00')
            {
                $transaction->update([
                    'status' => 'completed',
                    'status_message' => $payent_details->Description,
                    'response_message' => $payent_details->Description,
                    'response_code' => $payent_details->ResponseCode,
                    'additional_data' => json_encode($payent_details),
                ]);

                $order->update([
                    'status' => 'in review',
                ]);

                //Send email to Admin and Buyer
                return redirect()->route('order.compleated', ['order_id' => $transaction->order_id]);
            }
            $transaction->update([
                'status' => 'declined',
                'status_message' => $payent_details->Description,
                'response_message' => $payent_details->Description,
                'response_code' => $payent_details->ResponseCode,
                'additional_data' => json_encode($payent_details),
            ]);

            $order->update([
                'status' => 'failed',
            ]);

            return redirect()->to('checkout?order='.$order->id.'&status=transaction-failed')->with('error', $payent_details->Description);

		}
    	
    	$transaction->update([
    		'status' => 'declined',
    		'status_message' => $request['description'],

    	]);

    	$order->update([
    		'status' => 'failed'
    	]);

    	return redirect()->to('checkout?order='.$order->id.'&status=transaction-failed')->with('error', $request['description']);
    }

    public function checkPaymentStatus($payment_id)
    {
        $data = $this->getPaymentDetails($payment_id); 
        
        return response()->json([
            'status' => 'true', 
            'html' => view('admin.orders.parts.transaction-details', [
                'title' => 'Ameria # '.$payment_id,
                'transaction' => $data
            ])->render()
        ]);
    }

    private function requestPaymentId($order, $transaction)
    {
		$params = array(
			'ClientID' => $this->client_id,
	        'Username' => $this->username,
	        'Password' => $this->password,                
	        'Description' => $this->description,
	        'OrderID' => $order->id,
	        'Amount' => !$this->test_mode ? $order->total : 1,
	        'BackURL' => $this->back_url.'?transaction_id='.$transaction->id.'&order_id='.$order->id,
	        'Currency ' => $this->currency,
	    );
        try {
            $request = $this->curl_post($this->api_url.'api/VPOS/InitPayment', $params);
        } catch (Exception $e) {
            return false;
        }

		return json_decode($request);
    }

    private function getPaymentDetails($payment_id)
    {
  		$params = array(
  			'PaymentID' => $payment_id,
  	        'Username' => $this->username,
  	        'Password' => $this->password,                
  	    );

  		$request = $this->curl_post($this->api_url.'api/VPOS/GetPaymentDetails', $params);

  		return json_decode($request);
    }

    private function getOrder($order_id)
    {
    	return $this->order->where('id', $order_id)->where('status', '!=', 'compleated')->first();
    }

	/**
  	* http://www.php.net/manual/ru/function.curl-exec.php
  	*/
  	/**
  	* Send a GET request using cURL
  	* @param string $url to request
  	* @param array $get values to send
  	* @param array $options for cURL
  	* @return string
  	*/

  	private function curl_get($url, array $get = NULL, array $options = array())
  	{

  	    $defaults = array(
  	        CURLOPT_URL => $url . (strpos($url, "?") === FALSE ? "?" : "") . http_build_query($get) ,
  	        CURLOPT_HEADER => 0,
  	        CURLOPT_RETURNTRANSFER => TRUE,
  	        CURLOPT_DNS_USE_GLOBAL_CACHE => false,
  	        CURLOPT_SSL_VERIFYHOST => 0, //unsafe, but the fastest solution for the error " SSL certificate problem, verify that the CA cert is OK"
  	        CURLOPT_SSL_VERIFYPEER => 0, //unsafe, but the fastest solution for the error " SSL certificate problem, verify that the CA cert is OK"
  	    );
  	    
  	    $ch = curl_init();
  	    curl_setopt_array($ch, ($options + $defaults));
  	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
  	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);


  	    if (!$result = curl_exec($ch)) {
  	        trigger_error(curl_error($ch));
  	    }

  	    curl_close($ch);
  	    
  	    return $result;
  	}


    /**
    * http://www.php.net/manual/ru/function.curl-exec.php
    */
    /**
    * Send a POST request using cURL
    * @param string $url to request
    * @param array|string $post values to send
    * @param array $options for cURL
    * @internal param array $get
    * @return string
    */
    private function curl_post($url, $post = null, array $options = array())
    {

        $ch = curl_init($url);

        $data_string = json_encode($post);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_ENCODING, "");
        curl_setopt($ch, CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json; charset=utf-8',
                'Content-Length: ' . strlen($data_string)
            )
        );

        $result = curl_exec($ch);
        if( !$result ){
            trigger_error(curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }
}
