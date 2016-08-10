<?php
$editLink = $this->AuthLink->link(' <i class="fa fa-pencil"></i> ', '/evaluations/edit/'.$evaluation->id, array('escape' => false));
$classroomLink = $this->AuthLink->link($evaluation->classroom->title, '/classrooms/viewtests/'.$evaluation->classroom->id, array('escape' => false));

$this->assign('header', $evaluation->title.$editLink    );
$this->assign('description', $classroomLink);
?>

<?= $this->cell('Test::header', [$evaluation->id]); ?>