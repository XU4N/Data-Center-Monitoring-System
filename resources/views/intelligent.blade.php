@extends('app')
@section('content')

<h1 class="page-header">Intelligent Prediction</h1>
<div class="container-fluid">
	<div class="row">
		<div class="col-lg-5 col-md-12 col-xs-sm-12 col-sm-12">
			<div class="container-fluid">
				<form>
					<div class="form-group">
						<label class="control-label">Monitoring Period</label>
						<input class="form-control" type="text" name="zone" value ="{{ $monitoring_period_description }}" readonly></input>
					</div>

					<div class="form-group">
						<label class="control-label">Zone</label>
						<input class="form-control" type="text" name="zone" value ="Zone 1" readonly></input>
					</div>

					<div class="form-group">
						<label class="control-label">Ideal Temperature</label>
						<input class="form-control" type="text" name="target_temperature" value ="{{$default_temperature}} degrees" readonly></input>
					</div>

					<div class="form-group">
						<label class="control-label">Temperature Difference</label>
						<input class="form-control" type="text" name="temperature_delta" value ="{{ $temperature_delta }} {{ ($temperature_delta == 1)? 'degree' : 'degrees' }}" readonly></input>
					</div>

					<div class="form-group">
						<label class="control-label">Status</label>
						<textarea class="form-control" name="status" rows="3" readonly>{{$feedback}}</textarea>
					</div>

<!-- 					<div class="form-group">
						<label class="control-label">Counter</label>
						<textarea class="form-control" name="status" rows="3" readonly>{{$calculatedTrend}}</textarea>
					</div> -->

				</form>	
			</div>			
		</div>

		<div class="col-lg-7 col-md-12 col-xs-sm-12 col-sm-12">
			<canvas id="intelligentChart" class="col-xs-12 col-sm-12 col-md-12 col-lg-12"></canvas>
		</div>
	</div>
</div>

@stop('content')

@section('scripts')
<script src="chart/Chart.js"></script>
<script>
$(document).ready(function() {
	var delta = <?php echo $temperature_delta; ?>;
	var setFillColor = function() {
		if (delta >= 1.5 && delta < 2.8) {
			//warning (warm)
			return "rgba(255,165,0,0.2)";
		} else if (delta >= 2.8) {
			//danger (hot)
			return "rgba(205,38,38,0.2)";
		} else if (delta < -1.5) {
			//cold
			return "rgba(198,226,255,0.2)";
		} 
		return "rgba(200,200,200,0.2)";
	}

	var setStrokeColor = function() {
		if (delta >= 1.5 && delta < 2.8) {
			//warning (warm)
			return "rgba(255,140,0,1)";
		} else if (delta >= 2.8) {
			//danger (hot)
			return "rgba(178,39,39,1)";
		} else if (delta < -1.5) {
			//cold
			return "rgba(24,116,205,1)";
		} 
		return "rgba(200,200,200,0.2)";
	}

	var setPointColor = function() {
		if (delta >= 1.5 && delta < 2.8) {
			//warning (warm)
			return "rgba(238,130,0,1)";
		} else if (delta >= 2.8) {
			//danger (hot)
			return "rgba(205,38,38,1)";
		} else if (delta < -1.5) {
			//cold
			return "rgba(24,116,205,1)";
		} 
		return "rgba(133,133,133,1)";
	}

	var setPointStrokeColor = function() {
		if (delta >= 1.5 && delta < 2.8) {
			//warning (warm)
			return "#EE7600";
		} else if (delta >= 2.8) {
			//danger (hot)
			return "#B22222";
		} else if (delta < -1.5) {
			//cold
			return "#1874CD";
		} 
		return "#858585";
	}

	var setPointHighlightFill = function() {
		if (delta >= 1.5 && delta < 2.8) {
			//warning (warm)
			return "#FFA500";
		} else if (delta >= 2.8) {
			//danger (hot)
			return "#EE0000";
		} else if (delta < -1.5) {
			//cold
			return "#1E90FF";
		} 
		return "#858585";
	}

	var setPointHighlightStroke = function() {
		if (delta >= 1.5 && delta < 2.8) {
			//warning (warm)
			return "rgba(255,165,0,1)";
		} else if (delta >= 2.8) {
			//danger (hot)
			return "rgba(238,0,0,1)";
		} else if (delta < -1.5) {
			//cold
			return "rgba(30,144,255,1)";
		} 
		return "rgba(250,250,250,1)";
	}

	var ctx = $("#intelligentChart").get(0).getContext("2d");
	var data = {
	    labels: <?php echo "[".implode($legend, ",")."]" ?>,
	    datasets: [
	        {
	            label: "Readings For the Period",
	            fillColor: setFillColor(),
	            strokeColor: setStrokeColor(),
	            pointColor: setPointColor(),
	            pointStrokeColor: setPointStrokeColor(),
	            pointHighlightFill: setPointHighlightFill(),
	            pointHighlightStroke: setPointHighlightStroke(),
	            data: <?php echo "[".implode($readings->toArray(), ",")."]"; ?>
	        }
	    ]
	};
	var options = {
		scaleBeginAtZero : false
	};

	var myLineChart = new Chart(ctx).Line(data, options);
});
</script>
@stop('section')