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
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/back/adm_announcements_section.js"></script>