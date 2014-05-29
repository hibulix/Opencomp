<div class="page-title">
    <h2><?php echo __('Modifier un utilisateur'); ?></h2>
    <?php echo $this->Html->link('<i class="fa fa-arrow-left"></i> '.__('retour aux utilisateurs'), 'index', array('class' => 'ontitle btn', 'escape' => false)); ?>
</div>

<?php 

echo $this->Form->create('User', array(
    'class' => 'form-horizontal',
    )
);

        echo $this->Form->input('id');
        echo $this->Form->input('username', array(
            'label' => array(
                'text' => 'Nom d\'utilisateur',
                'class' => 'control-label'
            )
        ));     
        echo $this->Form->input('first_name', array(
            'label' => array(
                'text' => 'Prénom',
                'class' => 'control-label'
            )
        ));
        echo $this->Form->input('name', array(
            'label' => array(
                'text' => 'Nom',
                'class' => 'control-label'
            )
        ));
        echo $this->Form->input('email', array(
            'label' => array(
                'text' => 'Adresse de courriel',
                'class' => 'control-label'
            )
        ));
        
        echo $this->Form->input('yubikeyID', array(
            'label' => array(
                'text' => 'YubikeyID',
                'class' => 'control-label'
            )
        ));

        echo $this->Form->input('role', array(
            'type' => 'select',
            'options' => array('0'=>'Enseignant','1'=>'Directeur', '2'=>'Responsable académique', '3'=>'Superviseur'),
            'label' => array(
                'text' => 'Profil utilisateur',
                'class' => 'control-label'
            )
        )); 
        echo $this->Form->input('Academy', array(
            'class'=>'chzn-select',
            'data-placeholder'=>'Sélectionnez une académie ...',
            'style'=>'width : 250px;',
            'label' => array(
                'text' => 'Responsable de(s) l\'académie(s)',
                'class' => 'control-label'
                )
            )
        );
        echo $this->Form->input('Establishment', array(
            'class'=>'chzn-select',
            'data-placeholder'=>'Pas titulaire d\'un établissement',
            'style'=>'width : 250px;',
            'empty'=>'',
            'label' => array(
                'text' => 'Directeur de l\'établissement scolaire',
                'class' => 'control-label'
                )
            )
        );
        echo $this->Form->input('Classroom', array(
            'class'=>'chzn-select',
            'data-placeholder'=>'Pas titulaire d\'une classe',
            'style'=>'width : 250px;',
            'empty'=>'',
            'label' => array(
                'text' => 'Enseignant principal de la classe',
                'class' => 'control-label'
                )
            )
        );
      ?>
     
      <div class="form-actions">
         <?php echo $this->Form->button('<i class="fa fa-hdd-o"></i> Enregistrer les modifications', array('type' => 'submit', 'class' => 'btn btn-primary')); ?>        
      </div>

<?php echo $this->Form->end(); ?>
