@extends('app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<h1 class="page-header">Forms</h1>
<div>

	<div class="notification"></div>
	<!-- Nav tabs -->
	<ul class="nav nav-tabs" role="tablist">
		<?php $count = 1; ?>
		@foreach ($zones as $zone)
			<li role="presentation" class="{{ $count == 1 ?  'active' : '' }}"><a href="#{{ $zone->id }}" aria-controls="{{ $zone->id }}" role="tab" data-toggle="tab">{{ $zone->zone_name }}</a></li>
		<?php $count++; ?>
		@endforeach
	</ul>
	<!-- TO DO: Make sure you add the logic to display checkboxes for parameters of type boolean -->
	<!-- Tab Panes -->
	
	<div class="tab-content">
	<?php $count = 1; ?>
	@foreach ($zones as $zone)
		<?php $parameters = App\Parameter::ofZone($zone->id)->get() ?>
		<form role="tabpanel" class="reading-form tab-pane {{ $count == 1 ?  'active' : '' }}" id="{{ $zone->id }}">
			@forelse($parameters as $parameter)
				<label> {{ $parameter->parameter_name }} </label>
				@if($parameter->parameter_type == "boolean")
				<select class="form-control" name="{{ $parameter->id }}">
					<option value="1" "selected"}>Normal</option>
					<option value="0">Faulty</option>
				</select>
				@else
					<input class="form-control" name="{{ $parameter->id }}" placeholder="Reading" required>
				@endif
			@empty
				<div class="alert alert-warning" role="alert" style = "margin-top: 10px"> Oops! There are no parameters to display here </div>
			@endforelse
			<button type="submit" class="btn btn-primary" style="margin-top: 10px">Submit</button>
		</form>
		<?php $count++; ?>
	@endforeach 
	</div>
</div>	
@stop

@section('scripts')
<!-- AJAX Script -->
<script>
$(document).ready(function() {
	$(".reading-form").submit(function(e) {

		e.preventDefault();
		var form = $(this);

		//clear the notification area
		$(".notification").html('');

		//get data from form
		var formData = $(this).serializeArray();
		var data = [];

		//set the key and value to submit 
		formData.forEach(function(item) {
			var temp = {};
			temp.parameter_id  = item.name;
			temp.reading_value = item.value;
			data.push(temp);
		});

		$.ajaxSetup({
		        headers: {
		            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		        }
		});

		$.ajax({
			type: "POST",
			url: '/forms',
			data: 'readings=' + JSON.stringify(data),
			success: function(data) {
				//show the status message in the notification area
				if(data.error){
					$('.notification').html('<div class="alert alert-warning">'+data.message+'</div>');
				} else {
					$('.notification').html('<div class="alert alert-success">'+data.message+'</div>');
					//reset the form
					$('.notification').delay(1500).slideUp(300);
					form[0].reset();
				}
			}
		});
	});
});
</script>
@stop

