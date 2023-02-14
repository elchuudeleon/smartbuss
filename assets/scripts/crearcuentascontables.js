var aDatos=[]; 
var aDatosc=[]; 
var aDatoss=[];

$(document).ready(function(e){
  $("#divPorcentajeRetencion").css("display","none");
  idEmpresa=0;

	$.ajax({
	    url:URL+"functions/cuentascontables/cargargrupos.php", 
	    type:"POST", 
	    data: {"idEmpresa":idEmpresa}, 
	    dataType: "json",
	    }).done(function(msg){  
	      msg.forEach(function(element,index){
	      	aDatos.push({
			        value: element.idGrupo,
			        label: element.codigo+" - "+element.denominacion,
			      })
	      })
	      autocomplete(); 
	  });   
});

$("body").on("click touchstart","#btnGuardar",function(e){

    e.preventDefault();
      if(true === $("#frmGuardar").parsley().validate()){
         Swal.fire({
        title: '¿Está seguro?',
        text: 'Está a punto de agregar la cuenta al plan de cuentas contables!',
        icon: 'warning', 
        showCancelButton: true,
        showLoaderOnConfirm: true,
        confirmButtonText: `Si, Guardar!`,
        cancelButtonText:'Cancelar',
        preConfirm: function(result) {
          return new Promise(function(resolve) {
            var formu = document.getElementById("frmGuardar");
            var data = new FormData(formu);
            $.ajax({
            url:URL+"functions/cuentascontables/guardarcuentacontable.php", 
            type:"POST", 
            data: data,
            contentType:false, 
            processData:false, 
            dataType: "json",
            cache:false 
            }).done(function(msg){  

              if(msg.msg){
                Swal.fire(
                  {
                  icon: 'success',
                  title: 'Cuenta contable agregada!',
                  text: 'con exito',
                  closeOnConfirm: true,
                }
              ).then((result) => {
                 location.reload(); 
                })
              }else{
                 Swal.fire(
                  'Algo ha salido mal!',
                  'Verifique su conexión a internet',
                  'error'
                )
              }
            });
          });
        }
      })
      }
  });

$(".grupo").on( 'change', function() {
    $("#idCuenta").val('')
    $("#cuenta").val('')
    autocomplete();
})


autocomplete=function(){
  $( ".grupo" ).autocomplete({
      minLength: 0,
      source: aDatos,
      focus: function( event, ui ) {
        var index=$(this).index(".grupo");
        $( ".grupo" ).eq(index).val( ui.item.label );
        $( ".idGrupo" ).eq(index).val( ui.item.value );

        return false;

      },

      select: function( event, ui ) {
        var index=$(this).index(".grupo");
        $( ".grupo" ).eq(index).val( ui.item.label );
        $( ".idGrupo" ).eq(index).val( ui.item.value );
                var id=ui.item.value;
        aDatosc=[];
        aDatoss=[];
        $.ajax({
        url:URL+"functions/cuentascontables/cargarcuentas.php", 
        type:"POST", 
        data: {"id":id}, 
        dataType: "json",
        }).done(function(msg){  
        msg.forEach(function(element,index){
          aDatosc.push({
              value: element.idCuenta,
              label: element.codigo+" - "+element.denominacion,
            })
        })
        autocompletec(); 
    }); 
        return false;
      },
      change: function(event, ui){
        var index=$(this).index(".grupo");
        if(ui.item==null){
          $( ".idGrupo" ).eq(index).val('');
        }
        return false;
      }
    })
}



