(function ($) {
  "use strict";
  var certif = {
    btoaFieldsAjax: false,
    typeAjax: "GET",
    data: [],
    course: 'a',
    table_id: '#tblstudents',
    table: '',
    launch: function () {
      this.fnChosen();
      this.listCourses();
      this.dataTable(this.table_id);
      this.consultCourse();
      this.Emitircertif();
    },
    consult: function (url, params, type, async, btoa) {
      if (url != undefined && url.length > 0 && typeof params === 'object') {
        var value = "", data = "", a = (async != undefined || async == false ? false : true), method = (type == undefined ? this.typeAjax : type);
        for (var key in params) {
          value = (this.btoaFieldsAjax ? (btoa != undefined || btoa == true ? params[key] : btoa(params[key])) : params[key]);
          data += (data.length <= 0 ? key + '=' + value : '&' + key + '=' + value);
        }
        return $.ajax({
          type: method,
          url: url,
          data: data,
          dataType: 'json',
          async: a,
          headers: (method == "POST" ? { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } : "")
        });
      }
    }, 
    fnChosen: function () {
      $(".chosen-select").chosen();
      $('.chosen-container input[type="text"]').attr("autocomplete", false);
    }, 
    listCourses: function () {
      var data = this.consult('certificates/liscourses', [], 'GET');
      data.done(function (d) {
        var select = $("#list_courses_eva");
        $.each(d, function (i, val) {
          select.append($('<option>', { 'value': val.id }).text(val.name));
        });
        select.trigger('chosen:updated');
      });
    }, 
    dataTable: function (idtable) {
      this.table = $(idtable).DataTable({
        "ajax": {
          'type': 'GET',
          'url': 'certificates/liscourses/' + btoa(certif.course),
          'data': {},
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
        },
        "language": {
          "lengthMenu": "Total de registros:_MENU_ ",
          "zeroRecords": "No se encontraron registros que mostrar",
          "info": "Total de p&aacute;ginas _PAGE_ de _PAGES_",
          "infoEmpty": "",
          "infoFiltered": "(filtered from _MAX_ total records)",
          "sSearch": "Buscar: ",
          "sProcessing": "",
          "paginate": {
            "previous": "Anterior",
            "next": "Siguiente"
          }
        },
        "columns": [
          { "data": "name", "sClass": 'namecoursetable' },
          { "data": "email", "sClass": 'text-center' },
          { "data": "qualification", "sClass": 'text-center' },
          { "data": "actions", "sClass": 'text-center' }
        ]
      });
    }, 
    consultCourse: function () {
      $("body").on("change", "#list_courses_eva", function () {
        if ($(this).val() != '') {
          certif.course = $("#list_courses_eva option:selected").val();
          certif.table.ajax.url("certificates/liscourses/" + btoa(certif.course)).load();
        }
      });
    }, 
    Emitircertif: function () {
      $("body").on("click", "#btncertif", function () {
        certif.listUsers();
        if (certif.data.length > 0) {
          var data = certif.consult('./certificates/savecertif', { 'users': certif.data, 'course': btoa(certif.course) }, 'POST');
          data.done(function (d) {
            var msj = 'Certificados Emitidos con exito';
            var title = 'Mensaje!';
            var error = '';
            if (d.oper == false) {
              msj = 'Error comuniquese con el Administrador';
              if (d.error = 'COD: 400') {
                msj = 'Ya se encuentra inscrito en el curso <b>' + ofertaCursos.course.name.toUpperCase() + '</b>';
              }
              title = 'Error!';
              error = (d.error != '') ? d.error : '';
            } else {
              var id = certif.table;
              id.ajax.reload();
            }
            certif.Modaloper('#modal_operacion', msj, title, error);
          });
        } else {
          certif.Modaloper('#modal_operacion', 'Debe seleccionar al menos un alumno', 'Error!', '');
        }
      });
    }, 
    listUsers: function () {
      var unique = [];
      $('.select-users').each(function (a, b) {
        if ($(this).is(':checked')) {
          if ($.inArray($(this).val(), unique) === -1) unique.push(btoa($(this).val()));
        }
      });
      console.log(unique);
      certif.data = unique;
    }, 
    Modaloper: function (modal, msj, title, error) {
      $(modal).modal('show');
      $(modal).on('shown.bs.modal', function () {
        if (typeof msj !== undefined || msj != '' || msj != null) {
          $(".oper_mensaje").html(msj).addClass('bold');
        }
        if (typeof title !== undefined || title != '' || title != null) {
          $("#oper_titulo").text(title);
        }
        if (typeof error !== undefined || error != '' || error != null) {
          $("#oper_error").text(error);
        }
      })
    },
  }
  $(document).ready(function () {
    certif.launch();
  });
})(jQuery);
