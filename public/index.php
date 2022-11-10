<?php

require_once __DIR__ . '/../vendor/autoload.php';
include __DIR__ . '/../config.php';

if(defined('MR_API_KEY') && (empty($_SERVER['HTTP_X_API_KEY']) || $_SERVER['HTTP_X_API_KEY']!=MR_API_KEY)){
	header('X-Response-Code: 401', true, 401);
	die('Unauthorized');
}

$config = json_decode($_REQUEST['config'],true);
$doc = json_decode($_REQUEST['doc'],true);

if(empty($doc)){
	header('X-Response-Code: 403', true, 403);
	die('No doc parameter');
}


$defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
$fontDirs = $defaultConfig['fontDir'];

$defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
$fontData = $defaultFontConfig['fontdata'];

if(defined('MR_FONTDATA')){
	$fontData = array_merge($fontData,MR_FONTDATA);
}

$mpdf_config = [
	'fontDir' => array_merge($fontDirs, [__DIR__.'/../fonts']),
	'fontdata' => $fontData
];

if(defined('MR_DEFAULT_FONT')){
	$mpdf_config['default_font']=MR_DEFAULT_FONT;
}

if(defined('MR_DEFAULT_FONT_SIZE')){
	$mpdf_config['default_font_size']=MR_DEFAULT_FONT_SIZE;
}

if(!empty($config)){
	$mpdf_config = array_merge($mpdf_config,$config);
}

$mpdf = new \Mpdf\Mpdf($mpdf_config);
$mpdf->charset_in = 'UTF-8';

foreach($doc as $d){
	switch($d['type']){
		case 'html':
			$mpdf->WriteHTML($d['html']);
			break;
		case 'page':
			$mpdf->AddPage($d['orientation']??'');
			break;
		case 'template':
			$pc = $mpdf->SetSourceFile($d['file']);
			$id = $mpdf->ImportPage($pc);
			$mpdf->UseTemplate($id);
			$mpdf->SetPageTemplate($id);
			break;
			
	}
}

$mpdf->WriteHTML('<h1>Hello world!</h1>');
$mpdf->Output();
