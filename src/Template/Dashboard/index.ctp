<?php
$this->assign('header', 'Mon bureau');
$this->assign('description', 'Votre point de départ, lancez-vous !');
?>

<div class="row">
    <div class="col-md-4">
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Vous serez prêt(e) en un clin d'oeil !</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <?php echo $this->Html->image('logo-opencomp.png', ['style' => 'height:100px; float: left;', 'alt' => 'Logo Opencomp']); ?>
                <div style="margin-top:25px; margin-bottom:20px;">Vous trouverez dans cette page l'essentiel pour vous
                    permettre de démarrer rapidement avec Opencomp !
                </div>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
    <div class="col-md-2">
        <div class="box box-default">
            <div class="box-header no-padding">
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            <p class="text-center text-warning"><span class="numbers-kickstart">1 </span><i
                    class="fa fa-5x fa-pencil-square-o"></i></p>
            <p><?php echo $this->Html->link('<i class="fa fa-question-circle"></i> Comment conçevoir les évaluations de mes élèves ? <i class="fa fa-external-link"></i>', 'https://opencomp.freshdesk.com/support/solutions/folders/1000218480', ['escape' => false, 'target' => '_blank']); ?></p>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
    <div class="col-md-2">
        <div class="box box-default">
            <div class="box-header no-padding">
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <p class="text-center text-primary"><span class="numbers-kickstart">2 </span><i
                        class="fa fa-5x fa-file-text-o"></i></p>
                <p><?php echo $this->Html->link('<i class="fa fa-question-circle"></i> Comment saisir les évaluations et définir les items évalués ? <i class="fa fa-external-link"></i>', 'https://opencomp.freshdesk.com/support/solutions/folders/1000218495', ['escape' => false, 'target' => '_blank']); ?></p>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
    <div class="col-md-2">
        <div class="box box-default">
            <div class="box-header no-padding">
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <p class="text-center text-success"><span class="numbers-kickstart">3 </span><i
                        class="fa fa-5x fa-check-square-o"></i></p>
                <p><?php echo $this->Html->link('<i class="fa fa-question-circle"></i> Comment saisir les résultats d\'une évaluation ? <i class="fa fa-external-link"></i>', 'https://opencomp.freshdesk.com/support/solutions/folders/1000218481', ['escape' => false, 'target' => '_blank']); ?></p>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
    <div class="col-md-2">
        <div class="box box-default">
            <div class="box-header no-padding">
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <p class="text-center text-danger"><span class="numbers-kickstart">4 </span><i
                        class="fa fa-5x fa-file-pdf-o"></i></p>
                <p><?php echo $this->Html->link('<i class="fa fa-question-circle"></i> Comment générer les bulletins élèves en fin de période ? <i class="fa fa-external-link"></i>', 'https://opencomp.freshdesk.com/support/solutions/folders/1000218496', ['escape' => false, 'target' => '_blank']); ?></p>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
</div>

<?php foreach ($classrooms as $establishmentName => $establishement) : ?>
    <?php foreach ($establishement as $classroomId => $classroomName) : ?>
        <div class="row">
            <div class="col-lg-12">
                <div class="box box-default box-solid">
                    <div class="box-header with-border">
                        <h2 class="box-title"><strong><?= $classroomName ?></strong> &nbsp; <sub class="text-white"><?= $establishmentName ?></sub></h2>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-4 border-right">
                                <h4><i class="fa fa-angle-double-right" aria-hidden="true"></i> Accès rapide</h4>
                                <?= $this->Html->link(
                                    '<!-- <span class="badge bg-purple">891</span> --><i class="fa fa-child"></i> Élèves',
                                    [
                                        'controller' => 'classrooms',
                                        'action' => 'pupils',
                                        $classroomId
                                    ],
                                    [
                                        'escape' => false,
                                        'class' => 'btn btn-app'
                                    ]
                                ); ?>
                                <?= $this->Html->link(
                                    '<i class="fa fa-file-text-o"></i> Évaluations',
                                    [
                                        'controller' => 'classrooms',
                                        'action' => 'tests',
                                        $classroomId
                                    ],
                                    [
                                        'escape' => false,
                                        'class' => 'btn btn-app'
                                    ]
                                ); ?>
                                <?= $this->Html->link(
                                    '<i class="fa fa-file-pdf-o"></i> Bilans périodiques',
                                    [
                                        'controller' => 'classrooms',
                                        'action' => 'reports',
                                        $classroomId
                                    ],
                                    [
                                        'escape' => false,
                                        'class' => 'btn btn-app'
                                    ]
                                ); ?>
                                <h4><i class="fa fa-angle-double-right" aria-hidden="true"></i> Actions rapides</h4>
                                <?= $this->Html->link(
                                    '<i class="fa fa-plus" style="display: inline;"></i><i class="fa fa-fw fa-file-text-o" style="display: inline-block;"></i><p>Créer évaluation</p>',
                                    [
                                        'controller' => 'evaluations',
                                        'action' => 'add',
                                        $classroomId
                                    ],
                                    [
                                        'escape' => false,
                                        'class' => 'btn btn-app'
                                    ]
                                ); ?>
                            </div>
                            <div class="col-md-8">
                                <h4><i class="fa fa-fw fa-magic"></i> Votre assistant à la saisie</h4>
                                <div class="callout callout-green">
                                    <h4><i class="icon fa fa-fw fa-check"></i> Tout va bien</h4>

                                    <p>Nous n'avons trouvé aucun problème dans votre saisie.</p>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endforeach; ?>