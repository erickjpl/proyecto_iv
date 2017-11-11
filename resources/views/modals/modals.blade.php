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
        <button type="button" class="btn btn-primary aceptar_confirmarcion" data-dismiss="modal" id="go_oper" >Si</button>
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


<!--modal_confirmacion-->
<div class="modal fade" id="modal_list_students" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Lista de Estudiantes</h4>
      </div>
      <div class="modal-body">
        <div id="list_students"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal" >Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!--Registro de Eventos-->
<div class="modal fade" id="modalRegistryEvents"  role="dialog" aria-labelledby="registryLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="registryLabel">Registrar Evento</h4>
      </div>
      <div class="modal-body">
        <form id="form-create-streaming">
            <div class="form-group">
              <label for="nombre">Curso: <span class="requerido">(*)</span></label>
              <select name="curso" id="curso" data-placeholder='Seleccione'  class="form-control chosen-select input-modal" >
              </select>
            </div>
            <div class="form-group">
              <label for="apellido">Descripción: <span class="requerido">(*)</span></label>
              <input type="text" class="form-control input-modal" id="descripcion" name="descripcion" placeholder="Descripcion">
            </div>
            <div class="form-group">
              <label for="email">Url: <span class="requerido">(*)</span></label>
              <div class="input-group url">
                <input type="text" class="form-control input-modal" id="url" name="url" placeholder="Url">
                 <div class="input-group-addon">
                      <span class="glyphicon glyphicon-link"></span>
                  </div>
                </div>
            </div>
            <div class="form-group">
                <label for="nombre">Fecha de Inicio: <span class="requerido">(*)</span></label>
                <div class="input-group date" data-provide="datepicker">
                  <input type="text" readonly class="no-edit form-control datepicker col-xs-12 input-modal"
                  id="fecha_inicio_streaming" name="fecha_inicio" placeholder="dd/mm/yyyy">
                  <div class="input-group-addon">
                      <span class="glyphicon glyphicon-th"></span>
                  </div>
                </div>
            </div>
            <div class="form-group">
                    <label for="nombre">Hora de Inicio: <span class="requerido">(*)</span></label>
                    <div class="input-group  clockpicker">
                    <input type="text" class="no-edit form-control clockpicker col-xs-12"
                    id="hora_inicio_streaming" readonly name="hora_inicio" placeholder="00:00:00">
                  <span class="input-group-addon">
                        <span class="glyphicon glyphicon-time"></span>
                    </span>
              </div>
            </div>
            <table class="table table-hover" id="list_students">
              <thead>
                <tr>
                  <th>Nombre</th>
                  <th>Email</th>
                </tr>
              </thead>
              <tbody id="tb_users">
                <tr id="tb_users_reg"><th colspan="2" class="tbl-registros" >No se encontraron registros</th></tr>
              </tbody>
            </table>
      </div>
      <div class=""></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal" >Cerrar</button>
        <button type="button" class="btn btn-primary modalstreaming" id="savestreaming">Guardar</button>
      </div>
      </form>
    </div>
  </div>
</div>

<!--modal_informacion-->
<div class="modal fade" id="modal_informacion" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="titulo_informacion"></h4>
      </div>
      <div class="modal-body">
        <p id="mensaje_informacion"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal" >Cerrar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal_status_exam" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <form action="" id="form_estatus_exam">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="titulo_modal_exam"></h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
            <div class="row">
            <div class="col-xs-12">
                <select class="form-control" name="selectstatus_exam" id="selectstatus_exam">
                  <option value="">Seleccione</option>
                  <option value="B">Borrador</option>
                  <option value="P">Activo para Presentar</option>
                  <option value="`F">Finalizado</option>
                </select>
            </div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal" >Cerrar</button>
        <button type="button" class="btn btn-primary" id="savestatus">Guardar</button>
      </div>
      </form>
    </div>
  </div>
</div>