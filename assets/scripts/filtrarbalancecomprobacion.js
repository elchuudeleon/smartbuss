var aDatos=[];
var tipoDetalle=3;


$(document).ready(function(e){

    var overlay = document.getElementById("overlayImpuestos");
    var popup   = document.getElementById("popupImpuestos");

   
    overlay.classList.add('active');
    popup.classList.add('active');





    if ($("#empresa").val()!=""){
  aDatos=[]; 
  var idEmpresa=$("#empresa").val();
  // alert(idEmpresa);
  $.ajax({

      url:URL+"functions/cuentascontables/cargarcuentascontables.php", 

      type:"POST", 

      data: {"idEmpresa":idEmpresa}, 

      dataType: "json",

      }).done(function(msg){  

        msg.forEach(function(element,index){

          aDatos.push({

              value: element.idCuentaContable,

              label: element.codigoCuentaContable+" - "+element.nombre,

              naturaleza: element.naturaleza,

              // tercero:element.tercero,

              centroCosto:element.centroCosto,

            })

        })
        console.log(aDatos);
        autocomplete(); 


    }); 
  }






});



$('[data-toggle="tooltip"]').tooltip();





autocomplete=function(){

  $( ".cuentaContable" ).autocomplete({
    appendTo:'#exampleModal',

      minLength: 0,
      source: aDatos,

      focus: function( event, ui ) {
        var index=$(this).index(".cuentaContable");

        return false;

      },
      select: function( event, ui ) {
        var index=$(this).index(".cuentaContable");
        $( ".cuentaContable" ).eq(index).val( ui.item.label );        
        var id=ui.item.value;
 
        return false;

      },

      change: function(event, ui){

        var index=$(this).index(".cuentaContable");

        if(ui.item==null){

          // $( ".idCuentaContable" ).eq(index).val('');
          // $( ".letreroCuentaContable" ).eq(index).removeClass('ocultar');

        }

        return false;

      }

    })

}