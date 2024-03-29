<?php 

header('Content-type: application/json');

require_once("../../php/restrict.php");

require_once('../../libraries/PHPExcel/Classes/PHPExcel.php');



if( isset($_FILES['excel']) && $_FILES['excel'] != 'undefined')

    {

               

        $sNombre = $_FILES['excel']['name'];                

        $sExtension = substr(strrchr($sNombre, '.'), 1);

        $sTemporal = $_FILES['excel']['tmp_name'];

        

        $nombreEncript = uniqid(); 

        

        $nombre_archivo = "{$nombreEncript}.{$sExtension}"; 

        

        $directorioTmp = 'EFINANCIERO/';

        $ubicacionTmp = "{$directorioTmp}{$nombre_archivo}";  



        if(move_uploaded_file($sTemporal, "../../".$directorioTmp.$nombre_archivo))

        {	

            $sFinanciero = 'EFINANCIERO/'.$nombre_archivo;

        }else{

        	echo "vacio"; 

        }

    

} 

$archivo = "../../".$sFinanciero;

try {

    $inputFileType = PHPExcel_IOFactory::identify($archivo);

    $objReader = PHPExcel_IOFactory::createReader($inputFileType);

    $objPHPExcel = $objReader->load($archivo);

    $sheet = $objPHPExcel->getSheet(0); 

    $highestRow = $sheet->getHighestRow(); 

    $highestColumn = $sheet->getHighestColumn();



    $cabecera["titulo"]=$sheet->getCell("A1")->getValue();

    $cabecera["fecha"]=$sheet->getCell("A2")->getValue();

    for ($row = 6; $row <= $highestRow; $row++){

        if($sheet->getCell("A".$row)->getValue()!=null){

        $aArray["cuenta"]=$sheet->getCell("A".$row)->getValue(); 

        $aArray["valor"]="$".number_format($sheet->getCell("E".$row)->getCalculatedValue(),0,".",","); 

        $aArray["equivalencia"]=$sheet->getCell("G".$row)->getValue();

        //var_dump($aArray["cuenta"], strpos($aArray["cuenta"],"UTILIDAD"));  

        if(strpos($aArray["cuenta"],"UTILIDAD")=== false){

           $aArray["utilidad"]=0;       

        }else{

           $aArray["utilidad"]=1;   

        }

        

        $aDatos[]=$aArray; 

       }

    } 



    unlink($archivo); 

}catch(Exception $e) {

    die('Error loading file "'.pathinfo($temp_dir,PATHINFO_BASENAME).'": '.$e->getMessage());

}



echo json_encode(array("info"=>$aDatos,"cabecera"=>$cabecera)); 



?>