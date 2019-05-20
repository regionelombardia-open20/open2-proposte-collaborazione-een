<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\een\models
 * @category   CategoryName
 */

namespace lispa\amos\een\models;

use lispa\amos\attachments\behaviors\FileBehavior;
use lispa\amos\attachments\models\File;
use lispa\amos\core\forms\editors\DateTime;
use lispa\amos\core\helpers\Html;
use lispa\amos\core\interfaces\ContentModelInterface;
use lispa\amos\core\interfaces\ViewModelInterface;
use lispa\amos\een\AmosEen;
use lispa\amos\een\i18n\grammar\EenGrammar;
use lispa\amos\een\widgets\icons\WidgetIconEenDashboard;
use lispa\amos\notificationmanager\behaviors\NotifyBehavior;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use lispa\amos\core\views\toolbars\StatsToolbarPanels;

/**
 * Class EenPartnershipProposal
 * This is the model class for table "een_partnership_proposal".
 *
 * @method \yii\db\ActiveQuery hasOneFile($attribute = 'file', $sort = 'id')
 * @method \yii\db\ActiveQuery hasMultipleFiles($attribute = 'file', $sort = 'id')
 *
 * @package lispa\amos\een\models
 */
class EenPartnershipProposal extends \lispa\amos\een\models\base\EenPartnershipProposal implements ContentModelInterface, ViewModelInterface
{
    // Workflow ID
    const EEN_WORKFLOW = 'EenWorkflow';
    
    // Workflow states IDS
    const EEN_WORKFLOW_STATUS_DRAFT = 'EenWorkflow/DRAFT';
    const EEN_WORKFLOW_STATUS_TOVALIDATE = 'EenWorkflow/TOVALIDATE';
    const EEN_WORKFLOW_STATUS_VALIDATED = 'EenWorkflow/VALIDATED';
    
    /**
     * @var File[] $attachments
     */
    private $attachments;
    
    /**
     * @var File[] $attachmentsForItemView
     */
    private $attachmentsForItemView;


    /**
     * Getter for $this->attachments;
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getAttachments()
    {
        if (empty($this->attachments)) {
            $query = $this->hasMultipleFiles('attachments');
            $query->multiple = false;
            $this->attachments = $query->one();
        }
        return $this->attachments;
    }
    
    /**
     * @param $attachments
     */
    public function setAttachments($attachments)
    {
        $this->attachments = $attachments;
    }
    
    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getAttachmentsForItemView()
    {
        
        if (empty($this->attachmentsForItemView)) {
            $query = $this->hasMultipleFiles('attachments');
            $query->multiple = false;
            $this->attachmentsForItemView = $query->all();
        }
        return $this->attachmentsForItemView;
    }
    
