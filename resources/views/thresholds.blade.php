@extends('app')
@section('content')

<h1 class="page-header">Manage Threshold</h1>
<br/>

<div class="container">
    <div class="col-xs-12 col-sm-12 col-md-11 col-lg-11">
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
        <!-- Trigger the modal with a button -->
        <button style="float: right" type="button" class="btn btn-primary btn--create btn-sm" data-toggle="modal" data-target="#addNewThreshold">
            <i role="button" class="glyphicon glyphicon-plus" data-toggle="tooltip" title="Create a new threshold"></i>
        </button>
    </div>

    <div class="panel panel-info">
        <div class="panel-heading">
          <strong class="panel-title">Thresholds</strong>
      </div>

      <div class="panel-body" style="overflow: auto">
        <table class='table table-hover'>
            <tr>
                <th>
                    Edit
                </th>
                <th>
                    Threshold Description
                </th>
                <th>
                    Unit
                </th>
                <th class="danger">
                    Min Critical Value
                </th>
                <th class="warning">
                    Min Warning Value
                </th>
                <th class="success">
                    Normal Value
                </th>
                <th class="warning">
                    Max Warning Value
                </th>
                <th class="danger">
                    Max Critical Value
                </th>
            </tr>

            <!-- all of the value which extrated from database called Threshold-->
            @foreach ($thresholds as $threshold)
            @if ($threshold->id != 1)
            <threshold>
                <tbody>
                    <tr>
                        <td>
                            <button class="btn btn-sm btn-default pull-left btn--edit" type="button" data-for="{{ $threshold->id }}" 
                             data-toggle="modal" data-target="#editThreshold">
                             <i class="glyphicon glyphicon-edit" data-toggle="tooltip" title="Update {{ $threshold->threshold_category }}'s Details"></i></button>
                         </td>
                         <td> 
                             {{ $threshold->threshold_category }}
                         </td>
                         <td>   
                            {{ $threshold->units }}
                        </td>
                        <td class="danger">
                            {{ $threshold->min_critical_value }}
                        </td>
                        <td class="warning">
                            {{ $threshold->min_warning_value }}
                        </td>
                        <td class="success">
                            {{ $threshold->normal_value }}
                        </td>
                        <td class="warning">
                            {{ $threshold->max_warning_value }}
                        </td>
                        <td class="danger">
                            {{ $threshold->max_critical_value }}
                        </td>
                    </tr>
                </tbody>
            </threshold>
            @endif
            @endforeach
        </table>
    </div>
</div>

<div id="addNewThreshold" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">New Threshold</h4>
			</div>

            <form class="form-horizontal" role="form" method="post" action="thresholds">
                <input name="_token" type="hidden" value="{{ csrf_token() }}">

                <div class="modal-body">
                    <label class="control-label" for="threshold_category">Threshold Category :</label>
                    <input class="form-control" name="threshold_category" type="text" id="threshold_category" placeholder="Enter the threshold category">

                    <label class="control-label" for="units">Unit :</label>

                    <select class="form-control" name="units" id="units">
                        <option>None</option>
                        <option>Degree/Celcius</option>
                        <option>%</option>
                        <option>Volts</option>
                        <option>Pascals</option>
                    </select>

                    <label class="control-label" for="min_critical_value">Critical Value(min) :</label>
                    <input class="form-control" name="min_critical_value" type="text" id="min_critical_value" placeholder="Enter minimum critical value">

                    <label class="control-label" for="min_warning_value">Warning Value(min) :</label>
                    <input class="form-control" name="min_warning_value" type="text" id="min_warning_value" placeholder="Enter minimum warning value">

                    <label class="control-label" for="normal_value">Normal Value :</label>
                    <input class="form-control" name="normal_value" type="text" id="normal_value" placeholder="Enter the normal value">

                    <label class="control-label" for="max_warning_value">Warning Value(max) :</label>
                    <input class="form-control" name="max_warning_value" type="text" id="max_warning_value" placeholder="Enter maximum warning value">

                    <label class="control-label" for="max_critical_value">Critical Value(max) :</label>
                    <input class="form-control" name="max_critical_value" type="text" id="max_critical_value" placeholder="Enter maximum critical value">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="editThreshold" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Edit Threshold</h4>
			</div>

			<form class="form-horizontal" role="form" method="post" action="thresholds/">
                <input name="_method" type="hidden" value="put">
                <input name="_token" type="hidden" value="{{ csrf_token() }}">
                <div class="modal-body">
                    <label class="control-label" for="min_critical_value">Critical Value(min) :</label>       
                    <input class="form-control" name="min_critical_value" type="text" id="min_critical_value" placeholder="Enter minimum critical value">

                    <label class="control-label" for="min_warning_value">Warning Value(min) :</label>
                    <input class="form-control" name="min_warning_value" type="text" id="min_warning_value" placeholder="Enter minimum warning value">

                    <label class="control-label" for="normal_value">Normal Value :</label>
                    <input class="form-control" name="normal_value" type="text" id="normal_value" placeholder="Enter the normal value">

                    <label class="control-label" for="max_warning_value">Warning Value(max) :</label>
                    <input class="form-control" name="max_warning_value" type="text" id="max_warning_value" placeholder="Enter maximum warning value">

                    <label class="control-label" for="max_critical_value">Critical Value(max) :</label>
                    <input class="form-control" name="max_critical_value" type="text" id="max_critical_value" placeholder="Enter maximum critical value">
                </div>

                <div class="modal-footer">
                   <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                   <button type="submit" class="btn btn-success">Update</button>
               </div>
           </form>
       </div>
   </div>
</div>
@stop

@section('scripts')
<script>
$('div.alert').delay(5000).slideUp(300);

$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip(); 
});
</script>

<script type="text/javascript">
$(function() {

  $('.btn--edit').on('click', function() {
    var thresholdId = $(this).attr('data-for');
    var formAction = "/thresholds/" + thresholdId;
    console.log(formAction);
    $('.form-horizontal').attr('action', formAction);
});

  $('.btn--create').on('click', function() {
    var formAction = "/thresholds";
    $('.form-horizontal').attr('action', formAction);
});
});
</script>

@stop