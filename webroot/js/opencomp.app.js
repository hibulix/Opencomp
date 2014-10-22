$(document).ready(function() {

    //Bootstrap Javascript components initialization
    $('.alert').alert();
    $('.info').tooltip();
    $('.chzn-select').chosen();
    $('.startdate').datepicker({
        weekStart: 1,
        format: 'yyyy-mm-dd',
        language: 'fr-FR',
        autoclose: true
    })

    //Sélectionner/déseletionner tous les élèves d'un groupe de niveau
     $(".selectPupils").click(function(event){
      event.preventDefault();
      var classe= $(event.delegateTarget).val();
      $('optgroup[label='+classe+']').children().attr('selected', 'selected');
      $("#PupilPupil").trigger("chosen:updated");
    })

    $(".unselectPupils").click(function(event){
      event.preventDefault();
      var classe= $(event.delegateTarget).val();
      $('optgroup[label='+classe+'] > option[selected=selected]').removeAttr('selected');
      $("#PupilPupil").trigger("chosen:updated");
    })

    //Configuration des arbres JsTree (Référentiels IO et LPC)
    $("#competences").jstree({
        'core' : {
          'strings' : {
              'Loading ...' : 'Veuillez patienter ...'
          },
          'data' : {
            'url' : 'competences/returnCompetences',
            'data' : function (node) {
                return { 'id' : node.id };
            }
          },
        }
	});

    $("#lpc_nodes").jstree({
        'core' : {
            'strings' : {
                'Loading ...' : 'Veuillez patienter ...'
            },
            'data' : {
                'url' : 'lpcnodes/returnNodes',
                'data' : function (node) {
                    return { 'id' : node.id };
                }
            }
        }
    });

    $("#tree_attach_item").jstree({
        'state' : { 'key' : 'tree_attach_item' },
        'contextmenu' : { 
          //'show_at_node' : false,
          'items' : returnContextMenu
        },
        'plugins' : [ 'state', 'contextmenu' ],
        'core' : {
            'check_callback' : true,
            'strings' : {
                'Loading ...' : 'Veuillez patienter ...'
            },
            'data' : {
                'url' : '../returnCompetences',
                'data' : function (node) {
                    return { 'id' : node.id };
                }
            },
        }
    });

    $("#tree_attach_unrated_item").jstree({
        'state' : { 'key' : 'tree_attach_unrated_item' },
        'contextmenu' : {
          'items' : returnContextMenuUnratedItems
        },
        'plugins' : [ 'state', 'contextmenu' ],
        'core' : {
            'check_callback' : true,
            'strings' : {
                'Loading ...' : 'Veuillez patienter ...'
            },
            'data' : {
                'url' : 'returnCompetences',
                'data' : function (node) {
                    return { 'id' : node.id };
                }
            },
        }
    });

    function returnContextMenu(node){
      if(node.data.type == "feuille"){
        var idItem = node.data.id;
        var competence = $('#'+node.parent+'>a').text();
        var idCompetence = $('#'+node.parent).attr('data-id');
      }
      else if(node.data.type == "noeud"){
        var competence = $('#'+node.data.id+'>a').text();
        var idCompetence = node.data.id;
      }

    var items = {
        "choose" : {
            "label" : "ajouter cet item à l'évaluation",
            "icon" : "fa text-info fa-check",
            "action" : function (obj){
                window.location.href = $('#base_url').text()+'evaluationsItems/attachitem/evaluation_id:'+$('#id_evaluation').text()+'/item_id:'+idItem;
            },
        },
        "createNew" : {
            "label" : "créer un nouvel item dans \""+competence.trim()+"\"",
            "separator_before" : true,
            "icon" : "fa text-success fa-plus",
            "action" : function (obj){
                window.location.href = $('#base_url').text()+'evaluationsItems/additem/evaluation_id:'+$('#id_evaluation').text()+'/competence_id:'+idCompetence;
            }
        }
    };

      if (node.data.type == "noeud") {
          delete items.choose;
      }

      return items;
    }

     function returnContextMenuUnratedItems(node){
      if(node.data.type == "feuille"){
        var idItem = node.data.id;
        var competence = $('#'+node.parent+'>a').text();
        var idCompetence = $('#'+node.parent).attr('data-id');
      }
      else if(node.data.type == "noeud"){
        var competence = $('#'+node.data.id+'>a').text();
        var idCompetence = node.data.id;
      }

    var items = {
        "choose" : {
            "label" : "choisir cet item",
            "icon" : "fa text-info fa-check",
            "action" : function (obj){
                window.location.href = $('#base_url').text()+'evaluationsItems/attachunrateditem/period_id:'+$('#period_id').text()+'/item_id:'+idItem+'/classroom_id:'+$('#classroom_id').text();
            },
        },
        "createNew" : {
            "label" : "créer un nouvel item dans \""+competence.trim()+"\"",
            "separator_before" : true,
            "icon" : "fa text-success fa-plus",
            "action" : function (obj){
                window.location.href = $('#base_url').text()+'evaluationsItems/addunrateditem/period_id:'+$('#period_id').text()+'/competence_id:'+idCompetence+'/classroom_id:'+$('#classroom_id').text();
            }
        }
    };

      if (node.data.type == "noeud") {
          delete items.choose;
      }

      return items;
    }

    $("#tree_attach_item").on("dblclick.jstree-default", function (event) {
        var node = $(event.target).closest("li");
        if($(node[0]).attr("data-type") == "feuille"){
            var idItem = $(node[0]).attr("data-id");
            window.location.href = $('#base_url').text()+'evaluationsItems/attachitem/evaluation_id:'+$('#id_evaluation').text()+'/item_id:'+idItem;
        }else if($(node[0]).attr("data-type") == "noeud"){
            var idCompetence = $(node[0]).attr("data-id");
            window.location.href = $('#base_url').text()+'evaluationsItems/additem/evaluation_id:'+$('#id_evaluation').text()+'/competence_id:'+idCompetence;
        }
    });

    $("#tree_attach_unrated_item").on("dblclick.jstree-default", function (event) {
        var node = $(event.target).closest("li");
        if($(node[0]).attr("data-type") == "feuille"){
            var idItem = $(node[0]).attr("data-id");
            window.location.href = $('#base_url').text()+'evaluationsItems/attachunrateditem/period_id:'+$('#period_id').text()+'/item_id:'+idItem+'/classroom_id:'+$('#classroom_id').text();
        }else if($(node[0]).attr("data-type") == "noeud"){
            var idCompetence = $(node[0]).attr("data-id");
            window.location.href = $('#base_url').text()+'evaluationsItems/addunrateditem/period_id:'+$('#period_id').text()+'/competence_id:'+idCompetence+'/classroom_id:'+$('#classroom_id').text();
        }
    });

    //Envoyer automatiquement le focus au premier champ visible de la page
	$('form').find('input[type=text],textarea,select').filter(':visible:first').focus();

	$('.send').change(function(event) {
		var pupil_id = $(event.delegateTarget).val();
		$(event.delegateTarget).val(pupil_id);
		$('#ResultSelectpupilForm').submit();
	});

	$('.send').focus(function(event) {
			$(event.delegateTarget).val('');
	});

	$('.focus').focus();

    //Remplacement des AAA, BBB, CCC, DDD et NEV par A, B, C, D, NE lors de l'utilisation de la saisie assistée.
	$('.result').blur(function(event) {
		if ($(event.delegateTarget).val() == 'AAA' || $(event.delegateTarget).val() == 'A' || $(event.delegateTarget).val() == 'a') { // Cette condition renvoie « true », le code est donc exécuté
		    $(event.delegateTarget).val('A');
		    $(event.delegateTarget).css("background-color", "#e4ffcb");
		} else if ($(event.delegateTarget).val() == 'BBB' || $(event.delegateTarget).val() == 'B' || $(event.delegateTarget).val() == 'b') {
			$(event.delegateTarget).val('B');
			$(event.delegateTarget).css("background-color", "#e4ffcb");
		} else if ($(event.delegateTarget).val() == 'CCC' || $(event.delegateTarget).val() == 'C' || $(event.delegateTarget).val() == 'c') {
			$(event.delegateTarget).val('C');
			$(event.delegateTarget).css("background-color", "#e4ffcb");
		} else if ($(event.delegateTarget).val() == 'DDD' || $(event.delegateTarget).val() == 'D' || $(event.delegateTarget).val() == 'd') {
			$(event.delegateTarget).val('D');
			$(event.delegateTarget).css("background-color", "#e4ffcb");
		} else if ($(event.delegateTarget).val() == 'NEV' || $(event.delegateTarget).val() == 'NE' || $(event.delegateTarget).val() == 'ne') {
			$(event.delegateTarget).val('NE');
			$(event.delegateTarget).css("background-color", "#e4ffcb");
		} else if ($(event.delegateTarget).val() == 'ABS' || $(event.delegateTarget).val() == 'abs') {
			$(event.delegateTarget).val('ABS');
			$(event.delegateTarget).css("background-color", "#e4ffcb");
		} else if ($(event.delegateTarget).val() == '') {
			$(event.delegateTarget).css("background-color", "#e4ffcb");
		} else {
			$(event.delegateTarget).val('Err.');
			$(event.delegateTarget).css("background-color", "#ffb9b9");
		}
		if($('form').find('input[type=text],textarea,select').filter(':visible:last').attr("id") == $(event.delegateTarget).attr("id")){
			$('#ResultsAddForm').submit();
		}
	});
});