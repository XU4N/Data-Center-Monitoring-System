<div class="modal-body">
    <label>Parameter Name</label>
    <input class="form-control param" name="parameter_name"/>

    <label>Parameter Description</label>
    <input class="form-control description" name="parameter_description"/>

    <label>Type</label>
    <select class="form-control" name="parameter_type">
      <option value="range">Range</option>
      <option value="boolean">Boolean</option>
    </select>

    <label>Zone</label>
    <select class="form-control" name="zone_id">
      @foreach ($zones as $zone)
      <option value="{{$zone->id}}">{{$zone->zone_name}}</option>
      @endforeach
    </select>

    <label>Threshold Type</label>
    <select class="form-control" name="threshold_id">
      @foreach ($thresholds as $threshold)
      <option value="{{$threshold->id}}">{{$threshold->threshold_category}}</option>
      @endforeach
    </select>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
  <button type="submit" class="btn btn-success">{{$submitButtonText}}</button>
</div>