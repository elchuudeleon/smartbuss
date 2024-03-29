$("body").on("change","[name='datos[idEmpresa]']",function(e){

    var id=$(this).val(); 



    if(id!=""){

      $("#periodoPago").val($(this).find("option:selected").attr("pago"));



      if($(this).find("option:selected").attr("pago")==1){

        $("[name='datos[tiempoPago]']").removeAttr("required"); 

        $("[name='datos[tiempoPago]']").attr("disabled","disabled"); 

        $("[name='datos[tiempoPago]']").val(''); 

      }else if($(this).find("option:selected").attr("pago")==2){

        $("[name='datos[tiempoPago]']").attr("required","required"); 

        $("[name='datos[tiempoPago]']").removeAttr("disabled"); 

      }

      $.ajax({

        url:URL+"functions/nomina/empleados.php", 

        type:"POST", 

        data: {"idEmpresa":id}, 

        dataType: "json",

        }).done(function(msg){  

          var sHtml="<option value=''>Seleccione una opción</option>"; 

          msg.lista.forEach(function(element,index){

            sHtml+="<option value='"+element.idEmpleado+"'>"+element.nombre+" "+element.apellido+"</option>"; 

          })



          $("[name='datos[idEmpleado]']").html(sHtml);

      });

    }else{

      $("[name='datos[idEmpleado]']").html("<option value=''>Seleccione una opción</option>");

    }

})



