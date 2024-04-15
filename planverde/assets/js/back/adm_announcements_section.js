$(document).ajaxComplete(function(){
  // $('input[type=checkbox]').bootstrapToggle();
});
function is_json(str){
  try{
    JSON.parse(str);
  }catch(e){
    return false;
  }
  return true;
}
var loading = '<h6 class="ajax-loading"><i class="fa fa-spinner fa-spin"></i>Cargando...</h6>'
$(document).on('click', '.status-anuncio', function(){
  let el = $(this), status = "", id = el.data('id');
  if($(this).is(":checked")){
      status = $(this).val();
  }else{
      status = "off";
  }
  $.ajax({
    type: "POST",
    url: base_url + 'admin/announcements_section/active/' + id + '/' + status,
    dataType: "json",
    beforeSend: function (){
      /*
      el.bootstrapToggle('disable');
      el.parent().parent().append(loading);
      */
    },
    success: function (data){
      toastr[data.type](data.message);
      if(status == 1){
        el.data('status', '2');
      }else{
        el.data('status', '1');
      }
      /*
      $(".ajax-loading").remove()
      el.bootstrapToggle('enable');
      */
    }
  });
});    
function deleteAnnouncements_section($data){
  Swal.fire({
    title: 'Estás seguro?',
    text: "Está acción no es reversible!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Sí, Eliminar!',
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if(result.isConfirmed){
      let url = base_url + 'admin/announcements_section/delete_announcements_section/' + $data;
      let urlReload = base_url + 'admin/announcements_section';
      $.ajax({
        type: "POST",
        url: url,
        data: {
          "_method": "DELETE",
        },
          success: function(e){
          if(is_json(e) && e != []){
            let r = JSON.parse(e);
            if(r.type == "success"){
              Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: 'La sección de anuncio se eliminó correctamente!',
              });
              setTimeout(function(){location.href=urlReload} , 500);
            }else{
              Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'No se pudo eliminar la sección de anuncio!',
              });
            }
          }else{
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'No se pudo eliminar la sección de anuncio!',
            });
          }
        }
      });
    }
  });
}