@extends('layouts.admin')

@section('content')


<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">{{__('Language Settings')}}</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}" class="text-dark">{{__('Home')}}</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="{{route('admin-localization-settings')}}" class="text-muted">{{__('Language')}}</a></li>
    </ol>
</div>




<div class="row mb-3">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h4>
                    {{__("Language list")}}
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary float-right btn-sm" data-toggle="modal" data-target="#staticBackdrop">
                        {{__("Add new")}}
                    </button>
                </h4>
            </div>
            <div class="card-body">
                @include('flashmessage')
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>{{__("Language Name")}}</th>
                                <th>{{__("Code")}}</th>
                                <th>{{__("Translate")}}</th>
                                <th>{{__("Status")}}</th>
                                <th>{{__("Default")}}</th>
                            </tr>
                        </thead>
                        <tbody>
                           
                            @forelse ($languageData as $value)
                            <tr>
                                <td>{{__($value->languageInfo->name)}}</td>
                                <td>{{$value->language_code}}</td>
                                    <td><a href="{{route('admin-language-edit',['lid'=>$value->id,'language'=>$value->language_code])}}"  class="btn btn-warning">{{__('Edit Transalation')}}</a></td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                            @if($value->status == 1)
                                                <button class="btn btn-success"><i class="fas fa-check-double"></i> {{__('Active')}}</button>
                                                <a href="{{route('admin-language-change-status',['id'=>$value->id,'value'=>0])}}"  class="btn btn-warning">{{__('Deactive')}}</a>
                                            @else
                                                <a href="{{route('admin-language-change-status',['id'=>$value->id,'value'=>1])}}"  class="btn btn-warning">{{__('Active')}}</a>
                                                <button class="btn btn-success"><i class="fas fa-check-double"></i> {{__('Deactive')}}</button>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($value->is_default == 1)
                                            <button class="btn btn-success">{{__('Default')}}</button>
                                        @else
                                            <a href="{{route('admin-language-set-default',['id'=>$value->id])}}"  class="btn btn-warning">{{__('Set Default')}}</a>
                                            <a  href="javascript:;"  data-toggle="modal" data-target="#confirm-delete"  data-href="{{route('admin-language-delete',$value->language_code)}}"  class="btn btn-danger  confirm-delete">{{__('Delete')}}</a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


  
  <!-- Modal -->
  <div class="modal fade" id="staticBackdrop" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Add new language</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form action="{{route('admin-language-add')}}" method="post">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-12">
                      <label for="inputEmail4">{{__("Language")}}</label>
                        <select name="language" class="form-control">
                           
                            @foreach ($languageList as $item)
                            @if (App\Models\Localization::where('language_code',$item->iso_639)->exists())
                                @continue
                            @endif
                                <option value="{{$item->iso_639}}">{{$item->name}} ({{$item->iso_639}})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="pt-3">
                    <button type="submit" class="btn btn-primary float-right">{{__("Submit")}}</button>
                    <button type="button" class="btn btn-secondary float-right mr-2" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
        <div class="modal-footer">
          
        </div>
      </div>
    </div>
  </div>


{{-- DELETE MODAL --}}

<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
  
            <div class="modal-header">
                <h5 class="modal-title">{{ __("Confirm Delete") }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
    
            <!-- Modal body -->
            <div class="modal-body">
                <p>{{ __("You are going to delete this data.") }}</p>
            </div>
    
            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ __("Cancel") }}</button>
                <a class="btn btn-danger text-white load-delete">{{ __("Delete") }}</a>
            </div>
  
        </div>
    </div>
  </div>
  
  {{-- DELETE MODAL ENDS --}}
@endsection
