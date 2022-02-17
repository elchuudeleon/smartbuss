$(document).ready(function(e){
	dataTable("#tableBalances"); 
})

$("body").on("click",".eliminar",function(e){
	var id=$(this).attr("delete"); 
  Swal.fire({
  title: 'Está seguro?',
  text: 'Está a punto de eliminar este balance general!',
  icon: 'warning', 
  showCancelButton: true,
  showLoaderOnConfirm: true,
  confirmButtonText: `Si, Eliminar!`,
  cancelButtonText:'Cancelar',
  preConfirm: function(result) {
    return new Promise(function(resolve) {
      
      $.ajax({
         url:URL+"functions/dashboard/eliminarbalancegeneral.php", 
         type:"POST", 
         data: {"id":id}, 
         dataType: "json",
         }).done(function(msg){  
	          Swal.fire({
            icon: 'success',
            title: 'Balance General Eliminado!',
            text: 'con exito',
            closeOnConfirm: true,
          }).then((result) => {
            location.reload();  
          })
	      });
	
    //   $.ajax({
    //   url:URL+"functions/dashboard/eliminarbalancegeneral.php", 
    //   type:"POST", 
    //   data: data,
    //   contentType:false, 
    //   processData:false, 
    //   dataType: "json",
    //   cache:false 
    //   }).done(function(msg){  
    //     if(msg.msg){
    //       Swal.fire({
    //         icon: 'success',
    //         title: 'Balance General Eliminado!',
    //         text: 'con exito',
    //         closeOnConfirm: true,
    //       }).then((result) => {
    //         location.reload();  
    //       })
    //     }else{
    //       Swal.fire(
    //         'Algo ha salido mal!',
    //         'Verifique su conexión a internet',
    //         'error'
    //       ).then((result) => {
            
    //       })
    //     }
      
    // });   
    });
  }
}).then((result) => {
  if (result.isConfirmed) {
  
  } 
})



	// swal({
 //    title: 'Está seguro?',
 //    text: 'Está a punto de eliminar este balance general!',
 //    icon: 'warning',
 //    buttons: {
 //        confirm : {text:'Si, Eliminar!',className:'sweet-warning',closeModal:false},
 //        cancel : 'Cancelar' 
 //    },
 //    dangerMode: true,
 //  })
 //    .then((willDelete) => {
 //      if (willDelete) {

 //      	$.ajax({
 //        url:URL+"functions/dashboard/eliminarbalancegeneral.php", 
 //        type:"POST", 
 //        data: {"id":id}, 
 //        dataType: "json",
 //        }).done(function(msg){  
	//           swal({   
 //              title: "Balance General Eliminado!",   
 //              text: "con exito",
 //              type: "success",        
 //              closeOnConfirm: true 
 //              }).then((element)=>{
 //                location.reload(); 
 //              })
	//       });

 //      } else {
        
 //      }
 //    });
})