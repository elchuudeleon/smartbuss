var peticiones=0; 
var lista=[];
var cantNoExite=0; 
$("#excel").change(function(e){
  var aCuentasCargadas=[]; 
  if($("[name='datos[idEmpresa]']").val()!=""){
    Swal.fire({
      title: 'Cargando!',
      html: 'Espere',
      allowOutsideClick: false,
      allowEscapeKey: false,
      didOpen: () => {
         Swal.showLoading()
        contError=0; 
    // $.ajax({
    //     url:URL+"functions/cuentascontables/cargarcuentascontables.php", 
    //     type:"POST", 
    //     data: {"idEmpresa":id}, 
    //     dataType: "json",
    //     }).done(function(msg){  
    //       aCuentasCargadas=msg; 
          $("#divComprobanteCargado").html('');
          var reader = new FileReader();
          reader.readAsArrayBuffer(e.target.files[0]);
          reader.onload = function(e){
            var data = new Uint8Array(reader.result);
            var wb = XLSX.read(data,  {type:"array"});
            var name = wb.SheetNames[0];
            var nameS=name.toString();
            
            var regex = /(\d+)/g;
            var filas = wb.Sheets[nameS]['!ref'].split(':');
            var numeroFilas=filas[1].match(regex)
          
            var tabla=0;
            var sHtml='<div class="col-md-12"><table class="table table-striped mayusculas" id="tableComprobantes">'+
            '<thead><th>Codigo Cuenta</th>'+
            '<th>Nombre</th>'+
            '<th>Clase</th>'+
            '<th>Detalles</th>'+
            '<th>C. Costo</th>'+
            '<th>Tercero</th>'+
            '<th>% Retención</th>'+
            '<th>Naturaleza</th>'+
            '</thead><tbody>';
            var eHtml='';
            var debito='0';
            var credito='0';
            var j =0;
            var cont=0;
            var tipoC='';
            var tipo='';
            var comprobanteT='';
            var numero=0;
            var control=1;
            var fecha='';
            var corrido=0;

            var totalDebitos=0.00;
            var totalCreditos=0.00;

            var debitos=0.00;
            var creditos=0.00;



            //if (numeroFilas<=500) {

            var error=0; 
            for (var i = 8; i <= numeroFilas; i++) {
                if (wb.Sheets[nameS]['A'+i] != undefined) {
                  // if (control==1) {
                  //   tipoC=wb.Sheets[nameS]['B'+i].w.split('-');
                  //   tipo=tipoC[0];
                  //   comprobanteT=parseInt(tipoC[1]);
                  //   //numero=parseInt(wb.Sheets[nameS]['C'+i].w);
                  //   //fecha=wb.Sheets[nameS]['A'+i].w;
                  //   //sHtml+='<div class="row"><div class="col-md-3"><div class="form-group"><label class="negrita">Tipo:</label><input type="text" class="form-control" name="item['+tabla+'][tipo]" value="'+tipo+'" readonly></div></div><div class="col-md-3"><div class="form-group"><label class="negrita">Comprobante:</label><input type="text" class="form-control" name="item['+tabla+'][comprobante]" value="'+comprobanteT+'" readonly></div></div><div class="col-md-3"><div class="form-group"><label class="negrita">Fecha:</label><input type="text" class="form-control" name="item['+tabla+'][fecha]" value="'+fecha+'" readonly></div></div><div class="col-md-3"><div class="form-group"><label class="negrita">Número:</label><input type="text" class="form-control" name="item['+tabla+'][numero]" value="'+numero+'" readonly></div></div></div>';  
                  //   // $("#divEncabezado").append(eHtml);
                  // }
                  if (wb.Sheets[nameS]['N'+i]!=undefined) {
                    // if (wb.Sheets[nameS]['N'+i].w==undefined) {
                    //   if (wb.Sheets[nameS]['N'+i].v!=undefined) {
                    //     debito=wb.Sheets[nameS]['N'+i].v;
                    //   }
                    //   if (wb.Sheets[nameS]['N'+i].v==undefined) {
                    //     debito=' 0.0 ';
                    //   }
                    // }
                    // if () {

                    // }
                    // if (wb.Sheets[nameS]['N'+i].w!=undefined) {
                    //   if (isNaN(eliminarMoneda(wb.Sheets[nameS]['N'+i].w,",",""))) {
                    //       if (wb.Sheets[nameS]['O'+i].w!=undefined) {
                    //         debito=wb.Sheets[nameS]['O'+i].w.trim();
                    //       }
                    //       if (wb.Sheets[nameS]['O'+i].w==undefined) {
                    //         debito=wb.Sheets[nameS]['O'+i].v;
                    //       }
                    //       if (wb.Sheets[nameS]['P'+i]!=undefined) {
                    //         if (wb.Sheets[nameS]['P'+i].w!=undefined) {
                    //           credito=wb.Sheets[nameS]['P'+i].w.trim();
                    //         }
                    //         if (wb.Sheets[nameS]['P'+i].w==undefined) {
                    //           credito=wb.Sheets[nameS]['P'+i].v;
                    //         }
                    //       }
                          

                    //       var centroCosto=wb.Sheets[nameS]['I'+i].w.trim(); 
                    //       var subcentroCosto=wb.Sheets[nameS]['J'+i].w.trim(); 
                    //       var detalle = wb.Sheets[nameS]['N'+i].w.trim();
                    //       var base = wb.Sheets[nameS]['K'+i].w.trim();

                    //         corrido=1;
                    //   }
                    //   if (!isNaN(eliminarMoneda(wb.Sheets[nameS]['N'+i].w,",",""))) {
                    //     debito=wb.Sheets[nameS]['N'+i].w.trim();

                    //     if (wb.Sheets[nameS]['O'+i]!=undefined) {
                    //       if (wb.Sheets[nameS]['O'+i].w!=undefined) {
                    //         credito=wb.Sheets[nameS]['O'+i].w.trim();
                    //       }
                    //       if (wb.Sheets[nameS]['O'+i].w==undefined) {
                    //         credito=wb.Sheets[nameS]['O'+i].v;
                    //       }
                    //     }

                    //     corrido=0;
                    //   }
                    // }
                  }
                  // if (corrido==0) {
                  //   if (wb.Sheets[nameS]['O'+i]!=undefined) {
                  //     if (wb.Sheets[nameS]['O'+i].w==undefined) {
                  //       if (wb.Sheets[nameS]['O'+i].v!=undefined) {
                  //         credito=wb.Sheets[nameS]['O'+i].v;
                  //       }
                  //       if (wb.Sheets[nameS]['O'+i].v==undefined) {
                  //         credito='0.0';
                  //       }
                  //     }
                      
                  //     if (wb.Sheets[nameS]['O'+i].w!=undefined) {
                  //       credito=wb.Sheets[nameS]['O'+i].w.trim();
                  //     }
                  //   }  
                  // }
                  

                //var cuentaC=wb.Sheets[nameS]['F'+i].w+'-'+wb.Sheets[nameS]['G'+i].w;
                // if (wb.Sheets[nameS]['F'+i].w=='0000000000') {
                //   var tercero=wb.Sheets[nameS]['E'+i].w;
                // }
                // if (wb.Sheets[nameS]['F'+i].w!='0000000000') {
                //   var tercero=wb.Sheets[nameS]['E'+i].w.trim();
                // }

                  // var centroCosto=$(elemento).val();
                  // if (corrido==0) {
                  //   // if (wb.Sheets[nameS]['H'+i]!=undefined) {
                  //   //   if (wb.Sheets[nameS]['H'+i].w==undefined) {
                  //   //     if (wb.Sheets[nameS]['H'+i].v!=undefined) {
                        
                  //   //       var centroCosto=wb.Sheets[nameS]['H'+i].v.trim(); 
                  //   //     }
                        
                  //   //   }
                  //   //   if (wb.Sheets[nameS]['H'+i].w!=undefined) {
                        
                  //   //     var centroCosto=wb.Sheets[nameS]['H'+i].w.trim(); 
                  //   //   }
                  //   // }
                  //   // if (wb.Sheets[nameS]['H'+i]==undefined) {
                  //   //   var centroCosto='';
                  //   // }


                  //   if (wb.Sheets[nameS]['M'+i].w!=undefined) {
                  //     var detalle = wb.Sheets[nameS]['M'+i].w.trim();
                  //   }
                  //   if (wb.Sheets[nameS]['M'+i]==undefined) {
                  //     var detalle = '';
                  //   }

                  //   if (wb.Sheets[nameS]['J'+i]==undefined) {
                  //     var base = '';
                  //   }
                  //   if (wb.Sheets[nameS]['J'+i]!=undefined) {

                  //     if (wb.Sheets[nameS]['J'+i].w!=undefined) {
                  //       var base = wb.Sheets[nameS]['J'+i].w.trim();
                  //     }else{
                  //       var base='';
                  //     }
                    
                  //   }

                  //   var subcentroCosto=wb.Sheets[nameS]['I'+i].w.trim(); 
                    
                  // }
                  var letreroCC=0;
               
                var findCuenta=false; 
                var idCuentaContable=0; 
                // aCuentasCargadas.forEach(function(element, e){
                  
                //   if(element.codigoCuentaContable==wb.Sheets[nameS]['F'+i].w||wb.Sheets[nameS]['F'+i].w=="0000000000"){
                //     findCuenta=true; 
                //     idCuentaContable=element.idCuentaContable; 
                //   }
                // })
                var codigoCuenta=wb.Sheets[nameS]['A'+i].w.trim(); 
                
                if(codigoCuenta.length>4){
                    var sValidar=true;
                    if(wb.Sheets[nameS]['E'+i]!=undefined){
                        var clase=wb.Sheets[nameS]['E'+i].w.trim()
                        switch (clase) {
                          case "Act":
                            var sClase="Activo"; 
                            var iClase=1; 
                          break;
                          case "Pas":
                            var sClase="Pasivo";
                            var iClase=2; 
                          break;
                          case "Pat":
                            var sClase="Patrimonio"; 
                            var iClase=3;
                          break;
                          case "Ing":
                            var sClase="Ingreso"; 
                            var iClase=4;
                          break;
                          case "Egr":
                            var sClase="Gasto"; 
                            var iClase=5;
                          break;
                          case "Cos":
                            var sClase="Costo"; 
                            var iClase=6;
                          break;
                          case "Ord":
                            var sClase="Orden"; 
                            var iClase=7;
                          break;
                          default:
                            var sClase='<select class="form-control clase" name="item['+cont+'][clase]" id="item['+cont+'][clase]" required>'+
                                  '<option value="">Seleccione una opción</option>'+
                                  '<option value="1">Activo</option>'+
                                  '<option value="2">Pasivo</option>'+
                                  '<option value="3">Patrimonio</option>'+
                                  '<option value="4">Ingreso</option>'+
                                  '<option value="5">Gasto</option>'+
                                  '<option value="6">Costo</option>'+
                                  '<option value="7">Orden</option>'+
                                '</select>'; 
                            var iClase="";
                            //sValidar=false;
                            //error++; 
                          break;
                        }
                    }else{
                        var sClase='<select class="form-control clase" name="item['+cont+'][clase]" id="item['+cont+'][clase]" required>'+
                                  '<option value="">Seleccione una opción</option>'+
                                  '<option value="1">Activo</option>'+
                                  '<option value="2">Pasivo</option>'+
                                  '<option value="3">Patrimonio</option>'+
                                  '<option value="4">Ingreso</option>'+
                                  '<option value="5">Gasto</option>'+
                                  '<option value="6">Costo</option>'+
                                  '<option value="7">Orden</option>'+
                                '</select>'; 
                            var iClase="";
                    }
                    

                    //console.log(i, codigoCuenta, wb.Sheets[nameS]['H'+i])
                    if(wb.Sheets[nameS]['H'+i]!=undefined){
                      var tercero=wb.Sheets[nameS]['H'+i].w.trim()
                      var retencion="";
                      switch (tercero) {
                        case "Terce":
                          var sTercero="Si Tercero"; 
                          var iTercero=1; 
                        break;
                        case "Reten":
                          var sTercero="Retenciòn";
                          var iTercero=3; 
                          retencion=parseFloat(wb.Sheets[nameS]['L'+i].v);
                          if(isNaN(retencion)){
                            retencion=0;
                          }
                        break;
                        default:
                          var sTercero="No Tercero"; 
                          var iTercero=2;
                        break;
                      }
                    }else{
                        var sTercero="No Tercero"; 
                          var iTercero=2;
                    }
                    
                    if(wb.Sheets[nameS]['F'+i]!=undefined){
                        var detalle=wb.Sheets[nameS]['F'+i].w.trim()
                        switch (detalle) {
                          case "Cobrar":
                            var sDetalle="Por Cobrar"; 
                            var iDetalle=2; 
                          break;
                          case "Pagar":
                            var sDetalle="Por Pagar";
                            var iDetalle=3; 
                          break;
                          default:
                            var sDetalle="No Detalla"; 
                            var iDetalle=1;
                          break;
                        }
                    }else{
                      var sDetalle="No Detalla"; 
                      var iDetalle=1;
                    }
                    
                    if(wb.Sheets[nameS]['G'+i]!=undefined){
                        if(wb.Sheets[nameS]['G'+i].w.trim()==""){
                          var ccosto="No"; 
                        }else{
                          var ccosto="Si"; 
                        }
                    }else{
                      var ccosto="No"; 
                    }
                    
                    
                    sHtml+='<tr style="background-color: '
                    if(!sValidar){
                      sHtml+='#d10d0d'; 
                    }
                    sHtml+='">';
                    sHtml+='<td><input type="text" name="item['+cont+'][codigo]" id="item['+cont+'][codigo]" readonly class="form-control codigo"  placeholder="Codigo Cuenta" value="'+codigoCuenta+'" readonly>'+
                    '</td>';
                    
                
                    sHtml+='<td><input type="text" name="item['+cont+'][nombre]" id="item['+cont+'][nombre]" class="form-control mayusculas nombre"  placeholder="Nombre" value="'+wb.Sheets[nameS]['B'+i].w.trim()+'"></td>';  
                    if(iClase!=""){
                       sHtml+='<td><input type="text" name="item['+cont+'][sClase]" id="item['+cont+'][sClase]" class="form-control subcentroCosto mayusculas"  placeholder="Clase" value="'+sClase+'" readonly>'+
                    '<input type="hidden" name="item['+cont+'][clase]" id="item['+cont+'][clase]" class="clase" value="'+iClase+'" readonly>'
                    +'</td>';
                    }else{
                      sHtml+='<td>'+sClase
                    +'</td>';
                    }
                   
                    
                     sHtml+='<td><input type="text" name="item['+cont+'][sDetalle]" id="item['+cont+'][sDetalle]" class="form-control subcentroCosto mayusculas"  placeholder="Detalle" value="'+sDetalle+'" readonly>'+
                    '<input type="hidden" name="item['+cont+'][detalle]" id="item['+cont+'][detalle]"  value="'+iDetalle+'" class="detalle" readonly>'
                    +'</td>';
                    sHtml+='<td><input type="text" name="item['+cont+'][costo]" id="item['+cont+'][costo]" class="form-control base mayusculas monedaComaPunto costo"   placeholder="Centro Costo" value="'+ccosto+'" readonly></td>';
                    sHtml+='<td><input type="text" name="item['+cont+'][sTercero]" id="item['+cont+'][sTercero]" class="form-control mayusculas"  placeholder="Tercero" value="'+sTercero+'" readonly>'+
                    '<input type="hidden" name="item['+cont+'][tercero]" id="item['+cont+'][tercero]" value="'+iTercero+'" class="tercero" readonly>'
                    +'</td>';
                    sHtml+='<td><input type="text" name="item['+cont+'][retencion]" id="item['+cont+'][retencion]" class="form-control debito mayusculas monedaComaPunto retencion"  placeholder="% Retencion" value="'+retencion+'" readonly></td>';
                    sHtml+='<td><select class="form-control naturaleza" name="item['+cont+'][naturaleza]" id="item['+cont+'][naturaleza]" required>'+
                              '<option value="">Seleccione una opción</option>'+
                              '<option value="debito">Débito</option>'+
                              '<option value="credito">Crédito</option>'+
                            '</select></td>';

                    // if (debito!='' && credito!='') {
                      
                    //   debitos=parseFloat(eliminarMoneda(debito.toString(),",",""));
                    //   creditos=parseFloat(eliminarMoneda(credito.toString(),",",""));

                    //   if (numero=='2107062') {

                    //     console.log('creditos:');
                    //     console.log(creditos);

                    //     console.log('debitos=>');
                    //     console.log(debitos);
                    //   }


                    //   totalDebitos=totalDebitos+parseFloat(Math.round((debitos)*100)/100);
                    //   totalCreditos=totalCreditos+parseFloat(Math.round((creditos)*100)/100);
                    //   if (numero=='2107062') {

                    //     console.log('debitosTotal=>');
                    //     console.log(totalDebitos);
                    //     console.log('creditosTotal=>:');
                    //     console.log(totalCreditos);
                    //   }
                    // }
                    
                    sHtml+='</tr>';
                    cont++;
                    control=0;
                }
                
              }
              //console.log(wb.Sheets[nameS]['A'+i], "validador", wb.Sheets[nameS]['A'+i] == undefined, i==numeroFilas, i, numeroFilas)
              if (wb.Sheets[nameS]['A'+i] == undefined||i==numeroFilas[0]) {
                if(sHtml!=''){

                  // if (totalDebitos.toFixed(2)!=totalCreditos.toFixed(2)) {

                  //   sHtml+='<tr><td class="negrita">TOTAL</td><td></td><td></td><td></td><td></td><td></td><td><input type="text"  id="totalDebito['+tabla+']" class="form-control totalDebito mayusculas moneda"  placeholder="total debito" style="color:red;" total="1" value="'+totalDebitos.toFixed(2)+'" readonly></td><td><input type="text" id="totalCredito['+tabla+']" class="form-control totalCredito mayusculas moneda"  placeholder="total creditos" style="color:red;" value="'+totalCreditos.toFixed(2)+'" readonly></td></tr>';
                  // }else{
                  //   sHtml+='<tr><td class="negrita">TOTAL</td><td></td><td></td><td></td><td></td><td></td><td><input type="text"  id="totalDebito['+tabla+']" class="form-control totalDebito mayusculas moneda"  placeholder="total debito" total="0" value="'+totalDebitos.toFixed(2)+'" readonly></td><td><input type="text" id="totalCredito['+tabla+']" class="form-control totalCredito mayusculas moneda"  placeholder="total creditos" value="'+totalCreditos.toFixed(2)+'" readonly></td></tr>';

                  // }
                  
                  

                  sHtml+='</tbody></table></div>';
                  //console.log(sHtml)
                  $("#divComprobanteCargado").html(sHtml);

                  if(error<1){
                      $("#btnGuardarInfo").removeClass("ocultar");
                  }else{
                    $("#btnGuardarInfo").addClass("ocultar");
                  }
                  Swal.close()
                }
                j=i+1;
               //  sHtml='';
               //  cont=0;
               //  if (wb.Sheets[nameS]['A'+j] != undefined) {
               //    control=1;

               //  sHtml='<div class="col-md-12"><table class="table table-striped mayusculas" id="tableComprobantes['+tabla+']"><thead><th>Cuenta</th><th>Centro de costo</th> <th>Subcentro costo</th><th>Tercero </th><th>Descripción</th><th>Base</th><th>Débito</th><th>crédito</th></thead><tbody>';
               //   tabla++;
               // }
              }
            }
            // if(contError>0){
            //   $("#btnGuardarInfo").addClass('ocultar');
            // }else{
            //   $("#btnGuardarInfo").removeClass('ocultar');
            // }

           // }else{

           //    $("#btnGuardarInfo").css("display","none");
           //    Swal.fire(
           //      'El archivo super el limite de filas!',
           //      'Por favor verifique que el archivo contenga maximo 300 filas',
           //      'error'
           //    )

           // } //aca toca cerrar el if del numero de filas
           //  // recorrer();
            


          }
      },
      willClose: () => {
        //clearInterval(timerInterval)
      }
    }).then((result) => {
    })
  }
});

