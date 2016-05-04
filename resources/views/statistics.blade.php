
@extends('app')
@section('header')

<link href="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/build/css/bootstrap-datetimepicker.css" rel="stylesheet">
<script src="chart/Chart.js"></script>

@stop

@section('content')
<h1 class="page-header">Statistics</h1>
	<?php
	    $zone_select = array();
		foreach ($zones as $zone) 
		{
		    $zone_select[$zone->id]['zone_id'] = $zone->id;
		    $zone_select[$zone->id]['zone_name'] = $zone->zone_name;
		    $zone_select[$zone->id]['parameters'] = $zone->parameter;
	    }
	?>

	@if (count($errors) > 0)
	  <div class="alert alert-danger">
	    <ul>
	      @foreach ($errors->all() as $error)
	        <li>{{ $error }}</li>
	      @endforeach
	    </ul>
	  </div>
	@endif

<div class="row">

	<form action="{{ url('/statistics') }}" method="POST">
	{!! csrf_field() !!}
    
	<div class="form-group col-xs-12 col-sm-5 col-md-5 col-lg-5 col-sm-offset-1 col-md-offset-1 col-lg-offset-1">
		<label class="control-label">Zone</label>
		<select class="form-control" id="zone" name="zone">
			@foreach($zone_select as $z)
		    <option value="{{$z['zone_id']}}"   {{ ( old('zone') == $z['zone_id'] ? 'selected' : NULL ) }}>{{$z['zone_name']}}</option>
		    @endforeach
  		</select>
	</div>

	<div class="form-group col-xs-12 col-sm-5 col-md-5 col-lg-5">
	    <label class="control-label">Sensor</label>
	    <select class="form-control" id="sensor" name="sensor">
	    </select>
	</div>

	<div class="form-group col-xs-12 col-sm-5 col-md-5 col-lg-5 col-sm-offset-1 col-md-offset-1 col-lg-offset-1">
	    <label class="control-label">Type</label>
	    <select class="form-control" id="type" name="type">
	    	<option value="Reading">Reading</option>
	    	<option value="Alert">Alert</option>
	    </select>
	</div>		

	<div class="form-group col-xs-12 col-sm-5 col-md-5 col-lg-5">
	    <label class="control-label">Particular</label>
	    <select class="form-control" id="particular" name="particular">
	    	<option value='Average'>Whole Day Average</option>
	    	<option value='Compare'>Morning & Evening</option>
	    </select>
	</div>

	<div class="form-group col-xs-12 col-sm-5 col-md-5 col-lg-5 col-sm-offset-1 col-md-offset-1 col-lg-offset-1">
		<label class="control-label">Year</label>
		<div class='input-group date' id='yearpicker'>
	    	<input type='text' class="form-control" name="year" value="{{ old('year') }}"/>
	    	<span class="input-group-addon">
	        	<span class="glyphicon glyphicon-calendar"></span>
	        </span>
	    </div>
	</div>

	<div class="form-group col-xs-12 col-sm-5 col-md-5 col-lg-5">
		<label class="control-label">Month</label>
		<select class="form-control" name="month">
			<option value="Annual" selected>None (Annual)</option>
			<option value="January">January</option>
		    <option value="February">February</option>
		    <option value="March">March</option>
		    <option value="April">April</option>
		    <option value="May">May</option>
		    <option value="June">June</option>
		    <option value="July">July</option>
		    <option value="August">August</option>
		    <option value="September">September</option>
		    <option value="October">October</option>
		    <option value="November">November</option>
		    <option value="December">December</option>
		</select>
	</div>

    <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-sm-offset-1 col-md-offset-1 col-lg-offset-1">
		<button class="btn btn-primary btn-block">View</button>
	</div>
	
	</form>
</div>

	<!-- Display Chart -->
	@if ($current_zone)
		@if ($display == TRUE)
		<div class="hidden-xs col-sm-10 col-md-10 col-lg-10 col-sm-offset-1 col-md-offset-1 col-lg-offset-1">
			<h3 style="margin-top: 100px"> {{ $current_zone->zone_name }} ({{ $current_para->parameter_name }}) --- {{ $year }} {{ $month }} {{ $current_type }} Statistics ({{ $threshold_unit }})</h3>
			<hr>
			<a id="download" class="btn btn-success btn-sm pull-right">Download as Image</a>
		</div>

		<div class="hidden-sm hidden-md hidden-lg col-xs-12">
			<h4 style="margin-top: 100px"> {{ $current_zone->zone_name }} ({{ $current_para->parameter_name }}) --- {{ $year }} {{ $month }} {{ $current_type }} Statistics ({{ $threshold_unit }}) </h4>
			<hr>
			<a id="download" class="btn btn-success btn-sm pull-right">Download as Image</a>
		</div>

		<div id="canvas-holder2" class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-sm-offset-1 col-md-offset-1 col-lg-offset-1">
			<canvas class="col-xs-12 col-sm-12 col-md-12 col-lg-12"  id="myChart"/>
		</div>

		<div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-sm-offset-1 col-md-offset-1 col-lg-offset-1"><hr></div>

		@if ($chart_data2 != "")
			<div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-sm-offset-1 col-md-offset-1 col-lg-offset-1">
				<div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
					<div style="color:rgba(151,187,205,1);">&#9632; Morning</div>
					<div style="color:rgba(241,193,108,1);">&#9632; Evening</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-sm-offset-1 col-md-offset-1 col-lg-offset-1"><hr></div>
		@endif

		@else
		<div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-sm-offset-1 col-md-offset-1 col-lg-offset-1">
			<h3 style="margin-top: 100px"> No Records To Generate Statistics <hr></h3>	
		</div>
		@endif
	@endif

