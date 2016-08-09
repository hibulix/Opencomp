<?php
$this->assign('header', $classroom->title);
$this->assign('description', $classroom->establishment->name);
?>

<?= $this->cell('Classroom::stats', [$classroom->id]); ?>

<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?= __('Élèves'); ?></h3>
                <div class="box-tools">
                    <div class="btn-group">
                        <a class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="fa fa-fw fa-arrow-up"></i> Exporter
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <?php echo $this->Html->link('<i class="fa fa-fw fa-table"></i> '.__('OASIS OpenDocument Spreadsheet (.ods)'), array('controller' => 'ClassroomsPupils', 'action' => 'opendocumentExport', 'classroom_id' => $classroom->id),array('escape' => false)); ?>
                            </li>
                            <li>
                                <?php echo $this->Html->link('<i class="fa fa-fw fa-database"></i> '.__('OASIS OpenDocument Database (.odb)'), array('controller' => 'Classrooms', 'action' => 'opendocumentdatabase', $classroom->id) ,array('escape' => false)); ?>
                            </li>
                            <li>
                                <?php echo $this->Html->link('<i class="fa fa-fw fa-question-circle"></i> '.__('quel format choisir ?'), '#' ,array('escape' => false)); ?>
                            </li>
                        </ul>
                    </div>
                    <div class="btn-group">
                        <a class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="fa fa-fw fa-arrow-down"></i> Importer
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <?php echo $this->Html->link('<i class="fa fa-fw fa-file-text-o"></i> '.__('depuis un export .csv BE1D'), array('controller' => 'pupils', 'action' => 'import', 'classroom_id' => $classroom->id),array('escape' => false)); ?>
                            </li>
                        </ul>
                    </div>
                    <?php echo $this->Html->link('<i class="fa fa-fw fa-plus"></i> '.__('ajouter un élève'), ['controller' => 'pupils', 'action' => 'add', 'classroom_id' => $classroom->id], array('class' => 'ontitle btn btn-sm btn-success', 'escape' => false)); ?>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <?php if (count($classroomsPupils)): ?>
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <?php $first = true; ?>
                            <?php foreach ($classroomsPupils as $level_id => $levels): ?>
                                <?php foreach ($levels as $level_label => $pupils): ?>
                                    <li class="<?= ($first==true)? 'active' : ''?>"><a href="#level_<?= $level_id ?>" data-toggle="tab"><?= $level_label ?></a></li>
                                <?php endforeach; ?>
                                <?php $first = false; ?>
                            <?php endforeach; ?>
                        </ul>
                        <div class="tab-content no-padding">
                            <?php $first = true; ?>
                            <?php foreach ($classroomsPupils as $level_id => $levels): ?>
                                <?php foreach ($levels as $level_label => $pupils): ?>
                                    <div class="tab-pane <?= ($first==true)? 'active' : ''?>" id="level_<?= $level_id ?>">
                                        <table class="table table-striped table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Prénom</th>
                                                    <th>Nom</th>
                                                    <th>Sexe</th>
                                                    <th>Date de naissance</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($pupils as $pupil_id => $pupil): ?>
                                                    <tr>
                                                        <td><?= $pupil['first_name'] ?></td>
                                                        <td><?= $pupil['name'] ?></td>
                                                        <td><?php if($pupil['sex'] == 'M') echo '<i class="text-info fa fa-mars fa-fw"></i> Masculin'; else echo '<i class="text-danger fa fa-venus fa-fw"></i> Féminin'; ?></td>
                                                        <td><?= $this->Time->format($pupil['birthday'],"dd/MM/YYYY"); ?></td>
                                                        <td>
                                                            <?php
                                                            echo $this->Html->link(
                                                                '<i class="fa fa-pencil"></i> Modifier',
                                                                array(
                                                                    'controller' => 'classroomsPupils',
                                                                    'action' => 'edit',
                                                                    $pupil_id
                                                                ),
                                                                array('escape' => false)
                                                            );
                                                            ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            <?php
                                                            echo $this->Form->postLink(
                                                                '<i class="fa fa-trash-o"></i> Supprimer',
                                                                array(
                                                                    'controller' => 'classroomsPupils',
                                                                    'action' => 'unlink',
                                                                    $pupil_id
                                                                ),
                                                                array(
                                                                    'escape' => false,
                                                                    'class' => 'text-danger',
                                                                    'confirm' => __('Êtes vous réellement sûr(e) de vouloir supprimer {0} de cette classe ?', $pupil['first_name'].' '.$pupil['name'])
                                                                )
                                                            );
                                                            ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php endforeach; ?>
                                <?php $first = false; ?>
                            <?php endforeach; ?>
                            <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        <i class="fa fa-info-circle"></i> Cette classe ne comporte encore aucun élève associé.<br />Vous pouvez ajouter des élèves manuellement (bouton vert à droite) ou les importer depuis une classe de l'année précédente.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>