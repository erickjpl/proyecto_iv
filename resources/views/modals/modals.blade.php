<!--Registro de Usuarios-->
<div class="modal fade" id="modalRegistry" tabindex="-1" role="dialog" aria-labelledby="registryLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="registryLabel">Registrar Usuarios</h4>
      </div>
      <div class="modal-body">
        <form id="form-create-user">
          <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" class="form-control input-modal" id="nombre" name="nombre_user" placeholder="Nombre">
          </div>
          <div class="form-group">
            <label for="apellido">Apellido</label>
            <input type="text" class="form-control input-modal" id="apellido" name="apellido" placeholder="Apellido">
          </div>
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control input-modal" id="email" name="email" placeholder="Email">
          </div>
          <div class="form-group">
            <label for="perfil">Perfil</label>
            <select class="form-control input-modal" id="perfil" name="perfil"></select>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal" >Cerrar</button>
        <button type="button" class="btn btn-primary modalsave" id="saveuser">Guardar</button>
      </div>
      </form>
    </div>
  </div>
</div>

<!--modal_operacion-->
<div class="modal fade" id="modal_operacion" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="oper_titulo">Operación!</h4>
      </div>
      <div class="modal-body">
        <p class="oper_mensaje"></p>
        <p id="oper_error"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal" >Cerrar</button>
      </div>
    </div>
  </div>
</div>


<!--modal_confirmacion-->
<div class="modal fade" id="modal_confirmacion" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="oper_titulo">Confirmación!</h4>
      </div>
      <div class="modal-body">
        <p class="oper_mensaje"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal" >No</button>
        <button type="button" class="btn btn-primary" data-dismiss="modal" id="go_oper" >Si</button>
      </div>
    </div>
  </div>
</div>