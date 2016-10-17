<?php
$this->assign('header', 'Référentiels');
$this->assign('description', 'Instructions officielles');
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
<div id="jstree_demo_div"></div>

<?php
$this->start('script');
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>

<script>
    $('#jstree_demo_div').jstree({ 'core' : {
        'data' : <?= $listjson ?>
    },
        "types" : {
            "0" : {
                "icon" : "fa fa-cubes"
            },
            "1" : {
                "icon" : "fa fa-cube"
            },
            "cycle" : {
                "icon" : "fa fa-certificate"
            }
        },
        "plugins" : [ "types" ]});
</script>
<?php $this->end();