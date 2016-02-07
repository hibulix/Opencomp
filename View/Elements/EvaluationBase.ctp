<div class="page-title">
    <h2><?php echo __('Détails d\'une évaluation'); ?></h2>
    <?php echo $this->Html->link('<i class="fa fa-pencil"></i> '.__('modifier'), 'edit/'.$evaluation['Evaluation']['id'], array('class' => 'ontitle btn btn-primary', 'escape' => false)); ?>
    <?php echo $this->Html->link('<i class="fa fa-arrow-left"></i> '.__('retour à la classe'), '/classrooms/viewtests/'.$evaluation['Classroom']['id'], array('class' => 'ontitle btn btn-default', 'escape' => false)); ?>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="well">
            <dl class="dl-horizontal">
                <dt><?php echo __('Identifiant'); ?></dt>
                <dd>
                    <?php echo h('#'.$evaluation['Evaluation']['id']); ?>
                    &nbsp;
                </dd>
                <dt><?php echo __('Titre'); ?></dt>
                <dd>
                    <?php echo h($evaluation['Evaluation']['title']); ?>
                    &nbsp;
                </dd>
                <dt><?php echo __('Classe'); ?></dt>
                <dd>
                    <?php echo $this->Html->link($evaluation['Classroom']['title'], array('controller' => 'classrooms', 'action' => 'view', $evaluation['Classroom']['id'])); ?>
                    &nbsp;
                </dd>
                <dt><?php echo __('Évalué par'); ?></dt>
                <dd>
                    <i class="fa fa-user"></i> <?php echo $evaluation['User']['first_name'].'&nbsp;'.$evaluation['User']['name']; ?>
                </dd>
                <dt><?php echo __('Période'); ?></dt>
                <dd>
                    <?php echo $evaluation['Period']['wellnamed']; ?>
                    &nbsp;
                </dd>
            </dl>
        </div>
    </div>
    <div class="col-md-6">
        <div class="page-title">
            <h3><?php echo __('Élèves ayant commis cette évaluation'); ?></h3>
        </div>

        <?php
        $pupils = '';
        foreach($evaluation['Pupil'] as $pupil){
            $pupils .= $pupil['first_name'].'&nbsp;'.$pupil['name'].', ';
        }
        $pupils = substr($pupils, 0, -2);
        $pupils .= '.';
        echo $pupils;
        ?>
    </div>
</div>