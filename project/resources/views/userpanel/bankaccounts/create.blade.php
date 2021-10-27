@extends('layouts.user') 

@section('content')
<div class="account-box">
    <div class="header-area">
        <h4 class="title">
          {{__("Credit or Debit Cards (for payments)")}}
      </h4>
    </div>
    <div class="content">
        <div class="row">

            <div class="col-xl-4  col-md-6  mb-4" id="craditcardslist">
                <div class="bank-card">
                    <a href="javascript:;" class="add-card"  data-toggle="modal" data-target=".creditcardmodal">
                        <i class="material-icons"> add_circle</i>
                        <p>{{__("Add New Card")}}</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="account-box mt-30">
    <div class="header-area">
        <h4 class="title">
          {{__("Bank Accounts (for withdrawal)")}}
      </h4>
    </div>
    <div class="content">
        <div class="row">
            <div class="col-xl-4 col-md-6  mb-4"  id="bankaccountlist">
                <div class="bank-card">
                    <a href="javascript:;" class="add-card" data-toggle="modal" data-target=".bankaccountmodal">
                        <i class="material-icons"> add_circle</i>
                        <p>{{__("Add New Account")}}</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- bank account add modal -->
<div class="modal fade creditcardmodal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{__("Add new credit card")}}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form id="formdata2" action="{{route('user-craditcard-store')}}" method="POST">
                @csrf
                <input type="hidden" id="cardtlisturl" value="{{route('user-craditcard-show')}}">
                <input type="hidden" name="user_id" value="{{ Auth::user()->id}}">
                <div class="form-group">
                    <label for="swift_code" class="col-form-label text-dark">{{__("Card Owner Name")}}:<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="card_owner_name" id="swift_code">
                </div>
                <div class="form-group">
                    <label for="routing_number" class="col-form-label text-dark">{{__("Card Number")}}:<span class="text-danger">*</span></label>
                    <input type="text" class="form-control allowIntiger" minlength="16" maxlength="16" name="card_number" id="card_number"  autocomplete="off"  autofocus oninput="validateCard(this.value);" >
                    <span id="errCard"  class="text-danger"></span>
                </div>
                <div class="form-group">
                    <label for="routing_number" class="col-form-label text-dark">{{__("CVC")}}:<span class="text-danger">*</span></label>
                    <input class="form-control card-elements allowIntiger" maxlength="3" minlength="3" name="card_cvc" type="text" placeholder="" autocomplete="off"  oninput="validateCVC(this.value);" />
                    <span id="errCVC" class="text-danger"></span>
                </div>
                <div class="form-group col-sm-12 p-0">
                  <div class="row">
                      <div class="col-sm-6">
                          <label for="routing_number" class="col-form-label text-dark">{{__("Month")}}:<span class="text-danger">*</span></label>
                          <input class="form-control card-elements allowIntiger" maxlength="2" minlength="1" min="1" max="12"  name="month" type="text"  />
                        </div>
                        <div class="col-sm-6"> 
                            <label for="routing_number" class="col-form-label text-dark">{{__("Year")}}:<span class="text-danger">*</span></label>
                          <input class="form-control card-elements allowIntiger"  maxlength="4" minlength="2" min="1"  name="year" type="text"  />
                        </div>
                  </div>
                </div>
                <div class="form-group">
                    <label for="recipient-name" class="col-form-label text-dark">{{__("Currency")}}:<span class="text-danger">*</span></label>
                    <select name="card_currency" class="form-control"  id="">
                        <option selected disabled> {{__('-- Select Currency --')}}</option>
                        @forelse ($currency  as $item)
                        <option value="{{$item->id}}">{{$item->name}}</option>
                        @empty
                            
                        @endforelse
                    </select>
                </div>
                <div class="col-lg-12 p-0">
                    <button type="submit" class="btn btn-primary btn-round">{{__("Save")}}</button>
                </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__("Close")}}</button>
          </div>
        </div>
  </div>
</div>


<!-- bank account add modal -->
<div class="modal fade  bankaccountmodal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{__("Add new bank account")}}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form id="formdata2" action="{{route('user-bankaccount-store')}}" method="POST">
                @csrf
                <input type="hidden" id="accountlisturl" value="{{route('user-bankaccounts-show')}}">

                <div class="form-group">
                    <label for="recipient-name" class="col-form-label text-dark ">{{__("Country")}}:<span class="text-danger">*</span></label>
                    <select name="country" class="form-control"  id="">
                        <option selected disabled> -- Select Country --</option>
                        @forelse ($countries  as $item)
                        <option value="{{$item->id}}">{{$item->name}}</option>
                        @empty
                            
                        @endforelse
                    </select>
                </div>    
                <div class="form-group">
                    <label for="recipient-name" class="col-form-label text-dark">{{__("Currency")}}:<span class="text-danger">*</span></label>
                    <select name="bank_account_currency" class="form-control"  id="">
                        <option selected disabled> -- Select Currency -- </option>
                        @forelse ($currency  as $item)
                        <option value="{{$item->id}}">{{$item->name}}</option>
                        @empty
                            
                        @endforelse
                    </select>
                </div>
                <div class="form-group">
                    <label for="recipient-name" class="col-form-label text-dark">{{__("Bank Name")}}:<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="bank_name" id="recipient-name">
                </div>
                <div class="form-group">
                    <label for="account_name" class="col-form-label text-dark">{{__("Account Name:")}}:<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="account_name" id="account_name">
                </div>
                <div class="form-group">
                    <label for="account_number" class="col-form-label text-dark">{{__("Account Number")}}:<span class="text-danger">*</span></label>
                    <input type="text" class="form-control allowIntiger" name="account_number" id="account_number">
                </div>
                <div class="form-group">
                    <label for="swift_code" class="col-form-label text-dark">{{__("Swift Code")}}:</label>
                    <input type="text" class="form-control" name="swift_code" id="swift_code">
                </div>
                <div class="form-group">
                    <label for="routing_number" class="col-form-label text-dark">{{__("Routing Number")}}:</label>
                    <input type="text" class="form-control" name="routing_number" id="routing_number">
                </div>
                <div class="col-lg-12 p-0">
                    <button type="submit" class="btn btn-primary btn-round">{{__("Save")}}</button>
                </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
          </div>
        </div>
  </div>
</div>




<!-- set default Modal -->
<div class="modal fade " id="setprimaryCard" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">{{__("Set Account Default")}}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <p>{{__("Are you want to set this card default?")}}</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
          <button type="button" class="btn btn-primary setinformationprimary" data-name="cardinfo">{{__("Set Default")}}</button>
        </div>
      </div>
    </div>
</div>
<div class="modal fade " id="setprimaryaccount" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">{{__("Set Account Default")}}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <p>{{__("Are you want to set this account default?")}}</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
          <button type="button" class="btn btn-primary setinformationprimary"  data-name="bankinfo"  >{{__("Set Default")}}</button>
        </div>
      </div>
    </div>
</div>


  
@endsection 

@section('script')
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script type="text/javascript">
    var cnstatus = false;
    var dateStatus = false;
    var cvcStatus = false;

    function validateCard(cn) {
      cnstatus = Stripe.card.validateCardNumber(cn);
      if (!cnstatus) {
        $("#errCard").html('Invalid Card Number!');
      } else {
        $("#errCard").html('');
      }



    }

    function validateCVC(cvc) {
      cvcStatus = Stripe.card.validateCVC(cvc);
      if (!cvcStatus) {
        $("#errCVC").html('Invalid CVC Number!');
      } else {
        $("#errCVC").html('');
      }

    }

  </script>
@endsection