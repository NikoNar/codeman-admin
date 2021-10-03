<?php

	function set_cookie($name, $value, $minutes = null){
	    $minutes = !$minutes ? time() + 60 * 60 * 24 * 365 : $minutes;
	    return \Cookie::queue($name, $value, $minutes);
	}

	function get_cookie($name = null){
	    $result = $name ? \Cookie::get($name) : \Cookie::get();
	    return $result;
	}

	function get_user_session_id(){
	    $cookie = \Cookie::get('user_session_id');
	    // $cookie = request()->cookie('user_session_id');
	    if($cookie){
	        return  $cookie;
	    }

	    $uuid = \Str::orderedUuid()->toString();
	    // $session_id = \Str::random(32);
	    $one_year = time() + 60 * 60 * 24 * 365;// one year
	    \Cookie::queue('user_session_id', $uuid, $one_year ); // change $session_id with uuid

	    return $uuid;
	}

	/**
	 * Generate a unique slug.
	 * If it already exists, a number suffix will be appended.
	 * It probably works only with MySQL.
	 *
	 * @link http://chrishayes.ca/blog/code/laravel-4-generating-unique-slugs-elegantly
	 *
	 * @param Illuminate\Database\Eloquent\Model $model
	 * @param string $value
	 * @return string
	 */
    function getUniqueSlug(\Illuminate\Database\Eloquent\Model $model, $value, $existing_id = null, $req_lang = null)
    {

    	$translitSlug = new Codeman\Admin\Http\TranslitSlug();
        // $slug = url_slug($value);
    	$slug = $translitSlug->build($value, '-', 2, false);

        if(!$req_lang){
        	$req_lang = (request()->lang)? request()->lang : \Codeman\Admin\Models\Language::orderBy('order')->first()->code;
        }
        if(isset($existing_id)){
            $lang = $model->where('id', $existing_id)->first()['lang'];
            $slugCount = count($model->whereRaw("slug REGEXP '^{$slug}(-[0-9]+)?$' and id != '{$existing_id}' and lang = '{$lang}'")->get());
        }else{
            // $slugCount = count($model->whereRaw("slug REGEXP '^{$slug}(-[0-9]+)?$' and id != '{$model->id}' and lang = '{$req_lang}' ")->get());
            $slugCount = checkReapeatedSlugsCount($slug, $model, $req_lang);
        }
        return ($slugCount > 0) ? "{$slug}-{$slugCount}" : $slug;
    }

    function checkReapeatedSlugsCount($slug, $model, $req_lang)
    {
    	if($model->id){
    		return count($model->whereRaw("slug REGEXP '^{$slug}(-[0-9]+)?$' and id != '{$model->id}' and lang = '{$req_lang}' ")->get());
    	}else{
    		return count($model->whereRaw("slug REGEXP '^{$slug}(-[0-9]+)?$' and lang = '{$req_lang}' ")->get());

    	}
    }

	function getMaxOrderNumber($modelName, $inputs = null)
	{
		$model = "Codeman\\Admin\\Models\\".$modelName;
		$model = new $model;

		if($inputs){
			$inputs['order'] = $model->max('order') + 1;
			return $inputs;
		}else{
			return $model->max('order') + 1;
		}

	}

	// make excerpt from string
	function str_excerpt($text, $max_length = 125, $cut_off = '...', $keep_word = false)
	{
		mb_internal_encoding("UTF-8");
		$text = strip_tags($text);
		$text = mb_convert_encoding((string)$text, 'UTF-8', mb_list_encodings());
		// Truncate slug to max. characters
		$text = mb_substr($text, 0, mb_strlen($text, 'UTF-8'), 'UTF-8');
		$text = preg_replace('/\s+/', ' ', trim($text));

	    if(strlen($text) <= $max_length) {
	        return $text;
	    }
        if($keep_word) {
            $text = mb_substr($text, 0, $max_length + 1, "utf-8");

            if($last_space = strrpos($text, ' ')) {
                $text = mb_substr($text, 0, $last_space, "utf-8");
                $text = rtrim($text);
                $text .=  $cut_off;
            }
        } else {
            $text = mb_substr($text, 0, $max_length, "utf-8");
            $text = rtrim($text);
            $text .=  $cut_off;
        }

	    return $text;
	}

	function seo_description($description)
	{
		$description = str_excerpt($description, 400, '.', true);
		$description = preg_replace('/\s+/', ' ', trim($description));
		// $description = preg_replace('&nbsp;', '', trim($description));
		return $description;
	}

	// check is url valid or not
	function is_url($url)
	{
		return filter_var($url, FILTER_VALIDATE_URL);
	}

	// check is video url from youtube or vimeo and return name of source
	function is_video($url)
	{
		if (strpos($url, 'youtube') > 0) {
	        return 'youtube';
	    } elseif (strpos($url, 'vimeo') > 0) {
	        return 'vimeo';
	    } else {
	        return false;
	    }
	}

	function get_vimeo_video_id($url)
	{
		return substr(parse_url($url, PHP_URL_PATH), 1);
	}

	// pluck video id from Youtube and Video Url
	function video_id($url)
	{
		if (strpos($url, 'youtube') > 0) {
	       	preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match);
			return  $match[1];
	    } elseif (strpos($url, 'vimeo') > 0) {
	        return (int) substr(parse_url($url, PHP_URL_PATH), 1);
	    }
	    return $url;
	}

	function get_lang()
	{
		$lang = LaravelLocalization::getCurrentLocale();

		if($lang == 'en'){
			return '';
		}
		return $lang;
	}

	function isJson($string) {
		json_decode($string);
	 	return (json_last_error() == JSON_ERROR_NONE);
	}

	function url_slug($str, $options = array()) {
		// Make sure string is in UTF-8 and strip invalid UTF-8 characters
		$str = mb_convert_encoding((string)$str, 'UTF-8', mb_list_encodings());

		$defaults = array(
			'delimiter' => '-',
			'limit' => null,
			'lowercase' => true,
			'replacements' => array(),
			'transliterate' => false,
		);

		// Merge options
		$options = array_merge($defaults, $options);

		$char_map = array(
			// Latin
			'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'AE', 'Ç' => 'C',
			'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I',
			'Ð' => 'D', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ő' => 'O',
			'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ű' => 'U', 'Ý' => 'Y', 'Þ' => 'TH',
			'ß' => 'ss',
			'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae', 'ç' => 'c',
			'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
			'ð' => 'd', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ő' => 'o',
			'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ű' => 'u', 'ý' => 'y', 'þ' => 'th',
			'ÿ' => 'y',
			// Latin symbols
			'©' => '(c)',
			// Greek
			'Α' => 'A', 'Β' => 'B', 'Γ' => 'G', 'Δ' => 'D', 'Ε' => 'E', 'Ζ' => 'Z', 'Η' => 'H', 'Θ' => '8',
			'Ι' => 'I', 'Κ' => 'K', 'Λ' => 'L', 'Μ' => 'M', 'Ν' => 'N', 'Ξ' => '3', 'Ο' => 'O', 'Π' => 'P',
			'Ρ' => 'R', 'Σ' => 'S', 'Τ' => 'T', 'Υ' => 'Y', 'Φ' => 'F', 'Χ' => 'X', 'Ψ' => 'PS', 'Ω' => 'W',
			'Ά' => 'A', 'Έ' => 'E', 'Ί' => 'I', 'Ό' => 'O', 'Ύ' => 'Y', 'Ή' => 'H', 'Ώ' => 'W', 'Ϊ' => 'I',
			'Ϋ' => 'Y',
			'α' => 'a', 'β' => 'b', 'γ' => 'g', 'δ' => 'd', 'ε' => 'e', 'ζ' => 'z', 'η' => 'h', 'θ' => '8',
			'ι' => 'i', 'κ' => 'k', 'λ' => 'l', 'μ' => 'm', 'ν' => 'n', 'ξ' => '3', 'ο' => 'o', 'π' => 'p',
			'ρ' => 'r', 'σ' => 's', 'τ' => 't', 'υ' => 'y', 'φ' => 'f', 'χ' => 'x', 'ψ' => 'ps', 'ω' => 'w',
			'ά' => 'a', 'έ' => 'e', 'ί' => 'i', 'ό' => 'o', 'ύ' => 'y', 'ή' => 'h', 'ώ' => 'w', 'ς' => 's',
			'ϊ' => 'i', 'ΰ' => 'y', 'ϋ' => 'y', 'ΐ' => 'i',
			// Turkish
			'Ş' => 'S', 'İ' => 'I', 'Ç' => 'C', 'Ü' => 'U', 'Ö' => 'O', 'Ğ' => 'G',
			'ş' => 's', 'ı' => 'i', 'ç' => 'c', 'ü' => 'u', 'ö' => 'o', 'ğ' => 'g',
			// Russian
			'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh',
			'З' => 'Z', 'И' => 'I', 'Й' => 'J', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
			'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
			'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sh', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'Yu',
			'Я' => 'Ya',
			'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh',
			'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o',
			'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
			'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sh', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu',
			'я' => 'ya',
			// Ukrainian
			'Є' => 'Ye', 'І' => 'I', 'Ї' => 'Yi', 'Ґ' => 'G',
			'є' => 'ye', 'і' => 'i', 'ї' => 'yi', 'ґ' => 'g',
			// Czech
			'Č' => 'C', 'Ď' => 'D', 'Ě' => 'E', 'Ň' => 'N', 'Ř' => 'R', 'Š' => 'S', 'Ť' => 'T', 'Ů' => 'U',
			'Ž' => 'Z',
			'č' => 'c', 'ď' => 'd', 'ě' => 'e', 'ň' => 'n', 'ř' => 'r', 'š' => 's', 'ť' => 't', 'ů' => 'u',
			'ž' => 'z',
			// Polish
			'Ą' => 'A', 'Ć' => 'C', 'Ę' => 'e', 'Ł' => 'L', 'Ń' => 'N', 'Ó' => 'o', 'Ś' => 'S', 'Ź' => 'Z',
			'Ż' => 'Z',
			'ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l', 'ń' => 'n', 'ó' => 'o', 'ś' => 's', 'ź' => 'z',
			'ż' => 'z',
			// Latvian
			'Ā' => 'A', 'Č' => 'C', 'Ē' => 'E', 'Ģ' => 'G', 'Ī' => 'i', 'Ķ' => 'k', 'Ļ' => 'L', 'Ņ' => 'N',
			'Š' => 'S', 'Ū' => 'u', 'Ž' => 'Z',
			'ā' => 'a', 'č' => 'c', 'ē' => 'e', 'ģ' => 'g', 'ī' => 'i', 'ķ' => 'k', 'ļ' => 'l', 'ņ' => 'n',
			'š' => 's', 'ū' => 'u', 'ž' => 'z'
		);

		// Make custom replacements
		$str = preg_replace(array_keys($options['replacements']), $options['replacements'], $str);

		// Transliterate characters to ASCII
		if ($options['transliterate']) {
			$str = str_replace(array_keys($char_map), $char_map, $str);
		}

		// Replace non-alphanumeric characters with our delimiter
		$str = preg_replace('/[^\p{L}\p{Nd}]+/u', $options['delimiter'], $str);

		// Remove duplicate delimiters
		$str = preg_replace('/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1', $str);

		// Truncate slug to max. characters
		$str = mb_substr($str, 0, ($options['limit'] ? $options['limit'] : mb_strlen($str, 'UTF-8')), 'UTF-8');

		// Remove delimiter from ends
		$str = trim($str, $options['delimiter']);

		return $options['lowercase'] ? mb_strtolower($str, 'UTF-8') : $str;
	}

	function buildUrl($page, $url = array(), $includeCurrentUrl = true, $single_of = null)
	{
		$lang = \LaravelLocalization::getCurrentLocale();
		if($includeCurrentUrl){
			$url[] = $page->slug;
		}
		if(null != $parent = $page->parent){
			if(!empty($parent))
			{
				if($parent->slug){
					$url = buildUrl($parent, $url, true);
				}
			}
		}else{
			if($single_of){
				$url[] = $single_of;
			}
			if($lang != \Codeman\Admin\Models\Language::orderBy('order')->first()->code){
				$url[] = $lang;
			}

			$url = array_reverse($url);
			$url = implode('/', $url);
		}
		return $url;
	}

	function isCurrentUrl($url)
	{
		if( request()->is('*/'.urldecode($url)) ||
			request()->is(urldecode($url)) ||
			request()->is(urldecode($url).'/*') ||
			request()->is('*/'.urldecode($url).'/*')
		){
			return true;
		}
		return false;
	}


	function urlLang($url, $def_lang = null){

		$lang = \LaravelLocalization::getCurrentLocale();

		if($lang != $def_lang){
            if(preg_match("/^http/i", $url)){
                $url = explode('/', rtrim($url, '/'));
                $url[3] = $lang.'/'.$url[3];
                $url = implode('/', $url) .'/';
            } else{
                $url = $lang.'/'.$url;
            }
		}
		// dd($url);
		return $url;
	}


	function transDate($date, $format = 'd F Y')
	{
		$lang = \LaravelLocalization::getCurrentLocale();
		$day    = date("l", strtotime($date));
		$daynum = date("j", strtotime($date));
		$month  = date("F", strtotime($date));
		$year   = date("Y", strtotime($date));
		if($lang == 'hy'){

			switch($day)
			{
				case "Monday":    $day = "Երկուշաբթի";  break;
				case "Tuesday":   $day = "Երեքշաբթի"; break;
				case "Wednesday": $day = "Չորեքշաբթի";  break;
				case "Thursday":  $day = "Հինգշաբթի"; break;
				case "Friday":    $day = "Ուրբաթ";  break;
				case "Saturday":  $day = "Շաբաթ";  break;
				case "Sunday":    $day = "Կիրակի";  break;
			}

			switch($month)
			{
				case "January":   $month = "Հունվար";    break;
				case "February":  $month = "Փետրվար";   break;
				case "March":     $month = "Մարտ";     break;
				case "April":     $month = "Ապրիլ";     break;
				case "May":       $month = "Մայիա";       break;
				case "June":      $month = "Հունիս";      break;
				case "July":      $month = "Հուլիս";      break;
				case "August":    $month = "Օգոստոս";    break;
				case "September": $month = "Սեպտեմբեր"; break;
				case "October":   $month = "Հոկտեմբեր";   break;
				case "November":  $month = "Նոյեմբեր";  break;
				case "December":  $month = "Դեկտեմբեր";  break;
			}

			if($format == 'd F Y'){
				return  $daynum . " " . $month . " " . $year;
			}else if($format == 'l, d F Y'){
				return  $day . ", ". $daynum . " " . $month . " " . $year;
			}
		}else if($lang == 'ru'){

			switch($day)
			{
				case "Monday":    $day = "Понедельник";  break;
				case "Tuesday":   $day = "Вторник"; break;
				case "Wednesday": $day = "Среда";  break;
				case "Thursday":  $day = "Четверг"; break;
				case "Friday":    $day = "Пятница";  break;
				case "Saturday":  $day = "Суббота";  break;
				case "Sunday":    $day = "Воскресенье";  break;
			}

			switch($month)
			{
				case "January":   $month = "Январь";    break;
				case "February":  $month = "Февраль";   break;
				case "March":     $month = "Март";     	break;
				case "April":     $month = "Апрель";    break;
				case "May":       $month = "Май";       break;
				case "June":      $month = "Июнь";      break;
				case "July":      $month = "Июль";      break;
				case "August":    $month = "Август";    break;
				case "September": $month = "Сентябрь"; 	break;
				case "October":   $month = "Октябрь";   break;
				case "November":  $month = "Ноябрь";  	break;
				case "December":  $month = "Декабрь";  	break;
			}

			if($format == 'd F, Y'){
				return  $daynum . " " . $month . ", " . $year;
			}else if($format == 'l, d F Y'){
				return  $day . ", ". $daynum . " " . $month . " " . $year;
			}
		}
		if($format == 'M/Y'){
			return $month . "/" . $year;
		}
		return $date;
	}


	function get_img_fulsize($image_url)
	{
		return str_replace('icon_size', 'full_size', $image_url);
	}

	function img_icon_size($image_url)
	{
		return str_replace('full_size', 'icon_size', $image_url);
	}

	function get_file_url($filename, $size = "full_size"){
		$default_sizes = ['fill_size', 'icon_size', 'otherfiles'];
		$file_path = public_path('/media/'.$size.'/'.$filename);
		$file_url = asset('/media/'.$size.'/'.$filename);

		if(file_exists($file_path)){
			return $file_url;
		}
		return false;
	}

	use Codeman\Admin\Models\Language;
	use Intervention\Image\ImageManager;

	function image_thumbnail($image_url, $w = null, $h = null, $safecrop = false)
	{
		if(!$image_url) return $image_url;

		$image_url_array = explode('/', $image_url);
		$image_url_array = str_replace('icon_size', 'full_size', $image_url_array);
		$index = array_search('full_size', $image_url_array);
		$image_folder_path = public_path('media');
		$image_folder_url = asset('media');
		$image_name = $image_url_array[count($image_url_array) - 1 ];
		if($index){

			for ($i = $index; $i < count($image_url_array)-1; $i++) {
				$image_folder_path .= '/'.$image_url_array[$i];
				$image_folder_url .= '/'.$image_url_array[$i];
			}
				$original_image = $image_folder_path.'/'.$image_name;
				$image_url = $image_folder_url.'/'.$image_name;
		}else{
			$original_image = base_path('/media/full_size/'.$image_name);
			$image_url = asset('/media/full_size/'.$image_name);
		}

				// dd($original_image);
		if(File::exists( $original_image )){
			if($w || $h){
				if($w == null && !$safecrop){
					$w = $h;
				}
				if($h == null && !$safecrop){
					$h = $w;
				}
				if($w && $h){
					$resized_folder_name = $w.'x'.$h;
				}elseif($w){
					$resized_folder_name = $w.'xauto';
				}else{
					$resized_folder_name = 'autox'.$h;
				}
					// $folder_name = $image_folder_path.'/'.$resized_folder_name;
					$folder_name = str_replace('full_size', $resized_folder_name , $image_folder_path);
					$image_url = str_replace('full_size', $resized_folder_name , $image_url);
				if(!File::exists($folder_name)) {
				// dd($folder_name);
					File::makeDirectory($folder_name, $mode = 0755, true, true);
				}

				if(!File::exists($folder_name.'/'.$image_name)) {
					$manager = new ImageManager();

					list($original_width, $original_height) = getimagesize($original_image);
					$original_ratio = $original_width / $original_height;
					$new_ratio = $w/$h;

					if($original_ratio == $new_ratio || $safecrop){

						$image = $manager->make($original_image)->resize($w, $h, function ($constraint) {
				           $constraint->aspectRatio();
				        })->save($folder_name.'/'.$image_name);
					}elseif($original_ratio > $new_ratio){
						$image = $manager->make($original_image)->resize(null, $h, function ($constraint) {
				           $constraint->aspectRatio();
				        })->crop($w, $h)->save($folder_name.'/'.$image_name);
					}else{
						$image = $manager->make($original_image)->resize($w, null, function ($constraint) {
				           $constraint->aspectRatio();
				        })->crop($w, $h)->save($folder_name.'/'.$image_name);
					}

				}else{
					return $image_url;
				}
			}
		}
		return $image_url;

	}


	function date_compare($a, $b)
	{
	    $t1 = strtotime($a['created_at']);
	    $t2 = strtotime($b['created_at']);
	    return $t1 - $t2;
	}

    // return Instance of Model
	function getModel($modelName)
    {
    	if($modelName[0] != "\\"){
       		$model = "App\\Models\\".$modelName;
    	}else{
    		$model = $modelName;
    	}
       	$model = new $model;
       	return $model;
    }

	function deleteDir($dirPath) {
	    if(! is_dir($dirPath))
	    {
	        throw new InvalidArgumentException("$dirPath must be a directory");
	    }
	    if(substr($dirPath, strlen($dirPath) - 1, 1) != '/')
	    {
	        $dirPath .= '/';
	    }

	    $files = glob($dirPath . '*', GLOB_MARK);
	    foreach($files as $file)
	    {
	        if(is_dir($file))
	        {
	            deleteDir($file);
	        } else {
	            unlink($file);
	        }
	    }
	    rmdir($dirPath);
    }

    /**
     * Helper function
     *
     * @param array   $d   flat data, implementing a id/parent id (adjacency list) structure
     * @param mixed   $r   root id, node to return
     * @param string  $pk  parent id index
     * @param string  $k   id index
     * @param string  $c   children index
     * @return array
     */
    function makeRecursive($d, $r = 0, $pk = 'parent_id', $k = 'id', $c = 'children') {
      $m = array();
      foreach ($d as $e) {
        isset($m[$e[$pk]]) ?: $m[$e[$pk]] = array();
        isset($m[$e[$k]]) ?: $m[$e[$k]] = array();
        $m[$e[$pk]][] = array_merge($e, array($c => &$m[$e[$k]]));
      }
      return $m[$r]; // remove [0] if there could be more than one root nodes
    }

    function categories_tree($data, $is_child = 0, $group_slug = null, $category_slug = null) {
        $is_child = $is_child ? 'ml-3' : '';
        $result = array();
        if (sizeof($data) > 0) {
            $result[] = '<ul class="list-unstyled '.$is_child.'">';
            foreach ($data as $entry) {
                $result[] = sprintf(
                    '<li><a href="%s" class="shop_side_nav_item link %s">%s</a>%s</li>',
                    route('products', ['group' => $group_slug, 'category' => $entry['slug']]),
                    $category_slug == $entry['slug'] ? 'active' : '',
                    $entry['title'],
                    categories_tree($entry['children'], 1)
                );
            }
            $result[] = '</ul>';

        }
        return implode($result);
    }

    function recursCategoriesOptions($result, $parent_id, $selected = null, $category_id = null, &$html = '')
    {
        if(isset($result[$parent_id])):

            foreach ($result[$parent_id] as $key => $category):
                    $is_selected = '';
                    if( (isset($category_id) && $category_id == $category->id) ||
                        (isset($selected) && is_array($selected) && in_array($category->id, $selected))):
                        $is_selected = " selected='selected'";
                    endif;
                    $html .='<option value="'.$category->id.'" '.$is_selected.' >';
                        for($i = 2; $i <= $category->level; $i++):
                            $html .= '---';
                        endfor;
                        $html .= $category->title;
                    $html .='</option>';
                recursCategoriesOptions($result, $category->id, $selected, $category_id, $html);
            endforeach;
        endif;
        return $html;
    }

    function categories_tree_header_menu($data, $is_child = 0, $group_slug = null, $active_slug = null) {
        $is_child = $is_child ? 'mb-123' : '';
        $result = array();
        if (sizeof($data) > 0) {
            // $result[] = '<ul class="project--nav-secondary navbar-nav '.$is_child.'">';
            foreach ($data as $entry) {
                $result[] = sprintf(
                    '<li class="nav-item pr-0"><a href="%s" class="nav-sublink text-uppercase pr-0 pl-0 text-dark %s">%s</a>%s</li>',
                    route('products', ['group' => $group_slug, 'category' => $entry['slug']]),
                    $active_slug == $entry['slug'] ? 'active' : '',
                    $entry['title'],
                    categories_tree_header_menu($entry['children'], 1)
                );
            }
            // $result[] = '</ul>';

        }
        return implode($result);
    }

    function content_builder_text($text){
        $text = str_replace('[delivery_addresses_dropdown]', delivery_addresses_dropdown_shortcode(), $text);
        return $text;
    }
    function delivery_addresses_dropdown_shortcode(){
        return '<div  class="form-wrapper">
                    <div class="project--input-wrapper form-group mb-0" style="max-width: 280px;">
                        <input name="shipping_city"
                        type="text"
                        class="project--input form-control autocomplete_address_city"
                        id="shipping-city"
                        data-group="1"
                        autocomplete="autocompleate_random_6359271"/>
                        <div class="show_address_list">
                            <ul class="show_address_list_ul"></ul>
                        </div>
                        <span class="help-block error-help-block address-city-error global_city_error"></span>
                        <label class="project--input-label" for="shipping-city">'.__('Перечень населённых пунктов').'</label>
                        <div class="city_data_id">
                        </div>
                    </div>
                </div>
                <div class="form-wrapper filter_checkbox">
                    <input type="hidden" name="shipping_type" data-logic="courier_delivery">
                    <p><b><span>Срок доставки: </span><span class="shipping-option-info"></span></b></p>
                </div>';
    }
?>
