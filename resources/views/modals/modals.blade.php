<div class="modal fade" id="modalRegistry" tabindex="-1" role="dialog" aria-labelledby="registryLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="registryLabel">Registrar Usuarios</h4>
      </div>
      <div class="modal-body">
        <form id="registro-usuario">
          <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre_user" placeholder="Nombre">
          </div>
          <div class="form-group">
            <label for="apellido">Apellido</label>
            <input type="text" class="form-control" id="apellido" name="apellido" placeholder="Apellido">
          </div>
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Email">
          </div>
          <div class="form-group">
            <label for="perfil">Perfil</label>
            <select class="form-control" id="perfil" name="perfil"></select>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal" >Cerrar</button>
        <button type="button" class="btn btn-primary" id="saveuser">Guardar</button>
      </form>
      </div>
    </div>
  </div>
</div>