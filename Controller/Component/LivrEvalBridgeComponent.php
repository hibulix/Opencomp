<?php

class LivrEvalBridgeComponent extends Component
{
    private $login;
    private $password;

    public function setLogin($login){
        $this->login = $login;
    }

    public function setPassword($password){
        $this->password = $password;
    }

    public function urlifyPostData($post){
        //url-ify the data for the POST
        $fields_string = "";
        foreach($post as $key=>$value) { $fields_string .= $key.'='.urlencode($value).'&'; }
        rtrim($fields_string, '&');

        return $fields_string;
    }

    public function sendAuthenticatedRequest($url, $post = null)
    {
        $curl = curl_init();

        $auth = array(
            'login' => $this->login,
            'mdp' => $this->password,
            'button' => 'Valider'
        );

        curl_setopt($curl, CURLOPT_URL, 'https://livreval.fr/amiens/param_ecole.php');
        curl_setopt($curl,CURLOPT_POST, 3);
        curl_setopt($curl,CURLOPT_POSTFIELDS, $this->urlifyPostData($auth));
        curl_setopt($curl, CURLOPT_COOKIESESSION, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        curl_exec($curl);

        curl_setopt($curl, CURLOPT_URL, $url);
        if(isset($post)) {
            curl_setopt($curl, CURLOPT_POST, count($post));
            curl_setopt($curl, CURLOPT_POSTFIELDS, $this->urlifyPostData($post));
        }
        $return = curl_exec($curl);

        curl_close($curl);

        return $return;
    }

    public function getPupils($first_pupil_id, $livreval_classroom_id){
        $fields = array(
            'eleve' => $first_pupil_id,
            'classe' => $livreval_classroom_id,
            'val_filtre' => '0',
            'choix_doc' => '1',
            'choix_palier' => '1'
        );
        $response = $this->sendAuthenticatedRequest('https://livreval.fr/amiens/include/js/ajaxgerer_lpc.php', $fields);

        $dom = str_get_html($response);
        $livreval_pupils_dom = $dom->find('select[id=choix_eleve] option');

        $livreval_pupils = array();
        foreach ($livreval_pupils_dom as $elmnt){
            $livreval_pupils[$elmnt->getAttribute('value')] = $elmnt->plaintext;
        }

        return $livreval_pupils;
    }

    public function getLPCValidatedItems($livreval_pupil_id, $livreval_classroom_id, $palier = 1){
        $fields = array(
            'eleve' => $livreval_pupil_id,
            'classe' => $livreval_classroom_id,
            'val_filtre' => '0',
            'choix_doc' => '1',
            'choix_palier' => $palier
        );
        $response = $this->sendAuthenticatedRequest('https://livreval.fr/amiens/include/js/ajaxgerer_lpc.php', $fields);

        $dom = str_get_html($response);
        $livreval_validation_lpc_dom = $dom->find('div[id^=date]');
        $livreval_validation_lpc = array();
        foreach ($livreval_validation_lpc_dom as $elmnt){
            if(!empty($elmnt->plaintext)){
                $id_item_lpc = explode('_',$elmnt->getAttribute('id'));
                $date = date_create_from_format('d/m/Y H:i:s',$elmnt->plaintext . " " . "00:00:00");
                $livreval_validation_lpc[$id_item_lpc[1]] = date_format($date, "Y-m-d");
            }
        }

        return $livreval_validation_lpc;
    }
}
