var empresa=document.getElementById('empresa').value;
var logo=document.getElementById('logo').value;
var nit=document.getElementById('nitEmpresa').value;
var digitoVerificador=document.getElementById('digitoVerificador').value;
var fecha=new Date();
var dia=fecha.getDate();
var mes=fecha.getMonth()+1;
var anio=fecha.getFullYear();
var hoy=fecha.toLocaleDateString();
var url=document.getElementById('url').value;
var tipo=document.getElementById('tipo').value;
var desde=document.getElementById('desde').value;
var hasta=document.getElementById('hasta').value;

$(document).ready(function(){

    var table = $('#tablecomprobacion').DataTable({
      // orderCellsTop: true,
      //  fixedHeader: true,
      paging: false,
      ordering: false,
      dom: 'Bfrtip',
        buttons: [

            {
            extend: 'copyHtml5',
            text: '<i class="far fa-copy" style="color: #fff;font-size: 26px;"></i>',
            className: 'botoncopiar',
            titleAttr: 'COPIAR'
            },
            {
            extend: 'excelHtml5',
            footer: true,
            title:'AS-Smartbuss           '+empresa+'             '+hoy+'\n Nit: '+nit+'-'+digitoVerificador+'\n BALANCE COMPROBACION - '+tipo+'\n DESDE:  '+desde+' HASTA: '+hasta,
            filename: 'BALANCE COMPROBACION',
            text:'<i class="fas fa-file-excel" style="color: #fff;font-size: 26px;"></i>',
            titleAttr: 'EXCEL',
            exportOptions: { orthogonal: 'export',
            modifier: { page: 'all'},
                    

            format: {
                        header: function ( data, columnIdx ) {
                            if(columnIdx==0){
                            return 'G';
                            }
                            if(columnIdx==1){
                            return 'C';
                            }
                            if(columnIdx==2){
                            return 'SC';
                            }
                            if(columnIdx==3){
                            return 'A';
                            }
                            if(columnIdx==4){
                            return 'SA';
                            }
                            else{
                            return data;
                            }
                        }
                    }
            },
            // customize: function ( xlsx ){
            //   var sheet = xlsx.xl.worksheets['sheet1.xml'];
            //   insertRowAfter( 1, sheet );
            //   populateRow( 2, extraData, sheet );
            // }
            },
            {
            extend: 'csvHtml5',
            text: '<i class="fas fa-file-csv" style="color: #fff;font-size: 26px;"></i>',
            titleAttr: 'CSV'
            
            },
            {
            extend: 'pdf',
            customize: function(doc) {
                doc.pageMargins = [ 10, 10, 15, 10 ];
                // doc.defaultStyle.font = 'Arial';
                doc.defaultStyle.fontSize = 8;
                //$(doc.document.body).find('h1').fillColor='white';
                //$(doc.document.body).find('h1').css('font-size', '12pt');
                //$(doc.document.body).find('h1').css('text-align', 'center'); 
               // $(doc.document.body).find('h1').css('justify-content', 'space-between');
                // $(doc.document).css('width', '95%');
            },
            // columns: [ 0, 1, 2, 3,4,5,7,8,9,10,11,12 ],
            pageSize: 'A4',
           
            text: '<i class="far fa-file-pdf" style="color: #fff;font-size: 24px;"></i>',
            
            titleAttr: 'PDF',

            title:'Smartbuss--       '+empresa+'            '+hoy+'\n Nit: '+nit+'-'+digitoVerificador+'\n BALANCE COMPROBACION - '+tipo+'\n DESDE:  '+desde+' HASTA: '+hasta,  
          
                },
            {
            extend: 'print',
            customize: function ( win ) {
                    $(win.document.body).find( '.derecha' ).css( 'text-align', 'right' );
                     $(win.document.body).find('td').css('font-size', '10pt');
                     $(win.document.body).find('th').css('font-size', '12pt');
                     $(win.document.head).css( 'font-size', '10pt' );
                      
                    $(win.document.body).find('h1').css('font-size', '12pt');
                    $(win.document.body).find('h1').css('text-align', 'center'); 
                    $(win.document.body).find('h1').css('justify-content', 'space-between');
                    
                },
            text: '<i class="fas fa-print" style="color: #fff;font-size: 26px;"></i>',
            autoPrint: true,
            titleAttr: 'IMPRIMIR',
            title:'<table style="font-family: "Arial";" class="table-sm"><thead style="justify-content:space-between;"><tr><th>Smartbuss</th><th ><img src="'+url+logo+'" width="60" height="60">    '+empresa+'</th><th></th><th>'+hoy+'</th></tr><tr><th> </th><th>Nit: '+nit+'-'+digitoVerificador+'</th><th> </th><th> </th></tr> <tr><th></th><th >BALANCE DE COMPROBACION - '+tipo+'</th><th> </th></tr><tr><th></th><th >DESDE:  '+desde+' HASTA: '+hasta+'</th><th> </th></tr></thead></table>',
            // title:'Smartbuss         <img src="'+url+logo+'" width="60" height="60">    '+empresa+'         '+hoy+'\n Nit: '+nit+'-'+digitoVerificador+'\n BALANCE DE COMPROBACION - '+tipo+'\n DESDE:  '+desde+' HASTA: '+hasta,
            // title:'Smartbuss--       '+empresa+'            '+hoy+'\n Nit: '+nit+'-'+digitoVerificador+'\n BALANCE COMPROBACION - '+tipo+'\n DESDE:  '+desde+' HASTA: '+hasta, 
            }

             
            
        ]
        
    });
  
});

$('[data-toggle="tooltip"]').tooltip();