<?php
$editLink = $this->AuthLink->link(' <i class="fa fa-pencil"></i> ', '/evaluations/edit/'.$evaluation->id, array('escape' => false));
$classroomLink = $this->AuthLink->link($evaluation->classroom->title, '/classrooms/tests/'.$evaluation->classroom->id, array('escape' => false));

$this->assign('header', $evaluation->title.$editLink    );
$this->assign('description', $classroomLink);
?>

<?= $this->cell('Test::header', [$evaluation->id]); ?>

<div class="box box-warning">
    <div class="box-header with-border">
        <h3 class="box-title">Compétences & connaissances évalués</h3>
        <div class="box-tools pull-right">
            <?= $this->AuthLink->link('<i class="fa fa-plus"></i> '.__('ajouter un item évalué'), '/competences/attachitem?evaluation_id='.$evaluation->id, array('class' => 'btn btn-sm btn-success', 'escape' => false)); ?>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body no-padding">
        <?php if (!empty($evaluation->competences)): ?>
            <table class="table table-stripped table-bordered table-hover">
                <thead>
                <tr>
                    <th><?php echo __('Libellé de l\'item évalué'); ?></th>
                    <th style="width:175px;" class="actions"><?php echo __('Déplacer').' '; echo $this->Html->link('<i class="fa fa-question-circle"></i>', '#aboutMoveFunc', array('data-toggle' => 'modal', 'escape' => false)); ?></th>
                    <th style="width:100px;" class="actions"><?php echo __('Action'); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                $nbcompetences = count($evaluation->competences);
                foreach ($evaluation->competences as $item): ?>
                    <tr>
                        <td><?php echo $item->title;
                            if($item->type == 3)
                                echo $this->Html->link(' <i class="fa fa-edit"></i>', '#editItem',
                                    array(
                                        'onclick'=>"
					$('#ItemTitle').val('".addslashes(html_entity_decode($item->title, ENT_QUOTES))."');
					var attr = $('#ItemcompetencesForm').attr('action');
					$('#ItemcompetencesForm').attr('action', attr + '/".$item->id."');
					$('#ItemEvaluationId').val('".$evaluation->id."');",
                                        'data-toggle' => 'modal',
                                        'escape' => false)); ?>
                        </td>
                        <td class="actions">
                            <?php if($item->_joinData->position == 1) $style = 'padding-left: 57px;'; else $style = null; ?>
                            <?php if($item->_joinData->position != 1) echo $this->Html->link('<i class="fa fa-arrow-up"></i> '.__('Monter'), '/EvaluationsCompetences/moveup/'.$item->_joinData->id, array('escape' => false)); ?>&nbsp;&nbsp;
                            <?php if($item->_joinData->position != $nbcompetences) echo $this->Html->link('<i class="fa fa-arrow-down"></i> '.__('Descendre'), '/EvaluationsCompetences/movedown/'.$item->_joinData->id, array('style' => $style, 'escape' => false)); ?>
                        </td>
                        <td class="actions">
                            <?php echo $this->Form->postLink(
                                '<i class="fa fa-trash-o"></i> '.__('Supprimer'),
                                array('controller' => 'EvaluationsCompetences', 'action' => 'unlinkitem', $item->_joinData->id),
                                array(
                                    'escape' => false,
                                    'confirm' => __('Êtes vous sûr(e) de vouloir dissocier cet item de cette évaluation ? L\'ensemble des résultats qui auraient éventuellement été saisis pour cet item dans le cadre de cette évaluation seront perdus.'),
                                    'class' => 'text-danger'
                                )
                            ); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-info margin">
                <i class="fa fa-info-circle"></i> Pour le moment, vous n'avez associé aucun item à cette évaluation.<br />
                Vous devriez commencer par <?php echo $this->Html->link('<i class="fa fa-plus"></i> '.__('ajouter un item'), '/competences/attachitem?evaluation_id='.$evaluation->id, array('class' => 'btn btn-xs btn-success', 'escape' => false)); ?> à cette évaluation.
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="modal fade" id="aboutMoveFunc" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">À propos de la fonction déplacer</h4>
            </div>
            <div class="modal-body">
                <p>La fonction déplacer vous permet de modifier l'ordre dans lequel les competences sont affichés dans cet écran de l'application et lors de la saisie des résultats. Vous pouvez modifier cet ordre à tout moment en cliquant sur monter où descendre.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">J'ai bien compris</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div class="modal fade" id="editItem" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        	<?php
            echo $this->Form->create('', array(
                'url' => array('controller' => 'competences', 'action' => 'editTitle'),
                'inputDefaults' => array(
                    'div' => 'form-group',
                    'label' => array(
                        'class' => 'col col-md-3 control-label'
                    ),
                    'wrapInput' => 'col col-md-6',
                    'class' => 'form-control'
                ),
                'class' => 'form-horizontal'
            ));
            ?>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Éditer un item</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-info"><i class="fa fa-info-circle fa fa-3x pull-left"></i> Lorsque vous modifiez le libellé d'un item, vos corrections sont répercutées sur toutes les évaluations utilisant l'item.</div>
                
                <?php echo $this->Form->input('title', array(
                        'value' => '',
                        'label' => array(
                            'text' => 'Libellé de l\'item'
                        )
                    )
                ); 

                echo $this->Form->hidden('evaluation_id', array('value' => ''));  ?>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                <?php echo $this->Form->button('Modifier', array('type'=>'submit','class' => 'btn btn-primary')); ?>
            </div>
            <?php echo $this->Form->end(); ?>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->