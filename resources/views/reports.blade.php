@extends('app')
@section('header')
<link href="css/reports.css" rel="stylesheet">
<link href="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/build/css/bootstrap-datetimepicker.css" rel="stylesheet">
<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.10.10/css/dataTables.bootstrap.min.css" rel="stylesheet">
@stop
@section('content')
<h1 class="page-header">Reports Archive</h1>     

  <?php
    $zone_select = array();
    foreach ($zones as $zone) {
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

<div class="col-md-6">
<form class="form-horizontal" action="{{ url('/reports') }}" method="POST">
{!! csrf_field() !!}
  <div class="form-group">
    <label class="control-label">Zone</label>
    <select class="form-control" id="zone" name="zone">
      @foreach($zone_select as $z)
      <option value="{{$z['zone_id']}}"   {{ ( old('zone') == $z['zone_id'] ? 'selected' : NULL ) }}>{{$z['zone_name']}}</option>
      @endforeach
    </select>
  </div>
  <div class="form-group">
    <label class="control-label">Parameter</label>
    <select class="form-control" id="sensor" name="sensor">
    </select>
  </div>
  <div class="form-group">
     <label class="control-label">Start Date</label>
     <div class='input-group date' id='datePicker_1'>
      <input type='text' class="form-control" name="start_date" value="{{ old('start_date') }}"/>
      <span class="input-group-addon">
          <span class="glyphicon glyphicon-calendar"></span>
      </span>
    </div>
  </div>
  <div class="form-group">
     <label class="control-label">End Date</label>
     <div class='input-group date' id='datePicker_2'>
      <input type='text' class="form-control" name="end_date" value="{{ old('end_date') }}"/>
      <span class="input-group-addon">
          <span class="glyphicon glyphicon-calendar"></span>
      </span>
    </div>
  </div>
  <div class="form-group">
<!--       <button class="btn btn-primary btn-block">Search</button>
      <button class="btn btn-primary btn-block" id="exportButton">Export</button> -->
      <div>
        <div class="btn-group">
          <button type="submit" class="btn btn-primary" id="submitButton">Search</button>
          <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="caret"></span>
            <span class="sr-only">Toggle Dropdown</span>
          </button>
          <ul class="dropdown-menu">
            <li><a id="exportToggle">Export to Excel</a></li>
            <li><a id="searchToggle">Search</a></li>
          </ul>
        </div>
      </div>
  </div>
</form>
</div>


@if ($current_zone)  
    <div class="col-xs-12 col-md-12" style="overflow:auto" id="zone_{{$current_zone->id}}">
      <h2> {{ $current_zone->zone_name }}  </h2>
      <br/>
      <table class="table table-striped table-hover" id="reportTable">
            <thead>
              <tr>
                <th>Sensor
                <th>Reading Value</th>
                <th>User</th>
                <th>Status</th>
                <th>Timestamp</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($current_readings as $reading)
              <tr>
                <td>{{ $reading->parameter->parameter_name }}</td>
                <td>{{ $reading->reading_value }} </td>
                <td>{{ $reading->user->name }} </td>
                <td>{{ $reading->parameter->threshold->getStatus($reading->reading_value) }} </td>
                <td>{{ date("F d Y - g:i a",strtotime("$reading->created_at")) }} </td>
              </tr>
              @empty
              <tr><td  colspan="6">No parameters</td></tr>
              @endforelse
            </tbody>
        </table>
    </div>
@endif
@stop


@stop

@section('scripts')
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
<script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>
<script src="https://cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.10/js/dataTables.bootstrap.min.js" type="text/javascript"></script>
<script type="text/javascript">
    
    var zones = <?php echo json_encode($zone_select)  ?>;


    $('#datePicker_1').datetimepicker({
      format: 'YYYY-MM-DD'
    });
    $('#datePicker_2').datetimepicker({
        format: 'YYYY-MM-DD',
        useCurrent: false //Important! See issue #1075
    });
    $("#datePicker_1").on("dp.change", function (e) {
        $('#datePicker_2').data("DateTimePicker").minDate(e.date);
    });
    $("#datePicker_2").on("dp.change", function (e) {
        $('#datePicker_1').data("DateTimePicker").maxDate(e.date);
    });

    populateZone(parseInt($("#zone option:selected" ).val()));
    <?php if(old('sensor')) {  echo  "$('#sensor').val('".old("sensor")."');"; } ?>

    $('#zone').change(function () {
      populateZone(parseInt($(this).val()))
    });

    function populateZone(zone){
      console.log(zones[zone]);
       //var params = parameters[zone];
      $("#sensor").empty();
      if(zones[zone].parameters.length > 0){
        $.each(zones[zone].parameters, function(index, value) {
          $("#sensor").append("<option value=\""+value.id+"\">" + value.parameter_name + "</option>");
        });
      } else {
        $("#sensor").append("<option disabled>No parameters</option>");
      } 
    }

    $(function() {
        $('#exportToggle').on('click', function() {
            $('#submitButton').html('Export To Excel');
            $('.form-horizontal').attr('action', '/reports/export');
        });

        $('#searchToggle').on('click', function() {
            $('#submitButton').html('Search');
            $('.form-horizontal').attr('action', '/reports');
        });
    });
</script>

<script>
$(document).ready(function() {
    $('#reportTable').DataTable();
} );
</script>
@stop

