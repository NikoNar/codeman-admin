<div class="form-group">
    <div class='input-group'>
        <button  type="button" class="btn btn-primary iconpicker {{ isset($social)? 'social-iconpicker' : ''}}" role="iconpicker" name="{{ isset($name)? $name : null }}" data-icon="{{ isset($value)? $value : null }}"></button>
    </div>
    @isset($info)
        <small id="{{ $id }}" class="form-text text-muted">{!! $info !!}</small>
    @endif
</div>