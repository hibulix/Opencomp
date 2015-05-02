<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\Controller;

class JsonTreeComponent extends Component {

    private $controller;

    public function passAllCompetencesJsonTreeToView(){
        $this->Competence = ClassRegistry::init('Competence');
        $competences = $this->Competences->findAllCompetences();

        $this->controller->set('competence_id', json_encode($competences));
    }

    public function passAllItemsJsonTreeToView(){
        $this->Competence = ClassRegistry::init('Competence');
        $this->Item = ClassRegistry::init('Item');
        $competences = $this->Competence->findAllCompetences();
        $items = $this->Item->findAllItems();

        $competences_items = array_merge($competences, $items);

        $this->controller->set('json', json_encode($competences_items));
    }

    public function passAllUsedItemsJsonTreeToView($competence_ids, $items_id){
        $this->Competence = ClassRegistry::init('Competence');
        $this->Item = ClassRegistry::init('Item');
        $competences = $this->Competence->findAllCompetencesFromCompetenceId(array_unique($competence_ids));
        $items = $this->Item->findAllItems($items_id);

        $competences_items = array_merge($competences, $items);

        $this->controller->set('json', json_encode($competences_items));
    }

    public function passAllLpcnodesToView(){
        $this->Lpcnode = ClassRegistry::init('Lpcnode');
        $lpcnodes = $this->Lpcnode->findAllLpcnodeIn();

        $this->controller->set('json', json_encode($lpcnodes));
    }

}
