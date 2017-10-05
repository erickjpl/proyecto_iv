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
            <label for="nombre">Nombre: <span class="requerido">(*)</span></label>
            <input type="text" class="form-control input-modal" id="nombre" name="nombre_user" placeholder="Nombre">
          </div>
          <div class="form-group">
            <label for="apellido">Apellido: <span class="requerido">(*)</span></label>
            <input type="text" class="form-control input-modal" id="apellido" name="apellido" placeholder="Apellido">
          </div>
          <div class="form-group">
            <label for="email">Email: <span class="requerido">(*)</span></label>
            <input type="email" class="form-control input-modal" id="email" name="email" placeholder="Email">
          </div>
          <div class="form-group">
            <label for="email">Documentación: <span class="requerido">(*)</span></label>
            <input type="text" class="form-control input-modal" id="identification_document" name="identification_document" placeholder="Identidad">
          </div>
          <div class="form-group">
            <label for="perfil">Ocupación: <span class="requerido">(*)</span></label>
            <select class="form-control input-modal" id="occupation" name="occupation"></select>
          </div>
          <div class="form-group form-perfil">
            <label for="perfil">Perfil: <span class="requerido">(*)</span></label>
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


<!--modal_confirmacion-->
<div class="modal fade" id="modal_estatus_usuario" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="oper_titulo">Confirmación!</h4>
      </div>
      <div class="modal-body">
        <p class="bold">
          ¿Desea cambiar el estatus de este usuario?
        </p>
        <div class="form-group">
            <div class="row">
            <div class="col-xs-6 bold">
              Notificacion por Email: <span class="requerido">(*)</span>
            </div>
            <div class="col-xs-6">
                <select class="form-control" id="email_notif" name="email_notif">
                  <option value="0" selected>No</option>
                  <option value="1">Si</option>
              </select>
            </div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal" >Cerrar</button>
        <button type="button" class="btn btn-primary" data-dismiss="modal" id="setestatus_user" >Aceptar</button>
      </div>
    </div>
  </div>
</div>