$("body").on("change","[name='datos[idDepartamento]']",function(e){

    var id=$(this).val(); 

    if(id!=""){

      $.ajax({

        url:URL+"functions/generales/ciudades.php", 

        type:"POST", 

        data: {"idDepartamento":id}, 

        dataType: "json",

        }).done(function(msg){  

          var sHtml="<option value=''>Seleccione una opción</option>"; 

          msg.ciudades.forEach(function(element,index){

            sHtml+="<option value='"+element.idCiudad+"'>"+element.nombre+"</option>"; 

          })



          $("[name='datos[idCiudad]']").html(sHtml);

      });

    }else{

      $("[name='datos[idCiudad]']").html("<option value=''>Seleccione una opción</option>");

    }

    

})

$(document).ready(function(e){
  $("#pais").css("display","none"); 
  $("#departamentoExtranjero").css("display","none"); 
  $("#ciudadExtranjero").css("display","none"); 
});

$("#extranjero").on('change', function() {

    if( $(this).is(':checked') ) {
        $("#pais").css("display","block");
        $("#departamentoExtranjero").css("display","block");
        $("#ciudadExtranjero").css("display","block");
        $( "#departamento" ).css("display","none");
        $( "#ciudad").css("display","none");
        $("#idDepartamento").removeAttr('required');
        $("#idCiudad").removeAttr('required');
        $("#idPais").attr('required','required');
        $("#idDepartamentoExtranjero").attr('required','required');
        $("#idCiudadExtranjero").attr('required','required');
    } else {
        $("#pais").css("display","none");
        $("#departamentoExtranjero").css("display","none"); 
        $("#ciudadExtranjero").css("display","none"); 
        $( "#departamento" ).css("display","block");
        $( "#ciudad").css("display","block");
        $("#idDepartamento").attr('required','required');
        $("#idCiudad").attr('required','required');
        $("#idPais").removeAttr('required');
        $("#idDepartamentoExtranjero").removeAttr('required');
        $("#idCiudadExtranjero").removeAttr('required');
        
    }
});

$("body").on("change","[name='datos[tipoPersona]']",function(e){

  if($(this).val()==1){

    $("[name='datos[digitoVerificador]']").attr("readonly","readonly");

    $("[name='datos[digitoVerificador]']").removeAttr("required");

  }else{

    $("[name='datos[digitoVerificador]']").attr("required",true);

    $("[name='datos[digitoVerificador]']").removeAttr("readonly");

  }

})



$("body").on("click touchstart","#btnGuardar",function(e){

    e.preventDefault();

      if(true === $("#frmGuardar").parsley().validate()){

          Swal.fire({

          title: 'Está seguro?',

          text: 'Está a punto de guardar un nuevo proveedor!',

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

            url:URL+"functions/proveedor/guardarproveedor.php", 

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

                  title: "Proveedor creado!",

                  text: 'con exito',

                  closeOnConfirm: true,

                }

                ).then((result) => {

                 location.reload(); 

                })

              }else{

                 Swal.fire(

                  'Algo ha salido mal!',

                  'El proveedor ya esta registrado',

                  'error'

                ).then((result) => {

                  

                })

              }

            

          });

          });

        }

        }).then((result) => {

          if (result.isConfirmed) {

          //   var formu = document.getElementById("frmGuardar");

        

          //   var data = new FormData(formu);

          //   $.ajax({

          //   url:URL+"functions/proveedor/guardarproveedor.php", 

          //   type:"POST", 

          //   data: data,

          //   contentType:false, 

          //   processData:false, 

          //   dataType: "json",

          //   cache:false 

          //   }).done(function(msg){  

          //     if(msg.msg){

          //       Swal.fire(

          //         {

          //         icon: 'success',

          //         title: "Proveedor creado!",

          //         text: 'con exito',

          //         closeOnConfirm: true,

          //       }

          //       ).then((result) => {

          //        location.reload(); 

          //       })

          //     }else{

          //        Swal.fire(

          //         'Algo ha salido mal!',

          //         'Verifique su conexión a internet',

          //         'error'

          //       ).then((result) => {

                  

          //       })

          //     }

            

          // });

          } 



         })







          // swal({

          //   title: 'Está seguro?',

          //   text: 'Está a punto de guardar un nuevo proveedor!',

          //   icon: 'warning',

          //   buttons: {

          //       confirm : {text:'Si, Guardar!',className:'sweet-warning',closeModal:false},

          //       cancel : 'Cancelar' 

          //   },

          //   dangerMode: true,

          // })

          //   .then((willDelete) => {

          //     if (willDelete) {

          //       var formu = document.getElementById("frmGuardar");

            

          //       var data = new FormData(formu);

          //       $.ajax({

          //       url:URL+"functions/proveedor/guardarproveedor.php", 

          //       type:"POST", 

          //       data: data,

          //       contentType:false, 

          //       processData:false, 

          //       dataType: "json",

          //       cache:false 

          //       }).done(function(msg){  

          //         if(msg.msg){



          //           swal({   

          //             title: "Proveedor creado!",   

          //             text: "con exito",

          //             type: "success",        

          //             closeOnConfirm: true 

          //             }).then((element)=>{

          //               location.reload(); 

          //             })

          //         }else{

          //           swal({   

          //             title: "Algo ha salido mal!",   

          //             text: "Verifique su conexión a internet",

          //             type: "error",        

          //             closeOnConfirm: true 

          //             }).then((element)=>{

                        

          //             });

          //         }

                

          //     });

          //     } else {

                

          //     }

          //   });

            

       

      }

  })