<?php

namespace Codeman\Admin\Helpers;
use Codeman\Admin\Models\Shop\User;
use App\Models\EmailTemplate;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Response;

class EmailShortCode
{

    
    const SHORTCODES = [
        "[:id:]",
        "[:user_full_name:]",
        "[:billing_first_name:]",
        "[:billing_last_name:]",
        "[:billing_email:]",
        "[:billing_phone:]",

        "[:order_items:]",
        "[:total:]",
        "[:status:]",
        "[:status_message:]",
        "[:tracking_number:]",
        "[:button-name=|url=:]",
    ];

    const SHORTCODES_USER_TB = 
    [
        "[:user_full_name:]",
        "[:first_name:]",
        "[:last_name:]",
        "[:email:]",
        "[:phone:]",
        "[:button-name=|url=:]",
        
    ];

    const SHORTCODES_PRODUCT_ALERT_EMAIL = [
         "[:subscribed_products:]",
    ];

    public static function product_subscription_convertation($data,$product_details)
    {
        $data = html_entity_decode($data);
        if(strpos($data,"[:subscribed_products:]") == true)
        {              
            $val =  "[:subscribed_products:]";
            while($start = strpos($data,"[:subscribed_products:]"))
            {
                $html = 
                        "<div>
                            <h3>{$product_details->title}</h3>
                            <div>
                                <a href='{$product_details->item_url}'>
                                    <img src='{$product_details->secondary_thumbnail}'>
                                </a>
                            </div>    
                            <div>Price - {$product_details->price}</div>
                            <div>Sale Price - {$product_details->sale_price}</div>
                        </div>";
                $data = str_replace($val,$html,$data); 
            }
        }
        return $data;
    }
    // Pass $data,null,$user_id if you want to act with user statuses
    public function get_converted_data($data,$order = null,$user_id = null)
    {

        // strip_tags
        $data = html_entity_decode($data);
        
        $user = null;
        $fetchShortCodes = EmailShortCode::SHORTCODES;
        if($user_id != null)
        {
            $user = User::find($user_id);
            $fetchShortCodes = EmailShortCode::SHORTCODES_USER_TB;
        }

     
        foreach($fetchShortCodes as $key => $val)
        {

            
            if(strpos($data,$val) == true || strpos($data,"[:button-name="))
            {
                $order_items = "";
                if($val == "[:order_items:]")
                {
                    $order_items = $this->get_order_items($order->items);

                }
                
                if($val == "[:button-name=|url=:]")
                {
                    while($start = strpos($data,"[:button-name="))
                    {
                        $result_button = $this->find_replace_button($data,$start);
                        $data = $result_button;
                    }

                    continue;
                }
                if($val == "[:user_full_name:]")
                {
                    while(strpos($data,"[:user_full_name:]"))
                    {
                        if($user != null)
                        {
                            $replace_with_fn = $user->full_name; 
                        }else{
                            $replace_with_fn = "{$order->billing_first_name} {$order->billing_last_name}";
                        }
                        
                        $data = str_replace($val,$replace_with_fn,$data); 
                        
                    }
                    continue;
                }

                $convert_ready_val = substr($val,2,-2);
                
                if($user != null)
                {
                    $replace_with = $user->$convert_ready_val;
                }else{
                    $replace_with = (!empty($order_items)) ? $order_items : $order->$convert_ready_val;  
                }
                $converted = str_replace($val,$replace_with,$data);
                $data = $converted;
                
            }
            
        }
        return $data;
    }
    

    public function find_replace_button($data,$start)
    {
        $end = strrpos($data,":]",$start);
        
        // dd($data,$start,$end-$start);
        $end+=2;
        $button = substr($data,$start,($end-$start));
       
        //cut name
        $button_name_start = strpos($button,"=");
        $button_name_start++;
        $button_name_end = strpos($button,"|");
        $get_name = substr($button,$button_name_start,($button_name_end-$button_name_start));
        //cut name
        
        //cut url
        $button_url_start = strpos($button,"url=");
        $button_url_start+=4;
        $button_url_end = strpos($button,":]");
        $get_url = substr($button,$button_url_start,($button_url_end-$button_url_start));
       
        //cut url
        $link = "<a href='{$get_url}'>{$get_name}<a/> ";
        $data = str_replace($button,$link,$data);
        return $data;
    }

    public function get_order_items($order_items)
    {
        $converted_string="";
        foreach($order_items as $item)
        {
            $price = ($item->sale_price == null) ? $item->price : $item->sale_price;
            $converted_string .= "{$item->title}, {$item->qty} - штук, цена - {$price} .";
        }
        return $converted_string;
    }

};


?>