autocompletec=function(){
  $( ".cuenta" ).autocomplete({
      minLength: 0,
      source: aDatosc,
      focus: function( event, ui ) {
        var index=$(this).index(".cuenta");
        $( ".cuenta" ).eq(index).val( ui.item.label );
        $( ".idCuenta" ).eq(index).val( ui.item.value );
      
        return false;
      },
      select: function( event, ui ) {
        var index=$(this).index(".cuenta");
        $( ".cuenta" ).eq(index).val( ui.item.label );
        $( ".idCuenta" ).eq(index).val( ui.item.value );
        var ids=ui.item.value;

        if ($("#idGrupo").val()==12 && ids==134) {
          $("#divPorcentajeRetencion").css("display","block");
          $("#porcentajeRetencion").attr("required","required");
          $('#tercero option[value="3"]').prop('selected', true)
          
        }else if ($("#idGrupo").val()==3 && ids==33) {

          $("#divPorcentajeRetencion").css("display","block");
          $("#porcentajeRetencion").attr("required","required");
          $('#tercero option[value="3"]').prop('selected', true)

        }else{
            $("#porcentajeRetencion").removeAttr("required");
            $("#divPorcentajeRetencion").css("display","none");
            // console.log($("#porcentajeRetencion").val());
            $('#tercero option[value="1"]').prop('selected', true);
        }
        $.ajax({
        url:URL+"functions/cuentascontables/cargarsubcuentas.php", 
        type:"POST", 
        data: {"id":ids}, 
        dataType: "json",
        }).done(function(msg){  
        
        msg.forEach(function(element,index){
          aDatoss.push({
              value: element.idSubcuenta,
              label: element.codigo+" - "+element.denominacion,
            })
        })
        autocompletes(); 
    }); 
        return false;
      },
      change: function(event, ui){
        var index=$(this).index(".cuenta");
        if(ui.item==null){
          $( ".idCuenta" ).eq(index).val('');
        }
        return false;
      }
    })
}

autocompletes=function(){
  $( ".subcuenta" ).autocomplete({
      minLength: 0,
      source: aDatoss,
      focus: function( event, ui ) {
        var index=$(this).index(".subcuenta");
        $( ".subcuenta" ).eq(index).val( ui.item.label );
        $( ".idSubcuenta" ).eq(index).val( ui.item.value );
      
        return false;
      },
      select: function( event, ui ) {
        var index=$(this).index(".subcuenta");
        $( ".subcuenta" ).eq(index).val( ui.item.label );
        $( ".idSubcuenta" ).eq(index).val( ui.item.value );
        return false;
      },
      change: function(event, ui){
        var index=$(this).index(".subcuenta");
        if(ui.item==null){
          $( ".idSubcuenta" ).eq(index).val('');
        }
        return false;
      }
    })
}

$("body").on("click","#checksubcuenta",function(e){
       if( $(this).is(':checked') ) {
        $( "#descripcionSubcuenta" ).removeAttr("readonly","readonly");
        $("#descripcionSubcuenta").removeAttr('disabled').attr("required");
        
        $( "#subcuenta" ).removeAttr("readonly","readonly");
        $("#subcuenta").removeAttr('disabled').attr("required");
        
        $( "[type='checkbox']#checkauxiliar" ).removeAttr("readonly","readonly");
        $("[type='checkbox']#checkauxiliar").removeAttr('disabled').attr("required");
    } else {
        $( "#descripcionSubcuenta" ).attr("readonly","readonly").removeAttr("required");
        $("#descripcionSubcuenta").attr('disabled','disabled');
        
        $( "#subcuenta" ).attr("readonly","readonly").removeAttr("required");
        $("#subcuenta").attr('disabled');
        
        $( "[type='checkbox']#checkauxiliar" ).attr("readonly","readonly");
        $("[type='checkbox']#checkauxiliar").attr('disabled','disabled');
    }
})


$("body").on("click","#checkauxiliar",function(e){
   if( $(this).is(':checked') ) {
        $( "#idAuxiliar" ).removeAttr("readonly");
        $("#idAuxiliar").removeAttr('disabled');
        $( "#auxiliar" ).removeAttr("readonly","readonly");
        $("#auxiliar").removeAttr('disabled');
        $("[type='checkbox']#checksubauxiliar").removeAttr('disabled');
        
        $( "#idAuxiliar" ).attr("required","required");
        $( "#auxiliar" ).attr("required","required");
    } else {
        $( "#idAuxiliar" ).attr("readonly","readonly");
        $("#idAuxiliar").attr('disabled','disabled');
        $( "#auxiliar" ).attr("readonly","readonly");
        $("#auxiliar").attr('disabled','disabled');
        $("[type='checkbox']#checksubauxiliar").attr('disabled','disabled');

        $( "#idAuxiliar" ).removeAttr("required","required");
        $( "#auxiliar" ).removeAttr("required","required");
    } 
})

