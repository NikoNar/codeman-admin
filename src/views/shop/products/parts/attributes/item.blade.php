<div class="box-group" id="accordion-{{ $group->id }}">
    <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
    <div class="panel box box-primary">
        <div class="box-header with-border">
            <h4 class="box-title col-md-6">
                <a data-toggle="collapse" data-parent="#accordion-{{ $group->id }}" href="#panel-{{ $group->id }}" aria-expanded="false" class="collapsed">
                    {{ $group->name }}
                </a>
            </h4>
            <div class="col-md-6 text-right">
                <button class="btn btn-icon btn-xs btn-danger delete-box-group" type="button">
                    <i class="fa fa-trash"></i> Remove
                </button>  
                <a class="btn btn-icon btn-xs btn-fefault" data-toggle="collapse" data-parent="#accordion-{{ $group->id }}" href="#panel-{{ $group->id }}" aria-expanded="false" class="collapsed">
                    <i class="fa fa-sort-down" style="font-size: 20px;line-height: 10px;"></i>
                </a>
            </div>
        </div>
        <div id="panel-{{ $group->id }}" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
            <div class="box-body">
                <div class="col-md-4">
                    <label for="{{ $group->id }}">Select {{ $group->name }} Options:</label>
                </div>
                <div class="col-md-8">
                   <div class="form-group">
                       <select name="group_options[{{ $group->id }}][]" @if(!isset($single_choice) || !$single_choice ) {{ 'multiple' }} @endif id="{{ $group->id }}" class="attribute-group-id form-control select2">
                           @isset($options)
                               @foreach($options as $key => $val)
                                   <option value="{{ $key }}" @if(isset($selectd_group_options) && in_array($key, $selectd_group_options)) {!! 'selected' !!} @endif>{{$val}}</option>
                               @endforeach
                           @endif
                       </select>
                   </div>
                   <div></div>
               </div>
            </div>
        </div>
    </div>
</div>