$("body").on("click touchstart","#btnGuardarInfo",function(e){
    e.preventDefault();

      
      
      
      
      if(true === $("#frmGuardar").parsley().validate()){

         Swal.fire({
          title: '¿Está seguro?',
          text: 'Está a punto de crear estas cuentas contables!',
          icon: 'warning', 
          showCancelButton: true,
          showLoaderOnConfirm: true,
          confirmButtonText: `Si, Guardar!`,
          cancelButtonText:'Cancelar',
          allowOutsideClick: false,
          allowEscapeKey: false,
          preConfirm: function(result) {
            return new Promise(function(resolve) {
              // var formu = document.getElementById("frmGuardar");
              // var data = new FormData(formu);
              var cantidad=$("#tableComprobantes tbody tr").length; 
              peticiones=Math.ceil(cantidad/50); 
              var item=[]; 
              lista=[];
              $("#tableComprobantes tbody tr").each(function(index, element){
                item.push({"codigo":$(element).find(".codigo").val(),
                  "nombre":$(element).find(".nombre").val(),
                  "clase":$(element).find(".clase").val(),
                  "detalle":$(element).find(".detalle").val(),
                  "costo":$(element).find(".costo").val(),
                  "tercero":$(element).find(".tercero").val(),
                  "retencion":$(element).find(".retencion").val(),
                  "naturaleza":$(element).find(".naturaleza").val(),
              })
                if(((index+1)%50)==0||(index+1)==cantidad){
                  lista.push(item)
                  item=[]; 
                }
                if((index+1)==cantidad){
                  enviarDatos(0)
                }
              })
              
          });
        }
      })
      
      }
  })

