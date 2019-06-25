<?php

namespace Codeman\Admin\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Codeman\Admin\Models\Image;
use DB;

class ImagesController extends Controller
{
    protected $image;

    public function __construct(Image $image)
    {
        $this->image = $image;
    }

    public function index()
    {	
    	// DB::raw('count(id) as `data`'),
    	$dates = $this->image->select(DB::raw('YEAR(created_at) year, MONTH(created_at) month'))
               ->groupby('year','month')
               ->get();
        return view('admin-panel::media.index', ['images' => Image::orderBy('created_at', 'DESC')->paginate(30), 'dates' => $dates] );
    }

    public function getAllImagesJson()
    {
        $image_folder_fill_size_path = url('/media/full_size');
        $image_folder_fill_thumb_path = url('/media/icon_size');
        return $this->image->select(DB::raw('CONCAT("'.$image_folder_fill_size_path.'", "/", filename) AS image, CONCAT("'.$image_folder_fill_thumb_path.'", "/", filename) AS thumb') )->get()->toJson();
    }

    public function popup()
    {

        $dates = $this->image->select(DB::raw('YEAR(created_at) year, MONTH(created_at) month'))
               ->groupby('year','month')
               ->get();
        $ckeditor = (request()->has('ckeditor') && request()->get('ckeditor') != 'false')? true : false;
        $pdf = (request()->has('pdf') && request()->get('pdf') != 'false')? true : false;
        $multichoose = request()->has('multichoose') && request()->get('multichoose') === 'true' ? 1 : null;
        // $resource_id = request()->has('resource_id') && request()->get('resource_id') != 0 ? request()->get('resource_id') : 0;
        $meta = request()->has('meta')? request()->get('meta') : null;
        if($pdf){
            $images = Image::where('filename', 'LIKE', '%.pdf')->orderBy('created_at', 'DESC')->paginate(20);
        } else {
            $images = Image::orderBy('created_at', 'DESC')->paginate(20);
        }
        $returnHTML =  view('admin-panel::media.popup', [
          'images' => $images,
          'dates' => $dates,
          'multichoose' => $multichoose,
          'pdf' => $pdf,
          'ckeditor'=>$ckeditor,
          'meta' => $meta
            ]);

        if(request()->has('json') && (request()->get('json') == 0 || request()->get('json') == '0')){
            return $returnHTML;
        }
        return response()->json(array('success' => true, 'html' => $returnHTML->render(), 'multichoose' => $multichoose, 'ckeditor'=>$ckeditor));
        // $returnHTML =  view('admin.media.index', ['images' => Image::all(), 'dates' => $dates])->render();
        // dd($returnHTML);
        // return response()->json(array('success' => true, 'html'=>$returnHTML));
        // return view('admin.media.popup');
    }

    public function upload()
    {
        return view('admin-panel::media.upload', ['images' => Image::all()] );
    }


    public function postUpload()
    {
        $media = request()->all();
        $response = $this->Imageupload($media);
        return $response;
    }


    public function update()
    {
      $media = $this->image->where('filename', '=', request()->get('file-id'))->first()->update(request()->all());
      return response()->json(array('success' => true, 'request' => $media));

    }

