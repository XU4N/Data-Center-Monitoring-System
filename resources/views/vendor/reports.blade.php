@extends('app')
@section('header')
<link href="css/reports.css" rel="stylesheet">
@stop
@section('content')

<h1 class="page-header">Reports Archive</h1>     
<div class="dropdown">
  <a style="font-weight:bold" id="drop5" href="#" class="btn btn-default  dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" role="button" aria-expanded="false">
    Zone Options: 
    <span class="caret"></span>
  </a>
  <ul id="menu2" class="dropdown-menu" role="menu" aria-labelledby="drop5">
    <?php $count = 1; ?>
    @foreach ($zones as $zone)
    <li role="presentation" class="{{ $count == 0 ?  'active' : '' }}">
      <a role="menuitem" tabindex="-1" href="#" data-target="#zone{{ $zone->id }}"> {{ $zone->zone_name }}</a></li>
      <?php $count++; ?>
      @endforeach
    </ul>
  </div>

  <div role="tabpanel">
    <!-- hide the below links-->
    <ul class="nav nav-tabs" style="display:none;" role="tablist" id="myTab">
      <?php $count = 1; ?>
      @foreach ($zones as $zone)
      <li role="presentation" class="{{ $count == 0 ?  'active' : '' }}"><a href="#zone{{ $zone->id }}" aria-controls="zone{{ $zone->id }}" role="tab" data-toggle="tab">{{ $zone->zone_name }}</a></li>
      <?php $count++; ?>
      @endforeach
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
      <?php $count = 1; ?>
      @foreach ($zones as $zone)
      <div role="tabpanel" class="tab-pane fade in {{ $count == 0 ?  'active' : '' }}" id="zone{{ $zone->id }}">
        <div class="col-md-12">
          <h2> {{ $zone->zone_name }}  </h2>
          <table class="table table-responsive table-hover table-bordered">
            <thead>
              <tr>
                <th style="vertical-align:middle">#</th>
                <th>
                  <div class="dropdown">
                    <a style="font-weight:bold" id="drop5" href="#" class="btn btn-default  dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" role="button" aria-expanded="false">
                      Parameter Name: 
                      <span class="caret"></span>
                    </a>
                    <ul id="menu2" class="dropdown-menu" role="menu" aria-labelledby="drop5">
                      @foreach ($zone->parameter as $param)
                      <li role="presentation">
                        <a class="btn btn-link" data-collapse-group="filters" data-target="#{{ $param->id }}" data-toggle="collapse">{{ $param->parameter_name }}</a></li>
                        @endforeach
                      </ul>
                    </div>
                  </th>
                  <th style="vertical-align:middle">Reading Value</th>
                  <th style="vertical-align:middle">User</th>
                  <th style="vertical-align:middle">Status</th>
                  <th style="vertical-align:middle">
                    <div class="dropdown" style="position:relative">
                      <a style="font-weight:bold" href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Timestamp
                        <span class="caret"></span>
                      </a>
                      <ul class="dropdown-menu">
                        <?php $validate = "default" ?>
                        <?php $records = App\Reading::sort()->get() ?> 
                        @foreach ($records as $record)
                        @if ($record->created_at->year != $validate)
                        <li>
                          <a class="trigger right-caret">
                            {{ $record->created_at->year }}
                          </a>
                          <ul class="dropdown-menu sub-menu">
                            <li><a href="#" data-collapse-group="filters" data-target="#{{ $record->created_at->year }}-1" data-toggle="collapse">January</a></li>
                            <li><a href="#" data-collapse-group="filters" data-target="#{{ $record->created_at->year }}-2" data-toggle="collapse">February</a></li>
                            <li><a href="#" data-collapse-group="filters" data-target="#{{ $record->created_at->year }}-3" data-toggle="collapse">March</a></li>
                            <li><a href="#" data-collapse-group="filters" data-target="#{{ $record->created_at->year }}-4" data-toggle="collapse">April</a></li>
                            <li><a href="#" data-collapse-group="filters" data-target="#{{ $record->created_at->year }}-5" data-toggle="collapse">May</a></li>
                            <li><a href="#" data-collapse-group="filters" data-target="#{{ $record->created_at->year }}-6" data-toggle="collapse">June</a></li>
                            <li><a href="#" data-collapse-group="filters" data-target="#{{ $record->created_at->year }}-7" data-toggle="collapse">July</a></li>
                            <li><a href="#" data-collapse-group="filters" data-target="#{{ $record->created_at->year }}-8" data-toggle="collapse">August</a></li>
                            <li><a href="#" data-collapse-group="filters" data-target="#{{ $record->created_at->year }}-9" data-toggle="collapse">September</a></li>
                            <li><a href="#" data-collapse-group="filters" data-target="#{{ $record->created_at->year }}-10" data-toggle="collapse">October</a></li>
                            <li><a href="#" data-collapse-group="filters" data-target="#{{ $record->created_at->year }}-11" data-toggle="collapse">November</a></li>
                            <li><a href="#" data-collapse-group="filters" data-target="#{{ $record->created_at->year }}-12" data-toggle="collapse">December</a></li>
                          </ul>
                        </li>
                        <?php $validate = $record->created_at->year ?>
                        @endif
                        @endforeach
                      </ul>
                    </div>
                  </th>
                </tr>
              </thead>

              <?php $validate = "default" ?>
              @foreach ($parameters as $param)
              @if ($param->zone_id == $zone->id)
              @foreach ($records as $record)
              @if ($record->created_at->year != $validate)
              <?php $month = 1; ?>
              @while ($month != 13)
              <?php $index = 1; ?>
              <tbody id="{{ $record->created_at->year }}-{{ $month }}" class="collapse">
                @foreach ($readings as $reading)
                @if ($reading->parameter_id == $param->id)
                @if ($reading->created_at->year == $record->created_at->year)
                @if ($reading->created_at->month == $month)
                <tr>
                  <td>{{ $index }}</td>
                  <td>{{ $reading->parameter->parameter_name }} </td>
                  <td>{{ $reading->reading_value }} </td>
                  <td>{{ $reading->user->name }} </td>
                  <td>{{ $reading->parameter->threshold->getStatus($reading->reading_value) }} </td>
                  <td>{{ $reading->created_at }} </td>
                </tr>
                <?php $index++; ?>
                @endif
                @endif
                @endif
                @endforeach
              </tbody>
            </div>
            <?php $month++; ?>
            @endwhile
            <?php $validate = $record->created_at->year ?>
            @endif
            @endforeach
            @endif
            @endforeach
          </div>
        </table>
      </div>
    </div>
    <?php $count++; ?>
    @endforeach
  </div>
</div>
@stop

@section('scripts')
<script>
$(function(){
  $(".dropdown-menu > li > a.trigger").on("click",function(e){
    var current=$(this).next();
    var grandparent=$(this).parent().parent();
    if($(this).hasClass('left-caret')||$(this).hasClass('right-caret'))
      $(this).toggleClass('right-caret left-caret');
    grandparent.find('.left-caret').not(this).toggleClass('right-caret left-caret');
    grandparent.find(".sub-menu:visible").not(current).hide();
    current.toggle();
    e.stopPropagation();
  });
  $(".dropdown-menu > li > a:not(.trigger)").on("click",function(){
    var root=$(this).closest('.dropdown');
    root.find('.left-caret').toggleClass('right-caret left-caret');
    root.find('.sub-menu:visible').hide();
  });
});
</script>
@stop