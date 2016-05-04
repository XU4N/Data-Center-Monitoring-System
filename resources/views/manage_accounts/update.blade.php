<div class="col-sm-offset-1 col-sm-2">

  <!-- Modal -->
  <div id="update" class="modal fade" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Update User Details</h4>
        </div>

        <form class="form-horizontal" role="form" method="POST" action="/manage_accounts/" novalidate>
          <input type="hidden" name="_method" value="PUT">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <div class="modal-body">

            <label class="control-label" for="name">Username:</label>
            <input type="text" class="form-control" type="hidden" id="name" name="name" placeholder="Enter username">

            <label class="control-label" for="password">Password:</label>
            <input type="password" class="form-control" type="hidden" id="pw" name="password" placeholder="Enter login password" 
            onkeypress="chkUpdatePw(event)">

            <label class="control-label" for="password_confirm">Confirm Password:</label>
            <input type="password" class="form-control" type="hidden" id="pw_confirm" name="password_confirm" placeholder="Re-type password again" 
            onkeypress="chkUpdatePwConfirm(event)">
            


            <label class="control-label" for="email">Email:</label>
            <input type="email" class="form-control" type="hidden" id="email" name="email" placeholder="Enter email address">

            <label class="control-label" for="mobile">Phone Number:</label>
            <input type="hpnum" class="form-control" type="hidden" id="mobile" name="mobile" placeholder="Enter handphone number">

            <!--<div class="form-group">
              <label class="control-label col-sm-3" for="officeEx">Office Extension:</label>
              <div class="col-sm-5"> 
    	          <input type="officeEx" class="form-control" id="officeEx" placeholder="Enter office extension">
              </div>
            </div> --> 

            <label class="control-label" for="role_id">Role:</label>
            <select class="form-control" type="hidden" id="role_id" name="role_id">
              @foreach ($roles as $role)
              @if($role->id != 1)
              <option value="{{ $role->id }}">{{ $role->role_description }}</option>
              @endif
              @endforeach
            </select>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success">Update</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>