    public function search()
    {
        $result = $this->image->where('original_name', 'LIKE', '%'.request()->get('query').'%')->get();
        if(isset($result) && !$result->isEmpty()){
            $returnHTML =  view('admin-panel::media.parts._media_list', ['images' => $result])->render();
        }else{
            $returnHTML =  view('admin-panel::media.parts._no_images_found', ['images' => $result])->render();
        }
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    public function delete()
    {

        $filename = request()->get('id');

        if(!$filename)
        {
            return 0;
        }

        $response = $this->Imagedelete( $filename );

        return $response;
    }

    public function Imageupload( $form_data )
   	{

       $validator = Validator::make($form_data, Image::$rules, Image::$messages);

       if ($validator->fails()) {

           return Response::json([
               'error' => true,
               'message' => $validator->messages()->first(),
               'code' => 400
           ], 400);

       }
       
       $photo = $form_data['file'];
       $originalName = $photo->getClientOriginalName();
       $extension = $photo->getClientOriginalExtension();
       $originalNameWithoutExt = substr($originalName, 0, strlen($originalName) - strlen($extension) - 1);
       list($width, $height) = getimagesize($photo);
       $filename = $this->sanitize($originalNameWithoutExt);
       $allowed_filename = $this->createUniqueFilename( $filename, $extension);
   
       if(!in_array($extension, ['png','gif','jpeg','jpg','bmp'])){
           $current_year = date("Y");;
           $current_day = date('z') + 1;
           $path = 'media/otherfiles/'.$current_year.'/'. $current_day;
           if(!is_dir($path)) {
               mkdir($path, 0755,true);
           }
           
           $unique_name = $current_year.'/'.$current_day.'/'.md5($filename. time()).'.'.$extension;
           $sessionImage = new Image;
           $sessionImage->filename      = $unique_name;
           $sessionImage->original_name = $filename;
           $sessionImage->alt = '';
           $sessionImage->file_size = filesize($photo);
           $sessionImage->file_type = $photo->getMimeType();
           $sessionImage->width = 0;
           $sessionImage->height = 0;
           $sessionImage->save();
           $photo->move(public_path($path), $unique_name);


           return Response::json([
               'error' => false,
               'code'  => 200,
               'file' => $sessionImage,
               'ext' => $extension
           ], 200);

       }
    

       $uploadSuccess1 = $this->original( $photo, $allowed_filename );

       $uploadSuccess2 = $this->icon( $photo, $allowed_filename );

       if( !$uploadSuccess1 || !$uploadSuccess2 ) {

           return Response::json([
               'error' => true,
               'message' => 'Server error while uploading',
               'code' => 500
           ], 500);

       }

       $sessionImage = new Image;
       $sessionImage->filename      = $allowed_filename;
       $sessionImage->original_name = $filename;
       $sessionImage->alt = $filename;
       $sessionImage->file_size = filesize($photo);
       $sessionImage->file_type = $photo->getMimeType();
       $sessionImage->width = $width;
       $sessionImage->height = $height;
       $sessionImage->save();

       return Response::json([
           'error' => false,
           'code'  => 200,
           'image' => $sessionImage
       ], 200);

   }

   public function createUniqueFilename( $filename, $extension )
   {
        $full_size_dir = Config::get('images.full_size');
        $icon_size_dir = Config::get('images.icon_size');
        
        $current_date = date('Y').'/'.(date('z') + 1);
        $newFiledir = $full_size_dir.$current_date.'/';
        $newFiledirIconSize = $icon_size_dir.$current_date.'/';
        $newfilename = $current_date.'/'.$filename;
        // dd($newFiledirIconSize);

        if(!File::exists($newFiledir)) {
            File::makeDirectory($newFiledir, $mode = 0755, true, true);
            File::makeDirectory($newFiledirIconSize, $mode = 0755, true, true);
        }

       $full_image_path = $newFiledir . $filename . '.' . $extension;

       if ( File::exists( $full_image_path ) )
       {
           // Generate token for image
           $imageToken = substr(sha1(mt_rand()), 0, 5);
           return $newfilename . '-' . $imageToken . '.' . $extension;
       }

       return $newfilename . '.' . $extension;
   }

   /**
    * Optimize Original Image
    */
   public function original( $photo, $filename )
   {
       $manager = new ImageManager();
       $image = $manager->make( $photo )->save(Config::get('images.full_size') . $filename );

       return $image;
   }

   /**
    * Create Icon From Original
    */
   public function icon( $photo, $filename )
   {
       $manager = new ImageManager();
       $image = $manager->make( $photo )->resize(200, null, function ($constraint) {
           $constraint->aspectRatio();
           })
           ->save( Config::get('images.icon_size')  . $filename );
       return $image;
   }

   /**
    * Delete Image From Session folder, based on original filename
    */
   public function Imagedelete( $filename)
   {

        $full_size_dir = Config::get('images.full_size');
        $icon_size_dir = Config::get('images.icon_size');

        $sessionImage = Image::where('filename', '=', $filename)->first();


        if(empty($sessionImage))
        {
            return Response::json([
                'error' => true,
                'code'  => 400
            ], 400);
        }

       $full_path1 = $full_size_dir . $sessionImage->filename;
       $full_path2 = $icon_size_dir . $sessionImage->filename;

        if ( File::exists( $full_path1 ) )
        {
           File::delete( $full_path1 );
        }

        if ( File::exists( $full_path2 ) )
        {
           File::delete( $full_path2 );
        }

        if( !empty($sessionImage))
        {
           $sessionImage->delete();
        }

       return Response::json([
           'error' => false,
           'code'  => 200
       ], 200);
   }

   function sanitize($string, $force_lowercase = true, $anal = false)
   {
       $strip = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "=", "+", "[", "{", "]",
           "}", "\\", "|", ";", ":", "\"", "'", "&#8216;", "&#8217;", "&#8220;", "&#8221;", "&#8211;", "&#8212;",
           "â€”", "â€“", ",", "<", ".", ">", "/", "?");
       $clean = trim(str_replace($strip, "", strip_tags($string)));
       $clean = preg_replace('/\s+/', "-", $clean);
       $clean = ($anal) ? preg_replace("/[^a-zA-Z0-9]/", "", $clean) : $clean ;

       return ($force_lowercase) ?
           (function_exists('mb_strtolower')) ?
               mb_strtolower($clean, 'UTF-8') :
               strtolower($clean) :
           $clean;
   }
}
