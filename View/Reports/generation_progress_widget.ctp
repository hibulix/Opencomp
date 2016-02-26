<?php

function returnStateIcon($state){
    $tabStates = [
        "ready" => "fa-lg fa-clock-o text-warning",
        "reserved" => "fa-lg fa-spinner fa-spin text-info",
        "done" => "fa-lg fa-check text-success"
    ];
    echo $tabStates[$state];
}

?>

<ul class="list-unstyled">
    <?php foreach($pupilsStates as $pupil): ?>
        <li class="list-group-item col-md-3"><i class="fa <?php returnStateIcon($pupil['state']); ?>"></i>  <?php echo $pupil['name']; ?></li>
    <?php endforeach; ?>
</ul>
