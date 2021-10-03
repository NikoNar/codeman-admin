<div class="clearfix"></div>
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">SEO Options</h3>
    </div>
    <div class="box-body">
        <div class="row row-flex">
            <div class="col-md-3 brr-1 pr-0">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs nav-pills nav-stacked" role="tablist">
                    <li role="presentation" class="active"><a href="#general" aria-controls="general" role="tab" data-toggle="tab" aria-expanded="true">General</a></li>
                    <li role="presentation"><a href="#social" aria-controls="social" role="tab" data-toggle="tab">Social</a></li>
                    {{-- <li role="presentation"><a href="#google_preview" aria-controls="google_preview" role="tab" data-toggle="tab">Google preview</a></li> --}}
                    {{-- <li role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">Settings</a></li> --}}
                </ul>
            </div>

            <!-- Tab panes -->
            <div class="tab-content col-md-9">
                <div role="tabpanel" class="tab-pane active" id="general">
                    <div class="form-group">
                        {!! Form::label('meta-title', 'Meta Title'); !!}
                        {!! Form::text('meta-title', null, ['class' => 'form-control', 'placeholder' => '%title% | %sitename%']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('meta-description', 'Meta Description'); !!}
                        {!! Form::text('meta-description', null, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('meta-keywords', 'Meta Keywords'); !!}
                        {!! Form::text('meta-keywords', null, ['class' => 'form-control', 'data-role' => "tagsinput" ]) !!}
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="social">
                    <div role="tabpanel" class="tab-pane active" id="general">
                        <div class="form-group">
                            {!! Form::label('og-title', 'Social Meta Title'); !!}
                            {!! Form::text('og-title', null, ['class' => 'form-control', 'placeholder' => '%title% | %sitename%']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('og-description', 'Social Meta Description'); !!}
                            {!! Form::text('og-description', null, ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            @include('admin-panel::components.thumbnail', [
                                'name' => 'og-image',
                                'attributes' => [],
                                'value' => isset($resource) && $resource['og-title']
                                    ? $resource['og-title'] 
                                    : (isset($resource) && $resource['thumbnail'] ? $resource['thumbnail'] : null)
                            ])
                        </div>
                    </div>
                </div>
                {{-- <div role="tabpanel" class="tab-pane" id="google_preview">
                    Google preview
                </div> --}}
                {{-- <div role="tabpanel" class="tab-pane" id="settings">...4</div> --}}
            </div>
        </div>
    	
    </div>
</div>