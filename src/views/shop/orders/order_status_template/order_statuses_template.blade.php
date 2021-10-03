<div id="order-status-container">
    <div>
        <label class="d-inline pull-left" style="line-height: 34px;">Status comment</label>    
        <button type="button" class="btn btn-link order_status_template_button d-inline pull-right" data-toggle="modal" data-target="#exampleModal">
            Saved Templates
        </button>
    </div>
    @if(!empty($order->status_message))
        <textarea class="form-control comment_for_status" name="status_message" rows="8">{{$order->status_message}}</textarea>
    @else
        <textarea class="form-control comment_for_status" name="status_message" rows="8"></textarea>
    @endif

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <span class="modal-title" id="exampleModalLabel">Saved Template</span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @foreach ($order_status_templates as $template)
                    @foreach ($template as $val)
                        @if($val->order_status == $filter)
                            <div class="order_status_message_block">
                                <div class="form-check status_radio_block">
                                    <input class="form-check-input template_status_option" value="{{$val->message}}" type="radio" name="flexRadioDefault" id="flexRadioDefault{{$loop->index}}">
                                </div>
                                <div class="status_main_message_block">  
                                    <label for="flexRadioDefault{{$loop->index}}">
                                        <span class="" for="flexRadioDefault{{$loop->index}}">{{$val->message}}</span>
                                    </label>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endforeach		
            
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary select_status_template_button">Select This</button>
            </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    
</div>
