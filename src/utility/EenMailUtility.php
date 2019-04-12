<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\partnershipprofiles\utility
 * @category   CategoryName
 */

namespace lispa\amos\een\utility;

use lispa\amos\admin\models\UserProfile;
use lispa\amos\core\interfaces\ModelLabelsInterface;
use lispa\amos\core\migration\libs\common\MigrationCommon;
use lispa\amos\core\record\Record;
use lispa\amos\core\user\User;
use lispa\amos\core\utilities\Email;
use lispa\amos\cwh\query\CwhActiveQuery;
use lispa\amos\een\AmosEen;
use lispa\amos\een\controllers\EenExprOfInterestController;
use lispa\amos\een\models\EenExprOfInterest;
use lispa\amos\een\models\EenPartnershipProposal;
use lispa\amos\een\models\EenStaff;
use lispa\amos\notificationmanager\models\Notification;
use lispa\amos\notificationmanager\models\NotificationChannels;
use lispa\amos\notificationmanager\models\NotificationsRead;
use yii\base\Object;
use yii\db\Expression;
use yii\db\Query;
use yii\log\Logger;

/**
 * Class EenMailUtility
 * @package lispa\amos\een\utility
 */
class EenMailUtility extends Object
{
    /**
     * @var bool $enableSendMail If true enable the mail send directly from this class.
     */
    public $enableSendMail = false;
    
    /**
     * @param int $eenId
     */
    public function sendMails($eenId)
    {
        try {
            $queryUsers = new Query();
            $users = $queryUsers->from(UserProfile::tableName())->all();
            
            foreach ($users as $user) {
                $userId = $user['user_id'];
                
                $cwhModule = \Yii::$app->getModule('cwh');
                $query = Notification::find()
                    ->leftJoin(NotificationsRead::tableName(), ['notification.id' => new Expression(NotificationsRead::tableName() . '.notification_id'), NotificationsRead::tableName() . '.user_id' => $userId])
                    ->andWhere(['channels' => NotificationChannels::CHANNEL_MAIL])
                    ->andWhere([NotificationsRead::tableName() . '.user_id' => null]);
                
                if (isset($cwhModule)) {
                    $cwhActiveQuery = new CwhActiveQuery(EenPartnershipProposal::className(), [
                        'queryBase' => EenPartnershipProposal::find()->distinct(),
                        'userId' => $userId
                    ]);
                    $queryModel = $cwhActiveQuery->getQueryCwhOwnInterest();
                    $queryModel->select(EenPartnershipProposal::tableName() . '.*');
                    $modelDatas = $queryModel->all();
                    $modelIds = [];
                    foreach ($modelDatas as $modelData) {
                        $modelIds[] = $modelData->id;
                    }
                    
                    $eenIds = [];
                    if (in_array($eenId, $modelIds)) {
                        $eenIds = [$eenId];
                    }
                    
                    $andWhere = '(' . Notification::tableName() . ".class_name = '" . addslashes(EenPartnershipProposal::className()) . "' AND " . Notification::tableName() . ".content_id in ('" . implode(',', $eenIds) . "'))";
                    $query->andWhere($andWhere);
                }
                $query->orderBy('class_name');
                
                $result = $query->all();
                
                if ($this->enableSendMail) {
                    if (!empty($result)) {
                        $this->sendEmail($userId, $result);
                    }
                    foreach ($result as $notify) {
                        /** @var Notification $notify */
                        $this->notifyReadFlag($notify->id, $userId);
                    }
                }
            }
        } catch (\Exception $ex) {
            \Yii::getLogger()->log($ex->getMessage(), \yii\log\Logger::LEVEL_ERROR);
        }
    }
    
    /**
     * @param int $userId
     * @param $result
     * @return bool
     */
    public function sendEmail($userId, $result)
    {
        $from = '';
        if (isset(\Yii::$app->params['email-assistenza'])) {
            //use default platform email assistance
            $from = \Yii::$app->params['email-assistenza'];
        }
        $userProfile = UserProfile::findOne(['user_id' => $userId]);
        if (is_null($userProfile)) {
            return false;
        }
        $to = [$userProfile->user->email];
        $subject = 'Notifica Proposta EEN';
        $message = $this->renderEmail($result);
        
        // Send Email
        try {
            /** @var \lispa\amos\core\utilities\Email $email */
            $email = new Email();
            $email->sendMail($from, $to, $subject, $message);
        } catch (\Exception $ex) {
            \Yii::getLogger()->log($ex->getMessage(), Logger::LEVEL_ERROR);
        }
        
        return true;
    }
    
