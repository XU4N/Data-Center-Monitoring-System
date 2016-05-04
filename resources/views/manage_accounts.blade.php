@extends('app')
@section('content')
<h1 class="page-header">Manage Accounts</h1>
<br/>

@if($errors->has())
<div class="alert alert-danger col-xs-8 col-sm-8 col-md-8 col-lg-8 col-xs-offset2 col-sm-offset-2 col-md-offset-2 col-lg-offset-2">
  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  @foreach ($errors->all() as $error)
  <li>{{ $error }}</li>
  @endforeach
</div>
@endif

@if(Session::has('flash_message'))
<div class="alert alert-success col-xs-8 col-sm-8 col-md-8 col-lg-8 col-xs-offset-2 col-sm-offset-2 col-md-offset-2 col-lg-offset-2">
  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  {{ Session::get('flash_message') }}
</div>
@endif

<!-- Trigger the modal with a button -->
<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8 col-xs-offset-2 col-sm-offset-2 col-md-offset-2 col-lg-offset-2">
  <button style="float: right; margin-bottom:15px" type="button" class="btn btn-primary btn-sm btn--create" data-toggle="modal" data-target="#register">
    <i class="glyphicon glyphicon-plus" data-toggle="tooltip" title="Register a new user"></i>
  </button>
</div>

<div class="well col-xs-8 col-sm-8 col-md-8 col-lg-8 col-xs-offset-2 col-sm-offset-2 col-md-offset-2 col-lg-offset-2">

  @foreach ($users as $user)
  <div class="row user-row">
    <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
      <h5 style="font-weight: bold; {{ ($user->isActive())? "" : "color: grey" }}">{{ $user->name }} {{ ($user->isActive())? "" : "(Inactive)" }}</h5>
    </div>
    <div style="cursor: pointer" class="col-xs-8 col-sm-9 col-md-10 col-lg-10 dropdown-user" data-for=".{{ $user->id }}">
      <h5 class="glyphicon glyphicon-chevron-down text-muted pull-right">&nbsp;</h5>
    </div>
  </div>
  <div class="row user-infos {{ $user->id }}">
    <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10 col-xs-offset-0 col-sm-offset-0 col-md-offset-1 col-lg-offset-1">
      <div class="panel panel-info">
        <div class="panel-heading">
          <h2 class="panel-title">User Information</h2>
        </div>
        <div class="panel-body">
          <div class="row">
            <div class=" col-md-10 col-lg-10">
              <div class="col-xs-5">User level:</div><div class="col-xs-5"> {{ $user->role->role_description }}</div>
              <div class="col-xs-5">Email:</div> <div class="col-xs-5"> {{ $user->email }}</div>
              <div class="col-xs-5">Phone number: </div> <div class="col-xs-5"> {{ $user->mobile }} </div>
              <!-- <div class="col-xs-5">Office extension: </div> <div class="col-xs-5"> [ TO IMPLEMENT ]</div> -->
            </div>
          </div>
        </div>

        @if ($user->isActive())
        <div class="panel-footer">
          <button class="btn btn-sm btn-default btn--edit" type="button" 
          data-toggle="modal" data-target="#update" data-for="{{$user->id}}" data-username="{{$user->name}}" data-email="{{$user->email}}" data-mobile="{{$user->mobile}}">
          <i class="glyphicon glyphicon-edit" data-toggle="tooltip" title="Update {{ $user->name }}'s Details"></i></button>
          
          @if (Auth::user()->name != $user->name)
          <span class="pull-right">
            <button class="btn btn-sm btn-danger btn--remove" type="button" data-toggle="modal" data-target="#remove" data-for="{{$user->id}}">
              <i class="glyphicon glyphicon-remove" data-toggle="tooltip" title="Remove {{ $user->name }}"></i>
            </button>
          </span>
          @endif
        </div>
        @endif
      </div>
    </div>
  </div> 
  @endforeach   		    
</div> 

