<?php ob_start(); 
if(!empty($_REQUEST["orientacion"]))
	$orientacion = $_REQUEST["orientacion"];
else
	$orientacion = "portrait";
echo utf8_decode($_REQUEST["data"]);

require_once("dompdf/dompdf_config.inc.php");
$dompdf = new DOMPDF();
$dompdf->set_paper("letter",$orientacion);
$dompdf->load_html(ob_get_clean());
$dompdf->render();
$pdf = $dompdf->output();
$filename = "PDF_".date("d_m_Y").'.pdf';
file_put_contents($filename, $pdf);
$dompdf->stream($filename);
?>