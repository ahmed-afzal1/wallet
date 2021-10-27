@extends('layouts.user')

@section('content')
        
			<div class="account-box">
				<div class="header-area">
				  <h4 class="title">
					  {{__("Personal Details")}} <a href="javascript:;" class="btn btn-sm btn-warning"   data-toggle="modal" data-target="#changepass">{{__("Change Password")}}</a>
				  </h4>
				  <a href="#" class="edit"  data-toggle="modal" data-target="#edit-account">
					  <i class="fas fa-edit"></i>
				  </a>
				</div>

				<div class="content">
				  <div class="table-responsive">
            <table>
              <tr>
                <td>
                  {{__("Name")}} <span>:</span>
                </td>
                <td>
                  {{__(ucwords($userinfo->name))}}
                </td>
              </tr>

              <tr>
                <td>{{__("Date of Birth")}}<span>:</span></td>
                <td> {{$userinfo->dob}}</td>
              </tr>

              <tr>
                <td>{{__("Gender")}} <span>:</span></td>
                <td>{{__(($userinfo->gender)?ucwords($userinfo->gender):'Not Defiend')}}</td>
              </tr>

              <tr>
                <td>{{__("About")}} <span>:</span></td>
                <td>{{__($userinfo->about)}}</td>
              </tr>  
            </table>
				  </div>
				</div>
      </div>
              
			  <div class="account-box mt-30">
				<div class="header-area">
				  <h4 class="title">
					  {{__("Account Settings")}}
				  </h4>

				</div>
				<div class="content">
					<div class="table-responsive">
				  <table>
					<tr>
					  <td>
						  {{__("Language")}} <span>:</span>
					  </td>
					  <td>
						 {{__(" English (United States)")}}
					  </td>
					</tr>
					<tr>
					  <td>
						  {{__("Time Zone")}} <span>:</span>
					  </td>
					  <td>
						  {{__("(GMT-06:00) Central America")}}
					  </td>
					</tr>
					<tr>
					  <td>
						 {{__("Account Status")}}<span>:</span>
					  </td>
					  <td>
						  <button class="btn btn-success btn-sm"><i class="fas fa-check-circle"></i> {{ __("Active")}} </button>
					  </td>
					</tr>
					<tr>
					  <td>
						  {{__("verified")}} <span>:</span>
					  </td>
					  <td>
						  <button class="btn btn-primary btn-sm"><i class="fas fa-check-circle"></i> {{__("Verified")}} </button>
					  </td>
					</tr>
				  </table>
				</div>
				</div>
              </div>
              
             
			  <div class="account-box mt-30">
          <div class="header-area">
            <h4 class="title">
              {{__("Contacts")}}
            </h4>
            <a href="javascript:;" class="edit"  data-toggle="modal" data-target="#changephone">
            <i class="fas fa-edit"></i>
            </a>
          </div>
          <div class="content">
          <div class="table-responsive">
            <table>
              <tr>
                <td>{{__("Phone")}} <span>:</span></td>
                <td>{{__($userinfo->phone)}}</td>
              </tr>

              <tr>
                <td>{{__("Alternative Phone")}} <span>:</span></td>
                <td>{{__(($userinfo->alt_phone)?$userinfo->alt_phone:'---')}}</td>
              </tr>

              <tr>
                <td>{{__("Email (Primary)")}} <span>:</span></td>
                <td>{{__($userinfo->email)}}</td>
              </tr>

              <tr>
                <td>{{__("Alternative Email")}} <span>:</span></td>
                <td>{{__(($userinfo->alt_email)?$userinfo->alt_email:'---')}}</td>
              </tr>
              
              <tr>
                <td>{{__("Address")}} <span>:</span></td>
                <td>{{__(ucwords($userinfo->address).' '.$userinfo->city.(($userinfo->postalcode)?'-'.$userinfo->postalcode:' ').$userinfo->postalcode.' '. $userinfo->country)}}</td>
              </tr>
            </table>
            </div>
          </div>
        </div>
              
        




