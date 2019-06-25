<?php 
namespace Codeman\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model {

    public static function getTableName($className)
    {
    	$model = "Codeman\\Admin\\Models\\".$className;
    	$model = new $model;
        return  $model->getTable();
    }

} 
?>