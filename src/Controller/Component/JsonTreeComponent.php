<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;

class JsonTreeComponent extends Component
{

    /**
     * @return string
     */
    public function allCompetencesToJson()
    {
        $this->Competences = TableRegistry::get('Competences');
        $competences = $this->Competences->findAllCompetences();

        return json_encode($competences);
    }

    /**
     * @return string
     */
    public function allItemsToJson()
    {
        $this->Competences = TableRegistry::get('Competences');
        $this->Items = TableRegistry::get('Items');
        $competences = $this->Competences->findAllCompetences();
        $items = $this->Items->findAllItems(null, $this->request->session()->read('Auth.User.id'));

        $competencesItems = array_merge($competences, $items);

        return json_encode($competencesItems);
    }

    /**
     * @param array $competenceIds An array containing competenceIds
     * @param array $itemsId An array containing itemsIds
     * @return string
     */
    public function allUsedItemsToJson($competenceIds, $itemsId)
    {
        $this->Competences = TableRegistry::get('Competences');
        $this->Items = TableRegistry::get('Items');
        $competences = $this->Competences->findAllCompetencesFromCompetenceId(array_unique($competenceIds));
        $items = $this->Items->findAllItems($itemsId, $this->request->session()->read('Auth.User.id'));

        $competencesItems = array_merge($competences, $items);

        return json_encode($competencesItems);
    }

    /**
     * @return string
     */
    public function allLpcnodesToJson()
    {
        $this->Lpcnodes = TableRegistry::get('Lpcnodes');
        $lpcnodes = $this->Lpcnodes->findAllLpcnodes();

        return json_encode($lpcnodes);
    }
}
