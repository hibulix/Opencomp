<?php echo $this->element('ClassroomBase'); ?>

<ul class="nav nav-pills">
    <li><?php echo $this->Html->link(__('Élèves'), array('controller' => 'classrooms', 'action' => 'view', $classroom['Classroom']['id'])); ?></li>
    <li><?php echo $this->Html->link(__('Évaluations'), array('controller' => 'classrooms', 'action' => 'viewtests', $classroom['Classroom']['id'])); ?></li>
    <li><?php echo $this->Html->link(__('Items non évalués'), array('controller' => 'classrooms', 'action' => 'viewunrateditems', $classroom['Classroom']['id'])); ?></li>
    <li><?php echo $this->Html->link(__('Bulletins'), array('controller' => 'classrooms', 'action' => 'viewreports', $classroom['Classroom']['id'])); ?></li>
    <li class="active"><?php echo $this->Html->link(__('LPC'), array('controller' => 'classrooms', 'action' => 'viewlpc', $classroom['Classroom']['id'])); ?></li>
</ul>


<div class="page-title">
    <h3><?php echo __('Livrets Personnels de Compétences'); ?></h3>
</div>

<div class="alert alert-warning">
    <i class="fa fa-warning fa fa-3x pull-left"></i><p><strong>Fonctionnalité expérimentale !</strong></p>
    La validation automatisée du Livret Personnel de Compétences est une fonctionnalité experimentale d'Opencomp.<br />
    Certains items maîtrisés sont susceptibles de ne pas apparaître s'ils n'ont pas été correctement liés.
    Vous avez la possibilité de valider manuellement un item/domaine/compétence.
</div>

<h4>Documents officiels</h4>

<table class="table table-bordered">
    <thead>
        <tr>
            <td></td>
            <td colspan="2"><strong>Attestation aux familles</strong></td>
            <td colspan="2"><strong>Attestation et détail</strong></td>
            <td colspan="2"><strong>Détail uniquement</strong></td>
        </tr>
        <tr>
            <td></td>
            <td>Palier 1</td>
            <td>Palier 2</td>
            <td>Palier 1</td>
            <td>Palier 2</td>
            <td>Palier 1</td>
            <td>Palier 2</td>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($pupils as $level => $pupil): ?>
        <tr>
            <td><?= $level ?></td>
            <td>
                <?= $this->Html->link(
                    '<i class="fa fa-file-pdf-o"></i> Télécharger le document',
                    array(
                        'controller' => 'lpcnodes',
                        'action' => 'pdfCert',
                        $classroom_id,
                        $level,
                        1
                    ),
                    array(
                        'escape' => false,
                        'class' => 'text-danger',
                        'target' => '_blank'
                    )
                );?>
            </td>
            <td>
                <?= $this->Html->link(
                    '<i class="fa fa-file-pdf-o"></i> Télécharger le document',
                    array(
                        'controller' => 'lpcnodes',
                        'action' => 'pdfCert',
                        $classroom_id,
                        $level,
                        2
                    ),
                    array(
                        'escape' => false,
                        'class' => 'text-danger',
                        'target' => '_blank'
                    )
                );?>
            </td>
            <td>
                <?= $this->Html->link(
                    '<i class="fa fa-file-pdf-o"></i> Télécharger le document',
                    array(
                        'controller' => 'lpcnodes',
                        'action' => 'pdf',
                        $classroom_id,
                        $level,
                        1
                    ),
                    array(
                        'escape' => false,
                        'class' => 'text-danger',
                        'target' => '_blank'
                    )
                );?>
            </td>
            <td>
                <?= $this->Html->link(
                    '<i class="fa fa-file-pdf-o"></i> Télécharger le document',
                    array(
                        'controller' => 'lpcnodes',
                        'action' => 'pdf',
                        $classroom_id,
                        $level,
                        2
                    ),
                    array(
                        'escape' => false,
                        'class' => 'text-danger',
                        'target' => '_blank'
                    )
                );?>
            </td>
            <td>
                <?= $this->Html->link(
                    '<i class="fa fa-file-pdf-o"></i> Télécharger le document',
                    array(
                        'controller' => 'lpcnodes',

                        'action' => 'pdfDetail',
                        $classroom_id,
                        $level,
                        1
                    ),
                    array(
                        'escape' => false,
                        'class' => 'text-danger',
                        'target' => '_blank'
                    )
                );?>
            </td>
            <td>
                <?= $this->Html->link(
                    '<i class="fa fa-file-pdf-o"></i> Télécharger le document',
                    array(
                        'controller' => 'lpcnodes',
                        'action' => 'pdfDetail',
                        $classroom_id,
                        $level,
                        2
                    ),
                    array(
                        'escape' => false,
                        'target' => '_blank',
                        'class' => 'text-danger',
                    )
                );?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>

