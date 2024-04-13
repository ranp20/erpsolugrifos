<div class="panel panel-custom">
  <header class="panel-heading ">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    <strong><?php echo strtoupper($title); ?></strong>
  </header>
  <?php
 /*  echo "<pre>";
  print_r($orden_detail);
  echo "</pre>"; */
  ?>
  <div class="tab-content mt" style="border: 0;padding:0;">
    <div class="tab-pane active" id="task_details" style="position: relative;">
      <div class="panel panel-custom">
        <div class="panel-heading">
          <div class="panel-title">
            <?php echo $visita_tecnica_info->servicio; ?>
            <div class="pull-right text-sm">

              <a href="<?= base_url() ?>admin/projects/index/#"></a>
            </div>
          </div>
        </div>
        <div class="panel-body form-horizontal ">
          <?php
          $client_info = $this->db->where('cliente_id', $orden_detail->cliente_id)->get('tbl_cliente')->row();

          $visita_info = $this->db->where('visita_tecnica_id', $orden_detail->visita_tecnica_id)->get('tbl_visita_tecnica')->row();

          if (!empty($cliente)) {
            $name = $cliente;
          } else {
            $name = '-';
          }

          if (!empty($sede)) {
            $name_sede = $sede;
          } else {
            $name_sede = '-';
          }
          ?>
          <?php $visita_details_view = 2;
          if (!empty($visita_details_view) && $visita_details_view == '2') {
          ?>
            <div class="row">
              <div class="col-md-12 br">
                <p class="lead bb"></p>
                <form class="form-horizontal p-20">
                  <div class="form-group">
                    <div class="col-sm-4"><strong>Numero :</strong></div>
                    <div class="col-sm-8">
                      <?php
                      if (!empty($visita_info->numero)) {
                        echo $visita_info->numero;
                      }
                      ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-4"><strong>Servicio :</strong></div>
                    <div class="col-sm-8">
                      <?php
                     
                        echo $service;
                      
                      ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-4"><strong>Cliente :</strong></div>
                    <div class="col-sm-8">
                      <strong><?php echo $name; ?></strong>
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="col-sm-4"><strong>Sede :</strong></div>
                    <div class="col-sm-8">
                      <strong><?php echo $name_sede; ?></strong>
                    </div>
                  </div>
                  <?php
                  $text = '';

                  if ($orden_detail->fecha_visita < date('Y-m-d')) {
                    $text = 'text-danger';
                  } else {
                    $text = 'text-success';
                  }
                  ?>
                  <div class="form-group">
                    <div class="col-sm-4"><strong>Fecha Para Visita :</strong></div>
                    <div class="col-sm-8 <?= $text ?>">
                      <strong><?= strftime('%d, %B, %Y', strtotime($orden_detail->fecha_visita)) ?>
                      </strong>
                    </div>
                  </div>

                  <?php /* ?>
                  <div class="form-group">
                    <div class="col-sm-4"><strong><?= lang('status') ?>
                        :</strong></div>
                    <div class="col-sm-8">
                      <?php
                      $disabled = null;
                      if (!empty($orden_detail->status)) {
                        if ($orden_detail->status == 'completed') {
                          $status = "<div class='label label-success'>" . lang($orden_detail->status) . "</div>";
                          $disabled = 'disabled';
                        } elseif ($orden_detail->status == 'in_progress') {
                          $status = "<div class='label label-primary'>" . lang($orden_detail->status) . "</div>";
                        } elseif ($orden_detail->status == 'cancel') {
                          $status = "<div class='label label-danger'>" . lang($orden_detail->status) . "</div>";
                        } else {
                          $status = "<div class='label label-warning'>" . lang($orden_detail->status) . "</div>";
                        } ?>
                        <?= $status; ?>
                      <?php }
                      ?>
                      <?php if (!empty($can_edit) && !empty($edited)) { ?>
                        <div class="btn-group">
                          <button class="btn btn-xs btn-success dropdown-toggle" data-toggle="dropdown">
                            <?= lang('change') ?>
                            <span class="caret"></span></button>
                          <ul class="dropdown-menu animated zoomIn">
                            <li>
                              <a href="<?= base_url() ?>admin/projects/change_status/<?= $project_details->project_id . '/started' ?>"><?= lang('started') ?></a>
                            </li>
                            <li>
                              <a href="<?= base_url() ?>admin/projects/change_status/<?= $project_details->project_id . '/in_progress' ?>"><?= lang('in_progress') ?></a>
                            </li>
                            <li>
                              <a href="<?= base_url() ?>admin/projects/change_status/<?= $project_details->project_id . '/on_hold' ?>"><?= lang('on_hold') ?></a>
                            </li>
                            <li>
                              <a href="<?= base_url() ?>admin/projects/change_status/<?= $project_details->project_id . '/cancel' ?>"><?= lang('cancel') ?></a>
                            </li>
                            <li>
                              <a href="<?= base_url() ?>admin/projects/change_status/<?= $project_details->project_id . '/completed' ?>"><?= lang('completed') ?></a>
                            </li>
                          </ul>
                        </div>
                      <?php } ?>
                    </div>
                  </div>
                  <?php */ ?>

                  <div class="form-group">
                    <div class="col-sm-4"><strong>Documento :</strong></div>
                    <div class="col-sm-8">
                    <span data-placement="top" data-toggle="tooltip" title="DESCARGAR DOCUMENTO DE VALORIZACION DE VISITA TECNICA" >
        <a target="_blank"  class="btn btn-success btn-lg"  href="<?php echo base_url() ?>uploads/visita_tecnica/orden_visita/<?php echo $orden_detail->ruta ?>"><span class="fa fa-download"> DESCARGAR</span></a>
        </span>
                    </div>
                  </div>

                </form>
              </div>
            <?php } ?>
            </div>
        </div>
      </div>
    </div>
  </div>