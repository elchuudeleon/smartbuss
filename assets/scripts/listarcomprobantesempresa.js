$(document).ready(function(e){

	// dataTable("#tableEnterprise"); 
    agregarTable()
	
});


function agregarTable(){
    var table = $('#tableEnterprise').DataTable({
      // orderCellsTop: true,
      //  fixedHeader: true,
      paging: true,
      ordering: false,
      dom: 'Blfrtip',
        buttons: [

            {
            extend: 'copyHtml5',
            text: '<i class="far fa-copy" style="color: #fff;font-size: 26px;"></i>',
            className: 'botoncopiar',
            titleAttr: 'COPIAR'
            },
            {
            extend: 'excel',
            footer: true,
            title: 'BALANCE COMPROBACION',
            filename: 'BALANCE COMPROBACION',
            text:'<i class="fas fa-file-excel" style="color: #fff;font-size: 26px;"></i>',
            titleAttr: 'EXCEL'
            },
            {
            extend: 'csvHtml5',
            text: '<i class="fas fa-file-csv" style="color: #fff;font-size: 26px;"></i>',
            titleAttr: 'CSV'
            
            },
            {
            extend: 'pdf',
            customize: function(doc) {
                doc.pageMargins = [ 10, 10, 10, 10 ]
            },
            pageSize: 'A4',
            text: '<i class="far fa-file-pdf" style="color: #fff;font-size: 26px;"></i>',
            
            titleAttr: 'PDF',

            // title:'Juriscon--           '+empresa+'             '+hoy+'\n Nit: '+nit+'-'+digitoVerificador+'\n ESTADO SITUACION FINANCIERA - '+tipo+'\n DESDE:  '+desde+' HASTA: '+hasta,  
          
                },
            {
            extend: 'print',
            customize: function ( win ) {
                    $(win.document.body).find( '.derecha' ).css( 'text-align', 'right' );
                },
            text: '<i class="fas fa-print" style="color: #fff;font-size: 26px;"></i>',
            autoPrint: true,
            titleAttr: 'IMPRIMIR',
            // title:'<table class="table"><thead><tr><th style="float:left;">Juriscon</th><th ><img src="'+url+logo+'" width="60" height="60">    '+empresa+'</th><th></th><th>'+hoy+'</th></tr><tr><th> </th><th>Nit: '+nit+'-'+digitoVerificador+'</th><th> </th><th> </th></tr> <tr><th></th><th >BALANCE DE COMPROBACION - '+tipo+'</th><th> </th></tr><tr><th></th><th >DESDE:  '+desde+' HASTA: '+hasta+'</th><th> </th></tr></thead<tbody><tr></tr></tbody></table>',

            

            }
            
        ]
        
    });

    //Creamos una fila en el head de la tabla y lo clonamos para cada columna
    //$('#tableEnterprise thead tr').clone(true).appendTo( '#tableEnterprise thead' );

    $('#tableEnterprise thead tr:eq(1) th').each( function (i) {
        var title = $(this).text(); //es el nombre de la columna
        var cantidad=$('#tableEnterprise thead tr:eq(1) th').length; 
        if(i>0&&i<(cantidad-1)){
            $(this).html( '<input type="text"  class="form-control" style="heigth:25%;" />' );
 
            $( 'input', this ).on( 'keyup change', function () {
                if ( table.column(i).search() !== this.value ) {
                    table
                        .column(i)
                        .search( this.value )
                        .draw();
                }
            } );
        }
        
    } ); 
}

