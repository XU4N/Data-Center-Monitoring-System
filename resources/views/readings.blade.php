@extends('app')

@section('content')
<h1 class="page-header">Manage Recent Readings</h1>
<br/>

<div class="container">
	<div class="panel panel-info">
		<div class="panel-heading">
			<strong class="panel-title">List of Recent Readings</strong>
		</div>
		<div class="panel-body">
			<table class="table table-hover">
				<thead>
					<tr>
						<th>Zone Name</th>
						<th>Parameter Name</th>
						<th>Reading Value</th>
						<th>Timestamp</th>
					</tr>
				</thead>
				<tbody>
					@forelse ($records as $record)
					<tr>
						<td>
							{{ $record->parameter->zone->zone_name }}
						</td>
						<td>
							{{ $record->parameter->parameter_name }}
						</td>
						<td>
							{{ $record->reading_value }}
						</td>
						<td>
							{{ date("F d Y - g:i a",strtotime("$record->created_at")) }} 
							<button class="btn btn-sm btn-danger btn--remove pull-right" type="button"
							data-for="{{ $record->id }}" data-toggle="modal" data-target="#remove"><i class="glyphicon glyphicon-remove"></i></button>
						</button>&nbsp;
						</td>
					</tr>
					@empty
						<td span="4">There are no readings for today</td>
					@endforelse
				</tbody>
			</table>
		</div>
	</div>

	@if(Session::has('flash_message'))
	<div class="alert alert-success">
		<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		{{ Session::get('flash_message') }}
	</div>
	@endif
</div>

<!-- Remove Modal -->
<div id="remove" class="modal fade" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Remove Selected Reading</h4>
			</div>

			<div class="modal-body">
				<form class="form-horizontal" role="form" method="POST" action="/readings/" novalidate>
					<input type="hidden" name="_method" value="DELETE">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					Are you sure you want to remove selected reading ?
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

<script type="text/javascript">
$(function() {
	$('.btn--remove').on('click', function() {
		var userId = $(this).attr('data-for');
		var formAction = "/readings/" + userId;
		console.log(formAction);
		$('.form-horizontal').attr('action', formAction);
	});
});
</script>
@stop