$("body").on("change",".sClase",function(e){
  $(this).parents("td").find("input").val($(this).val()); 
})

function enviarDatos(contador){
  //console.log(lista[contador], contador)
  if(lista[contador]!=undefined){
      var data = new FormData();
      data.append("item", JSON.stringify(lista[contador]))
      data.append("idEmpresa",$("[name='datos[idEmpresa]']").val())
      $.ajax({
        url:URL+"functions/cuentascontables/guardarcuentascargado.php", 
        type:"POST", 
        data: data,
        contentType:false, 
        processData:false, 
        dataType: "json",
        cache:false 
        }).done(function(msg){  

          if(msg.msg){
            contador++; 
            if(msg.noRegister.length>0){
              cantNoExite=cantNoExite+msg.noRegister.length
            }
            enviarDatos(contador); 
            //if (msg.fallos.length==0) {
              // var msge=""; 
              
              
          }else{
             Swal.fire(
              'Algo ha salido mal!',
              'Verifique su conexión a internet',
              'error'
            ).then((result) => {
            })
          }
      });
      
  }else{
    var msgS=""; 
    if(cantNoExite>0){
      msgS=cantNoExite+" cuentas no registradas "; 
    }
    Swal.fire(
      {
      icon: 'success',
      title: 'Cuentas contables cargadas! '+msgS,
      text: 'con exito',
      closeOnConfirm: true,
    }
    ).then((result) => {
     location.reload(); 
    })
  } 
}

// $("body").on("change",".naturaleza",function(e){
//   var id=$(this).val()
//   $(".naturaleza").each(function(index, element){
//         $(element).val(id)
//       })
      
// })

// $("body").on("change",".clase",function(e){
//   var id=$(this).val()
//   $("select.clase").each(function(index, element){
//         $(element).val(id)
//       })
      
// })