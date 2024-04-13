$(() => {
  $(document).on("input keydown keyup mousedown mouseup select contextmenu drop focusout", "input[data-valformat=withspacesforthreenumbers]", function(e){
    let val = e.target.value;
    $(this).val(val.replace(/\D+/g, '').replace(/(\d{3})(\d{3})(\d{3})/, '$1 $2 $3'));
    // ------------ Restringir el campo de texot a 11 caracteres...
    if(val.length > 11){
      this.setCustomValidity('Solo se permiten 11 números');
      this.reportValidity();
      $(this).val(val.slice(0, 11));
    }
  });
});