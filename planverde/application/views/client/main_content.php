<!-- <link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/fullcalendar/fullcalendar.min.css" rel="stylesheet" type="text/css"> -->
<style type="text/css">

  .easypiechart {
    margin: 0px auto;
  }

  .title-folder {
    width: 100%;
    text-overflow: ellipsis;
    overflow: hidden;
    white-space: pre;
    padding: 0px;
    margin: 0px;
    color: #7b7b7bc9;
    font-weight: bold;
  }

  .link-folder:hover, .link-folder:focus {
    text-decoration: none;
    }

  .folder {
    border: 1px solid #7b7b7bc9 !important;
    padding: 10px;
    border-radius: 8px;
    color: #7b7b7bc9;
  }

  .folder:hover {
    background: #e8f0fe;
  }

  .box-folder {
    padding-bottom: 15px;
    }
</style>
<?php echo message_box('success'); ?>
<?php
$user_id = $this->session->userdata('user_id');

$client_id = $this->session->userdata('client_id');


?>

<div class="row">
  <?php if (!empty($all_categories)) {
    foreach ($all_categories as $category) {
      $category = (object) $category;
  ?>
      <div class="col-lg-2 col-sm-4 box-folder">
        <a class="link-folder" title="<?php echo $category->nombre_categoria; ?>" href="<?php echo base_url() . 'client/subcategoria/list/' . $category->categoria_id; ?>">
        <div class="panel widget mb0 b0 folder">
            <div class="row-table row-flush">
              <div class="col-xs-12  text-center">
                <em class="fa fa-folder fa-3x" ></em>
                <h5 class="title-folder" ><?php echo trim($category->nombre_categoria); ?></h5>
              </div>

            </div>
          </div>
        </a>
      </div>
  <?php

    }
  }
  ?>
</div>