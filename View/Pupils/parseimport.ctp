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

<?php
foreach($preview as $pupil){
    echo"<tr><td>".$pupil['prenom']."</td><td>".$pupil['nom']."</td><td>".$pupil['datebase']." (".$pupil['datenaiss'].")</td><td>".$pupil['niveaubase']." (".$pupil['niveau'].")</td><td>".$pupil['sexe']."</td></tr>";
}

$to = $this->Html->url(array(
    "controller" => "pupils",
    "action" => "parseimport",
    "classroom_id" => $classroom_id,
    "step" => "go"
));

?>
    </tbody>
</table>

<div class="form-actions">
    <a href="<?php echo $to ?>" class="btn btn-large btn-success pull-right"><i class="fa fa-check"></i> Valider et importer</a>
</div>
