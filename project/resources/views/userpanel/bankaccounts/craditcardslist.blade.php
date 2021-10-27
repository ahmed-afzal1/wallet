@if($craditcards != null)
    @foreach ($craditcards as $key => $value) 
    <div class="col-xl-4 col-md-6 mb-4 cards">
        <div class="bank-card">
            <div class="content">
                <div class="left">
                    <img src="{{asset("assets/userpanel/img/bank-icon-w.png")}}" alt="">
                </div>
                <div class="right">
                    <p class="bank-name">
                    {{ucwords($value->card_owner_name)}}
                    </p>
                    <p class="account-num">
                        @php
                            $length = strlen($value->card_number)-2;
                            for ($i=0; $i <= $length; $i++) { 
                               echo 'x';
                            }
                        @endphp
                         - {{substr($value->card_number,-2)}}
                    </p>
                    <p class="status">
                        {{ucwords($value->is_approved)}} 
                        @if ($value->is_approved == 'approved')
                        <i class="material-icons">check_circle</i>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
    @endforeach
@endif

