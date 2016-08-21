<?php
$this->assign('header', 'Établissement');
$this->assign('description', $establishment->id);
?>

<div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-purple"><i class="fa fa-group"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Classes</span>
                <span class="info-box-number"><?= $stats['nbClassrooms'] ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-purple"><i class="fa fa-user"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Utilisateurs</span>
                <span class="info-box-number"><?= $stats['nbClassrooms'] ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-purple"><i class="fa fa-child"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Elèves</span>
                <span class="info-box-number"><?= $stats['nbPupils'] ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-purple"><i class="fa fa-calendar"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Périodes</span>
                <span class="info-box-number"><?= $stats['nbPeriods'] ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="box">
            <div class="box-header with-border">
                <i class="fa fa-home"></i>
                <h3 class="box-title">Informations générales</h3>
                <div class="box-tools">
                    <?= $this->Html->link('<i class="fa fa-fw fa-pencil"></i> Modifier', [
                        'controller' => 'establishments',
                        'action' => 'edit',
                        $establishment->id
                    ], [
                        'escape' => false,
                        'class' => 'btn btn-default btn-sm'
                    ]); ?>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <dl class="dl-horizontal">
                    <dt><?php echo __('Appellation officielle'); ?></dt>
                    <dd>
                        <?php echo h($establishment->name); ?>
                    </dd>
                    <dt><?php echo __('Dénomination principale'); ?></dt>
                    <dd>
                        <?php echo h($establishment->main_naming); ?>
                    </dd>
                    <dt><?php echo __('Patronyme UAI'); ?></dt>
                    <dd>
                        <?php echo h($establishment->uai_patronym); ?>
                    </dd>
                </dl>
                <dl class="dl-horizontal">
                    <dt><?php echo __('Secteur'); ?></dt>
                    <dd>
                        <?php if($establishment->sector == 'Public'): ?>
                            <td width="10%"><span class="label label-success"><?= $establishment->sector; ?></span></td>
                        <?php else: ?>
                            <td width="10%"><span class="label label-danger"><?= $establishment->sector; ?></span></td>
                        <?php endif; ?>
                    </dd>
                </dl>
                <dl class="dl-horizontal">
                    <dt><?php echo __('Libellé acheminement'); ?></dt>
                    <dd>
                        <?php echo h($establishment->address); ?>
                    </dd>
                    <dt><?php echo __('Ville INSEE'); ?></dt>
                    <dd>
                        <i class="fa fa-globe"></i> <?php echo h($establishment->town->id) . ' - ' .h($establishment->town->name); ?>
                    </dd>
                    <?php if(isset($establishment->user)): ?>
                    <dt><?php echo __('Direction'); ?></dt>
                    <dd>
                        <?= $this->Html->link('<i class="fa fa-user"></i> '.$establishment->user->full_name, array('controller' => 'users', 'action' => 'view', $establishment->user->id), array('escape' => false)); ?>
                    </dd>
                    <?php endif; ?>
                    <dt><?php echo __('Académie'); ?></dt>
                    <dd>
                        <?php echo $this->Html->link('<i class="fa fa-graduation-cap"></i> '.$establishment->town->academy->name, array('controller' => 'academies', 'action' => 'view', $establishment->town->academy->id), array('escape' => false)); ?>
                    </dd>
                </dl>
                <dl class="dl-horizontal">
                    <dt><?php echo __('Coord. Lambert 93 (IGN)'); ?></dt>
                    <dd>
                        <?php echo h($establishment->X).','.h($establishment->Y); ?>
                    </dd>
                    <dt><?php echo __('Coord. WGS84 (GPS)'); ?></dt>
                    <dd>
                        <?= $lat.','.$lgt; ?>
                    </dd>
                </dl>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
    <div class="col-md-6">
        <div class="box">
            <div class="box-header with-border">
                <i class="fa fa-group"></i>
                <h3 class="box-title">Classes</h3>
                <div class="box-tools">
                    <?= $this->Html->link('<i class="fa fa-fw fa-plus"></i> Ajouter', [
                        'controller' => 'classrooms',
                        'action' => 'add',
                        $establishment->id
                    ], [
                        'escape' => false,
                        'class' => 'btn btn-success btn-sm'
                    ]); ?>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tbody><tr>
                            <th>ID</th>
                            <th>Libellé</th>
                            <th>Enseignant</th>
                            <th>Actions</th>
                        </tr>
                        <?php foreach ($establishment->classrooms as $classroom): ?>
                            <tr>
                                <td><?= $classroom->id ?></td>
                                <td><?= $classroom->title ?></td>
                                <td><?= $classroom->users[0]->full_name ?></td>
                                <td width="30%">
                                    <?php echo $this->Html->link('<i class="fa fa-fw fa-eye"></i> '.__('Voir'), array('controller' => 'classrooms', 'action' => 'view', $classroom->id), array('escape'=>false)); ?>&nbsp;&nbsp;&nbsp;
                                    <?php echo $this->Html->link('<i class="fa fa-fw fa-pencil"></i> '.__('Modifier'), array('controller' => 'classrooms', 'action' => 'edit', $classroom->id), array('escape'=>false)); ?>&nbsp;&nbsp;&nbsp;
                                    <?php echo $this->Form->postLink('<i class="fa fa-fw fa-trash-o"></i> '.__('Supprimer'), array('controller' => 'classrooms', 'action' => 'delete', $classroom->id), array('escape'=>false,'class'=>'text-danger'), __('Are you sure you want to delete # {0}?', $classroom['id'])); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="box">
            <div class="box-header with-border">
                <i class="fa fa-key"></i>
                <h3 class="box-title">Utilisateurs</h3>
                <div class="box-tools">
                    <?= $this->Html->link('<i class="fa fa-fw fa-plus"></i> Ajouter', [
                        'controller' => 'establishments',
                        'action' => 'adduser',
                        $establishment->id
                    ], [
                        'escape' => false,
                        'class' => 'btn btn-success btn-sm'
                    ]); ?>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
                <table class="table table-hover">
                    <tbody><tr>
                        <th><?= __d('opencomp','Libellé') ?></th>
                        <th>Propriété</th>
                        <th>Actions</th>
                    </tr>
                    <?php foreach ($establishment->users as $user): ?>
                        <tr>
                            <td><?= $user->full_name ?></td>
                            <td><?= $user->_joinData['ownership'] ?></td>
                            <td width="30%">&nbsp;
                                <?php echo $this->Form->postLink('<i class="fa fa-fw fa-trash-o"></i> Supprimer', array('controller' => 'establishment', 'action' => 'removeUser', $user->id), array('escape'=>false,'class'=>'text-danger'), __('Are you sure you want to delete # {0}?')); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
    <!-- ./col -->
    <div class="col-md-6">
        <div class="box">
            <div class="box-header with-border">
                <i class="fa fa-calendar"></i>
                <h3 class="box-title">Périodes</h3>
                <div class="box-tools">
                    <?= $this->Html->link('<i class="fa fa-fw fa-cloud-download"></i> Importer', [
                        'controller' => 'classrooms',
                        'action' => 'add',
                        $establishment->id
                    ], [
                        'escape' => false,
                        'class' => 'btn btn-default btn-sm'
                    ]); ?>
                    <?= $this->Html->link('<i class="fa fa-fw fa-plus"></i> Ajouter', [
                        'controller' => 'classrooms',
                        'action' => 'add',
                        $establishment->id
                    ], [
                        'escape' => false,
                        'class' => 'btn btn bg-green btn-sm'
                    ]); ?>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
                <table class="table table-hover">
                    <tbody><tr>
                        <th>ID</th>
                        <th>Libellé</th>
                        <th>Actions</th>
                    </tr>
                    <?php foreach ($establishment->periods as $period): ?>
                        <tr>
                            <td><?= $period->id ?></td>
                            <td><?= $period->well_named ?></td>
                            <td width="30%">
                                <?php echo $this->Html->link('<i class="fa fa-fw fa-pencil"></i> Modifier', array('controller' => 'classrooms', 'action' => 'edit', $classroom->id), array('escape'=>false)); ?>&nbsp;&nbsp;&nbsp;
                                <?php echo $this->Form->postLink('<i class="fa fa-fw fa-trash-o"></i> ', array('controller' => 'classrooms', 'action' => 'delete', $classroom->id), array('escape'=>false,'class'=>'text-danger'), __('Are you sure you want to delete # {0}?', $classroom['id'])); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6 visible-lg-block">
        <div class="box">
            <div class="box-header with-border">
                <i class="fa fa-globe"></i>

                <h3 class="box-title">Localisation en France</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
                <div id="establishment-france"></div>
                <style>
                    #establishment-france { height: 300px; }
                </style>
                <?php $this->append('javascript'); ?>
                var mapFrance = L.map('establishment-france').setView([46.76306,2.42472], 5);
                L.tileLayer('https://maps.wikimedia.org/osm-intl/{z}/{x}/{y}.png', {
                attribution: 'Wikimedia Maps | Cartographie &copy; <a href="http://openstreetmap.org/copyright">contributeurs OpenStreetMap</a>',
                maxZoom: 18,
                }).addTo(mapFrance);
                var marker = L.marker([<?= $lat ?>, <?= $lgt ?>]).addTo(mapFrance);
                <?php $this->end(); ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="box">
            <div class="box-header with-border">
                <i class="fa fa-map-marker"></i>

                <h3 class="box-title">Localisation détaillée</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
                <div id="establishment-localization"></div>
                <style>
                    #establishment-localization { height: 300px; }
                </style>
                <?php $this->append('javascript'); ?>
                var mapDetail = L.map('establishment-localization').setView([<?= $lat ?>, <?= $lgt ?>], 14);
                L.tileLayer('https://maps.wikimedia.org/osm-intl/{z}/{x}/{y}.png', {
                attribution: 'Wikimedia Maps | Cartographie &copy; <a href="http://openstreetmap.org/copyright">contributeurs OpenStreetMap</a>',
                maxZoom: 18,
                }).addTo(mapDetail);
                var marker = L.marker([<?= $lat ?>, <?= $lgt ?>]).addTo(mapDetail);
                <?php $this->end(); ?>
            </div>
        </div>
    </div>
</div>