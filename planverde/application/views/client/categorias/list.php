<?php echo message_box('success'); ?>
<?php
$user_id = $this->session->userdata('user_id');
$client_id = $this->session->userdata('client_id');
?>
<div class="row">
    <div class="col-xs-12">
        <div class="cTitle-h">
            <a class="btn btn-success cTitle-h__cLink" title="Retornar" href="<?php echo base_url() . 'client/sede'; ?>">
                <em class="fa fa-arrow-left"></em>
                <span>&nbsp;&nbsp;Volver</span>
            </a>
            <div class="cTitle-h__cTitle">
                <h4 class="title text-center">
                    <span>SEDE: <?php echo $sede;?></span>
                </h4>
            </div>
        </div>
    </div>
</div>
<div class="row maincCategories">
  <div class="cFolderItm">
    <ul class="cFolderItm__m">
      <?php 
      if(isset($all_categories) && !empty($all_categories)){
        foreach($all_categories as $category){
          $category = (object) $category;
          $cat_nombre = strlen($category->nombre_categoria) > 42 ? substr($category->nombre_categoria, 0, 42 - 3) . '...' : $category->nombre_categoria;
      ?>
          <div class="col-lg-2 col-sm-4 box-folder cFolderItm__m__cItm">
            <a class="cFolderItm__m__cItm__link link-folder" title="<?php echo $category->nombre_categoria; ?>" data-toggle="tooltip" data-placement="bottom" href="<?php echo base_url() . 'client/subcategoria/list/' . $category->categoria_id; ?>">
              <span class="cFolderItm__m__cItm__link__cIcon">
                <i class="mdi mdi-folder"></i>
              </span>
              <span class="cFolderItm__m__cItm__link__cTitle">
                <span class="title-folder" ><?php echo trim($cat_nombre); ?></span>
              </span>
            </a>
          </div>
      <?php
        }
      }
      ?>
    </ul>
  </div>
</div>