<?php

namespace open20\amos\een\models;

use open20\amos\attachments\behaviors\FileBehavior;
use open20\amos\comments\models\CommentInterface;
use open20\amos\core\interfaces\ModelLabelsInterface;
use open20\amos\een\events\EenExprOfInterestWorkflowEvent;
use open20\amos\een\AmosEen;
use open20\amos\een\i18n\grammar\EenExprOfInterestGrammar;
use open20\amos\workflow\behaviors\WorkflowLogFunctionsBehavior;
use raoul2000\workflow\base\SimpleWorkflowBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "een_expr_of_interest".
 */
class EenExprOfInterest extends \open20\amos\een\models\base\EenExprOfInterest implements CommentInterface, ModelLabelsInterface
{
    // Workflow ID
    const EEN_EXPR_OF_INTEREST_WORKFLOW = 'EenExpressionOfInterestWorkflow';

    // Workflow states IDS
    const EEN_EXPR_WORKFLOW_STATUS_SUSPENDED = 'EenExpressionOfInterestWorkflow/SUSPENDED';
    const EEN_EXPR_WORKFLOW_STATUS_TAKENOVER = 'EenExpressionOfInterestWorkflow/TAKENOVER';
    const EEN_EXPR_WORKFLOW_STATUS_CLOSED = 'EenExpressionOfInterestWorkflow/CLOSED';

    const EEN_SUB_STATUS_REQUEST_INFO = "request_info";
    const EEN_SUB_STATUS_EXPR_OF_INTEREST = "expr_of_interest";
    const EEN_SUB_STATUS_ACTIVITY_SUSPENDED = "activity_supended";
    const EEN_SUB_STATUS_TAKEN_OVER_EFFECTUTED = "taken_over_effectuated";
    const EEN_SUB_STATUS_FIRST_CONTACT = "first_contact";
    const EEN_SUB_STATUS_WAITING_INTEGRATION = "waiting_integration";
    const EEN_SUB_STATUS_EOI_SENDED = "eoi_sended";
    const EEN_SUB_STATUS_CONTACT_STARTED = "contact_started";
    const EEN_SUB_STATUS_NEGOTIATION_ONGOING = "negotiation_ongoin";
    const EEN_SUB_STATUS_AGREEIMENT_EFFECTUATED = "agreeiment_effettuated";
    const EEN_SUB_STATUS_NOT_INTERESTED_OTHER_PARTY = "not_interested_other_person";
    const EEN_SUB_STATUS_TRANSFERRED_CLOSED = "transferred_closed";
    const EEN_SUB_STATUS_USER_NOT_INTERESTED = "user_not_interested";
    const EEN_SUB_STATUS_REQUEST_INFO_CLOSED = "request_info_closed";

    public $otherOrganization;
    public $companyOrganizationName;

    /**
     */
    public function init()
    {
        parent::init();
        if ($this->isNewRecord) {
            $this->status = $this->getWorkflowSource()->getWorkflow(self::EEN_EXPR_OF_INTEREST_WORKFLOW)->getInitialStatusId();
        }
        $this->on('afterEnterStatus{' . self::EEN_EXPR_WORKFLOW_STATUS_SUSPENDED . '}', [new EenExprOfInterestWorkflowEvent(), 'afterEnterStatus'], $this);
        $this->on('afterEnterStatus{' . self::EEN_EXPR_WORKFLOW_STATUS_TAKENOVER . '}', [new EenExprOfInterestWorkflowEvent(), 'afterEnterStatus'], $this);
        $this->on('afterEnterStatus{' . self::EEN_EXPR_WORKFLOW_STATUS_CLOSED . '}', [new EenExprOfInterestWorkflowEvent(), 'afterEnterStatus'], $this);
    }

