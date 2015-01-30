<?php

$this->ReportFormater->report = $report;
$this->ReportFormater->competences = $competences;
$this->ReportFormater->items = $items;

$header = $this->ReportFormater->formatHeader();
$footer = $this->ReportFormater->formatFooter();
$content = $this->ReportFormater->getContent();

$stylesheet = WWW_ROOT.'/css/opencomp.report.css';

//On prépare le HTML pour Dompdf
$html = <<<HTML
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
	<head>
		<title>Report</title>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
		<link href="$stylesheet" rel="stylesheet" type="text/css">

	</head>
	<body>
		<div id='copyright'>Opencomp system v 1.0</div>
		<div id='footer'>
			<p class='page'>$footer</p>
		</div>
		<div id='content'>
		    <p class='title'>$header</p>
		    $content
		</div>
    </body>
</html>
HTML;

if(isset($output_type) && $output_type == 'pdf')
    $this->ReportFormater->renderPdf($html, $classroom_id, $period_id, $pupil_id);
else
    echo $html;
