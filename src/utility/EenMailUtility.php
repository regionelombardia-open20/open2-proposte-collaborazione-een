<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\partnershipprofiles\utility
 * @category   CategoryName
 */

namespace open20\amos\een\utility;

use open20\amos\admin\models\UserProfile;
use open20\amos\core\interfaces\ModelLabelsInterface;
use open20\amos\core\migration\libs\common\MigrationCommon;
use open20\amos\core\record\Record;
use open20\amos\core\user\User;
use open20\amos\core\utilities\Email;
use open20\amos\cwh\query\CwhActiveQuery;
use open20\amos\een\AmosEen;
use open20\amos\een\controllers\EenExprOfInterestController;
use open20\amos\een\models\EenExprOfInterest;
use open20\amos\een\models\EenNetworkNode;
use open20\amos\een\models\EenPartnershipProposal;
use open20\amos\een\models\EenStaff;
use open20\amos\een\models\ProposalForm;
use open20\amos\notificationmanager\models\Notification;
use open20\amos\notificationmanager\models\NotificationChannels;
use open20\amos\notificationmanager\models\NotificationsRead;
use yii\base\BaseObject;
use yii\db\Expression;
use yii\db\Query;
use yii\log\Logger;

/**
 * Class EenMailUtility
 * @package open20\amos\een\utility
 */