$("body").on("click touchstart","#btnEliminar",function(e){

    e.preventDefault();

      var idEliminar=$(this).attr("value");
      var idEmpresa=$(this).attr("idEmpresa");
      

    // alert(idEliminar);
      

        Swal.fire({

        title: '¿Está seguro?',

        text: 'Está a punto de eliminar este comprobante contable!',

        icon: 'warning', 

        showCancelButton: true,

        showLoaderOnConfirm: true,

        confirmButtonText: `Si, Eliminar!`,

        cancelButtonText:'Cancelar',

        preConfirm: function(result) {

          return new Promise(function(resolve) {

            // var formu = document.getElementById("frmEliminar");

      

            // var data = new FormData(formu);

            $.ajax({

            url:URL+"functions/comprobantes/eliminarcomprobante.php", 

            type:"POST", 

            data:  {"idEliminar":idEliminar,"idEmpresa":idEmpresa},

            dataType: "json"


            }).done(function(msg){  

              if(msg.msg){

                Swal.fire(

                 {icon: 'success',

                  title: 'Comprobante contable eliminado!',

                  text: 'con exito',

                  closeOnConfirm: true,

                }

                ).then((result) => {

                 location.reload(); 

                })

              }else{

                 Swal.fire(

                  'Algo ha salido mal!',

                  msg.texto!=""?msg.texto:'Verifique su conexión a internet',

                  'error'

                ).then((result) => {

                  

                })

              }

            

          });

          });

        }

      })
      // }

  })







