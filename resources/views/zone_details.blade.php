@extends('app')
@section('header')

<link href="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/build/css/bootstrap-datetimepicker.css" rel="stylesheet">
<script src="chart/Chart.js"></script>
<style>
	th {
		width: 30%;
		background-color: #e6e6e6;
	}
</style>
@stop

@section('content')
<h1 class="page-header">{{ $zone->zone_name }}</h1>
<!-- Zone Summary -->
<div class="panel panel-info">

	<div class="panel-heading">
		<h2 style="font-weight: bold" class="panel-title">
			<span class="glyphicon glyphicon-home"></span> | Zone Summary
			<a href="statistics"><span class="glyphicon glyphicon-option-horizontal pull-right"></span></a>
		</h2>	
	</div>

	<div class="panel-body" style="overflow: auto">
		<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"> <!-- left -->

			<table class="table table-hover table-condensed table-bordered">
				<tbody>
					<tr>
						<th>Condition</th>
						@if ($condition == 'Normal')
							<td class="success">{{ $condition }}</td>
						@else
							<td class="danger"><a href="alerts" style="display:block; text-decoration: none">{{ $condition }}</a></td>
						@endif			
					</tr>
					<tr>
						<th>Total Parameters</th>
						<td>{{ $parameters->count() }}</td>
					</tr>
					<tr>
						<th>Current Temperature</th>
						@if ($current_temp != 0) <!-- show current temperature -->
							<td>{{ $current_temp }} °C</td>
						@else
							<td>---</td>
						@endif
					</tr>
					<tr>
						<th>Current Humidity</th>
						@if ($current_hum != 0) <!-- show current temperature -->
							<td>{{ $current_hum }} %</td>
						@else
							<td>---</td>
						@endif
					</tr>
				</tbody>
			</table>

			<table class="table table-hover table-condensed table-bordered">
				<tbody>
					<tr>
						<th style="width: 5%">#</th>
						<th style="width: 15%">Parameter Name</th>
						<th>Description</th>
					</tr>

					<?php $count = 1; ?>
					@forelse ($parameters as $param)
					<tr>
						<td>{{ $count }}</td>
						<td>{{ $param->parameter_name }}</td>
						<td>{{ $param->parameter_description }}</td>
					</tr>
					<?php $count++; ?>
					@empty
					<tr>
						<td>---</td>
						<td>---</td>
						<td>---</td>
					</tr>
					@endforelse
				</tbody>
			</table>
		</div>
		<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"> <!-- right -->
			<h4>Temperature °C</h4>
			<div id="canvas-holder">
				<canvas id="temperatureChart" height="120"/>
			</div>
			<?php $count = 0?>
			<p>
				@foreach ($tempParam as $p)
					@if ($count == 0)
					<strong style="color:rgba(151,187,205,1);">&#9632; {{ App\Parameter::find($p)->parameter_name }} </strong>&nbsp;
					@elseif ($count == 1)
					<strong style="color:rgba(220,220,220,1);">&#9632; {{ App\Parameter::find($p)->parameter_name }} </strong>&nbsp;
					@elseif ($count == 2)
					<strong style="color:rgba(128,255,128,1);">&#9632; {{ App\Parameter::find($p)->parameter_name }} </strong><br>
					@elseif ($count == 3)
					<strong style="color:rgba(217,102,255,1);">&#9632; {{ App\Parameter::find($p)->parameter_name }} </strong>&nbsp;
					@elseif ($count == 4)
					<strong style="color:rgba(255,212,128,1);">&#9632; {{ App\Parameter::find($p)->parameter_name }} </strong>
					@endif
					<?php $count++ ?>
				@endforeach
			</P>
			<hr>
			<h4>Humidity %</h4>
			<div id="canvas-holder">
				<canvas id="humidityChart" height="120"/>
			</div>
			<?php $count = 0?>
			<p>
				@foreach ($humParam as $p)
					@if ($count == 0)
					<strong style="color:rgba(151,187,205,1);">&#9632; {{ App\Parameter::find($p)->parameter_name }} </strong>&nbsp;
					@elseif ($count == 1)
					<strong style="color:rgba(220,220,220,1);">&#9632; {{ App\Parameter::find($p)->parameter_name }} </strong>&nbsp;
					@elseif ($count == 2)
					<strong style="color:rgba(128,255,128,1);">&#9632; {{ App\Parameter::find($p)->parameter_name }} </strong><br>
					@elseif ($count == 3)
					<strong style="color:rgba(217,102,255,1);">&#9632; {{ App\Parameter::find($p)->parameter_name }} </strong>&nbsp;
					@elseif ($count == 4)
					<strong style="color:rgba(255,212,128,1);">&#9632; {{ App\Parameter::find($p)->parameter_name }} </strong>
					@endif
					<?php $count++ ?>
				@endforeach
			</P>
		</div>
	</div>
</div>

