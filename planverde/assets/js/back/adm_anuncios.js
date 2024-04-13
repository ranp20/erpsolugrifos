function is_json(str){
  try{
    JSON.parse(str);
  }catch(e){
    return false;
  }
  return true;
}
var loading = '<h6 class="ajax-loading"><i class="fa fa-spinner fa-spin"></i>Cargando...</h6>';
$(document).on('click', '.status-anuncio', function(){
  let status = "", id = $(this).data('id');
  if($(this).is(":checked")){
    status = $(this).val();
  }else{
    status = "off";
  }
  $.ajax({
    type: "POST",
    url: base_url + 'admin/anuncio/active/' + id + '/' + status,
    dataType: "json",
    success: function(data){
      toastr[data.type](data.message);
      if(status == 1){
        table_url(base_url + "admin/anuncio/anuncioList/");
      }else{
        table_url(base_url + "admin/anuncio/anuncioList/");
      }
    }
  });
});    
function deleteAnuncio($data){
  Swal.fire({
    title: 'Estás seguro?',
    text: "Está acción no es reversible!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Sí, Eliminar!',
    cancelButtonText: "Cancelar",
  }).then((result) =>{
    if(result.isConfirmed){            
      let url = base_url + 'admin/anuncio/delete_anuncio/' + $data;
      let urlReload = base_url + 'admin/anuncio';
      $.ajax({
        type: "POST",
        url: url,
        data:{
          "_method": "DELETE",
        },
        success: function(e){
          if(is_json(e) && e != []){
            let r = JSON.parse(e);
            if(r.type == "success"){
              Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: 'El anuncio se eliminó correctamente!',
              });
              setTimeout(function(){location.href=urlReload}, 500);
            }else{
              Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'No se pudo eliminar el anuncio!',
              });
            }
          }else{
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'No se pudo eliminar el anuncio!',
            });
          }
        }
      });
    }
  });
}
var reader;
var progress = document.querySelector('.percent');
function abortRead(){
  reader.abort();
}
function errorHandler(evt){
  switch(evt.target.error.code){
    case evt.target.error.NOT_FOUND_ERR:
      alert('File Not Found!');
      break;
    case evt.target.error.NOT_READABLE_ERR:
      alert('File is not readable');
      break;
    case evt.target.error.ABORT_ERR:
      break; // noop
    default:
      alert('An error occurred reading this file.');
  };
}
function updateProgress(evt){
  if(evt.lengthComputable){
    var percentLoaded = Math.round((evt.loaded / evt.total) * 100);
    if(percentLoaded < 100){
      evt.target.nextElementSibling.childNodes[0].style.width = percentLoaded + '%';
      evt.target.nextElementSibling.childNodes[0].textContent = percentLoaded + '%';
    }
  }
}
function handleFileSelect(evt){
  evt.target.nextElementSibling.childNodes[0].style.width = '0%';
  evt.target.nextElementSibling.childNodes[0].textContent = '0%';
  reader = new FileReader();
  reader.onerror = errorHandler;
  reader.onprogress = updateProgress;
  reader.onabort = function(e){
    alert('File read cancelled');
  };
  reader.onloadstart = function(e){
    evt.target.nextElementSibling.classList.add("loading");
    // document.getElementById('progress_bar').className = 'loading';
  };
  reader.onload = function(e){
    evt.target.nextElementSibling.childNodes[0].style.width = '100%';
    evt.target.nextElementSibling.childNodes[0].textContent = '100%';
    setTimeout(function(){evt.target.nextElementSibling.classList.remove('loading');}, 2000);
    // setTimeout("document.getElementById('progress_bar').className='';", 2000);
  }
  reader.readAsBinaryString(evt.target.files[0]);
}

document.addEventListener('DOMContentLoaded', function() {
  var photoInput = document.getElementById('photo');
  var attachmentInput = document.getElementById('adjunto');
  if (photoInput) {
    photoInput.addEventListener('change', handleFileSelect, false);
  }
  if (attachmentInput) {
    attachmentInput.addEventListener('change', handleFileSelect, false);
  }
  function handleFileSelect(event) {
    // Código de manejo de archivo...
    console.log('Change event validated');
  }
});