$("body").on("change","[name='datos[idEmpleado]'], [name='datos[periodo]']",function(e){

    var idEmpleado=$("[name='datos[idEmpleado]']").val();

    $("#idEliminar").val(idEmpleado);
    if (idEmpleado!="") {
      $("#divEliminar").removeClass('ocultar');
    } 
    if (idEmpleado=="") {
      $("#divEliminar").addClass('ocultar');
    } 

    var idPeriodo=$("[name='datos[periodo]']").val();

    var idEmpresa=$("[name='datos[idEmpresa]']").val(); 

    var periodoPago=$("#periodoPago").val(); 

    if(idEmpleado!=""&&idPeriodo!=""){

      $.ajax({

        url:URL+"functions/nomina/informacionempleado.php", 

        type:"POST", 

        data: {"idEmpresa":idEmpresa,"idEmpleado":idEmpleado,"idPeriodo":idPeriodo,"tiempo":periodoPago}, 

        dataType: "json",

        }).done(function(msg){  

          $("[name='datos[salario]']").val(msg.valorSalario).trigger("change");

          $("[name='datos[riesgo]']").val(msg.nivelRiesgo);

          $("[name='datos[diasTrabajados]']").val(msg.diasPago);
          $("[name='datos[auxilioTransporteInicial]']").val(msg.auxilioTransporte);

          $("#vacacionesControl").val(msg.vacaciones);
          var vacacionesControl=msg.vacaciones;

          var sHtmlA=""; 



          if(msg.adiciones!=null){

            msg.adiciones.forEach(function(element,index){

              sHtmlA+='<tr>'+

                      '<td class="text-center">'+(index+1)+'</td>'+

                      '<td><input type="text" name="adiciones['+index+'][producto]" id="adiciones['+index+'][producto]" class="form-control producto mayusculas" value="'+element.texto+'" required placeholder="Detalle">'+

                        '<input type="hidden" name="adiciones['+index+'][idProducto]" id="adiciones['+index+'][idProducto]" class="form-control idProducto" value="'+element.idTipo+'"></td>'+
                        '<input type="hidden" name="adiciones['+index+'][idNovedad]" id="adiciones['+index+'][idNovedad]" class="form-control idNovedad" value="'+element.idNovedad+'"></td>'+

                      '<td><input type="text" class="form-control moneda mayusculas valor" readonly value="'+element.valor+'" name="adiciones['+index+'][valor]" id="adiciones['+index+'][valor]" placeholder="valor" required></td>'+

                      '<td style="text-align:center"><a href="javascript:void(0)"  data-toggle="tooltip" data-placement="top" title="Eliminar" class="btn btn-icon btn-sm btn-danger eliminar"><i class="fas fa-trash"></i></a></td>'+

                    '</tr>';  

            })

          }

          

          

          $("#tableAdiciones tbody").html(sHtmlA);



          var sHtmlL=""; 

          if(msg.deduccionesLey!=null){

            var i=0; 

            msg.deduccionesLey.forEach(function(element,index){

            clase=''; 

            if(element.oculto==1){

              clase='empleador'; 

            }

            sHtmlL+='<tr class="'+clase+'">'+

                    '<td class="text-center">'+(i+1)+'</td>'+

                    '<td><input type="text" name="ley['+index+'][producto]" id="ley['+index+'][producto]" class="form-control producto mayusculas" value="'+element.descripcion+'" required placeholder="Detalle">'+

                      '<input type="hidden" name="ley['+index+'][tipoDeduccion]" id="ley['+index+'][tipoDeduccion]" class="form-control idProducto" value="'+element.oculto+'"></td>'+

                      '<input type="hidden" name="ley['+index+'][tipoConcepto]" id="ley['+index+'][tipoConcepto]" class="form-control idProducto" value="'+element.concepto+'"></td>'+

                    '<td><input type="text" class="form-control moneda mayusculas valor '+clase+'" readonly value="'+element.valor+'" name="ley['+index+'][valor]" id="ley['+index+'][valor]" placeholder="valor" required></td>'+

                    '<td style="text-align:center"><a href="javascript:void(0)"  data-toggle="tooltip" data-placement="top" title="Eliminar" class="btn btn-icon btn-sm btn-danger eliminar"><i class="fas fa-trash"></i></a></td>'+

                  '</tr>';

                i++; 
              // if(element.oculto==0){
              // }

                

          })

          }

          

          $("#tableDeduccionesLey tbody").html(sHtmlL);



          var sHtmlD=""; 

          if(msg.deducciones!=null){

            msg.deducciones.forEach(function(element,index){

            sHtmlD+='<tr>'+

                    '<td class="text-center">'+(index+1)+'</td>'+

                    '<td><input type="text" name="deducciones['+index+'][producto]" id="deducciones['+index+'][producto]" class="form-control producto mayusculas" value="'+element.texto+'" required placeholder="Detalle">'+

                      '<input type="hidden" name="deducciones['+index+'][idProducto]" id="deducciones['+index+'][idProducto]" class="form-control idProducto" value="'+element.idTipo+'"></td>'+
                      '<input type="hidden" name="deducciones['+index+'][idNovedad]" id="deducciones['+index+'][idNovedad]" class="form-control idNovedad" value="'+element.idNovedad+'"></td>'+

                    '<td><input type="text" class="form-control moneda mayusculas valor" readonly value="'+element.valor+'" name="deducciones['+index+'][valor]" id="deducciones['+index+'][valor]" placeholder="valor" required></td>'+

                    '<td style="text-align:center"><a href="javascript:void(0)"  data-toggle="tooltip" data-placement="top" title="Eliminar" class="btn btn-icon btn-sm btn-danger eliminar"><i class="fas fa-trash"></i></a></td>'+

                  '</tr>'; 

          })

          }

          

          $("#tableDeducciones tbody").html(sHtmlD);



          $(".valor").trigger("change"); 

          calcularSalario(); 
          calcularProvisiones();

      });

    }else{

    }

    

})

calcularProvisiones=function(){
  var salarioBase=parseFloat(eliminarMoneda(eliminarMoneda(eliminarMoneda($("[name='datos[salario]']").val(),"$",""),".",""),",","."));
  if ($("#vacacionesControl").val() ==0) {
    var diasTrabajados=$("#diasTrabajados").val();

  }
  if ($("#vacacionesControl").val() ==1 || $("#vacacionesControl").val() ==2) {
    var diasTrabajados=30;
  }
  var salarioDias= (salarioBase/30)*diasTrabajados;
  salarioDias=Math.round(salarioDias);
  
  var auxilioTransporte=parseFloat(eliminarMoneda(eliminarMoneda(eliminarMoneda($("#auxilioTransporteInicial").val(),"$",""),".",""),",","."));
  var valorAuxilioTransporte=(auxilioTransporte/30)*diasTrabajados;
  valorAuxilioTransporte=Math.round(valorAuxilioTransporte);
  

  var cesantias=((salarioDias+valorAuxilioTransporte)*8.333)/100;
  cesantias=Math.round(cesantias);
  var interesesCesantias=((cesantias)*1)/100;
  interesesCesantias=Math.round(interesesCesantias);
  var prima=((salarioDias+valorAuxilioTransporte)*8.333)/100;
  prima=Math.round(prima);
  var vacaciones=((salarioDias)*4.166)/100;
  vacaciones=Math.round(vacaciones);
  
  $("#cesantias").val(cesantias).trigger('change');
  $("#interesesCesantias").val(interesesCesantias).trigger('change');
  $("#prima").val(prima).trigger('change');
  $("#vacaciones").val(vacaciones).trigger('change');
}


