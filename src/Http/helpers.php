<?php 

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
    function getUniqueSlug(\Illuminate\Database\Eloquent\Model $model, $value, $existing_id = null)
    {

        // $slug = \Illuminate\Support\Str::slug($value);
        $slug = url_slug($value);
//        dd($slug);

        $req_lang = request()->language_id;
//dd($existing_id);
        if(isset($existing_id)){
            $lang = $model->where('id', $existing_id)->first()['language_id'];
//            dd($lang);
            $slugCount = count($model->whereRaw("slug REGEXP '^{$slug}(-[0-9]+)?$' and id != '{$existing_id}' and language_id = '{$lang}'")->get());
//             dd('set', $lang, $slugCount);
        }else{
            $slugCount = count($model->whereRaw("slug REGEXP '^{$slug}(-[0-9]+)?$' and id != '{$model->id}' and language_id = '{$req_lang}' ")->get());
//             dd('notset', $slugCount, $req_lang);
        }
//        dd(($slugCount > 0) ? "{$slug}-{$slugCount}" : $slug);
        return ($slugCount > 0) ? "{$slug}-{$slugCount}" : $slug;
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

	function is_url($url)
	{
		return filter_var($url, FILTER_VALIDATE_URL);
	}

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
			if($lang != 'en'){
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


	function urlLang($url, $def_lang){
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
		return $url;
	}


	function transDate($date, $format = 'd F Y')
	{
		$lang = \LaravelLocalization::getCurrentLocale();
		$day    = date("l", strtotime($date));
		$daynum = date("j", strtotime($date));
		$month  = date("F", strtotime($date));
		$year   = date("Y", strtotime($date));
		if($lang == 'arm'){
			
			switch($day)
			{
				case "Monday":    $day = "Երկուշաբթի";  break;
				case "Tuesday":   $day = "Երեքշաբթի"; break;
				case "Wednesday": $day = "Չորեքշաբթի";  break;
				case "Thursday":  $day = "Հինգշաբթի"; break;
				case "Friday":    $day = "Ուրբաթ";  break;
				case "Saturday":  $day = "Շաբաթ";  break;
				case "Sunday":    $day = "Կիրակի";  break;
				default:          $day = "Անհայտ"; break;
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
				default:          $month = "Անհայտ";   break;
			}

			if($format == 'd F Y'){
				return  $daynum . " " . $month . " " . $year;
			}else if($format == 'l, d F Y'){
				return  $day . ", ". $daynum . " " . $month . " " . $year;
			}
		} 
		if($format == 'M/Y'){
			return $month . "/" . $year;
		}
		return $date;
	}


use Codeman\Admin\Models\Language;
use Intervention\Image\ImageManager;

	function image_thumbnail($image_url, $w = null, $h = null, $safecrop = false)
	{
		if(!$image_url) return $image_url;

		$image_url_array = explode('/', $image_url);
		
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

	function filmUrl($slug, $year, $category = null, $subcategory = null )
	{
		$url = ['films'];
		if($subcategory){
			$url[] = $subcategory;
		}else{
			if($category){
				$url[] = $category;
			}
		}
		$url[] = $year;
		$url[] = $slug;
		$url = implode('/', $url);
		return $url;
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
       $model = "App\\Models\\".$modelName;
       $model = new $model; 
       return $model;
    }

	function getFilmsByYear($year = null){
		$model = getModel('Film');

		if($year){
		    $films = $model->where('year', $year)->orderBy('created_at', 'DESC')->get()->pluck('title', 'id')->toArray();
		}else{
		    $films = $model->where('year', date('Y'))->orderBy('created_at', 'DESC')->get()->pluck('title', 'id')->toArray();
		}

		if($films){
		    return $films;
		}
		return array();
	}

	function get_img_fulsize($image_url)
	{
		return str_replace('icon_size', 'full_size', $image_url);
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
?>