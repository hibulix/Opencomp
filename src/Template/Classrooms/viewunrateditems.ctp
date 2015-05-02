<?php echo $this->element('ClassroomBase'); ?>

<ul class="nav nav-pills">
  <li><?php echo $this->Html->link(__('Élèves'), array('controller' => 'classrooms', 'action' => 'view', $classroom['Classroom']['id'])); ?></li>
  <li><?php echo $this->Html->link(__('Évaluations'), array('controller' => 'classrooms', 'action' => 'viewtests', $classroom['Classroom']['id'])); ?></li>
  <li class="active"><?php echo $this->Html->link(__('Items non évalués'), array('controller' => 'classrooms', 'action' => 'viewunrateditems', $classroom['Classroom']['id'])); ?></li>
  <li><?php echo $this->Html->link(__('Bulletins'), array('controller' => 'classrooms', 'action' => 'viewreports', $classroom['Classroom']['id'])); ?></li>
</ul>

<div class="page-title">
    <h3><?php echo __('Items travaillés mais non évalués'); ?></h3>
    <?php echo $this->Html->link('<i class="fa fa-plus"></i> '.__('ajouter un item'), '#addUnratedItem', array(
    'class' => 'ontitle btn btn-success', 
    'data-toggle' => 'modal',
    'escape' => false
    )); ?>
</div>


<?php if (!empty($classroom['Evaluation'])): ?>
<table class="table table-striped table-condensed">
<tr>
	<th><?php echo __('Période'); ?></th>
	<th><?php echo __('Libellé de l\'item'); ?></th>
	<th class="actions"><?php echo __('Actions'); ?></th>
</tr>
<?php
	$i = 0;
	foreach ($classroom['Evaluation'] as $evaluations): 
	foreach ($evaluations['Item'] as $item):?>
	
	<tr>
		<td><?php echo h($evaluations['Period']['wellnamed']); ?></td>
		<td><?php echo h($item['title']); ?></td>		
		<td class="actions">
			
		</td>
	</tr>
	<?php endforeach; ?>
<?php endforeach; ?>
</table>
<?php else: ?>
    <div class="alert alert-info">
        <i class="fa fa-info-circle fa fa-3x pull-left"></i>
        Dans Opencomp, les items non évalués permettent de faire figurer sur les bulletins des items travaillés mais pas nécessairement évalués.<br />À la place du résultat, une coche sera affichée pour indiquer que l'item a été travaillé mais non évalué.<br /><br />
        Actuellement, aucun item non évalué n'a été associé à cette classe. Vous pouvez ajouter un item en cliquant sur le bouton vert ci-dessus.
    </div>
<?php endif; ?>

<div class="modal fade" id="addUnratedItem" tabindex="-1" role="dialog" aria-labelledby="addUnratedItemLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <?php
            echo $this->Form->create(null, array(
                'url' => array('controller' => 'competences', 'action' => 'attachunrateditem'),
                'inputDefaults' => array(
                    'div' => 'form-group',
                    'label' => array(
                        'class' => 'col col-md-3 control-label'
                    ),
                    'wrapInput' => 'col col-md-6',
                    'class' => 'form-control'
                ),
                'class' => 'form-horizontal'
            )); ?>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Quelle période pour cet item ?</h4>
            </div>
            <div class="modal-body">
                <?php
                echo $this->Form->input('period_id', array(
                        'label' => array(
                            'text' => 'Période associée'
                        )
                    )
                );
                echo $this->Form->hidden('classroom_id',array('value' => $classroom['Classroom']['id']));
                ?>
            </div>
            <div class="modal-footer">
                <?php echo $this->Form->submit('Valider', array('class'=>'btn btn-primary', 'div'=>false)); ?>
            </div>
            <?php echo $this->Form->end(); ?>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
