<?php echo message_box('success'); ?>
<?php echo message_box('error'); ?>
<?php
$user_id = $this->session->userdata('user_id');
$profile_info = $this->db->where('user_id', $user_id)->get('tbl_account_details')->row();
$user_info = $this->db->where('user_id', $user_id)->get('tbl_users')->row();
$languages = $this->db->where('active', 1)->order_by('name', 'ASC')->get('tbl_languages')->result();
$locales = $this->db->order_by('name')->get('tbl_locales')->result();
?>
<div class="row">
  <div class="col-sm-1"></div>
  <div class="cDsh__secCont">
    <div class="cDsh__secCont--cFrmCard">
      <div class="panel panel-custom cDsh__secCont--cFrmCard__cnt">
        <header class="panel-heading cDsh__secCont--cFrmCard__cnt__cHeading">
          <h3 class="mt-0"><?= $title;?></h3>
        </header>
        <?php echo form_open(base_url('admin/cliente/save_cliente/' . (isset($cliente_info) ? $cliente_info->cliente_id : '')), array('id' => 'cliente', 'class' => 'form-horizontal', "enctype" => "multipart/form-data")); ?>
        <div class="cCtrlsGroup__Row">
          <div class="ctFrm__cCtrlsGroup">
            <div class="ctFrm__cCtrlsGroup__cFlagTitle">
              <span>Datos Principales</span>
            </div>
            <div class="form-group">
              <div class="col-sm-8">
                <label class=" control-label"><?= ('Razon social') ?></label>
                <input type="text" name="razon_social" class="form-control" placeholder="RAZON SOCIAL DEL CLIENTE" value="<?php echo (isset($cliente_info->razon_social)) ? $cliente_info->razon_social : ''; ?>" required>
              </div>
              <div class="col-sm-4">
                <label class=" control-label"><?= ('RUC') ?></label>
                <input type="number" name="ruc" class="form-control" placeholder="NUMERO DE RUC" value="<?php echo (isset($cliente_info->ruc)) ? $cliente_info->ruc : ''; ?>" required minlength="10" maxlength="20">
              </div>
            </div>
          </div>
          <div class="ctFrm__cCtrlsGroup">
            <div class="ctFrm__cCtrlsGroup__cFlagTitle">
              <span>Dirección</span>
            </div>
            <div class="form-group grpcol-2 mb-0">
              <div class="col-sm-6">
                <label class=" control-label">Distrito</label>
                <input type="text" name="distrito" class="form-control" placeholder="DISTRITO" value="<?php echo (isset($cliente_info->distrito)) ? $cliente_info->distrito : ''; ?>" required>
              </div>
              <div class="col-sm-6">
                <label class=" control-label">Provincia</label>
                <input type="text" name="provincia" class="form-control" placeholder="PROVINCIA" value="<?php echo (isset($cliente_info->provincia)) ? $cliente_info->provincia : ''; ?>" required>
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-12">
                <label class=" control-label">Direccion Legal</label>
                <input type="text" name="direccion_legal" class="form-control" placeholder="DIRECCION LEGAL" value="<?php echo (isset($cliente_info->direccion_legal)) ? $cliente_info->direccion_legal : ''; ?>" required>
              </div>
            </div>
          </div>
        </div>
        <div class="cCtrlsGroup__Row alignitems-fstart">
          <div class="ctFrm__cCtrlsGroup">
            <div class="ctFrm__cCtrlsGroup__cFlagTitle">
              <span>Representante Legal</span>
            </div>
            <div class="form-group">
              <div class="col-sm-8">
                <label class=" control-label">Nombre</label>
                <input type="text" name="representante_legal" class="form-control" placeholder="REPRESENTANTE LEGAL" value="<?php echo (isset($cliente_info->representante_legal)) ? $cliente_info->representante_legal : ''; ?>">
              </div>
              <div class="col-sm-4">
                <label class=" control-label">DNI</label>
                <input type="number" name="dni_representante" class="form-control" placeholder="DNI REPRESENTANTE" value="<?php echo (isset($cliente_info->dni_representante)) ? $cliente_info->dni_representante : ''; ?>" minlength="8" maxlength="8">
              </div>
              <div class="col-sm-4">
                <label class=" control-label">Correo Electrónico</label>
                <input type="email" name="email_representante" class="form-control" placeholder="CORREO REPRESENTANTE" value="<?php echo (isset($cliente_info->email_representante)) ? $cliente_info->email_representante : ''; ?>">
              </div>
            </div>
          </div>
          <div class="ctFrm__cCtrlsGroup">
            <div class="ctFrm__cCtrlsGroup__cFlagTitle">
              <span>Gerente Legal</span>
            </div>
            <div class="form-group">
              <div class="col-sm-8">
                <label class=" control-label">Nombre</label>
                <input type="text" name="gerente_legal" class="form-control" placeholder="GERENTE LEGAL" value="<?php echo (isset($cliente_info->gerente_legal)) ? $cliente_info->gerente_legal : ''; ?>">
              </div>
              <div class="col-sm-4">
                <label class=" control-label">DNI</label>
                <input type="number" name="dni_gerente" class="form-control" placeholder="DNI GERENTE" value="<?php echo (isset($cliente_info->dni_gerente)) ? $cliente_info->dni_gerente : ''; ?>" minlength="8" maxlength="8">
              </div>
              <div class="col-sm-4">
                <label class=" control-label">Correo Electrónico</label>
                <input type="email" name="email_gerente" class="form-control" placeholder="CORREO GERENTE" value="<?php echo (isset($cliente_info->email_gerente)) ? $cliente_info->email_gerente : ''; ?>">
              </div>
            </div>
          </div>
          <div class="ctFrm__cCtrlsGroup">
            <div class="ctFrm__cCtrlsGroup__cFlagTitle">
              <span>Supervisor</span>
            </div>
            <div class="cGrpInfData-c">
              <div class="cGrpInfData-c__m show-scrollbar" id="d34-ndHHl0f0">
                <?php 
                $supervsAllCollection = json_decode($cliente_info->superv_collection, TRUE);
                if(!empty($supervsAllCollection)){
                  $allSupervs = json_decode($cliente_info->superv_collection, TRUE)['superv_collection']['superv'];
                  foreach($allSupervs as $k => $v){
                  if($k == 0){
                  ?>
                  <div class="form-group cGrpInfData-c__m__itm">
                    <div class="col-sm-6">
                      <label class=" control-label">Nombre</label>
                      <input type="text" name="superv_name[]" class="form-control" placeholder="SUPERVISOR" value="<?php echo $v['name'];?>">
                    </div>
                    <div class="col-sm-6">
                      <label class=" control-label">Correo Electrónico</label>
                      <input type="email" name="superv_email[]" class="form-control" placeholder="CORREO SUPERVISOR" value="<?php echo $v['email'];?>">
                    </div>
                    <div class="col-sm-4">
                      <label class=" control-label">Celular</label>
                      <input type="number" name="superv_phone[]" class="form-control" placeholder="Celular Supervisor" value="<?php echo $v['phone'];?>" minlength="9" maxlength="9">
                    </div>
                  </div>
                  <?php }else{ ?>
                  <div class="form-group cGrpInfData-c__m__itm">
                    <div class="cIcn-c--btnClose-v2 btn-ClsAddSuperv">
                      <span class="refAll_ic"></span>
                    </div>
                    <div class="col-sm-6">
                      <label class=" control-label">Nombre</label>
                      <input type="text" name="superv_name[]" class="form-control" placeholder="SUPERVISOR" value="<?php echo $v['name'];?>">
                    </div>
                    <div class="col-sm-6">
                      <label class=" control-label">Correo Electrónico</label>
                      <input type="email" name="superv_email[]" class="form-control" placeholder="CORREO SUPERVISOR" value="<?php echo $v['email'];?>">
                    </div>
                    <div class="col-sm-4">
                      <label class=" control-label">Celular</label>
                      <input type="number" name="superv_phone[]" class="form-control" placeholder="Celular Supervisor" value="<?php echo $v['phone'];?>" minlength="9" maxlength="9">
                    </div>
                  </div>
                <?php 
                    }
                  }
                }else{
                  if(!empty($cliente_info->correo) || $cliente_info->correo != "-"){
                ?>
                  <div class="form-group cGrpInfData-c__m__itm">
                    <div class="col-sm-6">
                      <label class=" control-label">Nombre</label>
                      <input type="text" name="superv_name[]" class="form-control" placeholder="SUPERVISOR" value="<?php echo $cliente_info->supervisor;?>">
                    </div>
                    <div class="col-sm-6">
                      <label class=" control-label">Correo Electrónico</label>
                      <input type="email" name="superv_email[]" class="form-control" placeholder="CORREO SUPERVISOR" value="<?php echo $cliente_info->correo;?>">
                    </div>
                    <div class="col-sm-4">
                      <label class=" control-label">Celular</label>
                      <input type="number" name="superv_phone[]" class="form-control" placeholder="Celular Supervisor" value="<?php echo $cliente_info->celular;?>" minlength="9" maxlength="9">
                    </div>
                  </div>
                <?php   
                  }else{
                ?>
                <div class="col-12" id="cScreenAny_supervisores">
                  <div class="m-auto text-center">
                    <h3>No existe ningún supervisor</h3>
                  </div>
                </div>
                <?php
                  }
                }?>
              </div>
              <div class="cGrpInfData-c__btnGrp" id="btn-AddSuperv">
                <button type="button" class="btn btn-success">
                  <span class="fa fa-plus"></span>
                  <span>Agregar</span>
                </button>
              </div>
            </div>
          </div>
        </div>
        <?php 
          if(isset($sedes) && $sedes != "[]" && count($sedes) != 0 && count($sedes) != ""){
        ?>
        <div class="ctFrm__cCtrlsGroup">
          <div class="ctFrm__cCtrlsGroup__cFlagTitle">
            <span>Lista de sedes</span>
          </div>
          <div id="sedes">
            <?php
            if (isset($sedes) && $sedes != "[]") {
              $data_sede = json_decode($sedes);
              $countTotalSedes = 0;
              foreach ($data_sede as $key => $sede) :
                // echo $key."<br>";
                // $permission = json_decode( $sede->permission );
            ?>
              <div class="panel panel-custom sede-panel-new added_panel">
                <header class="panel-heading">
                  <span>Sede Operativa</span>
                  <span class="btn btn-danger delete-sede" data-clientid="<?php echo $sede->cliente_id;?>" data-sedeid="<?php echo $sede->sede_id;?>"><i class="fa fa-trash"></i> Eliminar Sede</span>
                </header>
                <div class="panel-body">
                  <div class="cCtrlsGroup__Row">
                    <div class="ctFrm__cCtrlsGroup">
                      <div class="ctFrm__cCtrlsGroup__cFlagTitle">
                        <span>Datos Principales</span>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-6">
                          <label class=" control-label">Administrador</label>
                          <input type="text" name="administrador_sede_new[]" class="form-control" placeholder="Administrador" value="<?php echo (isset($sede->administrador)) ? $sede->administrador : ''; ?>">
                        </div>
                        <div class="col-sm-6">
                          <label class=" control-label">Administrador SST</label>
                          <input type="text" name="administrador_sst_sede_new[]" class="form-control" placeholder="Administrador SST" value="<?php echo (isset($sede->administrador_sst)) ? $sede->administrador_sst : ''; ?>">
                        </div>
                        <div class="col-sm-4">
                          <label class=" control-label">Correo</label>
                          <input type="text" name="correo_sede_new[]" class="form-control" placeholder="Correo" value="<?php echo (isset($sede->correo)) ? $sede->correo : ''; ?>">
                        </div>
                        <div class="col-sm-4">
                          <label class=" control-label">Celular</label>
                          <input type="text" name="celular_sede_new[]" class="form-control" placeholder="Celular" value="<?php echo (isset($sede->celular) && $sede->celular != 0) ? $sede->celular : ''; ?>">
                        </div>
                      </div>
                    </div>
                    <div class="ctFrm__cCtrlsGroup">
                      <div class="ctFrm__cCtrlsGroup__cFlagTitle">
                        <span>Dirección</span>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-8">
                          <label class=" control-label">Direccion</label>
                          <input type="text" name="direccion_sede_new[]" class="form-control" placeholder="Dirección" value="<?php echo (isset($sede->direccion)) ? $sede->direccion : ''; ?>" required>
                        </div>
                        <div class="col-sm-4">
                          <label class=" control-label">Distrito</label>
                          <input type="text" name="distrito_sede_new[]" class="form-control" placeholder="Distrito" value="<?php echo (isset($sede->distrito)) ? $sede->distrito : ''; ?>" required>
                        </div>
                        <div class="col-sm-4">
                          <label class=" control-label">Provincia</label>
                          <input type="text" name="provincia_sede_new[]" class="form-control" placeholder="Provincia" value="<?php echo (isset($sede->provincia)) ? $sede->provincia : ''; ?>" required>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="ctFrm__cCtrlsGroup">
                    <div class="ctFrm__cCtrlsGroup__cFlagTitle">
                      <span>Permisos</span>
                    </div>
                    <div class="row">
                      <?php
                      $permissions = json_decode($sede->permission, TRUE);
                      $data_categories = $this->db->get('tbl_categoria')->result_object();

                      foreach ($data_categories as $key => $cat) :
                        $data_subcategories = $this->db->where(['categoria_id' => $cat->categoria_id])->get('tbl_subcategoria')->result_object();
                        $countCheck = 0;
                        foreach( $data_subcategories as $key => $v ):
                          $countCheck += (int)(( in_array( $v->subcategoria_id, $permissions ) ) ? 1 : 0);
                        endforeach;
                        $checked = (count($data_subcategories) == ($countCheck)) ? 'checked' : '';

                      ?>
                        <div class="col-md-4 col-xs-12 col-sm-6">
                          <div class="panel panel-primary cGrpCtrls__secGrp__cBody__cItem__c">
                            <div class="panel-heading mb-0">
                              <span><strong><?php echo $cat->nombre_categoria;?></strong></span>
                              <!-- <span><?php //echo count($data_subcategories).'-'. ($countCheck); ?></span> -->
                              <!-- <div class=" checkbox d-none non-visvalipt h-alternative-shwnon s-fkeynone-step" style="display:none;"> -->
                              <div class="checkbox">
                                <input data-id="" data-toggle="toggle" name="permissions-all[]" class="permissions-all" value="" <?php echo $checked; ?> data-on="SI" data-off="NO" data-onstyle="success btn-xs" data-offstyle="danger btn-xs" type="checkbox">
                              </div>
                            </div>

                            <div class="cGrpCtrls__secGrp__cBody__cItem__c__cList">
                              <?php
                              foreach ($data_subcategories as $key_sub => $subcat) :
                              ?>
                                <div class="form-group">
                                  <label class="col-lg-8 control-label text-right"><?= $subcat->nombre_subcategoria ?></label>
                                  <!-- <span style="color: red !important;"><?php //echo $subcat->subcategoria_id ?></span> -->
                                  <div class="col-lg-4 checkbox">
                                    <input class="permission-check" data-id="" data-toggle="toggle" name="permisos_new_<?php echo $countTotalSedes . '[]'; ?>" value="<?= $subcat->subcategoria_id ?>" <?php echo (!empty($permissions) && in_array($subcat->subcategoria_id, $permissions)) ? 'checked' : ''; ?> data-on="SI" data-off="NO" data-onstyle="success btn-xs" data-offstyle="danger btn-xs" type="checkbox">
                                  </div>
                                </div>
                              <?php
                              endforeach;
                              ?>
                            </div>

                          </div>
                        </div>
                      <?php
                      endforeach;
                      ?>
                    </div>
                  </div>
                </div>
              </div> 
            <?php
              $countTotalSedes++;
              endforeach;
            }
            ?>
          </div>
        </div>
        <?php
          }else{
        ?>
        <div class="ctFrm__cCtrlsGroup">
          <div class="ctFrm__cCtrlsGroup__cFlagTitle">
            <span>Lista de sedes</span>
          </div>
          <div id="sedes">
            <div class="col-12" id="cScreenAny_sedes">
              <h3>No existe ninguna Sede</h3>
              <input tabindex="-1" placeholder="phdr-whidipts" type="hidden" width="0" height="0" autocomplete="off" spellcheck="false" f-hidden="aria-hidden" class="non-visvalipt h-alternative-shwnon s-fkeynone-step" id="iptinvalid-forvalid_client" name="iptinvalid-forvalid_client" required>
            </div>
          </div>
        </div>
        <?php
          }
        ?>
        
        <div class="text-center">
          <span id="new-sede" class="btn btn-primary">Agregar Sede</span>
        </div>
        <div class="row">
          <div class="col-lg-12">
            <div class="w-100 ml-auto" style="display: flex;align-items: center;justify-content: flex-end;">
              <?php if (isset($cliente_info)) : ?>
                <button type="submit" class="btn btn-sm btn-primary mx-3">Actualizar</button>
              <?php else : ?>
                <button type="submit" class="btn btn-sm btn-primary">Guardar</button>
              <?php endif; ?>
              <a href="<?php echo base_url(); ?>admin/cliente" class="btn btn-default">Cancelar</a>
            </div>
          </div>
        </div>
        <?php echo form_close(); ?>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/back/adm_cliente.js"></script>