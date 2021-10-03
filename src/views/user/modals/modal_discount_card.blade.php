<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <span class="modal-title" id="exampleModalLongTitle">
                    {{ __("Assign Discount Card To User") }}
                </span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="{{ url('admin/users/profile/attach-discount-card/'. $user->id ) }}" method="GET">
                @csrf
                <div class="modal-body">
                    <ul>
                        @foreach ($discount_cards as $key => $card)

                            <div class="custom-control custom-radio">

                                @php
                                    $disable_state = false;
                                    $card_exist = in_array($card->id,$user_cards_id);

                                    foreach($user_cards_precent as $key => $value)
                                    {
                                        if($card->discount_percent <= $value)
                                        {
                                            $disable_state = true;
                                        }
                                    }
                                @endphp

                                <input type="radio" id="discount_card{{$card->id}}"
                                       name="discount_card" class="custom-control-input"
                                       value="{{$card->id}}"
                                       @if($card_exist) disabled @elseif($disable_state) disabled @endif >

                                <label class="custom-control-label" for="discount_card{{ $card->id }}"
                                       @if($card_exist) style="color:grey;" @elseif($disable_state) style="color:grey;" @endif>
                                    {!! $card->name !!} -
                                    {{$card->discount_percent}}%
                                    ( Amount min limit
                                    {{ number_format($card->amount_limit, 0, 0, ' ') }} )
                                </label>
                                @switch($card->status)
                                    @case('active')
                                    <span class="label label-success pull-right">
                                                                    {{ $card->status }}
                                                                </span>
                                    @break
                                    @case('disabled')
                                    <span class="label label-danger pull-right">
                                                                    {{ $card->status }}
                                                                </span>
                                    @break
                                    @default
                                    <span class="label label-default pull-right">
                                                                    {{ $card->status }}
                                                                </span>
                                @endswitch
                            </div>
                            @if($key+1 < $discount_cards->count())
                                <hr>
                            @endif
                            @php
                                $disable_state = false;
                            @endphp
                        @endforeach
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="input" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>
