<?php

namespace App\Controller\Component;

use Cake\Controller\Component;

class CheckParamsComponent extends Component {

    public function checkForNamedParam($modelOfNamedParam, $namedParamName, $namedParamValue) {
        if(isset($namedParamValue)) {
            $id = intval($namedParamValue);
            $this->controller->set($namedParamName, $id);

            $this->{$modelOfNamedParam} = ClassRegistry::init($modelOfNamedParam);
            $this->{$modelOfNamedParam}->id = $id;

            if (!$this->{$modelOfNamedParam}->exists()) {
                $this->controller->Session->setFlash(__('Le paramètre que vous avez passé est invalide !'), 'flash_error');
                $this->controller->redirect($this->controller->referer());
            }
        } else {
            $this->controller->Session->setFlash(__('Vous devez passer un paramètre à cette méthode !'), 'flash_error');
            $this->controller->redirect($this->controller->referer());
        }

        return $id;
    }
}
