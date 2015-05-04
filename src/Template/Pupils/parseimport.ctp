<div class="page-title">
    <h2><i class="fa fa-hand-o-right"></i> <?php  echo __('Prévisialisation, vérifiez les données à importer'); ?></h2>
</div>

<div class="alert alert-info">
    En cas de données incohérentes, assurez vous d'avoir un fichier .csv provenant bien de BE1D.</br>
    Pour plus d'informations sur les imports .csv, consultez la base de connaissances à l'adresse <a target="_blank" href="http://kb.opencomp.fr/index.php?sid=117968">http://kb.opencomp.fr/index.php?sid=117968</a>
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
        <td><?php echo $line[1]; ?></td>
        <td><?php echo $line[0]; ?></td>
        <td><?php echo $line[9]; ?></td>
        <td><?php echo $line[2]; ?></td>
        <td><?php echo $line[13]; ?></td>
    </tr>
<?php endforeach;

$to = $this->Url->build(array(
    "controller" => "pupils",
    "action" => "parseimport",
    "classroom_id" => $classroom->id,
    "step" => "go"
));

?>
    </tbody>
</table>

<div class="form-actions" style="margin-bottom: 100px;">
    <a href="<?php echo $to ?>" class="btn btn-large btn-success pull-right"><i class="fa fa-check"></i> Valider et importer</a>
</div>