</table>


<h4>Détail par élève</h4>

<div class="col-sm-5">
    <?= $this->Form->create('LPC', array(
        'inputDefaults' => array(
            'div' => 'form-group',
            'label' => array(
                'class' => 'col col-md-2 control-label'
            ),
            'wrapInput' => 'col col-md-3',
            'class' => 'form-control'
        ),
        'class' => 'form-inline'
    )); ?>
    <?= $this->Form->select('palier', ['1' => 'Palier 1', '2' => 'Palier 2'], ['class' => 'form-control', 'default'=>$palier,'empty' => false]); ?>
    <?= $this->Form->select('pupil_id', $pupils, ['class' => 'form-control', 'default'=>$pupil_id, 'empty' => 'Sélectionnez un élève ...']); ?>
    <?= $this->Form->end(); ?>
</div>

<?php if(isset($lpc)): ?>
    <table class="table table-striped">
        <thead>
        <tr>            
            <td></td>
            <td>Forcer</td>
            <td>Type</td>
            <td style="width: 140px;" class="text-center">Date de validation</td>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($lpc as $line): ?>
            <tr>
                <td class="type_<?= $line['Lpcnode']['type'] ?>"><?= $line['Lpcnode']['title']; ?></td>
                <td class="text-center">
                    <?php if(empty($line['LpcnodesPupil']['validation_date'])):
                        echo $this->Html->link(
                            '<i class="fa fa-check-square-o"></i>',
                            array(
                                'controller' => 'lpcnodesPupils',
                                'action' => 'validate_lpcitem',
                                $line['Lpcnode']['id'],
                                $pupil_id,
                                $classroom_id,
                                $palier
                            ),
                            array('escape' => false)
                        );
                    elseif(!empty($line['LpcnodesPupil']['validation_date']) &&
                        $line['LpcnodesPupil']['type_val'] == 'M'):
                        echo $this->Html->link(
                            '<i class="fa fa-square-o"></i>',
                            array(
                                'controller' => 'lpcnodesPupils',
                                'action' => 'unvalidate_lpcitem',
                                $line['Lpcnode']['id'],
                                $pupil_id,
                                $classroom_id,
                                $palier
                            ),
                            array('escape' => false)
                        );
                    endif; ?>
                </td>
                <td class="text-center">
                    <?php if($line['LpcnodesPupil']['type_val'] == 'A'): ?>
                        <i class="fa fa-magic"></i>
                    <?php elseif($line['LpcnodesPupil']['type_val'] == 'M'): ?>
                        <i class="fa fa-hand-paper-o"></i>
                    <?php endif; ?>
                </td>
                <td class="validated text-center">
                    <?php if(!empty($this->Time->format($line['LpcnodesPupil']['validation_date']))): ?>
                        <i class="fa fa-check"></i> <?= $this->Time->format($line['LpcnodesPupil']['validation_date'], '%d/%m/%Y'); ?>
                    <?php else: ?>
                        <i class="fa fa-times red"></i>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif;

$this->start('script'); ?>

<script type='text/javascript'>
    $( ".form-control" ).change(function() {
        var palier = $("#LPCPalier").val();
        var pupil_id = $("#LPCPupilId").val();
        document.location.href = "<?= Router::url('/', true) ?>classrooms/viewlpc/<?= $classroom_id ?>/"+palier+"/"+pupil_id;
    });
</script>

<?php
$this->end();
?>