    /**
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'workflow' => [
                'class' => SimpleWorkflowBehavior::className(),
                'defaultWorkflowId' => self::EEN_EXPR_OF_INTEREST_WORKFLOW,
                'propagateErrorsToModel' => true
            ],
            'WorkflowLogFunctionsBehavior' => [
                'class' => WorkflowLogFunctionsBehavior::className(),
            ],
            'fileBehavior' => [
                'class' => FileBehavior::className()
            ],
        ]);
    }

    public function representingColumn()
    {
        return [
            //inserire il campo o i campi rappresentativi del modulo
        ];
    }

    public function attributeHints()
    {
        return [
        ];
    }

    /**
     * Returns the text hint for the specified attribute.
     * @param string $attribute the attribute name
     * @return string the attribute hint
     */
    public function getAttributeHint($attribute)
    {
        $hints = $this->attributeHints();
        return isset($hints[$attribute]) ? $hints[$attribute] : null;
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
        ]);
    }

    public function attributeLabels()
    {
        return
            ArrayHelper::merge(
                parent::attributeLabels(),
                [
                ]);
    }

    public static function getEditFields()
    {
        $labels = self::attributeLabels();

        return [
            [
                'slug' => 'user_id',
                'label' => $labels['user_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'een_partnership_proposal_id',
                'label' => $labels['een_partnership_proposal_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'area',
                'label' => $labels['area'],
                'type' => 'string'
            ],
            [
                'slug' => 'contact_een',
                'label' => $labels['contact_een'],
                'type' => 'integer'
            ],
            [
                'slug' => 'company_organization',
                'label' => $labels['company_organization'],
                'type' => 'string'
            ],
            [
                'slug' => 'sector',
                'label' => $labels['sector'],
                'type' => 'string'
            ],
            [
                'slug' => 'address',
                'label' => $labels['address'],
                'type' => 'string'
            ],
            [
                'slug' => 'city',
                'label' => $labels['city'],
                'type' => 'string'
            ],
            [
                'slug' => 'postal_code',
                'label' => $labels['postal_code'],
                'type' => 'string'
            ],
            [
                'slug' => 'web_site',
                'label' => $labels['web_site'],
                'type' => 'string'
            ],
            [
                'slug' => 'contact_person',
                'label' => $labels['contact_person'],
                'type' => 'string'
            ],
            [
                'slug' => 'phone',
                'label' => $labels['phone'],
                'type' => 'string'
            ],
            [
                'slug' => 'fax',
                'label' => $labels['fax'],
                'type' => 'string'
            ],
            [
                'slug' => 'email',
                'label' => $labels['email'],
                'type' => 'string'
            ],
            [
                'slug' => 'technology_interest',
                'label' => $labels['technology_interest'],
                'type' => 'string'
            ],
            [
                'slug' => 'organization_presentation',
                'label' => $labels['organization_presentation'],
                'type' => 'string'
            ],
            [
                'slug' => 'privacy',
                'label' => $labels['privacy'],
                'type' => 'integer'
            ],
            [
                'slug' => 'status',
                'label' => $labels['status'],
                'type' => 'string'
            ],
        ];
    }

    public function isCommentable()
    {
        if(\Yii::$app->user->can('STAFF_EEN')) {
            return true;
        }
        else {
            return  false;
        }
    }

    /**
     * @return array
     */
    public static function getSubstatus(){
        $areaTypes = [
             EenExprOfInterest::EEN_SUB_STATUS_REQUEST_INFO => AmosEen::t('amoseen', "Richiesta di informazioni fatta"),
             EenExprOfInterest::EEN_SUB_STATUS_EXPR_OF_INTEREST => AmosEen::t('amoseen', "Manifestazione di interesse EEN fatta"),
             EenExprOfInterest::EEN_SUB_STATUS_ACTIVITY_SUSPENDED  =>  AmosEen::t('amoseen', "Attività in sospeso"),

             EenExprOfInterest::EEN_SUB_STATUS_TAKEN_OVER_EFFECTUTED  => AmosEen::t('amoseen', "Presa in carico avvenuta"),
             EenExprOfInterest::EEN_SUB_STATUS_FIRST_CONTACT  => AmosEen::t('amoseen', "Primo contatto stabilito"),
             EenExprOfInterest::EEN_SUB_STATUS_WAITING_INTEGRATION  => AmosEen::t('amoseen', "In attesa di integrazioni"),
             EenExprOfInterest::EEN_SUB_STATUS_EOI_SENDED => AmosEen::t('amoseen', "EOI inoltrata"),
             EenExprOfInterest::EEN_SUB_STATUS_CONTACT_STARTED =>  AmosEen::t('amoseen', "Contatto avviato"),
             EenExprOfInterest::EEN_SUB_STATUS_NEGOTIATION_ONGOING => AmosEen::t('amoseen', "Negoziazione in corso"),
             EenExprOfInterest::EEN_SUB_STATUS_AGREEIMENT_EFFECTUATED => AmosEen::t('amoseen', "Accordo raggiunto"),
             EenExprOfInterest::EEN_SUB_STATUS_NOT_INTERESTED_OTHER_PARTY => AmosEen::t('amoseen', "Mancato interesse di controparte/ mancato accordo"),

             EenExprOfInterest::EEN_SUB_STATUS_TRANSFERRED_CLOSED  => AmosEen::t('amoseen', "Caso trasferito e chiuso"),
             EenExprOfInterest::EEN_SUB_STATUS_USER_NOT_INTERESTED => AmosEen::t('amoseen', "Non interessa più all’utente"),
             EenExprOfInterest::EEN_SUB_STATUS_REQUEST_INFO_CLOSED => AmosEen::t('amoseen', "Richiesta di informazioni chiusa")
        ];
        return $areaTypes;
    }

    public function getAvaiableSubStatus(){
        $status = $this->status;
        $sub_status = '';
        switch ($status){
            case EenExprOfInterest::EEN_EXPR_WORKFLOW_STATUS_SUSPENDED:
            case EenExprOfInterest::EEN_EXPR_WORKFLOW_STATUS_CLOSED:
            case EenExprOfInterest::EEN_EXPR_WORKFLOW_STATUS_TAKENOVER:
                $sub_status = [EenExprOfInterest::EEN_SUB_STATUS_ACTIVITY_SUSPENDED => EenExprOfInterest::getSubstatus()[EenExprOfInterest::EEN_SUB_STATUS_ACTIVITY_SUSPENDED]];
                if($this->is_request_more_info == 1){
                    $sub_status [EenExprOfInterest::EEN_SUB_STATUS_REQUEST_INFO] = EenExprOfInterest::getSubstatus()[EenExprOfInterest::EEN_SUB_STATUS_REQUEST_INFO];
                }
                else {
                    $sub_status [EenExprOfInterest::EEN_SUB_STATUS_EXPR_OF_INTEREST] = EenExprOfInterest::getSubstatus()[EenExprOfInterest::EEN_SUB_STATUS_EXPR_OF_INTEREST];
                }
                $sub_status = ArrayHelper::merge($sub_status, [
                        EenExprOfInterest::EEN_SUB_STATUS_TAKEN_OVER_EFFECTUTED => EenExprOfInterest::getSubstatus()[EenExprOfInterest::EEN_SUB_STATUS_TAKEN_OVER_EFFECTUTED],
                        EenExprOfInterest::EEN_SUB_STATUS_FIRST_CONTACT  => EenExprOfInterest::getSubstatus()[EenExprOfInterest::EEN_SUB_STATUS_FIRST_CONTACT ],
                        EenExprOfInterest::EEN_SUB_STATUS_WAITING_INTEGRATION  => EenExprOfInterest::getSubstatus()[EenExprOfInterest::EEN_SUB_STATUS_WAITING_INTEGRATION ],
                        EenExprOfInterest::EEN_SUB_STATUS_EOI_SENDED => EenExprOfInterest::getSubstatus()[EenExprOfInterest::EEN_SUB_STATUS_EOI_SENDED],
                        EenExprOfInterest::EEN_SUB_STATUS_CONTACT_STARTED => EenExprOfInterest::getSubstatus()[EenExprOfInterest::EEN_SUB_STATUS_CONTACT_STARTED],
                        EenExprOfInterest::EEN_SUB_STATUS_NEGOTIATION_ONGOING => EenExprOfInterest::getSubstatus()[EenExprOfInterest::EEN_SUB_STATUS_NEGOTIATION_ONGOING],
                        EenExprOfInterest::EEN_SUB_STATUS_AGREEIMENT_EFFECTUATED => EenExprOfInterest::getSubstatus()[EenExprOfInterest::EEN_SUB_STATUS_AGREEIMENT_EFFECTUATED],
                        EenExprOfInterest::EEN_SUB_STATUS_NOT_INTERESTED_OTHER_PARTY => EenExprOfInterest::getSubstatus()[EenExprOfInterest::EEN_SUB_STATUS_NOT_INTERESTED_OTHER_PARTY]]
                    );
                $sub_status = ArrayHelper::merge($sub_status, [
                        EenExprOfInterest::EEN_SUB_STATUS_TRANSFERRED_CLOSED => EenExprOfInterest::getSubstatus()[EenExprOfInterest::EEN_SUB_STATUS_TRANSFERRED_CLOSED],
                        EenExprOfInterest::EEN_SUB_STATUS_USER_NOT_INTERESTED => EenExprOfInterest::getSubstatus()[ EenExprOfInterest::EEN_SUB_STATUS_USER_NOT_INTERESTED],
                        EenExprOfInterest::EEN_SUB_STATUS_REQUEST_INFO_CLOSED => EenExprOfInterest::getSubstatus()[EenExprOfInterest::EEN_SUB_STATUS_REQUEST_INFO_CLOSED]
                    ]
                );
            break;

        }
        return $sub_status;
    }

    /**
     * @return bool
     */
    public static function isNumExprOfInterestExceeded(){
        $count = EenExprOfInterest::find()
            ->andWhere(['user_id' => \Yii::$app->user->id])
            ->andWhere(['!=','status', EenExprOfInterest::EEN_EXPR_WORKFLOW_STATUS_CLOSED ])->count();
        return $count >= 5;
    }

    /**
     * @return bool
     */
    public function isLoggedUserInCharge()
    {
        if ($this->eenStaff) {
            if ($this->eenStaff->user_id == \Yii::$app->user->id) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return EenExprOfInterestGrammar
     */
    public function getGrammar()
    {
        return new EenExprOfInterestGrammar();
    }

    public function getTitle(){
          return $this->eenPartnershipProposal->content_title;
    }

    /**
     * @return bool
     */
   public static function canCreateExpressionOfInterest($idPartnershipProposal){
		$user = \Yii::$app->getUser();
		$userProfileClass = \open20\amos\admin\AmosAdmin::instance()->createModel('UserProfile');
		$userProfile = $userProfileClass::findOne(['user_id' => $user->id]);
        $validatedUser = $userProfile->validato_almeno_una_volta;
		if($validatedUser){
        $count = EenExprOfInterest::find()
            ->andWhere(['user_id' => \Yii::$app->user->id])
            ->andWhere(['een_partnership_proposal_id' =>$idPartnershipProposal ])
            ->andWhere(['!=','status', EenExprOfInterest::EEN_EXPR_WORKFLOW_STATUS_CLOSED ])->count();
        if(EenExprOfInterest::isNumExprOfInterestExceeded() || $count > 0){
            return false;
        }
        return true;
		}
			return false;
    }

}
