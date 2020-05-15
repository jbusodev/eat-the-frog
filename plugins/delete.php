<?php
session_start();
require_once('../connexionPDO.php');
require_once ('../calls/functions.php');
$output_dir = "../images/classeurs/";
if(isset($_POST["op"]) && $_POST["op"] == "delete" && isset($_POST['name']))
{
	$fileName =$_POST['name'];
	$filePath = $output_dir. $fileName;
        unset($_SESSION['fileName']);
        unset($_SESSION['iImage']);
            if (file_exists($filePath)) {
                if( !existeImage($connexion, $fileName) ) {
                    unlink($filePath);
                    suppressionImage($connexion, $fileName);
                }
            }
	echo "Deleted File ".$fileName."<br/>";
}
?>