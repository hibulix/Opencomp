<div class="page-title">
    <h2><?php echo __('Ajouter un utilisateur'); ?></h2>
    <?php echo $this->Html->link('<i class="fa fa-arrow-left"></i> '.__('retour aux utilisateurs'), 'index', array('class' => 'ontitle btn', 'escape' => false)); ?>
</div>

<?php 

echo $this->Form->create('User', array(
    'class' => 'form-horizontal',
    'inputDefaults' => array(
        'format' => array('before', 'label', 'between', 'input', 'error', 'after'),
        'div' => array('class' => 'control-group'),
        'between' => '<div class="controls">',
        'after' => '</div>',
        'error' => array('attributes' => array('wrap' => 'span', 'class' => 'help-inline'))
        )
    )
);

?>

<div class="tabbable">
  <ul id="myTab" class="nav nav-tabs">
    <li class="active"><a href="#tab1" data-toggle="tab">Informations personnelles</a></li>
    <li><a href="#tab2" data-toggle="tab">Affectations</a></li>
  </ul>
  <div class="tab-content">
    <div class="tab-pane active fade in" id="tab1">
    <?php
        echo $this->Form->input('username', array(
            'label' => array(
                'text' => 'Nom d\'utilisateur',
                'class' => 'control-label'
            )
        ));     
        echo $this->Form->input('password', array(
            'label' => array(
                'text' => 'Mot de passe',
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
    ?>
    
        <div class="form-actions">
             <?php echo $this->Form->button('définir les affectations <i class="fa fa-arrow-right"></i>', array('class' => 'btn btn-primary tab2')); ?>        
        </div>
    </div>
    <div class="tab-pane fade in" id="tab2">
      <?php
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
         <?php echo $this->Form->button('<i class="fa fa-arrow-left"></i> informations personnelles', array('type' => 'submit', 'class' => 'btn tab1')); ?> 
         <?php echo $this->Form->button('<i class="fa fa-hdd-o"></i> Enregistrer les modifications', array('type' => 'submit', 'class' => 'btn btn-primary')); ?>        
      </div>
    </div>
  </div>
</div>

<?php echo $this->Form->end(); ?>