class EenMailUtility extends BaseObject
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
            \Yii::getLogger()->log($ex->getTraceAsString(), \yii\log\Logger::LEVEL_ERROR);
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
            /** @var \open20\amos\core\utilities\Email $email */
            $email = new Email();
            $email->sendMail($from, $to, $subject, $message);
        } catch (\Exception $ex) {
            \Yii::getLogger()->log($ex->getTraceAsString(), Logger::LEVEL_ERROR);
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
            \Yii::getLogger()->log($ex->getTraceAsString(), \yii\log\Logger::LEVEL_ERROR);
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
        $ris = $controller->renderPartial("@vendor/open20/amos-proposte-collaborazione-een/src/views/email/content_header", [
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
        $ris = $controller->renderPartial("@vendor/open20/amos-proposte-collaborazione-een/src/views/email/content_title", [
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
        $ris = $controller->renderPartial("@vendor/open20/amos-proposte-collaborazione-een/src/views/email/content", [
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
        $ris = $controller->renderPartial("@vendor/open20/amos-proposte-collaborazione-een/src/views/email/content_footer");
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
            \Yii::getLogger()->log($ex->getTraceAsString(), Logger::LEVEL_ERROR);
        }
    }


    /**
     * @param $modelEenExprOfInterest
     * @param int $request_info
     * @param string $path
     */
    public static function sendEmailExprOfInterest($modelEenExprOfInterest, $request_info = 0, $path = null)
    {
        /**@var $modelEenExprOfInterest EenExprOfInterest */
        $controller = \Yii::$app->controller;
        $userDefault = null;
        $users = [];
        if (!empty($modelEenExprOfInterest->eenStaff)) {
            $users[] = $modelEenExprOfInterest->eenStaff->user;
        }
        $profileDefault = EenStaff::getProfileStaffDefault();
        if ($profileDefault) {
            $users[] = $profileDefault->user;
        }

        if (!empty($modelEenExprOfInterest->user)) {
            $users[] = $modelEenExprOfInterest->user;
        }
        if ($request_info == 1) {
            $subject = AmosEen::t('amoseen', 'Richiesta di informazioni sui servizi della rete EEN (Enterprise Europe Network)');
        } else {
            $subject = AmosEen::t('amoseen', 'Manifestazione di interesse su proposta di collaborazione EEN {codice_een}', ['codice_een' => $modelEenExprOfInterest->eenPartnershipProposal->reference_external]);;
        }
        $users = array_unique($users);

        $message = $controller->renderPartial("@vendor/open20/amos-proposte-collaborazione-een/src/views/email/content_expr_of_interest", [
            'model' => $modelEenExprOfInterest
        ]);

        if (empty($path)) {
            $files = [];
        } else {
            $files = [$path];
        }


        foreach ($users as $user) {
            EenMailUtility::sendEmailGeneral([$user->email], $user->profile, $subject, $message, $files);
        }

    }

    /**
     * @param $model EenExprOfInterest
     */
    public function sendEmailTransferEoi($model, $old, $path)
    {
        $staffEen = $model->eenStaff;
        $oldStaff = $old->eenStaff;
        //email creator eoo
        $to = [$model->user->email];

        $profile = $model->user->userProfile;
        $titolo = $model->eenPartnershipProposal->content_title;
        $code = $model->eenPartnershipProposal->reference_external;

        if ($staffEen) {
            // email new staff in charge
            $to [] = $staffEen->user->email;
            if ($oldStaff) {
                //email old staff  in charge
                $to [] = $oldStaff->user->email;
            }
            $orgName = '';
            $nomeCognomeStaff = $staffEen->user->userProfile->nomeCognome;
            $emailStaff = $staffEen->user->email;
            if (!empty($staffEen->user->userProfile->prevalentPartnership)) {
                $orgName = "," . $staffEen->user->userProfile->prevalentPartnership->name;
            }
            if ($model->is_request_more_info == 1) {
                $subject = AmosEen::t('amoseen', "Trasferimento della richiesta di informazioni sui servizi della rete EEN (Enterprise Europe Network)");
                $message = AmosEen::t('amoseen',
                    "<p>Questa è una notifica automatica generata a seguito del trasferimento della tua manifestazione di interesse relativa alla tua richiesta di informazioni sui servizi della rete EEN (Enterprise Europe Network)  ad un altro centro / esperto della rete EEN.<br>
                                <br>L’esperto a cui è stato assegnato il tuo caso è: <strong>{nomeCognomeStaff}</strong> {orgName} ({emailStaff}).<br>
                                L’esperto a cui è stato trasferito il caso riceve questa notifica in copia e si metterà in contatto diretto con te per darti supporto nelle fasi successive. <br>
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
            $message .= "<br><p>" . AmosEen::t('amoseen', "Richiedente: {nomeCognome} <br>Proposta di collaborazione {titolo} {codice}", [
                    'titolo' => $titolo, 'codice' => $code, 'nomeCognome' => $model->user->userProfile->nomeCognome

                ])
                . "</p>";

            EenMailUtility::sendEmailGeneral($to, $profile, $subject, $message, [$path]);
        }
    }


    /**
     * @param $model EenExprOfInterest
     */
    public static function sendEmailWorkflowTakeOver($model)
    {
        $staffEen = $model->eenStaff;
        //email creator eoi
        $to = [$model->user->email];

        $profile = $model->user->userProfile;
        $titolo = $model->eenPartnershipProposal->content_title;
        $code = $model->eenPartnershipProposal->reference_external;

        if ($staffEen) {
            // email staff in charge
            $to [] = $staffEen->user->email;
            $orgName = '';
            $nomeCognomeStaff = $staffEen->user->userProfile->nomeCognome;
            $emailStaff = $staffEen->user->email;
            if (!empty($staffEen->user->userProfile->prevalentPartnership)) {
                $orgName = "," . $staffEen->user->userProfile->prevalentPartnership->name;
            }
            if ($model->is_request_more_info == 0) {
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

            $message .= "<br><p>" . AmosEen::t('amoseen', "Richiedente: {nomeCognome} <br>Proposta di collaborazione {titolo} {codice}", [
                    'titolo' => $titolo, 'codice' => $code, 'nomeCognome' => $model->user->userProfile->nomeCognome

                ])
                . "</p>";
            EenMailUtility::sendEmailGeneral($to, $profile, $subject, $message);
        }
    }

    /**
     * @param $model EenExprOfInterest
     */
    public static function sendEmailWorkflowClosed($model)
    {
        $staffEen = $model->eenStaff;
        //email creator eoi
        $to = [$model->user->email];

        $profile = $model->user->userProfile;
        $titolo = $model->eenPartnershipProposal->content_title;
        $code = $model->eenPartnershipProposal->reference_external;

        if ($staffEen) {
            // email staff in charge
            $to [] = $staffEen->user->email;
            $orgName = '';
            $nomeCognomeStaff = $staffEen->user->userProfile->nomeCognome;
            $emailStaff = $staffEen->user->email;
            if (!empty($staffEen->user->userProfile->prevalentPartnership)) {
                $orgName = "," . $staffEen->user->userProfile->prevalentPartnership->name;
            }
            if ($model->is_request_more_info == 0) {
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
            $message .= "<br><p>" . AmosEen::t('amoseen', "Richiedente: {nomeCognome} <br>Proposta di collaborazione {titolo} {codice}", [
                    'titolo' => $titolo, 'codice' => $code, 'nomeCognome' => $model->user->userProfile->nomeCognome

                ])
                . "</p>";
            EenMailUtility::sendEmailGeneral($to, $profile, $subject, $message);
        }
    }

    /**
     * @param $model EenExprOfInterest
     */
    public static function sendEmailNotInterested($model)
    {
        $staffEen = $model->eenStaff;
        //email creator eoi
        $to = [$model->user->email];

        $profile = $model->user->userProfile;
        $titolo = $model->eenPartnershipProposal->content_title;
        $code = $model->eenPartnershipProposal->reference_external;

        if ($staffEen) {
            // email staff in charge
            $to [] = $staffEen->user->email;
            $orgName = '';
            $nomeCognomeStaff = $staffEen->user->userProfile->nomeCognome;
            $emailStaff = $staffEen->user->email;
            if (!empty($staffEen->user->userProfile->prevalentPartnership)) {
                $orgName = "," . $staffEen->user->userProfile->prevalentPartnership->name;
            }
            if ($model->is_request_more_info == 0) {
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
            $message .= "<br><p>" . AmosEen::t('amoseen', "Richiedente: {nomeCognome} <br>Proposta di collaborazione {titolo} {codice}", [
                    'titolo' => $titolo, 'codice' => $code, 'nomeCognome' => $model->user->userProfile->nomeCognome

                ])
                . "</p>";
            EenMailUtility::sendEmailGeneral($to, $profile, $subject, $message);
        }
    }

    /**
     * @param $model EenExprOfInterest
     */
    public static function sendEmailWorkflowSuspended($model)
    {
        $staffEen = $model->eenStaff;
        $to = [$model->user->email];

        $profile = $model->user->userProfile;
        $titolo = $model->eenPartnershipProposal->content_title;
        $code = $model->eenPartnershipProposal->reference_external;

        if ($staffEen) {
            $to [] = $staffEen->user->email;
            $orgName = '';
            $nomeCognomeStaff = $staffEen->user->userProfile->nomeCognome;
            $emailStaff = $staffEen->user->email;
            if (!empty($staffEen->user->userProfile->prevalentPartnership)) {
                $orgName = "," . $staffEen->user->userProfile->prevalentPartnership->name;
            }
            if ($model->is_request_more_info == 0) {
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
            $message .= "<br><p>" . AmosEen::t('amoseen', "Richiedente: {nomeCognome} <br>Proposta di collaborazione {titolo} {codice}", [
                    'titolo' => $titolo, 'codice' => $code, 'nomeCognome' => $model->user->userProfile->nomeCognome

                ])
                . "</p>";
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
    public static function sendEmailGeneral($to, $profile, $subject, $message, $files = [])
    {
        try {
            $from = '';
            if (isset(\Yii::$app->params['email-assistenza'])) {
                //use default platform email assistance
                $from = \Yii::$app->params['email-assistenza'];
            }

            /** @var \open20\amos\core\utilities\Email $email */
            $email = new Email();
            $email->sendMail($from, $to, $subject, $message, $files);
        } catch (\Exception $ex) {
//            pr($ex->getMessage());
            \Yii::getLogger()->log($ex->getTraceAsString(), Logger::LEVEL_ERROR);
        }
        return true;
    }


    /**
     * @param $model EenStaff
     */
    public static function sendEmailChangeStaffDefault($model)
    {
        /**@var $modelEenStaff */
        $controller = \Yii::$app->controller;
        $userDefault = null;

        $subject = AmosEen::t('amoseen', '#change_staff_default_subject');
        $message = $controller->renderPartial("@vendor/open20/amos-proposte-collaborazione-een/src/views/email/content_change_staff_default");

        EenMailUtility::sendEmailGeneral([$model->user->email], $model->user->profile, $subject, $message);

    }

    /**
     * @param $model EenStaff
     */
    public static function sendEmailNewStaffMember($model)
    {
        /**@var $modelEenStaff */
        $controller = \Yii::$app->controller;
        $userDefault = null;

        $subject = AmosEen::t('amoseen', '#new_staff_member');
        $message = AmosEen::t('amoseen',
            "<p>Sei stato aggiunto come membro dello Staff EEN</p>");

        EenMailUtility::sendEmailGeneral([$model->user->email], $model->user->profile, $subject, $message);

    }


    /**
     * @param $model ProposalForm
     */
    public static function sendEmailProposalRequest($model, $user){
        $staff = EenUtility::getStaffDefault();
        $subject = AmosEen::t('amoseen', "Richiesta pubblicazione di una proposta di collaborazione EEN");
        $network = EenNetworkNode::findOne($model->een_network_node_id);
        $networkName =  !empty($network) ? $network->name : '';


        $message = "<p>".AmosEen::t('amoseen', "L'utente <strong>{nomeCognome}</strong> richiede la pubblicazione della seguente proposta Een:", ['nomeCognome' => $user->userProfile->nomeCognome])."</p>";
        $message .= "<p><strong>Email</strong>: ".$user->email."</p>";
        $message .= "<p><strong>".$model->getLabels()['een_network_node_id']."</strong>: ".$networkName."</p>";
        $message .= "<p><strong>".$model->getLabels()['text']."</strong>: ".$model->text."</p>";
        $message .= "<p><strong>".$model->getLabels()['interestedTo']."</strong>: ".$model->getScelte()[$model->interestedTo]."</p>";

        if($staff){
            $userStaff = $staff->user;
            $to = [$userStaff->email];
            EenMailUtility::sendEmailGeneral($to, $userStaff->userProfile, $subject, $message);
            return true;
        }
        return false;

    }

}