<!-- Modal -->
<div class="modal fade" id="edit-account" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">{{__("Update Profile Info")}}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <p><span class="text-danger">*</span> <small>{{__('Marked are Required')}}</small></p>
            <form id="formdata2" action="{{route('userpersonalinfo')}}" method="POST">
                @csrf
                <div class="form-group">
                  <label for="exampleInputEmail1">{{__('Name')}}<span class="text-danger">*</span></label>
                  <input type="text" name="name" value="{{$userinfo->name}}" class="form-control" id="exampleInputEmail1">
                </div>

                <div class="form-group">
                  <label for="exampleInputEmail1">{{__('Date Of Birth')}}<span class="text-danger">*</span></label>
                  <input type="date" name="dob" value="{{$userinfo->dob}}" class="form-control">
                </div>

                <div class="form-group">
                  <label for="exampleInputEmail1">{{__('Gender')}}<span class="text-danger">*</span></label>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="gender" id="exampleRadios" value="male" {{($userinfo->gender== 'male')?'checked':''}}>
                    <label class="form-check-label" for="exampleRadios">
                     {{__("Male")}}
                    </label>
                  </div>

                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="gender" id="exampleRadios1" value="female"  {{($userinfo->gender== 'female')?'checked':''}}>
                    <label class="form-check-label" for="exampleRadios1">
                     {{__("Female")}}
                    </label>
                  </div>
                </div>

                <div class="form-group">
                  <label for="exampleInputEmail1">{{__('About')}}</label>
                  <input type="text" class="form-control"  name="about" value="{{$userinfo->about}}"  id="exampleInputEmail1">
                </div>

                <div class="form-group">
                  <label for="exampleInputEmail1">{{__('Password')}}<span class="text-danger">*</span></label>
                  <input type="password" class="form-control"  name="password" required  id="exampleInputEmail1">
                </div>

                <div class="btn-submit">
                    <button type="submit" class="btn btn-primary">{{__("Update")}}</button>
                </div>
              </form>
        </div>
        <div class="modal-footer">
         &nbsp;
        </div>
      </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="changepass" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">{{__("Change Password")}}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <p><span class="text-danger">*</span> <small>{{__('Marked are Required')}}</small></p>
            <form id="formdata" action="{{route('user-change-password')}}"  method="POST" >
                @csrf
                <div class="form-group">
                  <label for="exampleInputEmail1">{{__('Current Password')}}<span class="text-danger">*</span></label>
                  <input type="password" class="form-control" name="currentpassword" required id="exampleInputEmail1">
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail1">{{__('New Password')}}<span class="text-danger">*</span></label>
                  <input type="password" class="form-control"  name="password"   required id="exampleInputEmail1">
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">{{__('Confirm Password')}}<span class="text-danger">*</span></label>
                  <input type="password" class="form-control"  name="password_confirmation"  required  id="exampleInputPassword1">
                </div>
                <div class="submit-btn">
                    <button type="submit" class="btn btn-primary btn-sm">{{__("Change")}}</button>
                </div>
              </form>
        </div>
        <div class="modal-footer">
         &nbsp;
        </div>
      </div>
    </div>
</div>

<div class="modal fade" id="changephone" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">{{__("Change Contacts")}}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <p><span class="text-danger">*</span> <small>{{__('Marked are Required')}}</small></p>
            <form id="formdata2" action="{{route('user-info-update')}}"  method="POST" >
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="exampleInputEmail1">{{__('Phone')}}<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="phone" required value="{{$userinfo->phone}}"  id="exampleInputEmail1">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">{{__('Alternative Phone')}}</label>
                            <input type="text" class="form-control"  name="alt_phone" value="{{$userinfo->alt_phone}}"  id="exampleInputEmail1">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">{{__('Alternative Email')}}</label>
                            <input type="email" class="form-control"  name="alt_email"  value="{{$userinfo->alt_email}}" id="exampleInputEmail1">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">{{__('Website')}}</label>
                            <input type="url" class="form-control"  name="website" value="{{$userinfo->website}}"  id="exampleInputEmail1">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">{{__('Address')}}<span class="text-danger">*</span></label>
                            <input type="text" class="form-control"  name="address"  required value="{{$userinfo->address}}" id="exampleInputEmail1">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="exampleInputEmail1">{{__('Postal Code')}}<span class="text-danger">*</span></label>
                            <input type="number" class="form-control"  name="postalcode" required value="{{$userinfo->postalcode}}"  id="exampleInputEmail1">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">{{__('City')}}<span class="text-danger">*</span></label>
                            <input type="text" class="form-control"  name="city" value="{{$userinfo->city}}" required  id="exampleInputEmail1">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">{{__('State')}}</label>
                            <input type="text" class="form-control"  name="state"  value="{{$userinfo->state}}" id="exampleInputEmail1">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">{{__('Country')}}<span class="text-danger">*</span></label>
                            <input type="text" class="form-control"  name="country" required value="{{$userinfo->country}}"  id="exampleInputEmail1">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">{{__('Password')}}<span class="text-danger">*</span></label>
                            <input type="password" class="form-control"  name="password" required  id="exampleInputEmail1">
                        </div>
                    </div>
                </div>
                <div class="submit-btn form-group">
                    <button type="submit" class="btn btn-primary btn-sm">{{__("Change")}}</button>
                </div>
              </form>
        </div>
        <div class="modal-footer">
         &nbsp;
        </div>
      </div>
    </div>
</div>



@endsection