$("body").on("click","#checksubauxiliar",function(e){
  if( $(this).is(':checked') ) {
        $( "#cuentaSubauxiliar" ).removeAttr("readonly","readonly");
        $("#cuentaSubauxiliar").removeAttr('disabled');
        $( "#subauxiliar" ).removeAttr("readonly","readonly");
        $("#subauxiliar").removeAttr('disabled');

        $( "#idSubauxiliar" ).attr("required","required");
        $( "#subauxiliar" ).attr("required","required");
    } else {
        $( "#cuentaSubauxiliar" ).attr("readonly","readonly");
        $("#cuentaSubauxiliar").attr('disabled','disabled');
        $( "#subauxiliar" ).attr("readonly","readonly");
        $("#subauxiliar").attr('disabled','disabled');

        $( "#idSubauxiliar" ).removeAttr("required","required");
        $( "#subauxiliar" ).removeAttr("required","required");
    }
})


$("#tercero").on( 'change', function() {
    if ($(this).val()=='3') {
      $("#divPorcentajeRetencion").css("display","block");
      $("#porcentajeRetencion").attr("required","required");
    }else{
      $("#divPorcentajeRetencion").css("display","none");
      $("#porcentajeRetencion").removeAttr("required");
    }
});


$('.decimales').keyup(function () { 
    this.value = this.value.replace(/[^0-9\,]/g,'');
});



$(".auxiliar").on( 'change', function() {
    var elemento=this; 
    if($(this).val()!=""){
        var subcuenta=$("#idSubcuenta").val(); 
        $.ajax({
	    url:URL+"functions/cuentascontables/buscarCuentaAuxiliar.php", 
	    type:"POST", 
	    data: {"codigoAuxiliar":$(this).val(),"subcuenta":subcuenta}, 
	    dataType: "json",
	    }).done(function(msg){  
            if(msg!=null){
                $(elemento).parents("tr").find(".nombreAuxiliar").val(msg.denominacion).attr("readonly","readonly")
                $(elemento).parents("tr").find(".cuentaAuxiliar").val(msg.idAuxiliar)
            }else{
                $(elemento).parents("tr").find(".nombreAuxiliar").removeAttr("readonly").val("")
                $(elemento).parents("tr").find(".cuentaAuxiliar").val("").removeAttr("readonly")
            }
	      

	  }); 
    }
    
});


$(".idSubauxiliar").on( 'change', function() {
    var elemento=this; 
    if($(this).val()!=""){
        var cuentaAuxiliar=$("#cuentaAuxiliar").val(); 
        $.ajax({
	    url:URL+"functions/cuentascontables/buscarCuentaSubaxiliar.php", 
	    type:"POST", 
	    data: {"codigoSubauxiliar":$(this).val(),"auxiliar":cuentaAuxiliar}, 
	    dataType: "json",
	    }).done(function(msg){  
            if(msg!=null){
                $(elemento).parents("tr").find(".nombresubaxiliar").val(msg.denominacion).attr("readonly","readonly")
                $(elemento).parents("tr").find(".cuentaSubAuxiliar").val(msg.idSubauxiliar)
            }else{
                $(elemento).parents("tr").find(".nombresubaxiliar").removeAttr("readonly").val("")
                $(elemento).parents("tr").find(".cuentaSubAuxiliar").val("").removeAttr("readonly")
            }
	      

	  }); 
    }
    
});

$('[data-toggle="tooltip"]').tooltip();