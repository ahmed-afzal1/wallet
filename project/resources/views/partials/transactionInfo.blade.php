<form class="geniusform" action="{{ route('admin-theme-settings-update') }}" method="POST" enctype="multipart/form-data">
    @include('includes.admin.form-both')

    {{ csrf_field() }}

    <div class="form-row">
        <div class="form-group col-md-12">
            <label for="exampleInputPassword1">{{__("Transaction Refund With Cost")}}</label>
            <select name="transaction_refund_with_cost"  required  class="form-control">
                <option value="1" {{($gs->transaction_refund_with_cost == 1)?'selected': ''}}>{{__("With Cost")}}</option>
                <option value="0" {{($gs->transaction_refund_with_cost == 0)?'selected': ''}}>{{__("Without Cost")}}</option>
            </select>
        </div>
    </div>
    <button type="submit" id="submit-btn" class="btn btn-primary d-block mx-auto mt-2">{{ __('Submit') }}</button>
</form>