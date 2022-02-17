
$("body").on("change","#departamento",function(e){
    var id=$(this).val(); 
    $.ajax({
    url:"functions/generales/ciudades.php", 
    type:"POST", 
    data: {"idDepartamento":id}, 
    dataType: "json",
    }).done(function(msg){  
      var sHtml="<option value=''>Seleccione una opción</option>"; 
      msg.ciudades.forEach(function(element,index){
        sHtml+="<option value='"+element.idCiudad+"'>"+element.nombre+"</option>"; 
      })

      $("[name='datos[idCiudadResidencia]']").html(sHtml);
  });
})

$("body").on("click","#btnGuardar",function(e){
    e.preventDefault();
      if(true === $("#frmGuardar").parsley().validate()){
            var formu = document.getElementById("frmGuardar");
            
            var data = new FormData(formu);
            $.ajax({
            url:"functions/empleados/guardarempleado.php", 
            type:"POST", 
            data: data,
            contentType:false, 
            processData:false, 
            dataType: "json",
            cache:false 
            }).done(function(msg){  
              if(!msg.error){
                swal({   
                  title: "Algo ha salido mal!",   
                  text: msg.msg,
                  type: "error",        
                  closeOnConfirm: true 
                  }, 
                  function(){  
                     
                  });
              }else{
                location.href=msg.msg; 
              }
            
          });
       
      }
  })