$(() => {
  var permiso = 0
  $(document).on('click', '#new-sede', function(){
    $("#cScreenAny_sedes").remove();
    $.ajax({
      type: "POST",
      url: base_url + 'admin/sede/form',
      dataType: "html",
      success: function(response){
        $("#sedes").append(response);
      },
      complete: function(){
        console.log('sis')
        $(".permisos").addClass('new_permission');
        $('.new_permission').attr('name', 'permisos_new_' + permiso + '[]');
        permiso += 1
        $(".permisos").removeClass('new_permission');
        $(".permisos").removeClass('permisos');
      }
    });
  })
  $(document).on('click', '.delete-sede', function(){
    let el = $(this);
    el.parent().parent('.sede-panel-new').remove();
    if($(".sede-panel-old").length > 0){
    }else{
      if($(".sede-panel-new").length){
      }else{
        $("#sedes").html(`<div class="col-12" id="cScreenAny_sedes">
          <h3>No existe ninguna Sede</h3>
        </div>`);
      }
    }    
  });  
  $(document).on('change', '.permissions-all', function(){
    console.log($(this));
    let el = $(this), 
        check = el.parent().parent().parent().parent().find('.permission-check');
    $.each(check, function(i,v){
      item = $(this);
      // console.log(item)
      if (el.prop('checked') == true){
        item.prop('checked', true);
        item.parent().addClass('btn-success on');
        item.parent().removeClass('btn-danger off');
      } else {
        item.prop('checked', false);
        item.parent().removeClass('btn-success on');
        item.parent().addClass('btn-danger off');
      }
      console.log(item.parent().parent());
    });
  });
  
  $(document).on('change', '.permissions-all-new', function() {
    let el = $(this), check = el.parent().parent().parent().parent().find('.permission-check-new');
    $.each(check, function(i,v){
      item = $(this);
      // console.log(item)
      if(el.prop('checked') == true){
        item.prop('checked', true);
        /* item.parent().addClass('btn-success on')
        item.parent().removeClass('btn-danger off') */
      }else{
        item.prop('checked', false);
        /* item.parent().removeClass('btn-success on')
        item.parent().addClass('btn-danger off') */
      }
      console.log(item.parent().parent());
    });
  });
  $(document).on("keyup keypress blur change", "input[type='number']", function(e){
    if(e.target.value.length >= $(this).attr("maxlength")){
      return false
    }
  });
  $(document).on("click","#btn-AddSuperv",function(){
    $("#cScreenAny_supervisores").remove();
    let $tmpSuperv = `<div class="form-group cGrpInfData-c__m__itm supervisor-panel-new">
                <div class="cIcn-c--btnClose-v2 btn-ClsAddSuperv">
                  <span class="refAll_ic"></span>
                </div>
                <div class="col-sm-6">
                  <label class=" control-label">Nombre</label>
                  <input type="text" name="superv_name[]" class="form-control" placeholder="SUPERVISOR" value="">
                </div>
                <div class="col-sm-6">
                  <label class=" control-label">Correo Electrónico</label>
                  <input type="email" name="superv_email[]" class="form-control" placeholder="CORREO SUPERVISOR" value="">
                </div>
                <div class="col-sm-4">
                  <label class=" control-label">Celular</label>
                  <input type="number" name="superv_phone[]" class="form-control" placeholder="Celular Supervisor" value="" minlength="9" maxlength="9">
                </div>
              </div>`;
    $("#d34-ndHHl0f0").append($tmpSuperv);
  });
  $(document).on("click",".btn-ClsAddSuperv",function(){
    $.each($(this), function(e,i){
      let parentThisSuperv = $(this).parent().parent('.cGrpInfData-c__m').children('.cGrpInfData-c__m__itm');
      if(parentThisSuperv.length == 1){
        $("#d34-ndHHl0f0").html(`<div class="col-12" id="cScreenAny_supervisores">
          <div class="m-auto text-center">
            <h3>No existe ningún supervisor</h3>
          </div>
        </div>`);
      }
      $(this).parent().remove();
    });
  });
})