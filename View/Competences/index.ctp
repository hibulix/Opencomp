<div class="page-title">
    <h2><?php echo __('Instructions officielles').' <small>B.O. élémentaire 2008, progressions 2012, B.O. maternelle 2015 & <abbr title="Enseignement Moral et Civique">EMC</abbr> 2015</small>'; ?></h2>
    <div class="btn-group ontitle">
        <?php echo $this->Html->link('<i class="fa fa-expand"></i> '.__('Déplier l\'arbre'), '#', array('class' => 'btn btn-default', 'escape' => false, 'onclick' => "$('#competences').jstree('open_all','',200);")); ?>
        <?php echo $this->Html->link('<i class="fa fa-compress"></i> '.__('Replier l\'arbre'), '#', array('class' => 'btn btn-default', 'escape' => false, 'onclick' => "$('#competences').jstree('close_all','',200);")); ?>
    </div>
</div>

<?php if(AuthComponent::user('role') !== 'admin'){ ?>
	<div class="alert alert-info">
	<i class="fa fa-info-circle fa fa-3x pull-left"></i>
	  Dans Opencomp, les référentiels sont utilisés de façon à hiérarchiser les items lors de l'impression de documents papier de synthèse (Bulletin, LPC).<br />
	  Seul l'administrateur de l'application a la possibilité de modifier les référentiels. Vous pouvez néanmoins consulter l'arborescence de ce référentiel.
	</div>
<?php }else{
	echo '<p>' . $this->Html->link(
		' <i class="fa fa-plus"></i> '.__('créer une nouvelle compétence à la racine de l\'arbre'),
		array(
			'controller' => 'competences',
			'action' => 'add'
		),
		array(
			'escape' => false,
			'style' => 'color:green;'
		)
	) . '</p>';
} ?>
<p>
<?php echo $this->Html->link(
		' <i class="fa fa-filter"></i> '.__('personnaliser les items affichés dans le référentiel'),
		array(
				'controller' => 'users',
				'action' => 'preferences'
		),
		array(
				'escape' => false,
				'class' => 'text-info'
		)
); ?>
</p>
<div class="col-md-3 pull-right">
	<input type="text" id="search" class="form-control" placeholder="🔍 chercher dans le référentiel" />
</div>
<?php
$this->start('script');
?>

