<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use App\Models\Cart;
use App\Models\Tracker;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

	public function __construct(Cart $cart)
	{
		// $this->session_id = $cart->get_cookie();
		$this->lang = \App::getLocale();
	}

	public function tracker($params = array())
	{
		$params['url'] = request()->fullUrl();
		$params['referral_url'] = request()->headers->get('referer'); 
		$params['ip_address'] = request()->ip();
		$params['lang'] = $this->lang;
		$params['user_id'] = auth()->check() ? auth()->id() : NULL;
		$params['session_id'] = $this->session_id;
		$params['fingerprint'] = request()->fingerprint();
		// dd(request()->fingerprint());
		Tracker::create($params);

	}
	//check this package // https://github.com/Potelo/laravel-block-bots 

	//simple way to detect some of the robots
	function _bot_detected() {

		return (
			isset($_SERVER['HTTP_USER_AGENT'])
	    	&& preg_match('/bot|crawl|slurp|spider|mediapartners/i', $_SERVER['HTTP_USER_AGENT'])
	    );
	}
}