    /**
     * @param array $resultset
     * @return string
     */
    public function renderEmail(array $resultset)
    {
        $mail = '';
        $class_content = '';
        try {
            $mail .= $this->renderContentHeader($resultset);
            /** @var Notification $notify */
            foreach ($resultset as $notify) {
                /** @var EenPartnershipProposal $cls_name */
                $cls_name = $notify->class_name;
                MigrationCommon::printConsoleMessage($cls_name);
                /** @var EenPartnershipProposal $model */
                $model = $cls_name::find()->andWhere(['id' => $notify->content_id])->one();
                if ($model != null) {
                    if (strcmp($class_content, $notify->class_name)) {
                        $mail .= $this->renderContentTitle($model);
                        $class_content = $notify->class_name;
                    }
                    $mail .= $this->renderContent($model);
                }
            }
            $mail .= $this->renderContentFooter($resultset);
        } catch (\Exception $ex) {
            \Yii::getLogger()->log($ex->getMessage(), \yii\log\Logger::LEVEL_ERROR);
        }
        return $mail;
    }
    
    /**
     * @param array $resultset
     * @return string
     */
    private function renderContentHeader(array $resultset)
    {
        $controller = \Yii::$app->controller;
        $contents_number = count($resultset);
        $ris = $controller->renderPartial("@vendor/lispa/amos-proposte-collaborazione-een/src/views/email/content_header", [
            'contents_number' => $contents_number
        ]);
        return $ris;
    }
    
    /**
     * @param EenPartnershipProposal $model
     * @return string
     */
    private function renderContentTitle(ModelLabelsInterface $model)
    {
        $controller = \Yii::$app->controller;
        $ris = $controller->renderPartial("@vendor/lispa/amos-proposte-collaborazione-een/src/views/email/content_title", [
            'content' => $model->content_description,
        ]);
        return $ris;
    }
    
    /**
     * @param EenPartnershipProposal $model
     * @return string
     */
    private function renderContent(Record $model)
    {
        $controller = \Yii::$app->controller;
        $ris = $controller->renderPartial("@vendor/lispa/amos-proposte-collaborazione-een/src/views/email/content", [
            'model' => $model->get,
        ]);
        return $ris;
    }
    
    /**
     * @param array $resultset
     * @return string
     */
    private function renderContentFooter(array $resultset)
    {
        $controller = \Yii::$app->controller;
        $ris = $controller->renderPartial("@vendor/lispa/amos-proposte-collaborazione-een/src/views/email/content_footer");
        return $ris;
    }
    
    /**
     * @param int $notify_id
     * @param int $reader_id
     */
    protected function notifyReadFlag($notify_id, $reader_id)
    {
        try {
            $model = new NotificationsRead();
            $model->notification_id = $notify_id;
            $model->user_id = $reader_id;
            $model->save();
        } catch (\Exception $ex) {
            \Yii::getLogger()->log($ex->getMessage(), Logger::LEVEL_ERROR);
        }
    }


    /**
     * @param $modelEenExprOfInterest
     * @param int $request_info
     * @param string $path
     */
    public static function sendEmailExprOfInterest($modelEenExprOfInterest, $request_info = 0, $path = null){
        /**@var $modelEenExprOfInterest EenExprOfInterest */
        $controller = \Yii::$app->controller;
        $userDefault = null;
        $users = [];
        if(!empty($modelEenExprOfInterest->eenStaff)){
            $users[] = $modelEenExprOfInterest->eenStaff->user;
        }
        $profileDefault = EenStaff::getProfileStaffDefault();
        if($profileDefault){
            $users[] = $profileDefault->user;
        }

        if(!empty($modelEenExprOfInterest->user)){
            $users[] = $modelEenExprOfInterest->user;
        }
        if($request_info == 1) {
            $subject = AmosEen::t('amoseen', 'Richiesta di informazioni sui servizi della rete EEN (Enterprise Europe Network)');
        }
        else {
            $subject = AmosEen::t('amoseen', 'Manifestazione di interesse su proposta di collaborazione EEN {codice_een}', ['codice_een' => $modelEenExprOfInterest->eenPartnershipProposal->reference_external]);;
        }
        $users = array_unique($users);

        $message = $controller->renderPartial("@vendor/lispa/amos-proposte-collaborazione-een/src/views/email/content_expr_of_interest", [
            'model' => $modelEenExprOfInterest
        ]);

        if(empty($path)){
            $files = [];
        } else {
            $files = [$path];
        }

        foreach ($users as $user){
            EenMailUtility::sendEmailGeneral([$user->email], $user->profile, $subject, $message, $files);
        }

    }

