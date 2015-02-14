<?php echo $this->element('ClassroomBase'); ?>

<ul class="nav nav-pills">
    <li><?php echo $this->Html->link(__('Élèves'), array('controller' => 'classrooms', 'action' => 'view', $classroom['Classroom']['id'])); ?></li>
    <li><?php echo $this->Html->link(__('Évaluations'), array('controller' => 'classrooms', 'action' => 'viewtests', $classroom['Classroom']['id'])); ?></li>
    <li><?php echo $this->Html->link(__('Items non évalués'), array('controller' => 'classrooms', 'action' => 'viewunrateditems', $classroom['Classroom']['id'])); ?></li>
    <li class="active"><?php echo $this->Html->link(__('Bulletins'), array('controller' => 'classrooms', 'action' => 'viewreports', $classroom['Classroom']['id'])); ?></li>
</ul>

<div class="page-title">
    <h3><?php echo 'Progression de la génération pour "'.$report['Report']['title'].'"'; ?></h3>
</div>

<div id="progress">
<?php echo $this->element('generationProgressWidget', [
    "report_id" => $report['Report']['id'],
]); ?>
</div>

<?php $this->start('script'); ?>

<script type="text/javascript">
    var interval = 500;
    var refresh = function() {
        $.ajax({
            url: "../../reports/generationProgressWidget/<?php echo $report['Report']['id'];?>" ,
            cache: false,
            success: function(html) {
                $('#progress').html(html);
                if($('#progress li i').hasClass('fa-clock-o')){
                    setTimeout(function() {
                        refresh();
                    }, interval);
                }else{
                    setTimeout(function() {
                        location.href = '../../reports/download/<?php echo $report['Report']['id'];?>';
                        setTimeout(function() {
                            location.href = '../../classrooms/viewreports/<?php echo $report['Report']['classroom_id'];?>';
                        }, 3000);
                    }, 1000);
                }
            }
        });
    };
    refresh();
</script>

<?php $this->end();