calcularSalario=function(){



  var salarioBase=parseFloat(eliminarMoneda(eliminarMoneda(eliminarMoneda($("[name='datos[salario]']").val(),"$",""),".",""),",","."));

  var adiciones=0; 
  var diasTrabajados=$("#diasTrabajados").val();
  var salarioDias= (salarioBase/30)*diasTrabajados;
  var auxilioTransporte=parseFloat(eliminarMoneda(eliminarMoneda(eliminarMoneda($("#auxilioTransporteInicial").val(),"$",""),".",""),",","."));
  var valorAuxilioTransporte=(auxilioTransporte/30)*diasTrabajados;
  $("#auxilioTransporte").val(valorAuxilioTransporte).trigger('change');
  $("#tableAdiciones .valor").each(function(index,element){

    var valor=0; 

    if($(element).val()!=""){valor=parseFloat(eliminarMoneda(eliminarMoneda(eliminarMoneda($(element).val(),"$",""),".",""),",",".")); }

    adiciones+=valor
    

  })
// alert(adiciones);
  var ley=0; 

  $("#tableDeduccionesLey tr:not('[class*=empleador]') .valor").each(function(index,element){

    var valor=0; 

    if($(element).val()!=""){valor=parseFloat(eliminarMoneda(eliminarMoneda(eliminarMoneda($(element).val(),"$",""),".",""),",",".")); }

    ley+=valor

  })



  var deducciones=0; 

  $("#tableDeducciones .valor").each(function(index,element){

    var valor=0; 

    if($(element).val()!=""){valor=parseFloat(eliminarMoneda(eliminarMoneda(eliminarMoneda($(element).val(),"$",""),".",""),",",".")); }

    deducciones+=valor

  })

  

  var total=salarioDias+valorAuxilioTransporte+adiciones-ley-deducciones; 

    

  $("[name='datos[valorPagar]']").val(total).trigger('change')

}

