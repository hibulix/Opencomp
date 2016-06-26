<div class="page-title">
    <h2><?php echo __('Importer les validations d\'items LPC précédentes depuis LivrEval'); ?></h2>
</div>

<?php if(!isset($livrEval_pupils)): ?>

<ul class="breadcrumb">
    <li class="active"><a href="#">Connexion à LivrEval</a></li>
    <li><a href="#">Correspondance des élèves</a></li>
    <li><a href="#">Import des items déjà validés</a></li>
</ul>

<div class="alert alert-info">
    <i class="fa fa-info-circle fa fa-3x pull-left"></i>
    Nous avons besoin de vos identifiants LivrEval pour récupérer vos élèves et les items validés du LPC.<br />
    Vos identifiants ne seront pas mémorisés et seront transmis de façon sécurisé (chiffrement SSL) à travers Internet.
</div>

<?php echo $this->Form->create('LivrEval', array(
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

echo $this->Form->input('username', ['label' => 'Nom d\'utilisateur LivrEval']);   //text
echo $this->Form->input('password', ['label' => 'Mot de passe LivrEval']);   //password

?>

<div class="form-group">
    <?php echo $this->Form->submit('Récupérer mes élèves LivrEval', array(
        'div' => 'col col-md-9 col-md-offset-2',
        'class' => 'btn btn-primary'
    )); ?>
</div>

<?php
echo $this->Form->end();

else:
    echo $this->Form->create('LivrEvalMapping', array(
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
?>

    <ul class="breadcrumb">
        <li class="completed"><a href="#">Connexion à LivrEval</a></li>
        <li class="active"><a href="#">Correspondance des élèves</a></li>
        <li><a href="#">Import des items déjà validés</a></li>
    </ul>

    <table class="table table-striped">
        <thead>
        <tr>
            <th>ID LivrEval</th>
            <th>Nom enregistré sur LivrEval</th>
            <th>Correspondance Opencomp</th>
        </tr>
        </thead>
        <tbody>
            <?php foreach ($livrEval_pupils as $id => $name): ?>
                <tr>
                    <td><?= $id ?></td>
                    <td><?= $name ?></td>
                    <td><?= $this->Form->select($id, $pupils, ['class' => 'form-control']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="form-group">
        <?php echo $this->Form->submit('Enregistrer les correspondances', array(
            'div' => 'col col-md-9',
            'class' => 'btn btn-primary'
        )); ?>
    </div>

<?php endif; ?>

