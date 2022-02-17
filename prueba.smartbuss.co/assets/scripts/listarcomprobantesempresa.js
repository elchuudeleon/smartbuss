$(document).ready(function(e){

	// dataTable("#tableEnterprise"); 

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
    $('#tableEnterprise thead tr').clone(true).appendTo( '#tableEnterprise thead' );

    $('#tableEnterprise thead tr:eq(1) th').each( function (i) {
        var title = $(this).text(); //es el nombre de la columna
        $(this).html( '<input type="text"  class="form-control" style="heigth:25%;" />' );
 
        $( 'input', this ).on( 'keyup change', function () {
            if ( table.column(i).search() !== this.value ) {
                table
                    .column(i)
                    .search( this.value )
                    .draw();
            }
        } );
    } ); 
});




$("body").on("click touchstart","#btnEliminar",function(e){

    e.preventDefault();

      var idEliminar=$(this).attr("value");
      

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

            data:  {"idEliminar":idEliminar},

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

                  'Verifique su conexión a internet',

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