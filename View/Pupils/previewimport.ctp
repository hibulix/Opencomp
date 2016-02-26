<div class="page-title">
    <h2><i class="fa fa-hand-o-right"></i> <?php  echo __('Prévisialisation, vérifiez les données à importer'); ?></h2>
</div>

<div class="alert alert-info">
    En cas de données incohérentes, assurez vous d'avoir un fichier .csv provenant bien de BE1D.</br>
    Pour plus d'informations sur les imports .csv, consultez la base de connaissances à l'adresse <a target="_blank" href="http://kb.opencomp.fr">http://kb.opencomp.fr/index.php?solution_id=1003</a>
</div>

<table class="table">
    <thead>
    <tr>
        <th>Prénom</th>
        <th>Nom</th>
        <th>Date de naissance</th>
        <th>Niveau</th>
        <th>Sexe</th>
    </tr>
    </thead>
    <tbody>

    <?php foreach($preview as $line): ?>
        <tr>
            <td><?php echo $line[$column['first_name']]; ?></td>
            <td><?php echo $line[$column['name']]; ?></td>
            <td><?php echo $line[$column['birthday']]; ?></td>
            <td><?php echo $line[$column['level']]; ?></td>
            <td><?php echo $line[$column['sex']]; ?></td>
        </tr>
    <?php endforeach;  ?>
    </tbody>
</table>

<?php
echo $this->Form->create('Pupil', array(
    'action' => 'runimport/classroom_id:'.$classroom_id,
));

echo $this->Form->hidden('Pupil.first_name', ['value' => $column['first_name']]);
echo $this->Form->hidden('Pupil.name', ['value' => $column['name']]);
echo $this->Form->hidden('Pupil.birthday', ['value' => $column['birthday']]);
echo $this->Form->hidden('Pupil.level', ['value' => $column['level']]);
echo $this->Form->hidden('Pupil.sex', ['value' => $column['sex']]);

echo $this->Form->submit('Valider et importer', array(
    'div' => 'col col-md-9 col-md-offset-5',
    'class' => 'btn btn-primary'
));
?>