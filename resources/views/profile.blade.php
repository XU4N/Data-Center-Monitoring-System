@extends('app')
@section('content')
<h1 class="page-header">Account Information</h1>


<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 col-md-offset-2 col-lg-offset-2">
<div class="panel panel-info">
  <div class="panel-heading">
    <strong class="panel-title">{{Auth::user()->name}}</strong>
  </div>

  <div class="panel-body" style="overflow:auto">
    <table class="table table-hover">
      <br/>
      <tbody>
        <tr>
          <td>Name:</td>
          <td>{{Auth::user()->name}}</td>
        </tr>
        <tr>
          <td>Role:</td>
          <td>{{Auth::user()->role->role_description}}</td>
        </tr>
        <tr>
          <td>Username/Email:</td>
          <td><a href="mailto:egenting@support.com">{{Auth::user()->email}}</a></td>
        </tr>
        <tr>
          <td>Phone Number:</td>
          <td>{{Auth::user()->mobile}}</td>
        </tr>
<!--               <tr>
            <td>Office Extension:</td>
            <td>[ TO IMPLEMENT ]</td>
          </tr> -->
        </tbody>
      </table>
    </div>

    <div class="panel-footer">
      <!-- Trigger the modal with a button -->
      <button type="button" class="btn btn-primary btn-md" data-toggle="modal" data-target="#update">Update Profile Info</button>
    </div>
  </div>
  @if(Session::has('flash_message'))
  <div class="alert alert-success">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    {{ Session::get('flash_message') }}
  </div>
  @endif
</div>

@stop

@section('modal_form')
<div class="col-sm-offset-1 col-sm-2">

  <!-- Modal -->
  <div id="update" class="modal fade" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Update Info</h4>
        </div>

        <form class="form-horizontal" role="form" method="POST" action="/profile/" novalidate>
          <input type="hidden" name="_method" value="PUT">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">

          <div class="modal-body">
            <label class="control-label" for="name">Username:</label>
            <div class="@if ($errors->has('name')) has-error @endif"> 
              <input type="text" class="form-control" type="hidden" id="name" name="name" placeholder="Enter username">
              @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
            </div>

            <label class="control-label" for="password">Password:</label>
            <div class="@if ($errors->has('password')) has-error @endif"> 
              <input type="password" class="form-control" type="hidden" id="password" name="password" placeholder="Enter login password" 
              onkeypress="chkPassword(event)">
              @if ($errors->has('password')) <p class="help-block">{{ $errors->first('password') }}</p> @endif
            </div>

            <label class="control-label" for="password_confirm">Confirm Password:</label>
            <div class="@if ($errors->has('password_confirm')) has-error @endif"> 
              <input type="password" class="form-control" type="hidden" id="password_confirm" name="password_confirm" placeholder="Re-type password again" 
              onkeypress="chkPwConfirm(event)">
              @if ($errors->has('password_confirm')) <p class="help-block">{{ $errors->first('password_confirm') }}</p> @endif
            </div>

            <label class="control-label" for="email">Email:</label>
            <div class="@if ($errors->has('email')) has-error @endif"> 
              <input type="email" class="form-control" type="hidden" id="email" name="email" placeholder="Enter email address">
              @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
            </div>

            <label class="control-label" for="mobile">Phone Number:</label>
            <div class="@if ($errors->has('mobile')) has-error @endif"> 
              <input type="hpnum" class="form-control" type="hidden" id="mobile" name="mobile" placeholder="Enter handphone number">
              @if ($errors->has('mobile')) <p class="help-block">{{ $errors->first('mobile') }}</p> @endif
            </div>

            <!--<div class="form-group">
              <label class="control-label col-sm-3" for="officeEx">Office Extension:</label>
              <div class="col-sm-5"> 
                <input type="officeEx" class="form-control" id="officeEx" placeholder="Enter office extension">
              </div>
            </div> --> 

            <label class="control-label" for="role_id">Role:</label>
            <select class="form-control" type="hidden" id="role_id" name="role_id">
              @foreach ($roles as $role)
              <option value="{{ $role->id }}">{{ $role->role_description }}</option>
              @endforeach
            </select>       
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success btn--edit" data-for="{{ Auth::user()->id }}">Update</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@stop

@section('scripts')
<script type="text/javascript">
$('div.alert').delay(3000).slideUp(300);

@if (count($errors) > 0)
$('#update').modal('show');
@endif

$(function() {
  $('.btn--edit').on('click', function() {
    var userId = $(this).attr('data-for');
    var formAction = "/profile/" + userId;
    console.log(formAction);
    $('.form-horizontal').attr('action', formAction);
  });
});
</script>

<script type="text/javascript">
function chkPassword(e) {
  var myKeyCode = e.which ? e.which : ( e.keyCode ? e.keyCode : ( e.charCode ? e.charCode : 0 ) );
  var myShiftKey = e.shiftKey || ( e.modifiers && ( e.modifiers & 4 ) );
  var charStr = String.fromCharCode(myKeyCode);
  if ( ( ( myKeyCode >= 65 && myKeyCode <= 90 ) && !myShiftKey ) || ( ( myKeyCode >= 97 && myKeyCode <= 122 ) && myShiftKey ) ) {
    $('#password').tooltip({title : "Caps Lock is On!", placement:'top', trigger:'manual'})
    $('#password').tooltip('show');
  }
  else
    $('#password').tooltip('hide');
}

function chkPwConfirm(e) {
  var myKeyCode = e.which ? e.which : ( e.keyCode ? e.keyCode : ( e.charCode ? e.charCode : 0 ) );
  var myShiftKey = e.shiftKey || ( e.modifiers && ( e.modifiers & 4 ) );
  var charStr = String.fromCharCode(myKeyCode);
  if ( ( ( myKeyCode >= 65 && myKeyCode <= 90 ) && !myShiftKey ) || ( ( myKeyCode >= 97 && myKeyCode <= 122 ) && myShiftKey ) ) {
    $('#password_confirm').tooltip({title : "Caps Lock is On!", placement:'top', trigger:'manual'})
    $('#password_confirm').tooltip('show');
  }
  else
    $('#password_confirm').tooltip('hide');
}
</script>
@stop