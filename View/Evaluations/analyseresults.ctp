<?php echo $this->element('EvaluationBase'); ?>


<ul class="breadcrumb">
    <li class="completed"><?php echo $this->Html->link(__('1. Définir les items évalués'), array('controller' => 'evaluations', 'action' => 'attacheditems', $evaluation['Evaluation']['id'])); ?></li>
    <li class="completed"><?php echo $this->Html->link(__('2. Saisir les résultats'), array('controller' => 'evaluations', 'action' => 'manageresults', $evaluation['Evaluation']['id'])); ?></li>
    <li class="active"><?php echo $this->Html->link(__('3. Analyser les résultats'), array('controller' => 'evaluations', 'action' => 'analyseresults', $evaluation['Evaluation']['id'])); ?></li>
</ul>

<div class="page-title">
    <h3><?php echo __('Analyse des résultats de l\'évaluation'); ?></h3>
</div>

<?php if (!empty($evaluation['Result'])): ?>

    <div class="col-md-6" id="globalresults"></div>
    <div class="col-md-6" id="itemsdivision" style="height:375px;"></div>

    <table class="table table-stripped table-condensed">
        <?php
        $nbitems = count($evaluation['Item']);
        $itemno = 1;
        foreach ($evaluation['Item'] as $item): ?>
            <tr>
                <td>item <?= $itemno ?><?php $itemno++; ?></td>
                <td><?php echo $item['title']; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <?php $this->start('script'); ?>
    <script type="text/javascript">
        var data = [{
            values: [<?= $global_results ?>],
            labels: ['A - Acquis', 'B - À renforcer', 'C - En cours d\'acquisition','D - Non acquis','NE - Non évalué','ABS - Absent'],
            marker: {
                colors: ['66CC66','66CCFF','FFCC66','FF9999','C0C0C0','787878']
            },
            type: 'pie'
        }];

        var layout = {
            title: 'Répartition globale des résultats de l\'évaluation',
            height: 380,
            width: 480
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
            title: 'Répartition détaillée des résultats de l\'évaluation par item',
            barmode: 'stack',
            yaxis: {
                title: 'Nombre d\'élèves'
            }
        };

        Plotly.newPlot('itemsdivision', data2, layout2);
    </script>
    <?php $this->end(); ?>

<?php else: ?>
    <div class="alert alert-info">
        <i class="fa fa-info-circle"></i> Pour le moment, vous n'avez saisi aucun résultat pour cette évaluation.<br />
        Vous devriez commencer par <?php echo $this->Html->link(__('saisir les résultats'), ['controller' => 'evaluations', 'action' => 'manageresults', $evaluation['Evaluation']['id']]); ?> à cette évaluation.
    </div>
<?php endif; ?>