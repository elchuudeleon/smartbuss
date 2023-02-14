var peticiones=0; 
var item=[]; 
var lista=[];

///////

var peticionesFra=0; 
var itemFra=[]; 
var listaFra=[];

////////
var peticionesComp=0; 
var itemComp=[]; 
var listaComp=[];  
$("body").on("click",".sincronizarProductos",function(){
  $(".infoGuardar").addClass("ocultar"); 
  $("#frmGuardarProductosSincronizados").removeClass("ocultar"); 
})


$("body").on("click",".sincronizarClientes",function(){
  $(".infoGuardar").addClass("ocultar");  
  $("#frmGuardarClientesSincronizados").removeClass("ocultar"); 
})

$("body").on("click",".sincronizarFacturas",function(){
  $(".infoGuardar").addClass("ocultar");  
  $("#frmGuardarFacturasSincronizados").removeClass("ocultar"); 
})

$("body").on("click",".sincronizarComprobantes",function(){
  $(".infoGuardar").addClass("ocultar");  
  $("#frmGuardarComprobantesSincronizados").removeClass("ocultar"); 
})

$("body").on("click",".verDetalle",function(){
  var id=$(this).attr("id")
  var ver=$(".detalle"+id).is(":visible"); 
  $(".fra").addClass("ocultar"); 
  if(!ver){
    $(".detalle"+id).removeClass("ocultar"); 
  }
  
})