    /**
     * @param $attachments
     */
    public function setAttachmentsForItemView($attachments)
    {
        $this->attachmentsForItemView = $attachments;
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['attachments'], 'file', 'maxFiles' => 0],
        ]);
    }
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'NotifyBehavior' => [
                'class' => NotifyBehavior::className(),
                'conditions' => [],
            ],
            'fileBehavior' => [
                'class' => FileBehavior::className()
            ]
        ]);
    }
    
    /**
     * @inheritdoc
     */
    public function getTitle()
    {
        return $this->content_title;
    }

    /**
     * @inheritdoc
     */
    public function getShortDescription()
    {
        return $this->__shortText($this->content_summary, 100);
    }
    
    /**
     * @inheritdoc
     */
    public function getDescription($truncate)
    {
        $ret = $this->content_description;
        
        if ($truncate) {
            $ret = $this->__shortText($this->content_summary, 200);
        }
        return $ret;
    }
    
    /**
     * @inheritdoc
     */
    public function getGridViewColumns()
    {
        return [
            'immagine' => [
                'label' => AmosEen::t('amoseen', 'Immagine'),
                'format' => 'html',
                'value' => function ($model) {
                    $url = '/img/img_default.jpg';
                    if (!is_null($model->attachments)) {
                        $url = $model->attachments[0]->getUrl('original');
                    }
                    return Html::img($url, [
                        'class' => 'gridview-image'
                    ]);
                },
                'headerOptions' => [
                    'id' => AmosEen::t('amoseen', 'immagine'),
                ],
                'contentOptions' => [
                    'headers' => AmosEen::t('amoseen', 'immagine'),
                ]
            ],
            'titolo' => [
                'attribute' => 'content_title',
                'headerOptions' => [
                    'id' => AmosEen::t('amoseen', 'titolo'),
                ],
                'contentOptions' => [
                    'headers' => AmosEen::t('amoseen', 'titolo'),
                ]
            ],
            'data_pubblicazione' => [
                'attribute' => 'datum_submit',
                'format' => 'date',
                'headerOptions' => [
                    'id' => AmosEen::t('amoseen', 'data pubblicazione'),
                ],
                'contentOptions' => [
                    'headers' => AmosEen::t('amoseen', 'data pubblicazione'),
                ]
            ],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function getPublicatedFrom()
    {
        return $this->datum_submit;
    }
    
    /**
     * @inheritdoc
     */
    public function getPublicatedAt()
    {
        return null;
    }
    
    /**
     * @inheritdoc
     */
    public function getCategory()
    {
        return null;
    }
    
    /**
     * @inheritdoc
     */
    public function getPluginWidgetClassname()
    {
        return WidgetIconEenDashboard::className();
    }
    
    /**
     * @inheritdoc
     */
    public function getToValidateStatus()
    {
        return self::EEN_WORKFLOW_STATUS_TOVALIDATE;
    }
    
    /**
     * @inheritdoc
     */
    public function getValidatedStatus()
    {
        return self::EEN_WORKFLOW_STATUS_VALIDATED;
    }
    
    /**
     * @inheritdoc
     */
    public function getDraftStatus()
    {
        return self::EEN_WORKFLOW_STATUS_DRAFT;
    }
    
    /**
     * @inheritdoc
     */
    public function getValidatorRole()
    {
        return null;
    }
    
    /**
     * @inheritdoc
     */
    public function getGrammar()
    {
        return new EenGrammar();
    }
    
    /**
     * @inheritdoc
     */
    public function getViewUrl()
    {
        return "een/een-partnership-proposal/view";
    }
    
    /**
     * @inheritdoc
     */
    public function getFullViewUrl()
    {
        return Url::toRoute(["/" . $this->getViewUrl(), "id" => $this->id]);
    }

    public function getReferenceTypes(){
        return [
            "TR" => AmosEen::t('amoseen', '#technology_request'),
            "TO" => AmosEen::t('amoseen', '#technology_offer'),
            "BR" => AmosEen::t('amoseen', '#business_request'),
            "BO" => AmosEen::t('amoseen', '#business_offer'),
            "RDR" => AmosEen::t('amoseen', '#r&d_proposal'),
        ];
    }

    public function getReferenceTypeLabel(){
        $types = $this->getReferenceTypes();
        return (
            array_key_exists($this->reference_type, $types)
            ?
            $types[$this->reference_type]
            :
            "---"
        );
    }

    /**
     * @return array
     */
    public function getStatsToolbar($disableLink = false)
    {
        $panels = [];

        try {
            $filescount =  $this->getFileCount();
            $panels = ArrayHelper::merge($panels, StatsToolbarPanels::getDocumentsPanel($this, $filescount,$disableLink));
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getMessage(), Logger::LEVEL_ERROR);
        }
        return $panels;
    }

    /**
     * @return array
     */
    public function getCountryTypes(){
        $query = new \yii\db\Query();
        $query->select('company_country_label')
                ->from(EenPartnershipProposal::tableName())
                ->groupBy(['company_country_label'])
                ;
        $countries = ArrayHelper::map($query->all(),'company_country_label','company_country_label');
       return $countries;
    }

    /**
     * @return array list of statuses that for cwh is validated
     */
    public function getCwhValidationStatuses()
    {
        return [$this->getValidatedStatus()];
    }

    /**
     * @return bool
     */
    public function isExprOfInterestSended(){
        $exprOfInt =  EenExprOfInterest::find()
            ->andWhere(['user_id' => \Yii::$app->user->id])
            ->andWhere(['een_partnership_proposal_id' => $this->id])
//            ->andWhere(['is_request_more_info' => false])
            ->andWhere(['!=','status', EenExprOfInterest::EEN_EXPR_WORKFLOW_STATUS_CLOSED ])->one();
        return !empty($exprOfInt);
    }

    /**
     * @return bool
     */
    public function isRequestInfoSended(){
        $exprOfInt =  EenExprOfInterest::find()
            ->andWhere(['user_id' => \Yii::$app->user->id])
            ->andWhere(['een_partnership_proposal_id' => $this->id])
            ->andWhere(['is_request_more_info' => true])
            ->andWhere(['!=','status', EenExprOfInterest::EEN_EXPR_WORKFLOW_STATUS_CLOSED ])->one();
        return !empty($exprOfInt);
    }

    /**
     * @return bool
     */
    public function isArchived(){
       $deadLine =  new \DateTime($this->datum_deadline);
       $now = new \DateTime(date('Y-m-d', strtotime('-1 day')));
        return ($deadLine < $now);

    }
}
