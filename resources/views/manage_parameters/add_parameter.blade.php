<!-- Modal -->
<div class="modal fade" id="addParam" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Create New Parameter</h4>
      </div>
      {!! Form::open(["url" => "parameters", "class" => "reading-form"]) !!}
        @include('manage_parameters._form', ['submitButtonText' => "Create"])
      {!! Form::close() !!}
    </div>
  </div>
</div>