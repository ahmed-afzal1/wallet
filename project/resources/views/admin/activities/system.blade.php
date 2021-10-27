@extends('layouts.admin')

@section('styles')
    <link rel="stylesheet" href="{{asset('assets/backend/vendor/jqueryDatatable/datatable.css')}}">    
@endsection

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
              <h4 class="d-inline-block">Today's System Activities</h4> 
                <a href="{{route('admin.activities')}}" class="btn btn-primary btn-sm ml-4"> Today's  </a>
                <form action="{{route('admin.activities')}}"  class="form-inline float-right" method="post">
                  @csrf
                   <div class="form-group mx-sm-3 mb-2">
                        <input type="date" class="form-control" name="date" required placeholder="Date">
                    </div>
                    <button type="submit" class="btn btn-primary mb-2">Show Me</button>
                </form>
            </div>
            <div class="card-body">
                <!-- Button trigger modal -->
                <div class="table-responsive">
                    <table id="myTable" class="table table-hover table-striped" width="100%">
                        <thead>
                            <tr>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($logdata != null)
                            @foreach($logdata as $data)
                                @if ($data == null)
                                    @continue
                                @endif
                                <tr>
                                    <td>{{$data}}</td>
                                </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
@section('jquerydatatabescript')
    <script src="{{asset('assets/backend/vendor/jqueryDatatable/datatable.js')}}"></script>
    <script>
        (function ($) {
             "use strict";

            $(document).ready( function () {
                $('#myTable').DataTable();
            } );
            
        })(jQuery);

    </script>
@endsection