$("body").on("click touchstart","#btnAnular",function(e){
    e.preventDefault();
      var idEliminar=$(this).attr("value");
    // alert(idEliminar);
        Swal.fire({
        title: '¿Está seguro?',
        text: 'Está a punto de anular este comprobante contable!',
        icon: 'warning', 
        showCancelButton: true,
        showLoaderOnConfirm: true,
        confirmButtonText: `Si, Anular!`,
        cancelButtonText:'Cancelar',
        preConfirm: function(result) {
          return new Promise(function(resolve) {
        // var formu = document.getElementById("frmEliminar");    
            // var data = new FormData(formu);
            $.ajax({
            url:URL+"functions/comprobantes/anularcomprobante.php", 
            type:"POST", 
            data:  {"idAnular":idEliminar},
            dataType: "json"
            }).done(function(msg){  
              if(msg.msg){
                Swal.fire(
                 {icon: 'success',
                  title: 'Comprobante contable anulado!',
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
  })


$("body").on("click touchstart",".comprobante",function(e){
    $("#frmGuardar")[0].reset(); 
    var factura=$(this).parents("tr").find("td").eq(3).html();
    var total=$(this).parents("tr").find("td").eq(5).html();
    var idEmpresaSesion = $("#idEmpresaSesion").val();
    if (idEmpresaSesion!="0") {
        var saldo=$(this).parents("tr").find("td").eq(7).html();
        var idEmpresa=$(this).parents("tr").find("td").eq(9).html();
    }
    if (idEmpresaSesion=="0") {
        var saldo=$(this).parents("tr").find("td").eq(8).html();
        var idEmpresa=$(this).parents("tr").find("td").eq(10).html();
    }
    var id=$(this).attr("id"); 
    if (saldo!='$0,00') {
        $("#total").val(saldo); 
        $("#totalSaldo").val(saldo); 
      }
    if (saldo=='$0,00') {
        $("#total").val(total); 
        $("#totalSaldo").val(total);  
      }
    $("[name='datos[idFacturaVenta]']").val(id); 
    $("#nroFactura").val(factura); 
  $.ajax({
      url:URL+"functions/facturacompra/consultarcuentatotal.php", 
      type:"POST", 
      data: {"empresa":idEmpresa,"tipoFactura":'venta'}, 
      dataType: "json",
      }).done(function(msg){  

          if(msg.length!=0){

              console.log('este:');
              console.log(msg);
              var sHtml=""; 
              msg.forEach(function(element,index){
                sHtml+="<option value='"+element.idEmpresaCuenta+"'>"+element.codigoCuenta+'-'+element.nombre+"</option>"; 
              })
              $("#cuentaContableTotal").html(sHtml);

              if(msg.length>1){
                $("#divCuentaContableTotal").removeClass('ocultar');
              }
          }
          if(msg.length==0){
          }
        })
    })


$("body").on("change","#fechaInicio, #fechaFinal",function(e){
    if($("#fechaInicio").val()!=""&&$("#fechaFinal").val()!=""){
        Swal.fire({
      title: 'Cargando!',
      html: 'Espere',
      allowOutsideClick: false,
      allowEscapeKey: false,
      didOpen: () => {
         Swal.showLoading()
         $.ajax({
        url:URL+"functions/comprobantes/listarcomprobantes.php", 
        type:"POST", 
        data: {"fechaInicio":$("#fechaInicio").val(),"fechaFin":$("#fechaFinal").val()}, 
        dataType: "json",
        }).done(function(msg){  
            Swal.close()
          if(msg.aComprobante.length>0){
              var sHtml="";
              $("#tableEnterprise").dataTable().fnDestroy();
              msg.aComprobante.forEach(function(element,index){
                sHtml+="<tr>"; 
                sHtml+="<td>"+(index+1)+"</td>"; 
                sHtml+="<td>"+element.letra+"-"+element.comprobante+""+element.numero+"</td>"; 
                sHtml+="<td>"+element.fecha+"</td>"; 
                sHtml+="<td>"+element.fechaRegistro+"</td>"; 
                sHtml+="<td>"+element.usuario+"</td>"; 
                sHtml+="<td>"+element.observaciones+"</td>"; 
                sHtml+="<td>"+element.saldoDebito+"</td>"; 
                sHtml+="<td>"+element.saldoCredito+"</td>"; 
                sHtml+="<td class='centrar'>"; 
                sHtml+='<a href="'+URL+'vercomprobante/'+element.idEncript+'" name="'+element.idEmpresa+'"  data-toggle="tooltip" data-placement="top" title="Ver Items comprobante"><i class="fas fa-eye" style="color: #8A96FF;font-size: 20px;"></i></a>'; 
                sHtml+='<a href="'+URL+''+element.archivo+'" target="_blank"data-toggle="tooltip" data-placement="top" title="ver archivo"><i class="fas fa-paperclip" style="color: #A59D9D;font-size: 20px;"></i></a>'; 
                if (element.estadoFra!=3) {
                    sHtml+='<a href="'+URL+'editarcomprobante/'+element.idEncript+'" name="'+element.idEmpresa+'"  data-toggle="tooltip" data-placement="top" title="Editar comprobante"><i class="fas fa-pencil-alt" style="color: #FFCC4D;font-size: 20px;"></i></a>'; 
                    sHtml+='<a href="javascript:void(0)" id="btnAnular" name="btnAnular['+index+']"  value="'+element.idComprobante+'" ><i class="fas fa-ban" style="color: #DF81FF;font-size: 19px;" data-toggle="tooltip" data-placement="top" title="Anular factura"  ></i></a>'; 
                    sHtml+='<a href="javascript:void(0)" id="btnEliminar" name="btnEliminar['+index+']" value="'+element.idComprobante+'"   idEmpresa="'+element.idEmpresa+'"><i class="fas fa-trash" style="color: #FF5D5D;font-size: 19px;" data-toggle="tooltip" data-placement="top" title="Eliminar"  ></i></a>'; 
                }
                sHtml+="</td>";
                sHtml+="</tr>"; 

              })
              
              $("#tableEnterprise tbody").html(sHtml);
              agregarTable()
          }
          
      });
         },
      willClose: () => {
        //clearInterval(timerInterval)
          }
        }).then((result) => {
        })
        
    }
})

$("body").on("click touchstart","#btnGuardar",function(e){
    e.preventDefault();
      if(true === $("#frmGuardar").parsley().validate()){
        Swal.fire({
        title: '¿Está seguro?',
        text: 'Está a punto de marcar como pagada esta factura!',
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
            url:URL+"functions/facturaventa/guardarcomprobanteingreso.php", 
            type:"POST", 
            data: data,
            contentType:false, 
            processData:false, 
            dataType: "json",
            cache:false 
            }).done(function(msg){  
              if(msg.msg){
                Swal.fire(
                 {icon: 'success',
                  title: 'Pago realizado!',
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
  })


$('[data-toggle="tooltip"]').tooltip();