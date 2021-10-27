@extends('layouts.user') 

@section('content')

<div class="transaction-area">
    <div class="d-flex justify-content-between">
        <div class="heading-area">
            <h3 class="title">
            {{__("Invoice List")}}
            </h3>
        </div>

        <div>
            <a href="{{route('user.account.invoice')}}" class="btn btn-info p-1 mr-5">{{__('Create Invoice')}}</a>
        </div>
    </div>

    <div class="content">
        @include('flashmessage')

        @if (count($invoices) == 0)
            <p class="text-center text-danger">{{_('NO INVOICE FOUND')}}</p>
        @else
            <table class="table mt-5">
                <thead class="thead-light">
                <tr>
                    <th scope="col">Invoice ID#</th>
                    <th scope="col">Date</th>
                    <th scope="col">Customer Name</th>
                    <th scope="col">Customer Email</th>
                    <th scope="col">Amount</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    @foreach ($invoices as $key=>$invoice)
                    @php
                        $currency_sign = App\Models\Currency::whereId($invoice->currency_id)->first();
                    @endphp
                    <tr>
                        <td style="cursor: pointer;text-decoration: underline;color:blue;" onclick="transdetail({{$invoice->id}})" data-toggle="modal" data-target="#myModal">{{str_pad($invoice->id, 9, '0', STR_PAD_LEFT)}}</td>
                        <td>{{ Carbon\Carbon::parse($invoice->created_at)->format('d-m-Y') }}</td>
                        <td>{{$invoice->name}}</td>
                        <td>{{$invoice->email}}</td>
                        <td><strong class="">{{$currency_sign->sign}} {{number_format($invoice->amount,2)}}</strong></td>
                        </td>
                    </tr>
                    @endforeach
                </tr>
                </tbody>
            </table>
        @endif
    </div>

        <!-- Starting of Modal -->
        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{__('Invoice Link Details')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                        
                    </div>
                    <div class="modal-body">
                        <div class="transition-details">
                            <div class="table-responsive" id="transdetail">
    
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default modal-btn" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Ending of Modal -->
</div>
        
@endsection

@section('script')
<script>

    function transdetail(id){
        $.get(mainurl+'/account/invoice/link/details/'+id, function(response){
            $("#transdetail").html(response);
        });
    }

    function linkReminder(linkID) {
        $("#sendReminder"+linkID).html('<img id="load" src="' + mainurl + '/assets/images/loading.gif" style="height: 20px;">');
        $.ajax({
            type: "GET",
            url: mainurl + '/account/linkreminder/'+linkID,
            success: function (data) {
                if ($.trim(data.status) === "success") {
                    $("#sendReminder"+linkID).html("<span style='color:green;'><i class='fa fa-check-circle-o'></i> Reminder Sent!</span>");
                } else {
                    $("#sendReminder"+linkID).html("");
                }
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    }

</script>
@endsection