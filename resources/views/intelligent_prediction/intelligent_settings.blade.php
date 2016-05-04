@extends('app')

@section('content')
<h1 class="page-header">Intelligent Prediction Settings</h1>
<div class="container">
	<div class="clearfix" style="padding-bottom: 10px">
		<span class="pull-right clearfix"><h2 id="status" class="label label-default">Status: OFF</h2></span>
	</div>

	<div class="panel panel-default clearfix">
		<div class="panel-body">
			<form class="form-inline">
				<label>Turn on/off Intelligent Prediction</label>
				<input id="ipredict_toggle" type="checkbox" class="clearfix pull-right" value=1></input>
			</form>
		</div>
	</div>

	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">Settings</h3>
		</div>
		<div class="panel-body">
			<form>
				<div class="form-group">
					<label for="target-temperature">Target Temperature </label>
					<input class="form-control" type="text" name="target-temperature" id="target-temperature"></input>
				</div>
				<div class="form-group">
					<label for="offset-limit">Offset Limit</label>
					<input class="form-control" type="number" name="offset-limit" id="offset-limit"></input>
				</div>
				<div class="form-group">
					<label>Monitoring Period (months)</label>
					<input class="form-control" type="number" name="monitoring-period" id="monitoring-period"></input>
				</div>
			</form>
		</div>
	</div>
</div>
@stop

@section('scripts')
<script>
$('document').ready(function() {
	var status = $('#status');
	var ipredict_toggle = $('#ipredict_toggle');
	
	//toggle label function 
	var toggle_label = function(item, isChecked) {
		if (isChecked) {
			item.text("Status: ON");
			item.addClass('label-success');
			item.removeClass('label-default');
			toggle_inputs(true);	
		} else {
			item.text("Status: OFF");
			item.addClass('label-default');
			item.removeClass('label-success');
			toggle_inputs(false);	
		}
	};

	//The settings input should be enabled/disabled when intelligent prediction is on/off
	var toggle_inputs = function(isChecked) {
		if (isChecked) {
			$('#target-temperature').prop('disabled', false);
			$('#offset-limit').prop('disabled', false);
			$('#monitoring-period').prop('disabled', false);
		} else {
			$('#target-temperature').prop('disabled', true);
			$('#offset-limit').prop('disabled', true);
			$('#monitoring-period').prop('disabled', true);
		}
	};

	//read the settings on the toggle and display the appropriate status and badge
	toggle_label(status, ipredict_toggle.checked);

	//on click event on the toggle checkbox
	ipredict_toggle.click(function() {
		toggle_label(status, this.checked);
	});
});
</script>
@stop