@include('manage_accounts.create')
@include('manage_accounts.update')

<!-- Modal -->
<div id="remove" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Remove Selected User</h4>
      </div>

      <div class="modal-body">
        <form class="form-horizontal" role="form" method="POST" action="/manage_accounts/" novalidate>
          <input type="hidden" name="_method" value="DELETE">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          Are you sure you want to remove selected user ?
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
$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip(); 
});

$(document).ready(function() {
  var panels = $('.user-infos');
  var panelsButton = $('.dropdown-user');
  panels.hide();

//Click dropdown
panelsButton.click(function() {
//Get data-for attribute
var dataFor = $(this).attr('data-for');
var idFor = $(dataFor);

//Current button
var currentButton = $(this);
idFor.slideToggle(400, function() {
//Completed slidetoggle
if(idFor.is(':visible'))
{
  currentButton.html('<h5 class="glyphicon glyphicon-chevron-up text-muted pull-right">&nbsp;</h5>');
}
else
{
  currentButton.html('<h5 class="glyphicon glyphicon-chevron-down text-muted pull-right">&nbsp;</h5>');
}
});
});
});

$('div.alert').delay(5000).slideUp(300);
</script>

<script type="text/javascript">
$(function() {
  $('.btn--edit').on('click', function() {
    var userId = $(this).attr('data-for');
    var formAction = "/manage_accounts/" + userId;
    $('.form-horizontal').attr('action', formAction);
  });

  $('.btn--create').on('click', function() {
    var formAction = "/manage_accounts";
    $('.form-horizontal').attr('action', formAction);
  });


  $('.btn--remove').on('click', function() {
    var userId = $(this).attr('data-for');
    var formAction = "/manage_accounts/" + userId;
    console.log(formAction);
    $('.form-horizontal').attr('action', formAction);
  });

  $('#update').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Button that triggered the modal
    var username = button.data('username'); // Extract info from data-* attributes
    var email = button.data('email');
    var mobile = button.data('mobile');


    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
    var modal = $(this)
    modal.find('.modal-title').text("Edit " + username + "'s Details");
    modal.find('#name').val(username);
    modal.find('#email').val(email);
    modal.find('#mobile').val(mobile);

  });

});
</script>

<script type="text/javascript">
function chkCreatePw(e) {
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

function chkCreatePwConfirm(e) {
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

function chkUpdatePw(e) {
  var myKeyCode = e.which ? e.which : ( e.keyCode ? e.keyCode : ( e.charCode ? e.charCode : 0 ) );
  var myShiftKey = e.shiftKey || ( e.modifiers && ( e.modifiers & 4 ) );
  var charStr = String.fromCharCode(myKeyCode);
  if ( ( ( myKeyCode >= 65 && myKeyCode <= 90 ) && !myShiftKey ) || ( ( myKeyCode >= 97 && myKeyCode <= 122 ) && myShiftKey ) ) {
    $('#pw').tooltip({title : "Caps Lock is On!", placement:'top', trigger:'manual'})
    $('#pw').tooltip('show');
  }
  else
    $('#pw').tooltip('hide');
}

function chkUpdatePwConfirm(e) {
  var myKeyCode = e.which ? e.which : ( e.keyCode ? e.keyCode : ( e.charCode ? e.charCode : 0 ) );
  var myShiftKey = e.shiftKey || ( e.modifiers && ( e.modifiers & 4 ) );
  var charStr = String.fromCharCode(myKeyCode);
  if ( ( ( myKeyCode >= 65 && myKeyCode <= 90 ) && !myShiftKey ) || ( ( myKeyCode >= 97 && myKeyCode <= 122 ) && myShiftKey ) ) {
    $('#pw_confirm').tooltip({title : "Caps Lock is On!", placement:'top', trigger:'manual'})
    $('#pw_confirm').tooltip('show');
  }
  else
    $('#pw_confirm').tooltip('hide');
}
</script>
@stop