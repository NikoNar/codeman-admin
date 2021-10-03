
<select name="options[]" id="option" class="select2 form-control" multiple>
    <option value="languages" @if(isset($module_options)&& in_array('languages', $module_options) )selected @endif>Translations</option>
    <option value="categories" @if(isset($module_options)&& in_array('categories', $module_options) )selected @endif>Categories</option>
    <option value="ckeditor" @if(isset($module_options)&& in_array('ckeditor', $module_options) )selected @endif>Ckeditor</option>
    <option value="content_builder" @if(isset($module_options)&& in_array('content_builder', $module_options) )selected @endif>Content Builder</option>
    <option value="thumbnail" @if(isset($module_options)&& in_array('thumbnail', $module_options) )selected @endif>Thumbnail</option>
    <option value="slug" @if(isset($module_options)&& in_array('slug', $module_options) )selected @endif>Slug</option>
</select>