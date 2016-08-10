<?php
$this->assign('header', 'Établissements');
$this->assign('description', $this->Paginator->counter('{{count}} résultat(s) correspondant(s), {{current}} résultat(s) affiché(s), page {{page}} sur {{pages}}'));
?>

<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Liste des établissements</h3>
                <div class="box-tools">
                    <ul class="pagination pagination-sm no-margin pull-right">
                        <?= $this->Paginator->first('<<') ?>
                        <?= $this->Paginator->prev('<',['disabledTitle'=>false]) ?>
                        <?= $this->Paginator->numbers(['before' => '', 'after' => '']) ?>
                        <?= $this->Paginator->next('>',['disabledTitle'=>false]) ?>
                        <?= $this->Paginator->last('>>') ?>
                    </ul>
                </div>
            </div>
            <div class="box-body table-responsive no-padding">

                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th><?= $this->Paginator->sort('id', 'Code UAI') ?></th>
                                <th><?= $this->Paginator->sort('name', 'Appellation Officielle') ?></th>
                                <th><?= $this->Paginator->sort('sector', 'Secteur') ?></th>
                                <th><?= $this->Paginator->sort('Towns.name', 'Commune') ?></th>
                                <th><?= $this->Paginator->sort('Academies.name', 'Académie') ?></th>
                            </tr>
                            <tr>
                                <?= $this->Form->create(); ?>
                                <?php
                                $this->Form->templates([
                                    'inputContainer' => '{{content}}',
                                ]);
                                ?>
                                <th><?= $this->Form->input('uai',['label'=>false,'placeholder'=>'UAI...']); ?></th>
                                <th><?= $this->Form->input('n',['label'=>false,'placeholder'=>'Rechercher appellation...']); ?></th>
                                <th><?= $this->Form->input('s',['label'=>false,'placeholder'=>'Rechercher secteur...']); ?></th>
                                <th><?= $this->Form->input('t',['label'=>false,'placeholder'=>'Rechercher commune...']); ?></th>
                                <th><?= $this->Form->input('a',['label'=>false,'placeholder'=>'Rechercher académie...']); ?></th>
                                <?= $this->Form->submit(null,['style'=>'display:none;']); ?>
                                <?= $this->Form->end(); ?>
                            </tr>
                            <tbody>
                            <?php foreach ($schools as $school): ?>
                                <tr>
                                    <td width="10%"><?= $school->id; ?></td>
                                    <td>
                                        <?= $this->Html->link(
                                        '<i class="fa fa-home"></i> '.$school->name,
                                        ['controller' => 'establishments', 'action' => 'view', $school->id],
                                        array('escape' => false)
                                        ); ?>
                                    </td>
                                    <?php if($school->sector == 'Public'): ?>
                                        <td width="10%"><span class="label label-success"><?= $school->sector; ?></span></td>
                                    <?php else: ?>
                                        <td width="10%"><span class="label label-danger"><?= $school->sector; ?></span></td>
                                    <?php endif; ?>
                                    <td>
                                        <?= $this->Html->link(
                                            '<i class="fa fa-globe"></i> '. $school->town->id . ' - ' . $school->town->name,
                                            ['controller' => 'establishments', 'action' => 'index', '?' => ['t' => $school->town->name]],
                                            array('escape' => false)
                                        ); ?>
                                    </td>
                                    <td>
                                        <?= $this->Html->link(
                                            '<i class="fa fa-graduation-cap"></i> '. $school->town->academy->name,
                                            ['controller' => 'establishments', 'action' => 'index', '?' => ['a' => $school->town->academy->name]],
                                            array('escape' => false)
                                        ); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
            </div>
        </div>
    </div>
</div>