@extends('app')
@section('header') <!-- For Data Table -->
<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.10.10/css/dataTables.bootstrap.min.css" rel="stylesheet">
@stop
@section('content')
<h1 class="page-header">Alerts</h1>

@forelse($alerts as $paramID => $alert_item)
<h3>{{ App\Parameter::find($paramID)->parameter_name }}</h3>
<table class="table table-striped table-hover">
  <tr>
   <th style="width: 10%">Alert ID</th>
   <th style="width: 10%">Zone</th>
   <th style="width: 20%">Created At</th>
   <th style="width: 35%">Alert Description</th>
   <th style="width: 15%">Action Taken</th>
   <th style="width: 10%">Handled By</th>
 </tr>
 @foreach ($alert_item as $alert)
 <tr>
  <td>   <button class="btn btn-md btn-default btn--edit" type="button" 
    data-toggle="modal" data-target="#update" data-for="{{ $alert->id }}">
    <i class="glyphicon glyphicon-edit" data-toggle="tooltip" title="Update"></i></button>&nbsp;{{$alert->id}} 
  </td>
  <td>{{App\Parameter::find($alert->reading_id)->zone->zone_name}}
    <td> {{ date("F d Y - g:i a",strtotime("$alert->created_at")) }}  </td>
    <td> {{$alert->alert_description}} </td>
    <td> {{$alert->action_taken}} </td>
    <td> {{App\User::find($alert->user_id)->name}} </td>
  </tr>
  @endforeach
</table>

@empty
<div class="well">
  Good News. Everything is looking good.
</div>
@endforelse

<div class="col-sm-offset-1 col-sm-2">

  <!-- Modal -->
  <div id="update" class="modal fade" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Update Alert Status</h4>
        </div>

        <form class="form-horizontal" role="form" method="POST" action="/alerts/" novalidate>
          <input type="hidden" name="_method" value="PUT">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          
          <div class="modal-body">
            <label class="control-label" for="action_taken">Action Taken</label>             
            <select class="form-control" type="hidden" id="action_taken" name="action_taken">   
              <option value="Attention Needed">Attention Needed</option>
              <option value="In Progress">In Progress</option>
              <option value="Resolved">Resolved</option>
            </select>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success">Update</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<br/>
<h1 class="page-header">Alerts History</h1>

<div class="container col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div style="overflow:auto">
        <table class="table table-hover" id="alertTable">
            <thead>
                <tr>
                    <th>
                        Zone
                    </th>       
                    <th>
                        Parameter
                    </th>
                    <th>
                        Alert Description
                    </th>
                    <th>
                        Handled By
                    </th>
                    <th>
                        Action Taken
                    </th>
                    <th>
                        Timestamp
                    </th>
                </tr>
            </thead>

            <tbody>
                @foreach ($allAlerts as $alert)
                <tr>
                    <td>
                        {{ App\Zone::find(App\Parameter::find($alert->reading_id)->zone_id)->zone_name }}
                    </td>
                    <td>
                        {{ App\Parameter::find($alert->reading_id)->parameter_name }}
                    </td>
                    <td>
                        {{ $alert->alert_description }}
                    </td>
                    <td>
                        {{ App\User::find($alert->user_id)->name }}
                    </td>
                    <td>
                        {{ $alert->action_taken }}
                    </td>
                    <td>
                        {{ date("Y/m/d - g:i a",strtotime("$alert->updated_at")) }}
                    </td>
                </tr>
                @endforeach
            </tbody> 
        </table>
    </div>
</div>
@stop

@section('scripts')
<script type="text/javascript">
$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip(); 
});

$(function() {
  $('.btn--edit').on('click', function() {
    var userId = $(this).attr('data-for');
    var formAction = "/alerts/" + userId;
    console.log(formAction);
    $('.form-horizontal').attr('action', formAction);
  });
});
</script>

<script type="text/javascript">
$(function() {
  $('.btn--edit').on('click', function() {
    var userId = $(this).attr('data-for');
    var formAction = "/alerts/" + userId;
    console.log(formAction);
    $('.form-horizontal').attr('action', formAction);
  });
});
</script>

<!-- Bootstrap Based Data Table Plugin Script -->
<script src="https://cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.10/js/dataTables.bootstrap.min.js" type="text/javascript"></script>
<script>
$(document).ready(function() {
    $('#alertTable').DataTable({
      "order": [[ 5, "desc" ]] // make default ordering on timestamp
    });
} );

</script>
@stop