<div class="page-title">
    <h2><?php echo __('Ajouter un item'); ?></h2>
    <?php echo $this->Html->link('<i class="fa fa-arrow-left"></i> '.__('retour à l\'arbre de compétences'), array('controller' => 'competences', 'action' => 'attachitem', 'evaluation_id' => $evaluation_id), array('class' => 'ontitle btn btn-default', 'escape' => false)); ?>
</div>

<div class="alert alert-info">
  Vous êtes sur le point de créer un nouvel item.<br />
  Le nouvel item sera inséré dans la compétence <code><?php echo $path; ?></code>.

</div>

<?php 

echo $this->Form->create('Item', array(
    'class' => 'form-horizontal',
    'url' => array(
    	'controller' => 'evaluationsItems',
    	'action' => 'additem',
    	'evaluation_id' => $evaluation_id,
    	'competence_id' => $competence_id
    ),
    'inputDefaults' => array(
        'div' => 'form-group',
        'label' => array(
            'class' => 'col col-md-2 control-label'
        ),
        'wrapInput' => 'col col-md-3',
        'class' => 'form-control'
    ),
    'class' => 'form-horizontal'
));

echo $this->Form->input('title', array(
	'type' => 'textarea',
    'label' => array(
        'text' => 'Libellé de l\'item'
    )
)); 

echo $this->Form->input('Level', array(
    'class'=>'chzn-select form-control',
    'data-placeholder' => 'Sélectionnez un/des niveau(x) ...',
    'style'=>'width : 220px;',
    'label' => array(
        'text' => 'Niveau de l\'item'
        )
    )
);

echo $this->Form->hidden('competence_id', array('value' => $competence_id));
echo $this->Form->hidden('classroom_id', array('value' => $eval['Evaluation']['classroom_id']));
echo $this->Form->hidden('user_id', array('value' => AuthComponent::user('id')));
echo $this->Form->hidden('type', array('value' => 3));
    
?>

<div class="form-group">
    <?php echo $this->Form->submit('Ajouter cet item', array(
        'div' => 'col col-md-9 col-md-offset-2',
        'class' => 'btn btn-primary'
    )); ?>
</div>

<?php echo $this->Form->end(); ?>