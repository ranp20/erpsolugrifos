<?php echo message_box('success'); ?>
<?php echo message_box('error'); ?>
<?php
$id = $this->uri->segment(5);
$search_by = $this->uri->segment(4);
$created = can_action('4', 'created');
$edited = can_action('4', 'edited');
$deleted = can_action('4', 'deleted');
?>
<div class="row">
  <div class="col-sm-12">
    <?php
      if ($this->session->userdata('user_type') == 1) { ?>
      <div class="row btnGroups-hTop">
        <div class="col-sm-12">
          <a class="btn btn-success" href="<?= base_url() ?>admin/cliente/add_cliente">
            <i class="fa fa-plus"></i>
            <span> Crear Cliente</span>
          </a>
        </div>
      </div>
    <?php
      }
    ?>
    <div class="panel panel-custom">
      <header class="panel-heading ">
        <div class="panel-title"><strong><?php echo $page; ?></strong></div>
      </header>
      <div class="box">
        <table class="table table-striped DataTables bulk_table " id="DataTables" cellspacing="0" width="100%">
          <thead>
            <tr>
              <th>Ruc</th>
              <th>Cliente</th>
              <th>Representante </th>
              <th>Gerente </th>
              <th class="hidden-print"><?= 'Accion' ?></th>
            </tr>
          </thead>
          <tbody>
            <script type="text/javascript">
              $(document).ready(function() {
                list = base_url + "admin/cliente/clienteList";
                bulk_url = base_url + 'admin/cliente/bulk_delete';
                $('.filtered > .dropdown-toggle').on('click', function() {
                  if ($('.group').css('display') == 'block') {
                    $('.group').css('display', 'none');
                  } else {
                    $('.group').css('display', 'block')
                  }
                });
                $('.filter_by').on('click', function() {
                  $('.filter_by').removeClass('active');
                  $('.group').css('display', 'block');
                  $(this).addClass('active');
                  var filter_by = $(this).attr('id');
                  if (filter_by) {
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
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/back/adm_cliente.js"></script>
<script type="text/javascript">
  function is_json(str){
    try{
      JSON.parse(str);
    }catch(e){
      return false;
    }
    return true;
  }
  function deleteClient($data){
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
      if (result.isConfirmed){        
        let url = base_url + 'admin/cliente/delete_client/' + $data;
        let urlReload = base_url + 'admin/cliente';
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
                  text: 'El cliente se eliminó correctamente!',
                });
                setTimeout(function(){location.href=urlReload} , 500);
              }else{
                Swal.fire({
                  icon: 'error',
                  title: 'Oops...',
                  text: 'No se pudo eliminar el cliente!',
                });
              }
            }else{
              Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'No se pudo eliminar el cliente!',
              });
            }
          }
        });
      }
    });
  }
</script>