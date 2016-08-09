<?php

namespace App\Shell;

use Cake\Console\Shell;
use App\View\Helper;
use Cake\Core\Configure;
use Cake\View\View;
use Pheanstalk\Pheanstalk;
use ZendPdf;

class GeneratePupilReportShell extends Shell
{

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Results');
        $this->loadModel('Reports');
    }

    public function main()
    {
        $pheanstalk = new Pheanstalk(Configure::read('beanstalkd_host'));
        while ($job = $pheanstalk->watch('generate-report')->ignore('default')->reserve()) {
            $data = json_decode($job->getData(), true);

            switch ($data['action']) {
                case 'generate':
                    $this->generateReport($data);
                    break;
                case 'concatenate':
                    $this->concatenateReports($data);
                    break;
            }

            $pheanstalk->delete($job);
        }
    }

    private function generateReport($data)
    {
        $report = $this->Reports->get($data['report_id']);
        $this->out("<info>Génération du bulletin ".$report->id." pour l'élève ".$data['pupil_id']."</info>", 1, Shell::NORMAL);
        $items = $this->Results->findResultsForReport(
            $data['pupil_id'],
            $report->classroom_id,
            $report->period_id
        )->hydrate(false)->toArray();

        $competences = $this->Results->Items->Competences->findAllCompetencesFromCompetenceId(
            $this->arrayValueRecursive('competence_id', $items),
            '!jstree'
        );

        $htmlReport = new Helper\ReportFormaterHelper(new View());
        $htmlReport->report = $report;
        $htmlReport->competences = $competences;
        $htmlReport->items = $items;

        $header = $htmlReport->formatHeader();
        $footer = $htmlReport->formatFooter();
        $content = $htmlReport->getContent();

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
        $htmlReport->renderPdf($html, $data['pupil_id']);
    }

    private function concatenateReports($data)
    {
        $report = $this->Reports->get($data['report_id']);
        $this->out("<info>Fusion du bulletin ".$report->id."</info>", 1, Shell::NORMAL);
        $pupils = unserialize($report->beanstalkd_jobs);
        array_pop($pupils);

        $pdfMerged = new ZendPdf\PdfDocument();

        foreach (array_keys($pupils) as $pupilId) {
            // Load PDF Document
            $OldPdf = ZendPdf\PdfDocument::load(APP . "files/reports/".$report->id."_".$pupilId.".pdf");

            // Clone each page and add to merged PDF
            $pages = count($OldPdf->pages);
            for ($i = 0; $i < $pages; ++$i) {
                $page = clone $OldPdf->pages[$i];
                $pdfMerged->pages[] = $page;
            }
        }

        // Save changes to PDF
        $pdfMerged->save(APP . "files/reports/".$report->id.".pdf");

        foreach (array_keys($pupils) as $pupilId) {
            unlink(APP . "files/reports/".$report->id."_".$pupilId.".pdf");
        }

        $report->beanstalkd_finished = 1;
        $this->Reports->save($report);
    }

    /**
     * @param string $key
     * @param array $arr
     * @return array|mixed
     */
    private function arrayValueRecursive($key, array $arr)
    {
        $val = [];
        array_walk_recursive($arr, function ($v, $k) use ($key, &$val) {
            if ($k == $key) {
                array_push($val, $v);
            }
        });
        

        return count($val) > 1 ? $val : array_pop($val);
    }
}