<!-- Alert Summary -->
<div class="panel panel-info">

	<div class="panel-heading">
		<h2 style="font-weight: bold" class="panel-title">
			<span class="glyphicon glyphicon-home"></span> | Alert Summary
			<a href="alerts"><span class="glyphicon glyphicon-option-horizontal pull-right"></span></a>
		</h2>	
	</div>

	<div class="panel-body" style="overflow: auto">
		<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"> <!-- left -->

			<table class="table table-hover table-condensed table-bordered">
				<tbody>
					<tr>
						<th>Total Warning</th>
						<td class="warning">{{ $warning }}</td>			
					</tr>
					<tr>
						<th>Total Critical</th>
						<td class="danger">{{ $critical }}</td>			
					</tr>
				</tbody>
			</table>

			<table class="table table-hover table-condensed table-bordered">
				<tbody>
					<tr>
						<th style="width: 5%">#</th>
						<th style="width: 75%">Alert Description</th>
						<th>Action Taken</th>
					</tr>

					<?php $count = 1; ?>
					@forelse ($alerts as $alert)
					<tr>
						<td>{{ $count }}</td>
						<td>{{ $alert->alert_description }}</td>
						<td>{{ $alert->action_taken }}</td>
					</tr>
					<?php $count++; ?>
					@empty
					<tr>
						<td>---</td>
						<td>---</td>
						<td>---</td>
					</tr>
					@endforelse
				</tbody>
			</table>

		</div>

		<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"> <!-- right -->
			<h4>Alert Occurrence</h4>
			<div id="canvas-holder">
				<canvas id="alertChart" height="120"/>
			</div>
		</div>
	</div>
</div>

<!-- Latest Records -->
<div class="panel panel-info">

	<div class="panel-heading">
		<h2 style="font-weight: bold" class="panel-title">
			<span class="glyphicon glyphicon-list-alt"></span> | Latest Records
			<a href="reports"><span class="glyphicon glyphicon-option-horizontal pull-right"></span></a>
		</h2>
	</div>

	<div class="panel-body" style="overflow: auto">
		<table class="table table-hover table-condensed table-bordered">
			<thead>
				<tr>
					<th style="width: 10%">#</th>
					<th style="width: 20%">Parameter Name</th>
					<th style="width: 20%">Reading Value</th>
					<th style="width: 15%">User</th>
					<th style="width: 15%">Status</th>
					<th style="width: 20%">Timestamp</th>
				</tr>
			</thead>

			<tbody>
				<?php $count = 1; ?>
				@forelse ($zone->parameter as $param)
				@if ($param->hasReadings())
				<?php $status = $param->threshold->getStatus($param->reading->last()->reading_value); ?>
				<?php 
					$td_class = "";
					if ($status == 'Critical') {
						$td_class = "danger";
					} elseif ($status == 'Warning') {
						$td_class = "warning";
					}
				?>
				<td class="{{$td_class}}">{{ $count }}</td>
				<td class="{{$td_class}}">{{ $param->parameter_name }} </td>
				<!-- <td class="{{$td_class}}">{{ $param->reading->last()->reading_value }} </td> -->
				<td class="{{$td_class}}">{{ ($param->reading->last()->isBoolean()) ? $param->reading->last()->getBooleanStatus() : $param->reading->last()->reading_value }} </td>
				<td class="{{$td_class}}">{{ $param->reading->last()->user->name }} </td>
				<td class="{{$td_class}}">{{ $status }}</td>
				<td class="{{$td_class}}">{{ $param->reading->last()->created_at }} </td>
				</tr>
				<?php $count++; ?>
				@endif
				@empty
				<tr><td  colspan="6">No readings to display here</td></tr>
				@endforelse  
			</tbody>
		</table>
	</div>

</div>
@stop

@section('scripts')
<script type="text/javascript">

// temperature chart
var ctx = document.getElementById("temperatureChart").getContext("2d");
var data = {
    labels: <?php echo "[".implode($labels, ",")."]" ?>,
    datasets: [
        {
        	label: "My first dataset",
            fillColor: "rgba(151,187,205,0.2)",
            strokeColor: "rgba(151,187,205,1)",
            pointColor: "rgba(151,187,205,1)",
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(151,187,205,1)",    
            @if (count($temperatureChartData) >= 1)
            	data: <?php echo "[".implode($temperatureChartData[0], ",")."]"; ?>
            @endif
        },
        {
            label: "My second dataset",
            fillColor: "rgba(220,220,220,0.2)",
            strokeColor: "rgba(220,220,220,1)",
            pointColor: "rgba(220,220,220,1)",
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            @if (count($temperatureChartData) >= 2)
            	data: <?php echo "[".implode($temperatureChartData[1], ",")."]"; ?>
            @endif
        },
        {
            label: "My third dataset",
            fillColor: "rgba(128, 255, 128,0.2)",
            strokeColor: "rgba(128, 255, 128,1)",
            pointColor: "rgba(128, 255, 128,1)",
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(128, 255, 128,1)",
            @if (count($temperatureChartData) >= 3)
            	data: <?php echo "[".implode($temperatureChartData[2], ",")."]"; ?>
            @endif
        },
        {
            label: "My forth dataset",
            fillColor: "rgba(217, 102, 255,0.2)",
            strokeColor: "rgba(217, 102, 255,1)",
            pointColor: "rgba(217, 102, 255,1)",
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(217, 102, 255,1)",
            @if (count($temperatureChartData) >= 4)
            	data: <?php echo "[".implode($temperatureChartData[3], ",")."]"; ?>
            @endif
        },
        {
            label: "My fifth dataset",
            fillColor: "rgba(255, 212, 128,0.2)",
            strokeColor: "rgba(255, 212, 128,1)",
            pointColor: "rgba(255, 212, 128,1)",
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(255, 212, 128,1)",
            @if (count($temperatureChartData) >= 5)
            	data: <?php echo "[".implode($temperatureChartData[4], ",")."]"; ?>
            @endif
        }
    ]
};

