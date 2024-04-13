<?php echo message_box('success');?>
<?php
$user_id = $this->session->userdata('user_id');
$client_id = $this->session->userdata('client_id');
?>
<h3 class="text-center">SEDES</h3>
<?php
  // print_r($this->session->userdata('user_id'));
?>
<div class="row maincSedes">
  <div class="cFolderItm">
    <ul class="cFolderItm__m">
    <?php 
    if(!empty($all_sedes)){
      foreach($all_sedes as $sede){
    ?>
      <div class="col-lg-2 col-sm-4 box-folder cFolderItm__m__cItm">
        <a class="cFolderItm__m__cItm__link link-folder" title="<?php echo $sede->direccion;?>" data-toggle="tooltip" data-placement="bottom" href="<?php echo base_url() . 'client/categoria/list/' . $sede->sede_id;?>">
          <span class="cFolderItm__m__cItm__link__cIcon">
            <i class="mdi mdi-folder"></i>
          </span>
          <span class="cFolderItm__m__cItm__link__cTitle">
            <span class="title-folder" ><?php echo trim($sede->direccion);?></span>
          </span>
        </a>
      </div>
    <?php
      }
    }else{
      echo "<h3>Comuníquese con el Administrador</h3>";
    }
    ?>
    </ul>
  </div>
</div>