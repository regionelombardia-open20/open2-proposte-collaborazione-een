<?php
namespace lispa\amos\een\controllers;

use lispa\amos\admin\models\LoginForm;
use lispa\amos\core\user\User;
use lispa\amos\een\models\EenPartnershipProposal;
use lispa\amos\tag\models\Tag;
use yii\filters\auth\HttpBasicAuth;
use yii\helpers\Json;
use yii\rest\Controller;
use yii2fullcalendar\yii2fullcalendar;


class ApiController extends Controller{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        /*$behaviors['authenticator'] = [
            'class' => HttpBasicAuth::className(),
            'auth' => [$this, 'auth'],
            'optional' => [
                'auth',
            ],
        ];*/

        $behaviors['basicAuth'] =  [
            'class' => \yii\filters\auth\HttpBasicAuth::className(),
            'only' => ['get-een'],
            'auth' => function ($username, $password) {
                $user = User::find()->where(['username' => $username])->one();
                if ($user->validatePassword($password) && \Yii::$app->authManager->checkAccess($user->id, 'GET_EEN')) {
                    return $user;
                }
                return null;
            },
        ];

        return $behaviors;

    }



    public function actionAuth()
    {
        //basic auth CASE
        $username = $_SERVER['PHP_AUTH_USER'];
        $password = $_SERVER['PHP_AUTH_PW'];

        //credential send like arguments
        if(empty($username) && empty($password)){
            $request = \Yii::$app->request->get();
            $username = $request['username'];
            $password = $request['password'];
        }

        $LoginForm = new LoginForm();
        $LoginForm->usernameOrEmail = $username;
        $LoginForm->password = $password;

        if ($LoginForm->validate()) {
            $User = User::findByUsername($LoginForm->usernameOrEmail);
            if ($User && $User->validatePassword($LoginForm->password)) {
                //set for
                $LoginForm->login();
                //$User->refreshAccessToken();
                //pr("tutto ok");
                return true;
            }

        }else{
            //pr($LoginForm->getErrors(), "errore");
        }
        return false;
    }

    public function actionGetEen(){

        if(!$this->actionAuth()){
            echo Json::encode("Login failed!");
            return false;
        }

        if( ! \Yii::$app->user->can('EEN_ENABLE_READ_WS') ){
            echo Json::encode("User not allowed to retrieve data");
            return false;
        }

        $request = \Yii::$app->request->get();

        if(!isset($request['date'])){
//            echo Json::encode("Missing 'date' parameter in format d/m/Y");
            echo Json::encode("Missing 'date' parameter in format Y-m-d. (e.g. 2018-07-01)");
            return false;
        }

//        $df = \DateTime::createFromFormat('d/m/Y', $request['date']);
        $df = \DateTime::createFromFormat('Y-m-d', $request['date']);
	
	$df2 = clone $df;
	$df->modify( '-7 days' );

        $date_min = $df->format( 'Y-m-d 000:00:00' );

        $date_max = $df2->format( 'Y-m-d 000:00:00' );

        $q = EenPartnershipProposal::find()->andWhere(['BETWEEN', 'datum_update', $date_min, $date_max ]);

        $arr_final = array();

        foreach ($q->all() as $k => $EenPP){
            //titolo
            $arr_elto['titolo'] = $EenPP->content_title;
            //proposta
            $arr_elto['proposta'] = $EenPP->content_description;
            //id proposta
            $arr_elto['id proposta'] = $EenPP->reference_external;
            //tipo
            $arr_elto['tipo'] = $EenPP->getReferenceTypeLabel();
            //paese
            $arr_elto['paese'] = $EenPP->company_country_label;
            //presentazione
            $arr_elto['presentazione'] = \Yii::$app->getFormatter()->asDate($EenPP->datum_submit);
            //ultimo aggiornamento
            $arr_elto['ultimo aggiornamento'] = \Yii::$app->getFormatter()->asDate($EenPP->datum_update);
            //data scadenza
            $arr_elto['data scadenza'] = \Yii::$app->getFormatter()->asDate($EenPP->datum_deadline);

            //Stadio di sviluppo
            $arr_elto['Stadio di sviluppo'] = $EenPP->cooperation_stagedev_stage;
            //Commenti sullo stadio di sviluppo
            $arr_elto['Commenti sullo stadio di sviluppo'] = $EenPP->cooperation_stagedev_comment;
            //Stato Diritti proprietà (IPR)
            $arr_elto['Stato Diritti proprietà (IPR)'] = $EenPP->cooperation_ipr_status;
            //Commenti sullo stato dei diritti
            $arr_elto['Commenti sullo stato dei diritti'] = $EenPP->cooperation_ipr_comment;
            //Area di collaborazione
            $arr_elto['Area di collaborazione'] = $EenPP->cooperation_partner_area;
            //Collaborazione richiesta
            $arr_elto['Collaborazione richiesta'] = $EenPP->cooperation_partner_sought;

            //Tipo e Dimensione
            $arr_elto['Tipo e Dimensione'] = $EenPP->company_kind;
            //Fatturato
            $arr_elto['Fatturato'] = $EenPP->company_turnover;
            //Anno inizio attività
            $arr_elto['Anno inizio attività'] = $EenPP->company_since;
            //Esperienza transnazionale
            $arr_elto['Esperienza transnazionale'] = $EenPP->company_transnational;

            //Consorzio
            //$arr_elto['Consorzio'] = $EenPP->contact_consortium;
            //Contact Consortiumcountry Label
            //$arr_elto['Contact Consortiumcountry Label'] = $EenPP->contact_consortiumcountry_label . '('. $EenPP->contact_consortiumcountry_key.')';
            $arr_elto['Contact Consortiumcountry Label'] = 'Bulgaria (BG)';
            //Organizzazione
            //$arr_elto['Organizzazione'] = $EenPP->contact_organization;
            $arr_elto['Organizzazione'] = 'Finlombarda s.p.a.';
            //Email
            //$arr_elto['Email'] = $EenPP->contact_email;
            $arr_elto['Email'] = 'simpler2@finlombarda.it';
            //Telefono
            //$arr_elto['Telefono'] = $EenPP->contact_phone;
            $arr_elto['Telefono'] = '+ 39 0260744529';
            //Identificativo
            //$arr_elto['Identificativo'] = $EenPP->contact_partnerid;
            $arr_elto['Identificativo'] = 'IT00284';


            //retrieve the tags
            $tags_ids = $EenPP->tagValues;
            if(empty($tags_ids)){
                $tags_ids = array(-1);
            }
            $TagsRecords = Tag::find()->andWhere(['id' => $tags_ids])->all();

            //prepare the tag array with id and name
            foreach ($TagsRecords as $kt => $tag){
                $arr_elto_tags[] = array('id' => $tag['id'], 'nome' => $tag['nome']);
            }
            $arr_elto['tags'] = $arr_elto_tags;

            //retrieve and prepare the attachments array
            foreach ($EenPP->attachmentsForItemView as $ka => $att){
                $arr_elto_att[] = array('id' => $att['id'], 'nome' => $att->name.'.'.$att->type);
            }
            $arr_elto['attachments'] = $arr_elto_att;

            $arr_final[] = $arr_elto;

        }

        echo Json::encode($arr_final);
    }
}
