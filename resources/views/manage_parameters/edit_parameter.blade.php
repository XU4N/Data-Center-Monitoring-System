<!-- Modal -->
<div class="modal fade" id="editParam" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Edit parameter</h4>
      </div>
      {!! Form::model($parameter, ["id" => "editParameter", "method" => "PATCH", "url" => "parameters/", "class" => "reading-form"]) !!}
        @include('manage_parameters._form', ['submitButtonText' => "Update"])
      {!! Form::close() !!}
    </div>
  </div>
</div>