<script type='text/javascript'>
	var role = '<?php echo AuthComponent::user('role'); ?>';
	var data = <?php echo $json; ?>;

	function returnContextMenuAdminCompetence(node){
		if(node.data.type == "feuille"){
			var idItem = node.id.substr(5);
            var regex = /<span(?:.*)<\/span>.(.*)/g;
            var item = node.text.replace(regex,"$1");
            var competence = $('#'+node.parent+'>a').text();
			var idCompetence = $('#'+node.parent).attr('data-id');
		}
		else if(node.data.type == "noeud"){
			var competence = $('#'+node.id+'>a').text();
			var idCompetence = node.id;
			var deleted = node.data.deleted;
		}

		var items = {
			"createNew" : {
				"label" : "<strong>créer une compétence</strong> enfant dans <em>\""+competence.trim()+"\"</em>",
				"icon" : "fa text-success fa-plus",
				"action" : function (obj){
					window.location.href = $('#base_url').text()+'competences/add/'+idCompetence;
				}
			},
			"createItem" : {
				"label" : "<strong>créer un item</strong> dans <em>\""+competence.trim()+"\"</em>",
				"icon" : "fa text-success fa-plus",
				"action" : function (obj){
					window.location.href = $('#base_url').text()+'items/add/'+idCompetence;
				}
			},
			"edit" : {
				"label" : "<strong>modifier</strong> l'intitulé ou la compétence parente de <em>\""+competence.trim()+"\"</em>",
				"icon" : "fa text-warning fa-pencil",
				"action" : function (obj){
					window.location.href = $('#base_url').text()+'competences/edit/'+idCompetence;
				}
			},
			"editItem" : {
				"label" : "<strong>modifier</strong> cet item",
				"icon" : "fa text-warning fa-pencil",
				"action" : function (obj){
					window.location.href = $('#base_url').text()+'items/edit/'+idItem;
				}
			},
            "copyItem" : {
                "label" : "<strong>copier</strong> dans le presse-papier",
                "icon" : "fa text-warning fa-clipboard",
                "action" : function (obj){
                    copyTextToClipboard(item);
                }
            },
			"softDelete" : {
				"label" : "<strong>masquer</strong> cette compétence dans le référentiel",
				"icon" : "fa text-danger fa-eye-slash",
				"action" : function (obj){
					window.location.href = $('#base_url').text()+'competences/softDelete/'+idCompetence;
				}
			},
			"softUnDelete" : {
				"label" : "<strong>réintégrer</strong> cette compétence dans le référentiel",
				"icon" : "fa text-success fa-eye",
				"action" : function (obj){
					window.location.href = $('#base_url').text()+'competences/softUnDelete/'+idCompetence;
				}
			},
			"moveTop" : {
				"label" : "déplacer <strong>vers le haut</strong>",
				"icon" : "fa text-info fa-arrow-up",
				"action" : function (obj){
					window.location.href = $('#base_url').text()+'competences/moveup/'+idCompetence;
				}
			},
			"moveDown" : {
				"label" : "déplacer <strong>vers le bas</strong>",
				"icon" : "fa text-info fa-arrow-down",
				"action" : function (obj){
					window.location.href = $('#base_url').text()+'competences/movedown/'+idCompetence;
				}
			}
		};

		if (node.data.type == "feuille" || role !== 'admin') {
			delete items.createNew;
            delete items.createItem;
			delete items.edit;
            delete items.editItem;
			delete items.softDelete;
			delete items.softUnDelete;
			delete items.moveTop;
			delete items.moveDown;
		}else{
			delete items.editItem;
			if(deleted)
				delete items.softDelete;
			else
				delete items.softUnDelete;
		}

        if (node.data.type == "noeud"){
            delete items.copyItem;
        }

		return items;
	}

	$("#competences").jstree({
		'contextmenu' : {
			'items' : returnContextMenuAdminCompetence
		},
        'state' : { 'key' : 'referentiel_io' },
		'plugins' : [ 'contextmenu','search','state' ],
		'core' : {
			'strings' : {
				'Loading ...' : 'Veuillez patienter ...'
			},
			'data' : data
		}
	});
    var to = false;
    $('#search').keyup(function () {
        if(to) { clearTimeout(to); }
        to = setTimeout(function () {
            var v = $('#search').val();
            $('#competences').jstree(true).search(v);
        }, 250);
    });

    function copyTextToClipboard(text) {
        var textArea = document.createElement("textarea");

        //
        // *** This styling is an extra step which is likely not required. ***
        //
        // Why is it here? To ensure:
        // 1. the element is able to have focus and selection.
        // 2. if element was to flash render it has minimal visual impact.
        // 3. less flakyness with selection and copying which **might** occur if
        //    the textarea element is not visible.
        //
        // The likelihood is the element won't even render, not even a flash,
        // so some of these are just precautions. However in IE the element
        // is visible whilst the popup box asking the user for permission for
        // the web page to copy to the clipboard.
        //

        // Place in top-left corner of screen regardless of scroll position.
        textArea.style.position = 'fixed';
        textArea.style.top = 0;
        textArea.style.left = 0;

        // Ensure it has a small width and height. Setting to 1px / 1em
        // doesn't work as this gives a negative w/h on some browsers.
        textArea.style.width = '2em';
        textArea.style.height = '2em';

        // We don't need padding, reducing the size if it does flash render.
        textArea.style.padding = 0;

        // Clean up any borders.
        textArea.style.border = 'none';
        textArea.style.outline = 'none';
        textArea.style.boxShadow = 'none';

        // Avoid flash of white box if rendered for any reason.
        textArea.style.background = 'transparent';


        textArea.value = text;

        document.body.appendChild(textArea);

        textArea.select();

        try {
            var successful = document.execCommand('copy');
            var msg = successful ? 'successful' : 'unsuccessful';
            console.log('Copying text command was ' + msg);
        } catch (err) {
            alert('Votre navigateur ne supporte pas la fonctionnalité de copier/coller depuis Opencomp. Merci de mettre à jour votre navigateur.');
        }

        document.body.removeChild(textArea);
    }
</script>
<?php
$this->end();

?>

<div id="competences" class="jstree-default" style="margin-top:20px;">

</div>