    /**
     * @param $model EenExprOfInterest
     */
    public function sendEmailTransferEoi($model, $old, $path){
        $staffEen = $model->eenStaff;
        $oldStaff = $old->eenStaff;
        //email creator eoo
        $to = [$model->user->email];

        $profile = $model->user->userProfile;
        $titolo = $model->eenPartnershipProposal->content_title;
        $code = $model->eenPartnershipProposal->reference_external;

        if($staffEen) {
            // email new staff in charge
            $to []= $staffEen->user->email;
            if($oldStaff){
                //email old staff  in charge
                $to []= $oldStaff->user->email;
            }
            $orgName = '';
            $nomeCognomeStaff = $staffEen->user->userProfile->nomeCognome;
            $emailStaff = $staffEen->user->email;
            if(!empty($staffEen->user->userProfile->prevalentPartnership)){
                $orgName = "," . $staffEen->user->userProfile->prevalentPartnership->name;
            }
            if($model->is_request_more_info == 1){
                $subject = AmosEen::t('amoseen', "Trasferimento della richiesta di informazioni sui servizi della rete EEN (Enterprise Europe Network)");
                $message = AmosEen::t('amoseen',
                    "<p>Questa è una notifica automatica generata a seguito del trasferimento della tua manifestazione di interesse relativa alla tua richiesta di informazioni sui servizi della rete EEN (Enterprise Europe Network)  ad un altro centro / esperto della rete EEN.<br>
                                <br>L’esperto a cui è stato assegnato il tuo caso è: <strong>{nomeCognomeStaff}</strong> {orgName} ({emailStaff}).<br>
                                L’esperto a cui è stato trasferito il caso riceve questa notifica in copia e si metterà in contatto diretto con te per darti supporto nelle fasi successive. <br>
                                <br>In allegato a questa mail entrambi trovate una copia della manifestazione di interesse e l’informativa sulle modalità di trattamento dei dati che ci autorizza a compiere questa operazione.<br>
                                Cordiali saluti,<br>
                                Staff della Piattaforma Open Innovation
                              </p>", ['nomeCognomeStaff' => $nomeCognomeStaff, 'orgName' => $orgName, 'emailStaff' => $emailStaff, 'titolo' => $titolo, 'een_id' => $code]);
            } else {
                $subject = AmosEen::t('amoseen', "Trasferimento della manifestazione di interesse relativa alla proposta di collaborazione EEN {een_id} ad un altro esperto EEN", ['een_id' => $code]);
                $message = AmosEen::t('amoseen',
                    "<p>Questa è una notifica automatica generata a seguito del trasferimento della tua manifestazione di interesse relativa alla proposta di collaborazione EEN dal titolo <strong>{titolo} e codice {een_id}</strong> ad un altro centro / esperto della rete EEN.<br>
                                <br>L’esperto a cui è stato assegnato il tuo caso è: <strong>{nomeCognomeStaff}</strong> {orgName} ({emailStaff}).<br>
                                L’esperto a cui è stato trasferito il caso riceve questa notifica in copia e si metterà in contatto diretto con te per darti supporto nelle fasi successive. <br>
                                <br>In allegato a questa mail entrambi trovate una copia della manifestazione di interesse e l’informativa sulle modalità di trattamento dei dati che ci autorizza a compiere questa operazione.<br>
                                Cordiali saluti,<br>
                                Staff della Piattaforma Open Innovation
                              </p>", ['nomeCognomeStaff' => $nomeCognomeStaff, 'orgName' => $orgName, 'emailStaff' => $emailStaff, 'titolo' => $titolo, 'een_id' => $code]);
            }
            EenMailUtility::sendEmailGeneral($to, $profile, $subject, $message, [$path]);
        }
    }


    /**
     * @param $model EenExprOfInterest
     */
    public static function sendEmailWorkflowTakeOver($model){
        $staffEen = $model->eenStaff;
        //email creator eoi
        $to = [$model->user->email];

        $profile = $model->user->userProfile;
        $titolo = $model->eenPartnershipProposal->content_title;
        $code = $model->eenPartnershipProposal->reference_external;

        if($staffEen) {
            // email staff in charge
            $to []= $staffEen->user->email;
            $orgName = '';
            $nomeCognomeStaff = $staffEen->user->userProfile->nomeCognome;
            $emailStaff = $staffEen->user->email;
            if(!empty($staffEen->user->userProfile->prevalentPartnership)){
                $orgName = "," . $staffEen->user->userProfile->prevalentPartnership->name;
            }
            if($model->is_request_more_info == 0){
                $subject = AmosEen::t('amoseen', "Presa in carico della manifestazione di interesse relativa alla proposta di collaborazione EEN {een_id}", ['een_id' => $code]);
                $message = AmosEen::t('amoseen',
                    "<p>Questa è una notifica automatica della presa in carico della tua manifestazione di interesse relativa alla proposta di collaborazione EEN dal titolo <strong>{titolo}</strong> e codice <strong>{een_id}</strong> 
                                    da parte di {nomeCognomeStaff} {orgName} ({emailStaff})  che riceve questa comunicazione in copia e provvederà a contattarti.<br>
                                Cordiali saluti,<br>
                                Lo Staff della Piattaforma Open Innovation
                              </p>", ['nomeCognomeStaff' => $nomeCognomeStaff, 'orgName' => $orgName, 'emailStaff' => $emailStaff, 'titolo' => $titolo, 'een_id' => $code]);
            } else {
                $subject = AmosEen::t('amoseen', "Presa in carico della richiesta di informazioni sui servizi della rete EEN (Enterprise Europe Network)");
                $message = AmosEen::t('amoseen',
                    "<p>Questa è una notifica automatica della presa in carico della tua richiesta di informazioni sui servizi della rete EEN (Enterprise Europe Network) da parte dell’esperto individuato <strong>{nomeCognomeStaff}</strong> {orgName} ({emailStaff}).<br>
                                che riceve questa comunicazione in copia e provvederà a contattarti.<br>
                                  Cordiali saluti,<br>
                                Lo Staff della Piattaforma Open Innovation
                              </p>", ['nomeCognomeStaff' => $nomeCognomeStaff, 'orgName' => $orgName, 'emailStaff' => $emailStaff, 'titolo' => $titolo, 'een_id' => $code]);
            }
            EenMailUtility::sendEmailGeneral($to, $profile, $subject, $message);
        }
    }

    /**
     * @param $model EenExprOfInterest
     */
    public static function sendEmailWorkflowClosed($model){
        $staffEen = $model->eenStaff;
        //email creator eoi
        $to = [$model->user->email];

        $profile = $model->user->userProfile;
        $titolo = $model->eenPartnershipProposal->content_title;
        $code = $model->eenPartnershipProposal->reference_external;

        if($staffEen) {
            // email staff in charge
            $to []= $staffEen->user->email;
            $orgName = '';
            $nomeCognomeStaff = $staffEen->user->userProfile->nomeCognome;
            $emailStaff = $staffEen->user->email;
            if(!empty($staffEen->user->userProfile->prevalentPartnership)){
                $orgName = "," . $staffEen->user->userProfile->prevalentPartnership->name;
            }
            if($model->is_request_more_info == 0){
                $subject = AmosEen::t('amoseen', "Chiusura della manifestazione di interesse relativa alla proposta di collaborazione EEN {een_id}", ['een_id' => $code]);
                $message = AmosEen::t('amoseen',
                    "<p>Questa è una notifica automatica generata a seguito della chiusura a sistema della manifestazione di interesse relativa alla proposta di collaborazione EEN dal titolo <strong>{titolo}</strong> e codice <strong>{een_id}</strong> 
                                    da te creata.<br><br>
                                    Per ulteriori informazioni puoi contattare l’esperto EEN che ti è stato assegnato e riceve questo messaggio in copia: <strong>{nomeCognomeStaff} {orgName} ({emailStaff}).</strong>
                                <br>Cordiali saluti,<br>
                                Staff della Piattaforma Open Innovation
                              </p>", ['nomeCognomeStaff' => $nomeCognomeStaff, 'orgName' => $orgName, 'emailStaff' => $emailStaff, 'titolo' => $titolo, 'een_id' => $code]);
            } else {
                $subject = AmosEen::t('amoseen', "Chiusura della richiesta di informazioni sui servizi della rete EEN");
                $message = AmosEen::t('amoseen',
                    "<p>Questa è una notifica automatica generata a seguito della chiusura a sistema della tua richiesta di informazioni sui servizi della rete EEN (Enterprise Europe Network).
                                  <br><br>
                                  L’esperto che ha gestito la tua richiesta, a cui ti puoi rivolgere per ulteriori informazioni è <strong>{nomeCognomeStaff} {orgName} ({emailStaff})</strong> <br>
                                 Cordiali saluti,<br>
                                Staff della Piattaforma Open Innovation
                              </p>", ['nomeCognomeStaff' => $nomeCognomeStaff, 'orgName' => $orgName, 'emailStaff' => $emailStaff, 'titolo' => $titolo, 'een_id' => $code]);
            }
            EenMailUtility::sendEmailGeneral($to, $profile, $subject, $message);
        }
    }

    /**
     * @param $model EenExprOfInterest
     */
    public static function sendEmailNotInterested($model){
        $staffEen = $model->eenStaff;
        //email creator eoi
        $to = [$model->user->email];

        $profile = $model->user->userProfile;
        $titolo = $model->eenPartnershipProposal->content_title;
        $code = $model->eenPartnershipProposal->reference_external;

        if($staffEen) {
            // email staff in charge
            $to []= $staffEen->user->email;
            $orgName = '';
            $nomeCognomeStaff = $staffEen->user->userProfile->nomeCognome;
            $emailStaff = $staffEen->user->email;
            if(!empty($staffEen->user->userProfile->prevalentPartnership)){
                $orgName = "," . $staffEen->user->userProfile->prevalentPartnership->name;
            }
            if($model->is_request_more_info == 0){
                $subject = AmosEen::t('amoseen', "Cancellazione della manifestazione di interesse relativa alla proposta di collaborazione EEN {een_id}", ['een_id' => $code]);
                $message = AmosEen::t('amoseen',
                    "<p>Questa è una notifica automatica di cancellazione della manifestazione di interesse relativa alla proposta di collaborazione EEN dal titolo <strong>{titolo}</strong> e codice <strong>{een_id}</strong> 
                                    da te creata.<br><br>
                                    Per ulteriori informazioni puoi contattare l’esperto EEN che ti è stato assegnato e riceve questo messaggio in copia: <strong>{nomeCognomeStaff} {orgName} ({emailStaff})</strong>
                                <br>Cordiali saluti,<br>
                                Staff della Piattaforma Open Innovation
                              </p>", ['nomeCognomeStaff' => $nomeCognomeStaff, 'orgName' => $orgName, 'emailStaff' => $emailStaff, 'titolo' => $titolo, 'een_id' => $code]);
            } else {
                $subject = AmosEen::t('amoseen', "Chiusura della richiesta di informazioni sui servizi della rete EEN");
                $message = AmosEen::t('amoseen',
                    "<p>Questa è una notifica automatica di cancellazione della richiesta di informazioni relativa alla proposta di collaborazione EEN dal titolo <strong>{titolo}</strong> e codice <strong>{een_id}</strong> 
                                    da te creata.<br><br>
                                    Per ulteriori informazioni puoi contattare l’esperto EEN che ti è stato assegnato e riceve questo messaggio in copia: <strong>{nomeCognomeStaff} {orgName} ({emailStaff})</strong>
                                <br>Cordiali saluti,<br>
                                Staff della Piattaforma Open Innovation
                              </p>", ['nomeCognomeStaff' => $nomeCognomeStaff, 'orgName' => $orgName, 'emailStaff' => $emailStaff, 'titolo' => $titolo, 'een_id' => $code]);
            }
            EenMailUtility::sendEmailGeneral($to, $profile, $subject, $message);
        }
    }

    /**
     * @param $model EenExprOfInterest
     */
    public static function sendEmailWorkflowSuspended($model){
        $staffEen = $model->eenStaff;
        $to = [$model->user->email];

        $profile = $model->user->userProfile;
        $titolo = $model->eenPartnershipProposal->content_title;
        $code = $model->eenPartnershipProposal->reference_external;

        if($staffEen) {
            $to []= $staffEen->user->email;
            $orgName = '';
            $nomeCognomeStaff = $staffEen->user->userProfile->nomeCognome;
            $emailStaff = $staffEen->user->email;
            if(!empty($staffEen->user->userProfile->prevalentPartnership)){
                $orgName = "," . $staffEen->user->userProfile->prevalentPartnership->name;
            }
            if($model->is_request_more_info == 0){
                $subject = AmosEen::t('amoseen', "La tua manifestazione di interesse {een_id} è in stato “sospeso”", ['een_id' => $code]);
                $message = AmosEen::t('amoseen',
                    "<p>Lo stato di avanzamento della tua manifestazione di interesse per la proposta di collaborazione EEN <strong>{titolo}</strong> e codice <strong>{een_id}</strong>  è ora in stato SOSPESO
                                    <br><br>
                                    Per ulteriori informazioni contatta l’esperto EEN che ti è stato assegnato e riceve questo messaggio in copia: <strong>{nomeCognomeStaff} {orgName} ({emailStaff})</strong>  che riceve questa comunicazione in copia e provvederà a contattarti.<br>
                                <br>Ti ricordiamo che trascorsi 15 giorni da questa notifica la manifestazione di interesse potrà essere cancellata senza bisogno di ulteriore comunicazione. <br>
                                Cordiali saluti,<br>
                                Staff della Piattaforma Open Innovation
                              </p>", ['nomeCognomeStaff' => $nomeCognomeStaff, 'orgName' => $orgName, 'emailStaff' => $emailStaff, 'titolo' => $titolo, 'een_id' => $code]);
            } else {
                $subject = AmosEen::t('amoseen', "La tua richiesta di informazione {een_id} è in stato “sospeso”", ['een_id' => $code]);
                $message = AmosEen::t('amoseen',
                    "<p>Lo stato di avanzamento della tua richiesta di informazione per la proposta di collaborazione EEN <strong>{titolo}</strong> e codice <strong>{een_id}</strong>  è ora in stato SOSPESO
                                    <br><br>
                                    Per ulteriori informazioni contatta l’esperto EEN che ti è stato assegnato e riceve questo messaggio in copia: <strong>{nomeCognomeStaff} {orgName} ({emailStaff})</strong>  che riceve questa comunicazione in copia e provvederà a contattarti.<br>
                                <br>Ti ricordiamo che trascorsi 15 giorni da questa notifica la manifestazione di interesse potrà essere cancellata senza bisogno di ulteriore comunicazione. <br>
                                Cordiali saluti,<br>
                                Staff della Piattaforma Open Innovation
                              </p>", ['nomeCognomeStaff' => $nomeCognomeStaff, 'orgName' => $orgName, 'emailStaff' => $emailStaff, 'titolo' => $titolo, 'een_id' => $code]);
            }
            EenMailUtility::sendEmailGeneral($to, $profile, $subject, $message);
        }
    }


    /**
     * @param $to
     * @param $profile
     * @param $subject
     * @param $message
     * @param array $files
     * @return bool
     */
    public static function sendEmailGeneral($to, $profile, $subject, $message, $files = []){
        try {
            $from = '';
            if (isset(\Yii::$app->params['email-assistenza'])) {
                //use default platform email assistance
                $from = \Yii::$app->params['email-assistenza'];
            }

            /** @var \lispa\amos\core\utilities\Email $email */
            $email = new Email();
            $email->sendMail($from, $to, $subject, $message, $files);
        } catch (\Exception $ex) {
            pr($ex->getMessage());
            \Yii::getLogger()->log($ex->getMessage(), Logger::LEVEL_ERROR);
        }
        return true;
    }


    /**
     * @param $model EenStaff
     */
    public static function sendEmailChangeStaffDefault($model){
        /**@var $modelEenStaff */
        $controller = \Yii::$app->controller;
        $userDefault = null;

        $subject = AmosEen::t('amoseen', '#change_staff_default_subject');
        $message = $controller->renderPartial("@vendor/lispa/amos-proposte-collaborazione-een/src/views/email/content_change_staff_default");

        EenMailUtility::sendEmailGeneral([$model->user->email], $model->user->profile, $subject, $message);

    }

    /**
     * @param $model EenStaff
     */
    public static function sendEmailNewStaffMember($model){
        /**@var $modelEenStaff */
        $controller = \Yii::$app->controller;
        $userDefault = null;

        $subject = AmosEen::t('amoseen', '#new_staff_member');
        $message = AmosEen::t('amoseen',
            "<p>Sei stato aggiunto come membro dello Staff EEN</p>");

        EenMailUtility::sendEmailGeneral([$model->user->email], $model->user->profile, $subject, $message);

    }

    }
