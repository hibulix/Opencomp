<div class="page-title">
    <h2><?php echo __('Modifier un utilisateur'); ?></h2>
    <?php echo $this->Html->link('<i class="fa fa-arrow-left"></i> '.__('retour aux utilisateurs'), 'index', array('class' => 'ontitle btn btn-default', 'escape' => false)); ?>
</div>

<?php 

echo $this->Form->create('User', array(
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

echo $this->Form->input('id');
echo $this->Form->input('username', array(
    'label' => array(
        'text' => 'Nom d\'utilisateur'
    )
));
echo $this->Form->input('password', array(
    'value' => '',
    'label' => array(
        'text' => 'Mot de passe'
    )
));
echo $this->Form->input('first_name', array(
    'label' => array(
        'text' => 'Prénom'
    )
));
echo $this->Form->input('name', array(
    'label' => array(
        'text' => 'Nom'
    )
));
echo $this->Form->input('email', array(
    'label' => array(
        'text' => 'Adresse de courriel'
    )
));

echo $this->Form->input('yubikeyID', array(
    'label' => array(
        'text' => 'YubikeyID'
    )
));

echo $this->Form->input('Academy', array(
    'class'=>'chzn-select form-control',
    'data-placeholder'=>'Pas responsable d\'une académie',
    'label' => array(
        'text' => 'Responsable de(s) l\'académie(s)'
        )
    )
);
echo $this->Form->input('Establishment', array(
    'class'=>'chzn-select form-control',
    'data-placeholder'=>'Pas titulaire d\'un établissement',
    'empty'=>'',
    'label' => array(
        'text' => 'Directeur de l\'établissement scolaire'
        )
    )
);
echo $this->Form->input('Classroom', array(
    'class'=>'chzn-select form-control',
    'data-placeholder'=>'Pas titulaire d\'une classe',
    'empty'=>'',
    'label' => array(
        'text' => 'Enseignant principal de la classe'
        )
    )
);

echo $this->Form->submit('Modifier cet utilisateur', array(
    'div' => 'col col-md-9 col-md-offset-2',
    'class' => 'btn btn-primary'
)); 
        
echo $this->Form->end();

