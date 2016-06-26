<?php
App::uses('AppModel', 'Model');

/**
 * LpcnodesPupil Model
 *
 */
class LpcnodesPupil extends AppModel {

    public function validateLPC(){
        $this->query("INSERT IGNORE INTO lpcnodes_pupils (lpcnode_id, pupil_id, validation_date)
                      SELECT lpcnode_id, pupil_id, CURDATE() AS validation_date FROM `v_lpc_items_to_validate`;");
        $this->query("INSERT IGNORE INTO lpcnodes_pupils (lpcnode_id, pupil_id, validation_date)
                      SELECT lpc_domaine, pupil_id, CURDATE() AS validation_date FROM `v_lpc_domains_to_validate`;");
        $this->query("INSERT IGNORE INTO lpcnodes_pupils (lpcnode_id, pupil_id, validation_date)
                      SELECT lpc_competence, pupil_id, CURDATE() AS validation_date FROM `v_lpc_competences_to_validate`;");
    }

}
