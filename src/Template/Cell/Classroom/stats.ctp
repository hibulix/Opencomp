<div class="row">
    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
        <div class="info-box <?= ($action == 'pupils') ? 'bg-blue' : '' ?>">
            <span class="info-box-icon <?= ($action == 'pupils') ? '' : 'bg-blue' ?>" style="opacity: 1">
                <?= $this->Html->link('<i class="fa fa-child"></i>', '/classrooms/pupils/' . $classroomId, ['style' => 'color:white;', 'escape' => false]); ?>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Élèves</span>
                <span class="info-box-number"><?= $pupils ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
        <div class="info-box <?= ($action == 'tests') ? 'bg-green' : '' ?>">
            <span class="info-box-icon <?= ($action == 'tests') ? '' : 'bg-green' ?>">
                <?= $this->Html->link('<i class="fa fa-file-text-o"></i>', '/classrooms/tests/' . $classroomId, ['style' => 'color:white;', 'escape' => false]); ?>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Évaluations</span>
                <span class="info-box-number">
                    <?= $this->Html->link($evaluations, '/classrooms/tests/' . $classroomId, ['style' => 'color:inherit;']); ?>
                </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->

    <!-- fix for small devices only -->
    <div class="clearfix visible-sm-block"></div>

    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
        <div class="info-box <?= ($action == 'workedskills') ? 'bg-yellow' : '' ?>">
            <span class="info-box-icon <?= ($action == 'workedskills') ? '' : 'bg-yellow' ?>">
                 <?= $this->Html->link('<i class="fa fa-check-square-o"></i>', '/classrooms/workedskills/' . $classroomId, ['style' => 'color:white;', 'escape' => false]); ?>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Compétences<br />travaillées</span>
                <span class="info-box-number"><?= $unratedItems ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
        <div class="info-box <?= ($action == 'reports') ? 'bg-red' : '' ?>">
            <span class="info-box-icon <?= ($action == 'reports') ? '' : 'bg-red' ?>">
                <?= $this->Html->link('<i class="fa fa-file-pdf-o"></i>', '/classrooms/reports/' . $classroomId, ['style' => 'color:white;', 'escape' => false]); ?>
            </span>

            <div class="info-box-content">
                <span class="info-box-text">Bilans<br />périodiques</span>
                <span class="info-box-number"><?= $reports ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
</div>
