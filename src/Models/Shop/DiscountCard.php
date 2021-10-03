<?php

namespace Codeman\Admin\Models\Shop;

use Illuminate\Database\Eloquent\Model;

class DiscountCard extends Model
{
    protected $fillable = [
    	'user_id',
    	'session_id',
    	'code',
    	'discount',
    	'cardholder_name',
    	'cardholder_phone',
    	'bonus',
    	'point',
    	'is_bonus_card',
    	'card_id',
    ];

    public function addOrUpdateDiscountCard($discount_card_data, $session_id, $user_id = '')
    {
        $discount_card = self::where('code', $discount_card_data['code'])->first();
        
        if($discount_card != null)
        {
            if($discount_card->user_id != null && $user_id != null && $discount_card->user_id == $user_id){
                $discount_card = self::updateOrCreate(
                    ['user_id' => $user_id, 'code' => $api_data->GetPartnerByCodeResult->Code],
                    $discount_card_data);
            }else if($discount_card->user_id == null && $discount_card->session_id == $session_id){
                $discount_card = self::updateOrCreate(
                    ['session_id' => $session_id, 'code' => $api_data->GetPartnerByCodeResult->Code],
                    $discount_card_data);
            }else{
                if($user_id != '' && $user_id > 0){
                    self::where('user_id', $user_id)->delete();
                }else if($session_id){
                    self::where('session_id', $session_id)->delete();
                }
                $discount_card = self::create($discount_card_data);
            }
        }else{
            if($user_id != '' && $user_id > 0){
                self::where('user_id', $user_id)->delete();
            }else if($session_id){
                self::where('session_id', $session_id)->delete();
            }
            $discount_card = self::create($discount_card_data);
        }
        return $discount_card;
    }

    public function getDiscountCardBy( $type = 'session_id', $value = null)
    {
    	return self::where($type, $value)->first();
    }
}
