<?php

class EvaluationsItemsController extends AppController
{function index()
    {
        
        $this->set('evaluations_items', $this->paginate());
    }
    
    
    //Ajout d'une liste évaluation 
    function add($id = null)
    {
        $this->set('title_for_layout', __('Ajouter une liste d\'évaluation',true));
    }
    
    //Edition d'une évaluation
      function edit($id = null)
    {
        $this->set('title_for_layout', __('Modifier une évaluation',true));
    }
    
    
    //Suppression d'une évaluation
    function delete ($id)
    {
    
        
    }
    
}
?>