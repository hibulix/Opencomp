<?php
$this->assign('header', $classroom->title);
$this->assign('description', $classroom->establishment->name);
?>

<?= $this->cell('Classroom::stats', [$classroom->id]); ?>

<div class="box box-warning">
    <div class="box-header with-border">
        <h3 class="box-title">Items non-évalués</h3>
        <div class="box-tools pull-right">
            <?= $this->AuthLink->link('<i class="fa fa-fw fa-plus"></i> '.__('ajouter un item non évalué'), '/competences/attachunrateditem/'.$classroom->id, array('class' => 'btn btn-sm btn-success', 'escape' => false)); ?>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <?php if (!empty($evaluations)): ?>
            <table class="table table-striped table-condensed">
                <tr>
                    <th><?php echo __('Période'); ?></th>
                    <th><?php echo __('Libellé de l\'item'); ?></th>
                    <th class="actions"><?php echo __('Actions'); ?></th>
                </tr>
                <?php
                $i = 0;
                foreach ($evaluations as $evaluation):
                    foreach ($evaluation->competences as $competence):?>

                        <tr>
                            <td>du <?php echo h($evaluation->period->begin) . " au " . h($evaluation->period->end); ?></td>
                            <td><?php echo h($competence->title); ?></td>
                            <td class="actions">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <div class="alert alert-info margin">
                <i class="fa fa-info-circle fa fa-3x pull-left"></i>
                Dans Opencomp, les items non évalués permettent de faire figurer sur les bulletins des items travaillés mais pas nécessairement évalués.<br />À la place du résultat, une coche sera affichée pour indiquer que l'item a été travaillé mais non évalué.<br /><br />
                Actuellement, aucun item non évalué n'a été associé à cette classe. Vous pouvez ajouter un item en cliquant sur le bouton vert ci-dessus.
            </div>
        <?php endif; ?>
    </div>
</div>
