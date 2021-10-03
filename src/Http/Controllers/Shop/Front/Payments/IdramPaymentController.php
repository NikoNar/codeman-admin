<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
// use App\Models\OrderItem;
use App\Models\PaymentMethods;
use App\Models\Transaction;

class IdramPaymentController extends Controller
{

	protected $order_id;

    public function __construct(Transaction $transaction, Order $order)
    {
    	$this->transaction = $transaction;
    	$this->order = $order;
    	$this->lang = \App::getLocale();

    	if($this->lang == 'hy'){
    		$this->lang = 'am';
    	}

    	$this->test_mode = env('idram_test_mode', false);
    	$this->client_id = env('idram_id', '110000395');
    	$this->secret_key = env('idram_secret_key', 'nmFDMz7M2JyzRcK8TCjjpyDNTrwVPSzgCqd6J4');
    	$this->currency = env('idram_currency', 051);
    	// $this->api_url = env('ameria_bank_api_url', 'https://servicestest.ameriabank.am/VPOS/');
    	// $this->back_url = env('ameria_bank_back_url', url($this->lang.'/order/transaction/gateway/ameriabank/back'));
    	
    	$this->description = 'Online Purchase';
    }

    public function process_payment($order_id)
    {

    	$order = $this->getOrder($order_id);
    	
    	$transaction = $this->transaction->create([
    		'order_id' => $order->id,
    		'merchant' => 'idram',
    		'amount' => $order->total,
    		'currency' => 'AMD',
    		'currency_code' => $this->currency, //AMD ISO CODE
    		'status_message' => 'Connecting with bank gateway'
    	]);

        $transaction->update([
            'payment_id' => $order->id,
            'status_message' => 'Connected with bank gateway, Redirect client to the bank payment page'
        ]);

		$params = array(
			'EDP_LANGUAGE' => strtoupper($this->lang),
			'EDP_REC_ACCOUNT' => $this->client_id,
	        'EDP_DESCRIPTION' => $this->description,
	        'EDP_BILL_NO' => $order->id,
	        'EDP_AMOUNT' => !$this->test_mode ? $order->total : 10,
	        'EDP_EMAIL' => $order->billing_email,
            'transaction_id' => $transaction->id
	    );
		return view('checkout.payment.idram_form', ['data' => $params]);
    }

    public function success()
    {
    	$request = request()->all();
        \Log::info($request);
        $order = $this->getOrder($request['EDP_BILL_NO']);

        if (!$order) {
            return redirect()->to('checkout')->with('error', __('Sorry, something wrong, please contact us by phone or email. Problem with order #').$request['EDP_BILL_NO']);
        }

        // $transaction = $this->transaction
        // ->where('order_id', $order->id)
        // ->where('merchant', 'idram')
        // ->where('status', 'pending')
        // ->first();

        // if($transaction){
        //     $transaction->update([
        //         'status' => 'completed',
        //         // 'payment_id' => $request['EDP_TRANS_ID'],
        //         // 'card_holder_id' => $request['EDP_PAYER_ACCOUNT'],
        //         'status_message' => 'Transaction approved',
        //     ]);
        // }

        // $order->update([
        //     'status' => 'in review'
        // ]);

        
        
        return redirect()->route('order.compleated', ['order_id' => $order->id]);

    }

    public function failed()
    {
        $request = request()->all();
        \Log::info($request);

    	if(isset($request['EDP_BILL_NO'])){
            if (null != $order = $this->getOrder($request['EDP_BILL_NO'])) {
                
                $order->update([
                    'status' => 'pending'
                ]);

                $transaction = $this->transaction
                ->where('order_id', $order->id)
                ->where('merchant', 'idram')
                ->where('status', 'pending')
                ->first();

                $transaction->update([
                    'status' => 'failed',
                    'status_message' => 'Payment Declined'
                ]);                
                
                return redirect()->to('checkout?order='.$order->id.'&status=transaction-failed')->with('error', 'There was a problem with idram payment. Please try again or choose a different payment method.');
            }
        }	
        return redirect()->to('checkout')->with('error', 'There was a problem with idram payment. Please try again or choose a different payment method.');

    }

    public function result()
    {
        $request = request()->all();
        \Log::info($request);

    	if (isset($request['EDP_PRECHECK']) && isset($request['EDP_BILL_NO']) &&
            isset($request['EDP_REC_ACCOUNT']) && isset($request['EDP_AMOUNT'])) {
            if ($request['EDP_PRECHECK'] == "YES") {
                if ($request['EDP_REC_ACCOUNT'] == $this->client_id) {
                    if (null != $order = $this->getOrder($request['EDP_BILL_NO'])) {
                        echo("OK");die;
                    }
                }
            }
        }

        if (isset($request['EDP_PAYER_ACCOUNT']) && isset($request['EDP_BILL_NO']) &&
            isset($request['EDP_REC_ACCOUNT']) && isset($request['EDP_AMOUNT'])
            && isset($request['EDP_TRANS_ID']) && isset($request['EDP_CHECKSUM'])) {
            
            $txtToHash =
                $this->client_id . ":" .
                $request['EDP_AMOUNT'] . ":" .
                $this->secret_key . ":" .
                $request['EDP_BILL_NO'] . ":" .
                $request['EDP_PAYER_ACCOUNT'] . ":" .
                $request['EDP_TRANS_ID'] . ":" .
                $request['EDP_TRANS_DATE'];

            $order = $this->getOrder($request['EDP_BILL_NO']);
            if($order){
                $transaction = $this->transaction
                ->where('id', $request['transaction_id'])
                ->where('order_id', $request['EDP_BILL_NO'])
                ->where('merchant', 'idram')
                ->where('status', 'pending')
                ->first();
                if($transaction){
                    if (strtoupper($request['EDP_CHECKSUM']) == strtoupper(md5($txtToHash))) {
                        $transaction->update([
                            'status' => 'completed',
                            'payment_id' => $request['EDP_TRANS_ID'],
                            'card_holder_id' => $request['EDP_PAYER_ACCOUNT'],
                            'status_message' => 'Transaction approved',
                        ]);

                        $order->update([
                            'status' => 'in review'
                        ]);
                        //Send email to Admin and Buyer

                        echo("OK");die;
                    }else{
                        $transaction->update([
                            'status' => 'failed',
                            'payment_id' => $request['EDP_TRANS_ID'],
                            'card_holder_id' => $request['EDP_PAYER_ACCOUNT'],
                            'status_message' => 'Payment Declined',
                        ]);
                    }
                }
            }
            
        }
    }

    private function getOrder($order_id)
    {
    	return $this->order->where('id', $order_id)->where('status', '!=', 'compleated')->first();
    }

}
