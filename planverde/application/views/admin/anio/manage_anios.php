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
        <a class="btn btn-success" data-toggle="modal" data-target="#myModal" href="<?= base_url() ?>admin/anio/add_anio">
          <i class="fa fa-plus"></i>
          <span> Crear Año</span>
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
        <table class="table table-striped DataTables bulk_table" id="DataTables" cellspacing="0" width="100%">
          <thead>
            <tr>
              <th class="col-sm-10">Año </th>
              <th class="col-sm-1 hidden-print">Acción</th>
            </tr>
          </thead>
          <tbody>
            <script type="text/javascript">
              $(document).ready(function(){
                list = base_url + "admin/anio/anioList";
                bulk_url = base_url + 'admin/anio/bulk_delete';
                $('.filtered > .dropdown-toggle').on('click', function(){
                    if($('.group').css('display') == 'block'){
                      $('.group').css('display', 'none');
                    }else{
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
                  }else{
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
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/back/adm_anios.js"></script>