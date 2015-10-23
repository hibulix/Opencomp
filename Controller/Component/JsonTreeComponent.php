<?php

class JsonTreeComponent extends Component {

    private $controller;

    public function initialize(Controller $controller) {
        $this->controller = $controller;
    }

    public function passAllCompetencesJsonTreeToView(){
        $this->Competence = ClassRegistry::init('Competence');
        $competences = $this->Competence->findAllCompetences();

        $this->controller->set('competence_id', json_encode($competences));
    }

    public function passAllItemsJsonTreeToView(){
        $this->Competence = ClassRegistry::init('Competence');
        $this->Item = ClassRegistry::init('Item');
        $this->ItemsLevel = ClassRegistry::init('ItemsLevel');
        $user_preferences = unserialize($this->controller->Session->read('Auth.User.user_preferences'));
        if(!empty($user_preferences['levels'])){
            $items = $this->Item->findAllItems($this->ItemsLevel->findItemsIdsFromLevelIds($user_preferences['levels']));
            $competences = $this->Competence->findAllCompetencesFromCompetenceId($this->Item->findCompetenceIdFromItemsIds($this->ItemsLevel->findItemsIdsFromLevelIds($user_preferences['levels'])));
        }else{
            $items = $this->Item->findAllItems();
            $competences = $this->Competence->findAllCompetences();
        }


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
