<?php
$this->assign('header', 'Utilisateurs');
?>

<div class="row">
    <div class="col-md-8">
        <div class="box">
            <div class="box-header with-border">
                <i class="fa fa-group"></i>
                <h3 class="box-title">Liste des utilisateurs</h3>
                <div class="box-tools">
                    <ul class="pagination pagination-sm no-margin pull-right">
                        <?= $this->Paginator->first('<<') ?>
                        <?= $this->Paginator->prev('<',['disabledTitle'=>false]) ?>
                        <?= $this->Paginator->numbers(['before' => '', 'after' => '']) ?>
                        <?= $this->Paginator->next('>',['disabledTitle'=>false]) ?>
                        <?= $this->Paginator->last('>>') ?>
                    </ul>
                    <?php echo $this->Html->link('<i class="fa fa-fw fa-plus"></i> '.__('Ajouter un utilisateur'), ['action'=>'add'], array('class' => 'ontitle btn btn-sm btn-success', 'escape' => false)); ?>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th><?php echo $this->Paginator->sort('id', $this->Utils->sortingSign('id', $this->Paginator->sortKey(), $this->Paginator->sortDir()).'#', array('escape' => false)); ?></th>
                        <th><?php echo $this->Paginator->sort('username', $this->Utils->sortingSign('username', $this->Paginator->sortKey(), $this->Paginator->sortDir()).__('Nom d\'utilisateur'), array('escape' => false)); ?></th>
                        <th><?php echo $this->Paginator->sort('first_name', $this->Utils->sortingSign('first_name', $this->Paginator->sortKey(), $this->Paginator->sortDir()).__('Prénom'), array('escape' => false)); ?></th>
                        <th><?php echo $this->Paginator->sort('name', $this->Utils->sortingSign('name', $this->Paginator->sortKey(), $this->Paginator->sortDir()).__('Nom'), array('escape' => false)); ?></th>
                        <th><?php echo $this->Paginator->sort('email', $this->Utils->sortingSign('email', $this->Paginator->sortKey(), $this->Paginator->sortDir()).__('Email'), array('escape' => false)); ?></th>
                        <th class="actions"><?php echo __('Actions'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo h($user->id); ?>&nbsp;</td>
                            <td><?php echo h($user->username); ?>&nbsp;</td>
                            <td><?php echo h($user->first_name); ?>&nbsp;</td>
                            <td><?php echo h($user->name); ?>&nbsp;</td>
                            <td><?php echo h($user->email); ?>&nbsp;</td>
                            <td class="actions">
                                <?= $this->Html->link('<i class="fa fa-fw fa-pencil"></i> '.__('Modifier'), array('action' => 'edit', $user->id), array('escape' => false)); ?>&nbsp;&nbsp;&nbsp;
                                <?= $this->Form->postLink('<i class="fa fa-fw fa-trash"></i>', ['action' => 'delete', $user->id], ['escape' => false, 'class'=>'text-danger', 'confirm' => __('Êtes vous sûr(e) de vouloir supprimer l\'utilisateur {0} {1} ?', $user->first_name, $user->name)]); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

	<?php echo $this->Paginator->numbers(); ?>

