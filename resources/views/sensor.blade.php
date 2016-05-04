@extends('app')

@section('content')
<h1 class="page-header">Realtime Sensor Readings (Last 10 Minutes)</h1>
<div class="container">
	<div class="row">
		<div class="col-xs-sm-12 col-sm-12">
			<canvas id="myChart" class="col-xs-12 col-sm-12 col-md-12 col-lg-12"></canvas>
		</div>
	</div>

	<div class="row">
		<div>
			<div style="color:rgba(47,121,185,1); text-align: center;">&#9632; Temperature</div>
			<div style="color:rgba(151,187,205,1); text-align: center;">&#9632; Humidity</div>
		</div>
	</div>
</div>
@stop

@section('scripts')
<script src="chart/Chart.js"></script>
<script>
$(document).ready(function() {
	var ctx = document.getElementById("myChart").getContext("2d");
 	var data = {
 	    labels: <?php echo "[".implode($count, ",")."]" ?>,
 	    datasets: [
 	        {
 	            label: "Temperature",
	            fillColor: "rgba(47,121,185,0.2)",
	            strokeColor: "rgba(47,121,185,1)",
	            pointColor: "rgba(220,220,220,1)",
	            pointStrokeColor: "#fff",
	            pointHighlightFill: "#fff",
	            pointHighlightStroke: "rgba(220,220,220,1)",
	            data: <?php echo "[".implode($temperature, ",")."]" ?>
	        },
	        {
	            label: "Humidity",
	            fillColor: "rgba(151,187,205,0.2)",
	            strokeColor: "rgba(151,187,205,1)",
	            pointColor: "rgba(151,187,205,1)",
	            pointStrokeColor: "#fff",
	            pointHighlightFill: "#fff",
	            pointHighlightStroke: "rgba(151,187,205,1)",
	            data: <?php echo "[".implode($humidity, ",")."]" ?>
	        }
	    ]
	};
	var myNewChart = new Chart(ctx).Bar(data, {
		responsive: true,
		legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].fillColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>"
	});

	var legend = myNewChart.generateLegend();
	$('#myChart').append(legend);
});
</script>
@stop