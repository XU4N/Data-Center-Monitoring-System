@extends('app')
@section('content')
<h1 class="page-header"> Dashboard </h1>
<div class="row placeholders">
	<?php $count = 0; ?>
	@foreach ($zones as $zone)
		@if(array_key_exists($zone->id, $alertsNeedingAttention->toArray()))
			<div class="col-xs-6 col-sm-3 placeholder">
				<a href="alerts" style="text-decoration: none">
					<img data-src="holder.js/200x200/auto/sky" class="img-responsive" src = "signal_critical.svg" data-holder-rendered = true alt="Generic placeholder thumbnail">
					@if ($current_temp[$count] != 0) <!-- show current temperature -->
						<div> {{ $current_temp[$count] }}°C</div>
					@endif
					<h4> {{ $zone->zone_name }} </h4>
					<span class="text-muted">Needs Attention</span>
				</a>
			</div>
		@elseif(array_key_exists($zone->id, $alertsInProgress->toArray()))
			<div class="col-xs-6 col-sm-3 placeholder">
				<a href="alerts" style="text-decoration: none">
					<img data-src="holder.js/200x200/auto/sky" class="img-responsive" src = "signal_warning.svg" data-holder-rendered = true alt="Generic placeholder thumbnail">
					@if ($current_temp[$count] != 0) <!-- show current temperature -->
						<div> {{ $current_temp[$count] }}°C</div>
					@endif
					<h4> {{ $zone->zone_name }} </h4>
					<span class="text-muted">In Progress</span>
				</a>
			</div>
		@else
		<div class="col-xs-6 col-sm-3 placeholder">
			<a href="details{{ $zone->id }}" style="text-decoration: none">
				<img data-src="holder.js/200x200/auto/sky" class="img-responsive" src = "signal_normal.svg" data-holder-rendered = true alt="Generic placeholder thumbnail">
				@if ($current_temp[$count] != 0) <!-- show current temperature -->
						<div> {{ $current_temp[$count] }}°C</div>
				@endif
				<h4> {{ $zone->zone_name }} </h4>
				<span class="text-muted">Normal</span>
			</a>
		</div>
		@endif
		<?php $count++; ?>
	@endforeach
</div>

@stop