var options = {
	scaleOverride: true,
	scaleSteps: 15,
	scaleStepWidth: 1,
	scaleStartValue: 15
};
var temp_chart = new Chart(ctx).Line(data, options);

// humidity chart
var ctx2 = document.getElementById("humidityChart").getContext("2d");
var data = {
    labels: <?php echo "[".implode($labels, ",")."]" ?>,
	datasets: [
	    {
	    	label: "My first dataset",
	        fillColor: "rgba(151,187,205,0.2)",
	        strokeColor: "rgba(151,187,205,1)",
	        pointColor: "rgba(151,187,205,1)",
	        pointStrokeColor: "#fff",
	        pointHighlightFill: "#fff",
	        pointHighlightStroke: "rgba(151,187,205,1)",    
	        @if (count($humidityChartData) >= 1)
	        	data: <?php echo "[".implode($humidityChartData[0], ",")."]"; ?>
	        @endif
	    },
	    {
	        label: "My second dataset",
	        fillColor: "rgba(220,220,220,0.2)",
	        strokeColor: "rgba(220,220,220,1)",
	        pointColor: "rgba(220,220,220,1)",
	        pointStrokeColor: "#fff",
	        pointHighlightFill: "#fff",
	        pointHighlightStroke: "rgba(220,220,220,1)",
	        @if (count($humidityChartData) >= 2)
	        	data: <?php echo "[".implode($humidityChartData[1], ",")."]"; ?>
	        @endif
	    },
	    {
	        label: "My third dataset",
	        fillColor: "rgba(128, 255, 128,0.2)",
	        strokeColor: "rgba(128, 255, 128,1)",
	        pointColor: "rgba(128, 255, 128,1)",
	        pointStrokeColor: "#fff",
	        pointHighlightFill: "#fff",
	        pointHighlightStroke: "rgba(128, 255, 128,1)",
	        @if (count($humidityChartData) >= 3)
	        	data: <?php echo "[".implode($humidityChartData[2], ",")."]"; ?>
	        @endif
	    },
	    {
	        label: "My forth dataset",
	        fillColor: "rgba(217, 102, 255,0.2)",
	        strokeColor: "rgba(217, 102, 255,1)",
	        pointColor: "rgba(217, 102, 255,1)",
	        pointStrokeColor: "#fff",
	        pointHighlightFill: "#fff",
	        pointHighlightStroke: "rgba(217, 102, 255,1)",
	        @if (count($humidityChartData) >= 4)
	        	data: <?php echo "[".implode($humidityChartData[3], ",")."]"; ?>
	        @endif
	    },
	    {
	        label: "My fifth dataset",
	        fillColor: "rgba(255, 212, 128,0.2)",
	        strokeColor: "rgba(255, 212, 128,1)",
	        pointColor: "rgba(255, 212, 128,1)",
	        pointStrokeColor: "#fff",
	        pointHighlightFill: "#fff",
	        pointHighlightStroke: "rgba(255, 212, 128,1)",
	        @if (count($humidityChartData) >= 5)
	        	data: <?php echo "[".implode($humidityChartData[4], ",")."]"; ?>
	        @endif
	    }
    ]
};
var options = {
	scaleOverride: true,
	scaleSteps: 12,
	scaleStepWidth: 5,
	scaleStartValue: 20
};
var hum_chart = new Chart(ctx2).Line(data, options);

// alert chart
var ctx3 = document.getElementById("alertChart").getContext("2d");
var data = {
    labels: <?php echo "[".implode($labels, ",")."]" ?>,
    datasets: [
        {
            label: "My first dataset",
            fillColor: "rgba(255, 255, 128,0.5)",
            strokeColor: "rgba(255, 255, 128,0.8)",
            highlightFill: "rgba(255, 255, 128,0.75)",
            highlightStroke: "rgba(255, 255, 128,1)",
            data: <?php echo "[".implode($alertChartWarningData, ",")."]"; ?>
        },
        {
            label: "My Second dataset",
            fillColor: "rgba(255, 153, 153,0.5)",
            strokeColor: "rgba(255, 153, 153,0.8)",
            highlightFill: "rgba(255, 153, 153,0.75)",
            highlightStroke: "rgba(255, 153, 153,1)",
            data: <?php echo "[".implode($alertChartCriticalData, ",")."]"; ?>
        }
    ]
};
var alert_chart = new Chart(ctx3).Bar(data);
</script>
@stop
