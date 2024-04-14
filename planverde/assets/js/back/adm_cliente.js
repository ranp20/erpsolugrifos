$(() => {
  $(document).on("click",".icon-changePassControlAdm_ipt",function(e){
    e.preventDefault();
    $iptPass = $(this).parent().find("input").attr('type');
    console.log($(this).parent().find("input"));
    if($iptPass == "password"){
      $(this).parent().find("input").attr("type", "text");
      $(this).html(`<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="cLogin__cont--fLogin--form--controls--cIcon--pass"><path d="M12.015 7c4.751 0 8.063 3.012 9.504 4.636-1.401 1.837-4.713 5.364-9.504 5.364-4.42 0-7.93-3.536-9.478-5.407 1.493-1.647 4.817-4.593 9.478-4.593zm0-2c-7.569 0-12.015 6.551-12.015 6.551s4.835 7.449 12.015 7.449c7.733 0 11.985-7.449 11.985-7.449s-4.291-6.551-11.985-6.551zm-.015 3c-2.209 0-4 1.792-4 4 0 2.209 1.791 4 4 4s4-1.791 4-4c0-2.208-1.791-4-4-4z"></path></svg>`);
    }else{
      $(this).parent().find("input").attr("type", "password");
      $(this).html(`<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="cLogin__cont--fLogin--form--controls--cIcon--pass"><path d="M19.604 2.562l-3.346 3.137c-1.27-.428-2.686-.699-4.243-.699-7.569 0-12.015 6.551-12.015 6.551s1.928 2.951 5.146 5.138l-2.911 2.909 1.414 1.414 17.37-17.035-1.415-1.415zm-6.016 5.779c-3.288-1.453-6.681 1.908-5.265 5.206l-1.726 1.707c-1.814-1.16-3.225-2.65-4.06-3.66 1.493-1.648 4.817-4.594 9.478-4.594.927 0 1.796.119 2.61.315l-1.037 1.026zm-2.883 7.431l5.09-4.993c1.017 3.111-2.003 6.067-5.09 4.993zm13.295-4.221s-4.252 7.449-11.985 7.449c-1.379 0-2.662-.291-3.851-.737l1.614-1.583c.715.193 1.458.32 2.237.32 4.791 0 8.104-3.527 9.504-5.364-.729-.822-1.956-1.99-3.587-2.952l1.489-1.46c2.982 1.9 4.579 4.327 4.579 4.327z"></path></svg>`);
    }
  });
  // ------------ CONFIRMAR CONTRASEÑA (VALIDACIÓN)
  $(document).on("input keyup keypress", "#confirm_password", function(e){
    var valPassFirst = $("#new_password").val();
    var lengthPassFirst = $("#new_password").val().length;
    let valThis = e.target.value;
    let lengthThis = e.target.value.length;
    let thisFrmSubmit = $(this).parent().parent().parent().parent().find("*[type=submit]");
    if(lengthThis == lengthPassFirst || lengthThis > lengthPassFirst){
      if(valThis === valPassFirst){
        thisFrmSubmit.removeClass("not-process");
        $("#mssg_cConfirmTwoPass").text("Las contraseñas coinciden*");
        $("#mssg_cConfirmTwoPass").removeClass("mssgSpn__error-mssg");
        $("#mssg_cConfirmTwoPass").addClass("mssgSpn__success-mssg");
        // setTimeout(() => { $("#mssg_cConfirmTwoPass").text(""); }, 2600); // Desaparecer mensaje...
      }else{
        thisFrmSubmit.addClass("not-process");
        $("#mssg_cConfirmTwoPass").text("Las contraseñas NO coinciden*");
        $("#mssg_cConfirmTwoPass").removeClass("mssgSpn__success-mssg");
        $("#mssg_cConfirmTwoPass").addClass("mssgSpn__error-mssg");
      }
    }else{
      thisFrmSubmit.addClass("not-process");
      $("#mssg_cConfirmTwoPass").text("");
      // console.log("No es igual."); // Si se desea mostrar el mensaje a medida que el usuario escribe antes de la validación de la longitud...
    }
  });
  $(document).ready(function(){
    function verificarInputYBotonSubmit(){
      var inputExists = $('#iptinvalid-forvalid_client').length > 0;
      var submitButton = $('form#cliente').find('button[type="submit"], input[type="submit"]');
      if (inputExists){
        submitButton.prop('disabled', true);
      } else {
        submitButton.prop('disabled', false);
      }
    }
    verificarInputYBotonSubmit();
    var observer = new MutationObserver(function(mutationsList){
      verificarInputYBotonSubmit();
    });
    observer.observe(document.body, { childList: true, subtree: true });
  });
  $(document).on('click', '#new-sede', function(){
    $("#cScreenAny_sedes").remove();
    var indSedes = 0;
    if($(".sede-panel-new").length > 0){
      $.each($(".sede-panel-new"), function(i,e){
        // console.log(e);
        indSedes++;
      });
    }
    $.ajax({
      type: "POST",
      url: base_url + 'admin/sede/form',
      dataType: "html",
      success: function(response){
        $("#sedes").append(response);
      },
      complete: function(){
        $(".permisos").addClass('new_permission');
        $('.new_permission').attr('name', 'permisos_new_' + indSedes + '[]');
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
          <input tabindex="-1" placeholder="phdr-whidipts" type="hidden" width="0" height="0" autocomplete="off" spellcheck="false" f-hidden="aria-hidden" class="non-visvalipt h-alternative-shwnon s-fkeynone-step" id="iptinvalid-forvalid_client" name="iptinvalid-forvalid_client" required>
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
  
  $(document).on('change', '.permissions-all-new', function(){
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