<?php foreach ($establishments as $establishment) : ?>
<li class="treeview <?= (in_array($params['controller'], ['Classrooms', 'Evaluations']) && (isset($currentClassroom) && $currentClassroom->establishment_id === $establishment->establishment->id)) ? 'active' : ''; ?>">
    <a href="#"><i class="fa fa-university"></i> <span class="ellipsis"><?= $establishment->establishment->name ?></span> <i class="fa fa-angle-right pull-right"></i></a>
    <ul class="treeview-menu">
        <?php if (isset($classrooms[$establishment->establishment->id])) : ?>
        <?php foreach ($classrooms[$establishment->establishment->id] as $classroomId => $classroomTitle) : ?>
        <li class="treeview <?= (($params['controller'] == 'Classrooms' && $params['pass'][0] == $classroomId) || (isset($currentClassroom) && $currentClassroom->id == $classroomId)) ? 'active' : ''; ?>">
            <a href="#"><i class="fa fa-group"></i> <span><?= $classroomTitle ?><i class="fa fa-angle-right pull-right"></i></a>
            <ul class="treeview-menu">
                <li
                    <?= ($params['controller'] == 'Classrooms' && $params['action'] == 'pupils' && $params['pass'][0] == $classroomId) ? 'class="active"' : ''; ?>
                ><?= $this->Html->link(
                    '<i class="fa fa-child"></i> <span>Élèves</span>',
                    [
                        'controller' => 'classrooms',
                        'action' => 'pupils',
                        $classroomId
                    ],
                    ['escape' => false]
                ); ?>
                </li>
                <li
                    <?= ($params['controller'] == 'Classrooms' && $params['action'] == 'tests' && $params['pass'][0] == $classroomId) ? 'class="active"' : ''; ?>
                    <?= (isset($currentClassroom) && $currentClassroom->id == $classroomId) ? 'class="active"' : ''; ?>
                ><?= $this->Html->link('<i class="fa fa-file-text-o"></i> <span>Évaluations</span>', ['controller' => 'classrooms', 'action' => 'tests', $classroomId], ['escape' => false]); ?>
                </li>
                <li
                    <?= ($params['controller'] == 'Classrooms' && $params['action'] == 'workedskills' && $params['pass'][0] == $classroomId) ? 'class="active"' : ''; ?>
                ><?= $this->Html->link('<i class="fa fa-check"></i> <span>Compétences travaillées</span>', ['controller' => 'classrooms', 'action' => 'workedskills', $classroomId], ['escape' => false]); ?>
                </li>
                <li
                    <?= ($params['controller'] == 'Classrooms' && $params['action'] == 'reports' && $params['pass'][0] == $classroomId) ? 'class="active"' : ''; ?>
                >
                    <?= $this->Html->link('<i class="fa fa-file-pdf-o"></i> <span>Bilans périodiques</span>', ['controller' => 'classrooms', 'action' => 'reports', $classroomId], ['escape' => false]); ?>
                </li>
            </ul>
        </li>
        <?php endforeach; ?>
        <?php endif; ?>
        <li <?= ($params['controller'] == 'Classrooms' && $params['action'] == 'add' && $params['pass'][0] == $establishment->establishment->id) ? 'class="active"' : ''; ?>>
            <?= $this->Html->link(
                '<i class="fa fa-plus"></i> <span>Créer une nouvelle classe</span>',
                [
                    'controller' => 'classrooms',
                    'action' => 'add',
                    $establishment->establishment->id
                ],
                ['escape' => false]
            ); ?>
        </li>
    </ul>
</li>
<?php endforeach; ?>