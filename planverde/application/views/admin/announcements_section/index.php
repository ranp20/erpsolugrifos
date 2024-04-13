<?php echo message_box('success'); ?>
<?php echo message_box('error'); ?>
<?php
$id = $this->uri->segment(5);
$search_by = $this->uri->segment(4);
?>
<div class="row">
  <div class="col-sm-12">
    <?php
    if($this->session->userdata('user_type') == 1){ ?>
    <div class="row btnGroups-hTop">
      <div class="col-sm-12">
        <a class="btn btn-success" data-toggle="modal" data-target="#myModal" href="<?= base_url() ?>admin/announcements_section/add_announcements_section">
          <i class="fa fa-plus"></i> 
          <span>Crear Sección</span>
        </a>
      </div>
    </div>
    <?php
    }
    ?>
    <div class="panel panel-custom">
      <header class="panel-heading ">
        <div class="panel-title">
          <strong><?php echo $page; ?></strong>
        </div>
      </header>
      <div class="box">
        <table class="table table-striped DataTables bulk_table " id="DataTables" cellspacing="0" width="100%">
          <thead>
            <tr>
              <th>Titulo</th>
              <th class="col-sm-1">Activo </th>
              <th class="col-sm-1 hidden-print"><?= 'Accion' ?></th>
            </tr>
          </thead>
          <tbody>
            <script type="text/javascript">
              $(document).ready(function(){
                list = base_url + "admin/announcements_section/announcements_sectionList";
                bulk_url = base_url + 'admin/announcements_section/bulk_delete';
                $('.filtered > .dropdown-toggle').on('click', function(){
                  if($('.group').css('display') == 'block'){
                    $('.group').css('display', 'none');
                  } else {
                    $('.group').css('display', 'block')
                  }
                });
                $('.filter_by').on('click', function(){
                  $('.filter_by').removeClass('active');
                  $('.group').css('display', 'block');
                  $(this).addClass('active');
                  var filter_by = $(this).attr('id');
                  if(filter_by){
                    filter_by = filter_by;
                  } else {
                    filter_by = '';
                  }
                  table_url(base_url + list + '/' + filter_by);
                });
              });
            </script>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
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
</script>