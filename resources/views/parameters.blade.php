@extends('app')
@section('content')
<h1 class="page-header">Manage Parameters</h1><br/>

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
	<button style="float: right" type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addParam">
		<i role="button" class="glyphicon glyphicon-plus" data-toggle="tooltip" title="Create a new parameter"></i>
	</button>	
</div>

@foreach ($zones as $zone)
@if ($zone->parameter->count() > 0)
<div class="panel panel-info">
	<div class="panel-heading">
		<h2 style="font-weight: bold" class="panel-title">{{ $zone->zone_name }}</h2>
	</div>
	<div class="panel-body" style="overflow: auto">
		<table class="table table-hover col-xs-12">
			<thead>
				<tr>
					<th style="width: 10%">Options</th>
					<th style="width: 20%">Parameter Name </th>
					<th style="width: 35%">Description </th>
					<th style="width: 15%">Type</th>
					<th style="width: 20%">Threshold Type</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($parameters as $parameter)
				@if ($parameter->zone_id == $zone->id)
				<tr>
					<td>
						<button type="button" name="{{ $parameter->id }}" 
							data-for="{{ $parameter->id }}" 
							data-param="{{ $parameter->parameter_name }}"
							data-description="{{ $parameter->parameter_description }}" 
							data-toggle="modal" data-target="#editParam" class="btn btn-sm btn-default editButton">
							<i class="glyphicon glyphicon-edit" data-toggle="tooltip" data-placement="right" title="Update {{ $parameter->parameter_name }}"></i>
						</button>
						@if ($parameter->hasReadings())
						<button type="button" data-toggle="modal" data-target="#errorMsg" class="btn btn-sm btn-danger">
							<span class="glyphicon glyphicon-remove" data-toggle="tooltip" data-placement="right" title="Remove {{ $parameter->parameter_name }}"></span>
						</button>
						@else
						<button type="button" name="{{ $parameter->id }}" 
							data-for="{{ $parameter->id }}" data-toggle="modal" data-target="#removeParam" class="btn btn-sm btn-danger removeButton">
							<span class="glyphicon glyphicon-remove" data-toggle="tooltip" data-placement="right" title="Remove {{ $parameter->parameter_name }}"></span>
						</button>
						@endif
					</td>
					<td>{{$parameter->parameter_name}}</td>
					<td>{{$parameter->parameter_description}}</td>
					<td>{{$parameter->parameter_type}}</td>
					<td>{{$parameter->threshold->threshold_category}}</td>
				</tr>
				@endif
				@endforeach
			</tbody>
		</table>
	</div>
</div>
@endif
@endforeach

@include('manage_parameters.add_parameter')
@include('manage_parameters.edit_parameter')

<!-- Error Message Modal -->
<div id="errorMsg" tabindex="-1"p class="modal fade" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Validation Error Message</h4>
			</div>

			<div class="modal-body">
				Error, sorry unable to remove parameter as selected parameter contains existing readings! 
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<!-- Remove Modal -->
<div id="removeParam" tabindex="-1"p class="modal fade" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Remove Selected Parameter</h4>
			</div>

			<div class="modal-body">
				<form class="form-horizontal" role="form" method="POST" action="/parameters/" novalidate>
					<input type="hidden" name="_method" value="DELETE">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					Are you sure you want to remove selected parameter ?
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
</script>

<script>
$(function(){
	$(".editButton").on('click', function(){
		var param_id = $(this).attr('data-for');
		var formAction = "/parameters/" + param_id;

		//set the form action to the url of the parameter to be edited
		$("#editParameter").attr('action', formAction);

		console.log(formAction);
	});	

	$('.removeButton').on('click', function() {
		var userId = $(this).attr('data-for');
		var formAction = "/parameters/" + userId;
		console.log(formAction);
		$('.form-horizontal').attr('action', formAction);
	});

	$('#editParam').on('show.bs.modal', function (event) {
	  var button = $(event.relatedTarget); // Button that triggered the modal
	  var parameter = button.data('param'); // Extract info from data-* attributes
	  var description = button.data('description');

	  // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
	  // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
	  var modal = $(this)
	  modal.find('.modal-title').text('Edit ' + parameter);
	  modal.find('.param').val(parameter);
	  modal.find('.description').val(description);

	});

});

$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip(); 
});
</script>
@stop