$("body").on("click touchstart","#btnSincronizarProductos",function(e){

  e.preventDefault();
  
  if(true === $("#frmGuardarProductosSincronizados").parsley().validate()){
    Swal.fire({
    title: 'Está seguro?',
    text: 'Está a punto de sincronizar los productos de SIIGO a esta empresa!',
    icon: 'warning', 
    showCancelButton: true,
    showLoaderOnConfirm: true,
    confirmButtonText: `Si, Continuar!`,
    cancelButtonText:'Cancelar',
    preConfirm: function(result) {
        return new Promise(function(resolve) {
          var formu = document.getElementById("frmGuardarProductosSincronizados");
          var data = new FormData(formu);
          
         $.ajax({
          url:URL+"functions/siigo/sincronizarproductos.php", 
          type:"POST", 
          data: data,
          contentType:false, 
          processData:false, 
          dataType: "json",
          cache:false 
          }).done(function(msg){  
            if(msg.msg){
              Swal.fire({
                  icon: 'success',
                  title: "Sincronizacion finalizada",
                  text: 'con exito! ',
                  closeOnConfirm: true,
                }).then((result) => {
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
  })


$("body").on("click touchstart","#btnSincronizarClientes",function(e){

  e.preventDefault();
  
  if(true === $("#frmGuardarClientesSincronizados").parsley().validate()){
    Swal.fire({
    title: 'Está seguro?',
    text: 'Está a punto de sincronizar los clientes de SIIGO a esta empresa!',
    icon: 'warning', 
    showCancelButton: true,
    showLoaderOnConfirm: true,
    confirmButtonText: `Si, Continuar!`,
    cancelButtonText:'Cancelar',
    preConfirm: function(result) {
        return new Promise(function(resolve) {
          item=[]; 
          peticiones=$("#tableClientes tbody tr").length; 
          $("#tableClientes tbody tr").each(function(index, element){
                item.push({"tipoPersona":$(element).find(".tipoPersona").val(),
                "tipoIdentificacion":$(element).find(".tipoIdentificacion").val(),
                "nombre":$(element).find(".nombre").val(),
                "identification":$(element).find(".identification").val(),
                "digito":$(element).find(".digito").val(),
                "telefono":$(element).find(".telefono").val(),
                "direccion":$(element).find(".direccion").val(),
                "email":$(element).find(".email").val(),
                "iva":$(element).find(".iva").is(":checked")?1:0,
                })
                
                if(((index+1)%50)==0||(index+1)==peticiones){
                  lista.push(item)
                  item=[]; 
                }
               if((index+1)==peticiones){
                  enviarDatos(0);
               }
            })
             
        });
      }
    })
  }
})

function enviarDatos(contador){
  if(lista[contador]!=undefined){
      var data = new FormData();
      
     data.append("itemCliente", JSON.stringify(lista[contador]))

     $.ajax({
      url:URL+"functions/siigo/sincronizarclientes.php", 
      type:"POST", 
      data: data,
      contentType:false, 
      processData:false, 
      dataType: "json",
      cache:false 
      }).done(function(msg){  
        if(msg.msg){
          contador++; 
          setTimeout(function(){
            enviarDatos(contador); 
          },2500) 
          
        }else{
          Swal.fire(
            'Algo ha salido mal!',
            'Verifique su conexión a internet',
            'error'
          )
          
        }
    });
    } else{
      Swal.fire({
        icon: 'success',
        title: "Sincronizacion finalizada",
        text: 'con exito! ',
        closeOnConfirm: true,
      }).then((result) => {
       location.reload();  
      })
    }
}


$("body").on("click touchstart","#btnSincronizarFra",function(e){

  e.preventDefault();
  
  if(true === $("#frmGuardarFacturasSincronizados").parsley().validate()){
    Swal.fire({
    title: 'Está seguro?',
    text: 'Está a punto de sincronizar las facturas de SIIGO a esta empresa!',
    icon: 'warning', 
    showCancelButton: true,
    showLoaderOnConfirm: true,
    confirmButtonText: `Si, Continuar!`,
    cancelButtonText:'Cancelar',
    preConfirm: function(result) {
        return new Promise(function(resolve) {
          itemFra=[]; 
          peticionesFra=$("#tableFacturas tbody tr.info").length; 
          $("#tableFacturas tbody tr.info").each(function(index, element){
                var productos=[]
                $("#tableFacturas tbody tr.productos").eq(index).find("li").each(function(indexItem, elementItem){
                  var impuestos=[];
                  $(elementItem).find(".codigoImpuesto").each(function(indexImpuesto, elementImpuesto){
                    impuestos.push({
                      "codigoImpuesto":$(elementItem).find(".codigoImpuesto").eq(indexImpuesto).val(),
                      "porcentajeImpuesto":$(elementItem).find(".porcentajeImpuesto").eq(indexImpuesto).val(),
                      "valorImpuesto":$(elementItem).find(".valorImpuesto").eq(indexImpuesto).val(),
                      "tipoImpuesto":$(elementItem).find(".tipoImpuesto").eq(indexImpuesto).val(),
                    })
                  })
                  productos.push({
                    "codigo":$(elementItem).find(".codigo").val(),
                    "descripcion":$(elementItem).find(".descripcion").val(),
                    "cantidad":$(elementItem).find(".cantidad").val(),
                    "valorItem":$(elementItem).find(".valorItem").val(),
                    "totalItem":$(elementItem).find(".totalItem").val(),
                    "impuestos":impuestos,
                  })
                })
                itemFra.push({
                  "nroFactura":$(element).find(".nroFactura").val(),
                  "fechaRegistro":$(element).find(".fechaRegistro").val(),
                  "fechaFactura":$(element).find(".fechaFactura").val(),
                  "tercero":$(element).find(".tercero").val(),
                  "subtotal":$(element).find(".subtotal").val(),
                  "total":$(element).find(".total").val(),
                  "metodopago":$(element).find(".metodopago").val(),
                  "fechaVencimiento":$(element).find(".fechaVencimiento").val(),
                  "observaciones":$(element).find(".observaciones").val(),
                  "estadoFinanciero":$(element).find(".estadoFinanciero").val(),
                  "usuario":$(element).find(".usuario").val(),
                  "productos":productos,
                })
                
                if(((index+1)%100)==0||(index+1)==peticionesFra){
                  listaFra.push(itemFra)
                  itemFra=[]; 
                }
               if((index+1)==peticionesFra){
                  enviarDatosFacturas(0);
               }
            })
             
        });
      }
    })
  }
})

function enviarDatosFacturas(contador){
  if(listaFra[contador]!=undefined){
      var data = new FormData();
      
     data.append("itemFactura", JSON.stringify(listaFra[contador]))

     $.ajax({
      url:URL+"functions/siigo/sincronizarfacturas.php", 
      type:"POST", 
      data: data,
      contentType:false, 
      processData:false, 
      dataType: "json",
      cache:false 
      }).done(function(msg){  
        if(msg.msg){
          contador++; 
          setTimeout(function(){
            enviarDatosFacturas(contador); 
          },2500) 
          
        }else{
          Swal.fire(
            'Algo ha salido mal!',
            'Verifique su conexión a internet',
            'error'
          )
          
        }
    });
    } else{
      Swal.fire({
        icon: 'success',
        title: "Sincronizacion finalizada",
        text: 'con exito! ',
        closeOnConfirm: true,
      }).then((result) => {
       location.reload();  
      })
    }
}

$("body").on("change",".completar",function(e){
  var atributo=$(this).attr("tipo")
  var valor=$(this).val(); 
  $(".completar[tipo='"+atributo+"']").each(function(){
    $(this).val(valor);
  })
})

$("body").on("click touchstart","#btnSincronizarCompro",function(e){

  e.preventDefault();
  
  if(true === $("#frmGuardarComprobantesSincronizados").parsley().validate()){
    Swal.fire({
    title: 'Está seguro?',
    text: 'Está a punto de sincronizar los comprobantes de SIIGO a esta empresa!',
    icon: 'warning', 
    showCancelButton: true,
    showLoaderOnConfirm: true,
    confirmButtonText: `Si, Continuar!`,
    cancelButtonText:'Cancelar',
    preConfirm: function(result) {
        return new Promise(function(resolve) {
          itemComp=[]; 
          peticionesComp=$(".cuadro-comprobante").length; 
          $(".cuadro-comprobante").each(function(index, element){
                var item=[];
                var validarProveedor=true; 
                $(".cuadro-comprobante").eq(index).find("tbody tr.itemComprobante").each(function(indexItem, elementItem){
                  if($(elementItem).find(".idTercero").val()==""||$(elementItem).find(".idCuentaContable").val()==""){
                    validarProveedor=false; 
                  }else{
                    item.push({
                      "cuentaContable":$(elementItem).find(".cuentaContable").val(),
                      "idCuentaContable":$(elementItem).find(".idCuentaContable").val(),
                      "idTercero":$(elementItem).find(".idTercero").val(),
                      "tipoTercero":$(elementItem).find(".tipoTercero").val(),
                      "descripcion":$(elementItem).find(".descripcion").val(),
                      "movimiento":$(elementItem).find(".movimiento").val(),
                      "valor":$(elementItem).find(".valor").val(),
                    })
                  }
                  
                })
                if(validarProveedor){
                  itemComp.push({
                    "codigo":$(element).find(".codigo").val(),
                    "codigoOculto":$(element).find(".codigoOculto").val(),
                    "fechaComprobante":$(element).find(".fechaComprobante").val(),
                    "tipoDocumento":$(element).find(".completar").val(),
                    "items":item,
                  })
                }
                
                
                if(((index+1)%100)==0||(index+1)==peticionesComp){
                  listaComp.push(itemComp)
                  itemComp=[]; 
                }
               if((index+1)==peticionesComp){
                  enviarDatosComprobantes(0);
               }
            })
             
        });
      }
    })
  }
})

function enviarDatosComprobantes(contador){
  if(listaComp[contador]!=undefined){
      var data = new FormData();
      
     data.append("itemComprobante", JSON.stringify(listaComp[contador]))

     $.ajax({
      url:URL+"functions/siigo/sincronizarcomprobantes.php", 
      type:"POST", 
      data: data,
      contentType:false, 
      processData:false, 
      dataType: "json",
      cache:false 
      }).done(function(msg){  
        if(msg.msg){
          contador++; 
          setTimeout(function(){
            enviarDatosComprobantes(contador); 
          },2500) 
          
        }else{
          Swal.fire(
            'Algo ha salido mal!',
            'Verifique su conexión a internet',
            'error'
          )
          
        }
    });
    } else{
      Swal.fire({
        icon: 'success',
        title: "Sincronizacion finalizada",
        text: 'con exito! ',
        closeOnConfirm: true,
      }).then((result) => {
       location.reload();  
      })
    }
}