@stop

@section('scripts')
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
<script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>
<script type="text/javascript">
    
    // date_time_picker
	$('#yearpicker').datetimepicker({
    	format: 'YYYY',
    	viewMode: 'years',
    	allowInputToggle: true,
    	maxDate: 'moment',
    	minDate: '1/1/2005'
    });

	var zones = <?php echo json_encode($zone_select)  ?>;

    populateZone(parseInt($("#zone option:selected" ).val()));
    <?php if(old('sensor')) {  echo  "$('#sensor').val('".old("sensor")."');"; } ?>

    $('#zone').change(function () {
    	populateZone(parseInt($(this).val()))
    });

    function populateZone(zone)
    {
    	console.log(zones[zone]);
        //var params = parameters[zone];
      	$("#sensor").empty();
      	if(zones[zone].parameters.length > 0)
      	{
        	$.each(zones[zone].parameters, function(index, value) 
        	{
          		$("#sensor").append("<option value=\""+value.id+"\">" + value.parameter_name + "</option>");
       		});
      	} 
      	else 
      	{
        	$("#sensor").append("<option disabled>No sensors available</option>");
      	}
    }

    // 'particular' option change based on 'type' option
    $(document).ready(function()
    {
    	$("#type").change(function()
    	{
    		var val = $(this).val();

    		if (val == "Reading")
    			$("#particular").html("<option value='Average'>Whole Day Average</option><option value='Compare'>Morning & Evening</option>");
    		else if (val == "Alert")
    			$("#particular").html("<option value='Occurrence'>Occurrence</option>");
    	});
    });

    // chart
    var ctx = document.getElementById("myChart").getContext("2d");
	var data = 
	{
		// if only one chart data required
		@if ($current_zone && $chart_data != "" && $chart_data2 == "")
		<?php
			echo $chart_labels;
			echo 'datasets: 
					[
					{
						label: "My dataset",
						fillColor: "rgba(151,187,205,0.3)",
						strokeColor: "rgba(151,187,205,1)",
						pointColor: "rgba(151,187,205,1)",
						pointStrokeColor: "#fff",
						pointHighlightFill: "#fff",
						scaleBeginAtZero: true,
						pointHighlightStroke: "rgba(151,187,205,1)",';
			echo $chart_data;
			echo '	}]';
		?>
		// if two chart data required
		@elseif ($current_zone && $chart_data != "" && $chart_data2 != "")
		<?php
			echo $chart_labels;
			echo 'datasets: 
					[
					{
						label: "My dataset",
						fillColor: "rgba(151,187,205,0.3)",
						strokeColor: "rgba(151,187,205,1)",
						pointColor: "rgba(151,187,205,1)",
						pointStrokeColor: "#fff",
						pointHighlightFill: "#fff",
						pointHighlightStroke: "rgba(151,187,205,1)",';
			echo $chart_data;

			echo '	},
					{
					label: "My dataset2",
		            fillColor: "rgba(241,193,108,0.3)",
		            strokeColor: "rgba(241,193,108,1)",
		            pointColor: "rgba(241,193,108,1)",
		            pointStrokeColor: "#fff",
		            pointHighlightFill: "#fff",
		            pointHighlightStroke: "rgba(241,193,108,1)",';

		    echo $chart_data2;
		    echo '  }]';
		?>
		@endif
	};
 
	var options = {
		@if ($current_zone && ($threshold_type == "Temperature" || $threshold_type == "Air Conditioning")) // set temperature chart display scale
			scaleOverride: true,
			scaleSteps: 15,
			scaleStepWidth: 1,
			scaleStartValue: 15
		@elseif ($current_zone && $threshold_type == "Humidity")
			scaleOverride: true,
			scaleSteps: 12,
			scaleStepWidth: 5,
			scaleStartValue: 20
		@endif
	};

	var myLineChart = new Chart(ctx).Line(data, options);

	// download chart
	function downloadCanvas(link, canvasId, filename) 
	{
    	link.href = document.getElementById('myChart').toDataURL();
    	link.download = filename;
	}

	/** 
	 * The event handler for the link's onclick event. We give THIS as a
	 * parameter (=the link element), ID of the canvas and a filename.
	*/
	

	document.getElementById('download').addEventListener('click', function() 
	{
		var currentdate = new Date().toLocaleDateString().toString();
		var currenttime = new Date().toLocaleTimeString().toString();
   		var filename = currentdate + ' @ ' + currenttime + '.png'
	    downloadCanvas(this, 'canvas', filename);
	}, false);
</script>

@stop