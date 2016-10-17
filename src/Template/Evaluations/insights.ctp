<?php
$editLink = $this->AuthLink->link(' <i class="fa fa-pencil"></i> ', '/evaluations/edit/' . $evaluation->id, ['escape' => false]);
$classroomLink = $this->AuthLink->link($evaluation->classroom->title, '/classrooms/tests/' . $evaluation->classroom->id, ['escape' => false]);

$this->assign('header', $evaluation->title . $editLink);
$this->assign('description', $classroomLink);
?>

<?= $this->cell('Test::header', [$evaluation->id]); ?>

<?php if (!empty($evaluation->results)) : ?>
    <div class="row">
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    <i class="fa fa-pie-chart"></i>
                    <h3 class="box-title">Répartition globale des résultats de l'évaluation</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div id="globalresults" style="width: 100%"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    <i class="fa fa-bar-chart"></i>
                    <h3 class="box-title">Répartition détaillée des résultats de l'évaluation par compétence</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div id="itemsdivision" style="width: 100%;"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <i class="fa fa-list"></i>
                    <h3 class="box-title">Rappel des compétences évaluées</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table class="table table-stripped table-condensed">
                        <?php
                        $nbitems = count($evaluation['Item']);
                        $competenceno = 1;
                        foreach ($evaluation->competences as $competence) : ?>
                            <tr>
                                <th style="width:100px;">Compétence <?= $competenceno ?><?php $competenceno++; ?></th>
                                <td><?php echo $competence->title; ?>
                                </td>
                            </tr>
                        <?php
                        endforeach; ?>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php $this->start('script'); ?>
    <script type="text/javascript" src="/components/plotly.js/dist/plotly.min.js"></script>
    <script type="text/javascript">
        var data = [{
            values: [<?= $globalResults ?>],
            labels: ['A - Acquis', 'B - À renforcer', 'C - En cours d\'acquisition','D - Non acquis','NE - Non évalué','ABS - Absent'],
            marker: {
                colors: ['66CC66','66CCFF','FFCC66','FF9999','C0C0C0','787878']
            },
            type: 'pie'
        }];
        var layout = {
            //title: 'Répartition globale des résultats de l\'évaluation',
            //height: 380,
            //width: 480
        };
        Plotly.newPlot('globalresults', data, layout);
        var ne = {
            x: [<?= $x ?>],
            y: [<?= $y['ne'] ?>],
            name: 'NE - Non évalué',
            marker: {color: '787878'},
            type: 'bar'
        };
        var abs = {
            x: [<?= $x ?>],
            y: [<?= $y['abs'] ?>],
            name: 'ABS - Absent',
            marker: {color: 'C0C0C0'},
            type: 'bar'
        };
        var d = {
            x: [<?= $x ?>],
            y: [<?= $y['d'] ?>],
            name: 'D - Non acquis',
            marker: {color: 'FF9999'},
            type: 'bar'
        };
        var c = {
            x: [<?= $x ?>],
            y: [<?= $y['c'] ?>],
            name: 'C - En cours d\'acquisition',
            marker: {color: 'FFCC66'},
            type: 'bar'
        };
        var b = {
            x: [<?= $x ?>],
            y: [<?= $y['b'] ?>],
            name: 'B - À renforcer',
            marker: {color: '66CCFF'},
            type: 'bar'
        };
        var a = {
            x: [<?= $x ?>],
            y: [<?= $y['a'] ?>],
            name: 'A - Acquis',
            marker: {color: '66CC66'},
            type: 'bar'
        };
        var data2 = [abs, ne, d, c, b, a];
        var layout2 = {
            //title: 'Répartition détaillée des résultats de l\'évaluation par item',
            barmode: 'stack',
            yaxis: {
                title: 'Nombre d\'élèves'
            }
        };
        Plotly.newPlot('itemsdivision', data2, layout2);
    </script>
    <?php $this->end(); ?>

<?php else : ?>
    <div class="alert alert-info">
        <i class="fa fa-info-circle"></i> Pour le moment, vous n'avez saisi aucun résultat pour cette évaluation.<br />
        Vous devriez commencer par <?php echo $this->Html->link(__('saisir les résultats'), ['controller' => 'evaluations', 'action' => 'manageresults', $evaluation['Evaluation']['id']]); ?> à cette évaluation.
    </div>
<?php endif; ?>
