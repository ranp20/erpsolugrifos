<div class="panel panel-custom">
  <header class="panel-heading ">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    <strong><?php echo strtoupper($title . ' ( ' . $data_cotizacion->nombre . ' ) '); ?></strong>
  </header>
  <?php
  
  ?>
  <div class="tab-content mt" style="border: 0;padding:0;">
    <div class="tab-pane active" id="task_details" style="position: relative;">
      <div class="panel panel-custom">

        <div class="panel-body form-horizontal ">
          <?php


          // $data_cotizacion = $this->db->where('visita_tecnica_id', $constancia_detail->visita_tecnica_id)->get('tbl_visita_tecnica')->row();

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
          <div class="row">
            <div class="col-md-12 br">
              <p class="lead bb"></p>
              <form class="form-horizontal p-20">

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

                if ($data_ot->start_date > date('Y-m-d')) {
                  $text = 'text-danger';
                } else {
                  $text = 'text-success';
                }
                
                ?>

                <?php foreach ($comprobantes as $key => $comprobante) :
                  if (!empty($comprobante->ruta)) :
                ?>
                    <div class="form-group" >
                      <div class="col-sm-4"><strong><?php echo $comprobante->descripcion ?> :</strong></div>
                      <div class="col-sm-4 <?= $text ?>">
                        <strong><?= strftime('%d, %B, %Y', strtotime($comprobante->fecha_comprobante)) ?>
                        </strong>
                      </div>
                      <div class="col-sm-4">
                        <span data-placement="top" data-toggle="tooltip" title="DESCARGAR COMPROBANTE DE PAGO">
                          <a target="_blank" class="btn btn-success btn-lg" href="<?php echo base_url() ?>uploads/cotizaciones/comprobante_pago_administracion/<?php echo $comprobante->ruta ?>"><span class="fa fa-download"> <?php echo $comprobante->monto_upload ?></span></a>
                        </span>
                      </div>
                    </div>
                <?php
                  endif;
                endforeach; ?>


              </form>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>