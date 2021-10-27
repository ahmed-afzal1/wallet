<form class="geniusform" action="{{ route('admin-theme-settings-update') }}" method="POST" enctype="multipart/form-data">
    @include('includes.admin.form-both')

    {{ csrf_field() }}

    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="exampleInputEmail1">{{__("Mail Driver")}}</label>
            <select name="mail_driver"  required  class="form-control">
                <option value="smtp" {{($gs->mail_driver == 'smtp')?'selected': ''}}>{{__("SMTP")}}</option>
                <option value="sendmail" {{($gs->mail_driver == 'sendmail')?'selected': ''}}>{{__("Sendmail")}}</option>
                <option value="mailgun" {{($gs->mail_driver == 'mailgun')?'selected': ''}}>{{__("Mailgun")}}</option>
                <option value="postmark" {{($gs->mail_driver == 'postmark')?'selected': ''}}>{{__("Postmark")}}</option>
            </select>
        </div>
        <div class="form-group col-md-6">
            <label for="exampleInputEmail1">{{__("Mail Port")}}</label>
            <select name="smtp_port"  required  class="form-control">
                <option value="25" {{($gs->smtp_port == '25')?'selected': ''}}>{{__("25")}}</option>
                <option value="465" {{($gs->smtp_port == '465')?'selected': ''}}>{{__("465")}}</option>
                <option value="587" {{($gs->smtp_port == '587')?'selected': ''}}>{{__("587")}}</option>
                <option value="2525" {{($gs->smtp_port == '2525')?'selected': ''}}>{{__("2525")}}</option>
            </select>
        </div>
        <div class="form-group col-md-6">
            <label for="exampleInputPassword1">{{__("Mail Host")}}</label>
            <input type="text" name="smtp_host" class="form-control" required  value="{{$gs->smtp_host}}"  id="exampleInputPassword1">
        </div>
        <div class="form-group col-md-6">
            <label for="exampleInputPassword1">{{__("Email Encryption")}}</label>
            <select name="email_encryption"  required  class="form-control">
                <option value="tls" {{($gs->email_encryption == 'tls')?'selected': ''}}>{{__("tls")}}</option>
                <option value="ssl" {{($gs->email_encryption == 'ssl')?'selected': ''}}>{{__("ssl")}}</option>
            </select>
        </div>

        <div class="form-group col-md-6">
            <label for="smtp_user">{{__("Mail Username")}}</label>
            <input type="text" name="smtp_user" class="form-control" required  value="{{$gs->smtp_user}}"  id="smtp_user">
        </div>

        <div class="form-group col-md-6">
            <label for="smtp_pass">{{__("Mail Password")}}</label>
            <input type="text" name="smtp_pass" class="form-control"  required  value="{{$gs->smtp_pass}}"  id="smtp_pass">
        </div>

        <div class="form-group col-md-6">
            <label for="from_email">{{__("From Email")}}</label>
            <input type="text" name="from_email" class="form-control" required  value="{{$gs->from_email}}"  id="from_email">
        </div>

        <div class="form-group col-md-6">
            <label for="from_name">{{__("From Name")}}</label>
            <input type="text" name="from_name" class="form-control"  required  value="{{$gs->from_name}}"  id="from_name">
        </div>

        <div class="form-group col-md-6">
            <label for="is_smtp">{{__("SMTP")}}</label>
            <select name="is_smtp"  required  class="form-control">
                <option value="1" {{($gs->is_smtp == 1)?'selected': ''}}>{{__("Enable")}}</option>
                <option value="0" {{($gs->is_smtp == 0)?'selected': ''}}>{{__("Disable")}}</option>
            </select>
        </div>
    </div>
    <button type="submit" id="submit-btn" class="btn btn-primary d-block mx-auto mt-2">{{ __('Submit') }}</button>
</form>