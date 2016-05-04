@extends('app')
@section('header')
<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.10.10/css/dataTables.bootstrap.min.css" rel="stylesheet">
@stop
@section('content')

<h1 class="page-header">View Log Information</h1>
<br/>

<div class="container-fluid">
    <div class="col-xs-12 col-md-12" style="overflow:auto">
        <table class="table table-hover" id="logTable">
            <thead>
                <tr>
                    <th>
                        User
                    </th>
                    <th>
                        Log Description
                    </th>
                    <th>
                        Timestamp
                    </th>
                </tr>
            </thead>

            <tbody>
                @foreach ($logs as $log)
                <tr>
                    <td>
                        {{ App\User::find($log->user_id)->name }}
                    </td>
                    <td>
                        {{ $log->log_description }}
                    </td>
                    <td>
                        {{ date("F d Y - g:i a",strtotime("$log->created_at")) }}
                    </td>
                </tr>
                @endforeach
            </tbody> 
        </table>
    </div>
</div>
@stop

@section('scripts')
<!-- Bootstrap Based Data Table Plugin Script-->
<script src="http://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.10/js/dataTables.bootstrap.min.js" type="text/javascript"></script>
<script>
$(document).ready(function() {
    $('#logTable').DataTable();
} );
</script>
@stop