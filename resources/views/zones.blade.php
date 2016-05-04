@extends('app')
@section('content')

<h1 class="page-header">Manage Zones</h1>
<br/>

<div class="container">

  @if(Session::has('flash_message'))
  <div class="alert alert-success">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    {{ Session::get('flash_message') }}
  </div>
  @endif

  @if($errors->any())
  <div class="alert alert-danger">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    @foreach ($errors->all() as $error)
    <li>{{ $error }}</li>
    @endforeach
  </div>
  @endif

  <div style="margin-bottom:15px" class="well well-sm clearfix col-sm-12">
    <button style="float: right; margin-right:14px" type="button" class="btn btn-primary btn-sm btn--create" data-toggle="modal" data-target="#create">
       <i role="button" class="glyphicon glyphicon-plus" data-toggle="tooltip" title="Create a new zone"></i>
    </button>
  </div>

  <div class="panel panel-info">
    <div class="panel-heading">
      <strong class="panel-title">List of Zones</strong>
    </div>
    <div class="panel-body">
      <table class="table table-hover">
        <tbody>
          @foreach ($zones as $zone)
          <tr>
            <td>
              <button class="btn btn-sm btn-default btn--edit" type="button" data-for="{{ $zone->id }}" data-zonename="{{ $zone->zone_name }}"
                data-toggle="modal" data-target="#update">
                <i role="button" class="glyphicon glyphicon-edit" data-toggle="tooltip" title="Update {{ $zone->zone_name }}"></i>
              </button>&nbsp;
              {{ $zone->zone_name }}
            </td>
            <td>
              @if ($zone->hasParameters())
              <button class="btn btn-sm btn-danger btn--remove pull-right" type="button" data-toggle="modal" data-target="#errorMsg">
                <i role="button" class="glyphicon glyphicon-remove" data-toggle="tooltip" title="Remove {{ $zone->zone_name }}"></i>
              </button>
              @else
              <button class="btn btn-sm btn-danger btn--remove pull-right" type="button" data-toggle="modal" data-target="#remove" data-for="{{ $zone->id }}">
                <i role="button" class="glyphicon glyphicon-remove" data-toggle="tooltip" title="Remove {{ $zone->zone_name }}"></i>
              </button>
              @endif
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Create Modal -->
<div id="create" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Create New Zone</h4>
      </div>
      
      <form class="form-horizontal" role="form" method="POST" action="zones">
        <input name="_token" type="hidden" value="{{ csrf_token() }}">
        
        <div class="modal-body">
          <label class="control-label" for="zone_name">Zone Name</label>
          <input class="form-control" name="zone_name" type="text" id="zone_name">
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success">Create</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Update Modal -->
<div id="update" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Update Existing Zone</h4>
      </div>

      <form class="form-horizontal" id="zoneUpdate" role="form" method="POST" action="zones/">
        <input name="_method" type="hidden" value="PUT">
        <input name="_token" type="hidden" value="{{ csrf_token() }}">
        
        <div class="modal-body">
          <label class="control-label" for="zone_name">Zone Name</label>
          <input class="form-control" name="zone_name" type="text" id="zone_name">          
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Error Message Modal -->
<div id="errorMsg" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Validation Error Message</h4>
      </div>

      <div class="modal-body">
        Error, sorry unable to remove zone as selected zone contains existing parameters!
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Remove Modal -->
<div id="remove" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Remove Selected Zone</h4>
      </div>

      <div class="modal-body">
        <form class="form-horizontal" role="form" method="POST" action="/zones/" novalidate>
          <input type="hidden" name="_method" value="DELETE">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          Are you sure you want to remove selected zone ?
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger">Remove</button>
        </div>
      </form>
    </div>
  </div>
</div>
@stop

@section('scripts')

<script>
$('div.alert').delay(3000).slideUp(300);

$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip(); 
});
</script>

<script type="text/javascript">
$(function() {

  $('.btn--edit').on('click', function() {
    var thresholdId = $(this).attr('data-for');
    var formAction = "/zones/" + thresholdId;
    console.log(formAction);
    $('.form-horizontal').attr('action', formAction);
  });

  $('.btn--create').on('click', function() {
    var formAction = "/zones";
    console.log('clicked');
    $('.form-horizontal').attr('action', formAction);
  });

  $('.btn--remove').on('click', function() {
    var userId = $(this).attr('data-for');
    var formAction = "/zones/" + userId;
    console.log(formAction);
    $('.form-horizontal').attr('action', formAction);
  });

  $('#update').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Button that triggered the modal
    var zone_name = button.data('zonename'); // Extract info from data-* attributes

    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
    var modal = $(this)
    modal.find('.modal-title').text('Edit ' + zone_name);
    modal.find('#zone_name').val(zone_name);

  });

});
</script>

@stop