$("body").on("keyup","#diasTrabajados",function(e){
  var cantidadDias= $(this).val();
  if (cantidadDias!="") {
    calcularSalario();
    calcularProvisiones();

  }
})
$("body").on("keyup","#diasSeguridadSocial",function(e){
  var cantidadDiasSeguridadSocial= $(this).val();
  var inputSalud=document.getElementById("ley[0][producto]").value;
  var inputARL=document.getElementById("ley[2][producto]").value;
  var inputCajaCompensacion=document.getElementById("ley[3][producto]").value;
  var inputSaludEmpleador=document.getElementById("ley[4][producto]").value;
  var inputPensionEmpleador=document.getElementById("ley[5][producto]").value;
  var inputValorSalud=document.getElementById("ley[0][valor]");
  var inputValorPension=document.getElementById("ley[1][valor]");
  var inputValorARL=document.getElementById("ley[2][valor]");
  var inputValorCajaCompensacion=document.getElementById("ley[3][valor]");
  var inputValorSaludEmpleador=document.getElementById("ley[4][valor]");
  var inputValorPensionEmpleador=document.getElementById("ley[5][valor]");
  var porcentajeSalud=inputSalud.substring(6,7);
  var porcentajeSalud=inputSalud.substring(6,7);

  var porcentajeARL=inputARL.split(' ');
  var porcentajeARL2=porcentajeARL[3].split('%');


  var porcentajeCajaCompensacion=inputCajaCompensacion.split(' ');
  var porcentajeCajaCompensacion2=porcentajeCajaCompensacion[2].split('%');

  var porcentajeSaludEmpleador=inputSaludEmpleador.split(' ');
  var porcentajeSaludEmpleador2=porcentajeSaludEmpleador[1].split('%');


  var porcentajePensionEmpleador=inputPensionEmpleador.split(' ');
  var porcentajePensionEmpleador2=porcentajePensionEmpleador[1].split('%');


  var salarioBase=parseFloat(eliminarMoneda(eliminarMoneda(eliminarMoneda($("[name='datos[salario]']").val(),"$",""),".",""),",","."));
  var valorSeguridadSocial =(((salarioBase*parseFloat(porcentajeSalud))/100)/30)*cantidadDiasSeguridadSocial;
  var valorARL =(((salarioBase*parseFloat(porcentajeARL2))/100)/30)*cantidadDiasSeguridadSocial;
  var valorCajaCompensacion =(((salarioBase*parseFloat(porcentajeCajaCompensacion2))/100)/30)*cantidadDiasSeguridadSocial;
  var valorSaludEmpleador =(((salarioBase*parseFloat(porcentajeSaludEmpleador2))/100)/30)*cantidadDiasSeguridadSocial;
  var valorPensionEmpleador =(((salarioBase*parseFloat(porcentajePensionEmpleador2))/100)/30)*cantidadDiasSeguridadSocial;
  // alert(valorSeguridadSocial);
  // inputValorSalud.value=valorSeguridadSocial.toFixed(3);
  // inputValorPension.value=valorSeguridadSocial.toFixed(3);
  
  // inputValorSalud.value=valorSeguridadSocial.toFixed(3);
  $("[name='ley[0][valor]']").val(valorSeguridadSocial.toFixed(3)).trigger('change');
  $("[name='ley[1][valor]']").val(valorSeguridadSocial.toFixed(3)).trigger('change');
  $("[name='ley[2][valor]']").val(valorARL.toFixed(3)).trigger('change');
  $("[name='ley[3][valor]']").val(valorCajaCompensacion.toFixed(3)).trigger('change');
  $("[name='ley[4][valor]']").val(valorSaludEmpleador.toFixed(3)).trigger('change');
  $("[name='ley[5][valor]']").val(valorPensionEmpleador.toFixed(3)).trigger('change');
  calcularSalario();
  // calcularSalario();
})
$("body").on("click touchstart",".eliminar",function(e){

  $(this).parents("tr").remove(); 

  calcularSalario(); 

})



$("body").on("click touchstart","#btnGuardar",function(e){
  e.preventDefault();
  Swal.fire({
    title: 'Está seguro?',
    text: 'Está a punto de editar la nomina de este empleado!',
    icon: 'warning', 
    showCancelButton: true,
    showLoaderOnConfirm: true,
    confirmButtonText: `Si, Continuar!`,
    cancelButtonText:'Cancelar',
    preConfirm: function(result) {
        return new Promise(function(resolve) {
          var formu = document.getElementById("frmGuardar");
          var data = new FormData(formu);
          $.ajax({
          url:URL+"functions/nomina/editarnomina.php", 
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
                title: 'Nomina editada!',
                text: 'con exito',
                closeOnConfirm: true,
              }
              ).then((result) => {
               location.reload(); 
              })
            }
          });    
        });
      }
    })
  })


$("body").on("click touchstart","#btnEliminar",function(e){
  e.preventDefault();
  Swal.fire({
    title: 'Está seguro?',
    text: 'Está a punto de eliminar el registro de la nomina de este empleado!',
    icon: 'warning', 
    showCancelButton: true,
    showLoaderOnConfirm: true,
    confirmButtonText: `Si, Continuar!`,
    cancelButtonText:'Cancelar',
    preConfirm: function(result) {
        return new Promise(function(resolve) {
          var formu = document.getElementById("frmGuardar");
          var data = new FormData(formu);
          $.ajax({
          url:URL+"functions/nomina/eliminarnominaempleado.php", 
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
                title: 'Registro empleado eliminado!',
                text: 'con exito',
                closeOnConfirm: true,
              }
              ).then((result) => {
               location.reload(); 
              })
            }
        });    
      });
    }
  })
})


$('[data-toggle="tooltip"]').tooltip();