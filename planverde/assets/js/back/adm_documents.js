$(() => {
  // ------------ COLOCAR LOADER Y MEDIO VALIDAR FORMULARIO
  $(document).on("submit","form#document_client",function(e){
    let formIsValid = true;
    $(this).find('input[required]').each(function(){
      if($(this).val() === ''){
        formIsValid = false;
        // $(this).addClass('input-error');
      }else{
        // $(this).removeClass('input-error');
      }
    });
    if(!formIsValid){
      event.preventDefault();
    }else{
      $(this).find('button, input[type="button"]').prop('disabled', true);
      if($(this).find('button[type="submit"]').hasClass("btn-tocreated")){
        $(`<div id="loading-indicator-tomoreforce">
          <div class="loading-indicator__c">
            <div class="hourglass"></div>
            <div class="loading-indicator__c--cMssg">
              <span>Creando registro...</span>
            </div>
          </div>
        </div>`).insertBefore('body > .wrapper');
      }else{
        $(`<div id="loading-indicator-tomoreforce">
          <div class="loading-indicator__c">
            <div class="hourglass"></div>
            <div class="loading-indicator__c--cMssg">
              <span>Actualizando registro...</span>
            </div>
          </div>
        </div>`).insertBefore('body > .wrapper');
      }      
      $(this).find('button[type="submit"]').addClass('submit-clicked');
    }
  });
  // ------------ ELIMINAR DOCUMENTO
  $(document).on('click', '.delete-document', function() {
    let id = $(this).data('id');
    Swal.fire({
      title: 'Estás seguro?',
      text: "Está acción no es reversible!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Sí, Eliminar!',
      cancelButtonText: "Cancelar",
    }).then((result) => {
      if (result.isConfirmed){
        let urlDelete = base_url + 'admin/document_client/delete_document/' + id;
        $.ajax({
          type: "POST",
          url: urlDelete,
          dataType: "json",
          beforeSend: function(){
            $(`<div id="loading-indicator">
            <div class="loading-indicator__c">
              <div class="loading-indicator__c--loader"></div>
              <div class="loading-indicator__c--cMssg">
                <span>Eliminando registro...</span>
              </div>
            </div>
          </div>`).insertBefore('.content-wrapper');
          },
          success: function(e){
            if(e != []){
              let r = e;
              if(r.type == "success"){
                $('#loading-indicator').remove();
                Swal.fire({
                  icon: 'success',
                  title: 'Éxito',
                  text: 'El documento se eliminó correctamente!',
                });
                table_url(base_url + "admin/document_client/documentsClientList/");
              }else{
                $('#loading-indicator').remove();
                Swal.fire({
                  icon: 'error',
                  title: 'Oops...',
                  text: 'No se pudo eliminar el documento!',
                });
              }
            }else{
              $('#loading-indicator').remove();
              Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'No se pudo eliminar el documento!',
              });
            }
          }
        });
      }
    });
  });
});