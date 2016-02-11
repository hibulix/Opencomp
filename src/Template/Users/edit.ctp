<div class="page-title">
    <h2><?php echo __('Modifier un utilisateur'); ?></h2>
    <?php echo $this->Html->link('<i class="fa fa-arrow-left"></i> '.__('retour aux utilisateurs'), ['action'=>'index'], array('class' => 'ontitle btn btn-default', 'escape' => false)); ?>
</div>

<?php 

echo $this->Form->create($user, ['novalidate',
    'align' => [
        'md' => [
            'left' => 2,
            'middle' => 3,
            'right' => 7,
        ],
    ]
]);

echo $this->Form->input('id');
echo $this->Form->input('username', array(
    'label' => array(
        'text' => 'Nom d\'utilisateur'
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

echo $this->Form->input('academy', array(
    'class'=>'chzn-select form-control',
    'data-placeholder'=>'Pas responsable d\'une académie',
    'empty'=>true,
    'label' => array(
        'text' => 'Responsable de(s) l\'académie(s)'
        )
    )
);
echo $this->Form->input('establishment', array(
    'class'=>'chzn-select form-control',
    'data-placeholder'=>'Pas titulaire d\'un établissement',
    'empty'=>true,
    'label' => array(
        'text' => 'Directeur de l\'établissement scolaire'
        )
    )
);
echo $this->Form->input('classrooms._ids', array(
    'class'=>'chzn-select form-control',
    'data-placeholder'=>'Pas titulaire d\'une classe',
    'label' => array(
        'text' => 'Enseignant principal de la classe'
        )
    )
);

?><div class="form-group"><?php
    echo $this->Form->submit('Modifier cet utilisateur', array(
        'class' => 'btn btn-primary'
    ));
    ?></div><?php

echo $this->Form->end();

