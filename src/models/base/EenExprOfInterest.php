<?php

namespace open20\amos\een\models\base;

use open20\amos\een\AmosEen;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the base-model class for table "een_expr_of_interest".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $een_partnership_proposal_id
 * @property string $een_network_node_id
 * @property integer $een_staff
 * @property integer $organization_id
 * @property string $company_organization
 * @property string $org_name_inserted_manually
 * @property string $sector
 * @property string $address
 * @property string $city
 * @property string $postal_code
 * @property string $web_site
 * @property string $contact_person
 * @property string $phone
 * @property string $fax
 * @property string $email
 * @property string $technology_interest
 * @property string $information_request
 * @property string $organization_presentation
 * @property integer $privacy
 * @property integer is_request_more_info
 * @property string $status
 * @property string $sub_status
 * @property string $note
 * @property string $know_een
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property string $created_by
 * @property string $updated_by
 * @property string $deleted_by
 *
 * @property \open20\amos\een\models\EenPartnershipProposal $eenPartnershipProposal
 * @property \open20\amos\een\models\eenExprOfInterestHistory $eenExprOfInterestHistory
 * @property \open20\amos\core\user\User $user
 */
class EenExprOfInterest extends \open20\amos\core\record\Record
{


    const SCENARIO_STAFF_EEN = 'scenario_staff_een';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'een_expr_of_interest';
    }

    public function scenarios()
    {
        return ArrayHelper::merge(parent::scenarios(), [
            self::SCENARIO_STAFF_EEN => ['sub_status']
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'een_partnership_proposal_id', 'know_een'], 'required'],
            [['user_id', 'een_partnership_proposal_id', 'een_staff_id', 'privacy', 'een_network_node_id', 'org_name_inserted_manually','know_een', 'organization_id'], 'integer'],
            [['is_request_more_info', 'created_at', 'updated_at', 'deleted_at', 'created_by', 'updated_by', 'deleted_by'], 'safe'],
            [['company_organization', 'sector', 'address', 'city', 'postal_code', 'web_site', 'contact_person', 'phone', 'fax', 'email', 'status',"sub_status"], 'string', 'max' => 255],
            [['technology_interest', 'organization_presentation', 'information_request', 'note'], 'string',  'max' => 600],
            [['een_partnership_proposal_id'], 'exist', 'skipOnError' => true, 'targetClass' => \open20\amos\een\models\EenPartnershipProposal::className(), 'targetAttribute' => ['een_partnership_proposal_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => \open20\amos\core\user\User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['een_network_node_id'], 'required'],
            [['privacy'], 'required', 'requiredValue' => 1,  'message' => AmosEen::t('amoseen',"E' necessario prendere visione dell'informativa sul trattamento dati."),
//                'when' => function($attribute){
//                    if($this->is_request_more_info == 0) {
//                        return ($this->een_network_node_id == 1);
//                    }
//                    else return false;
//                },
//                'whenClient' => "function(attribute, value){
//                        if($('#request-more-info-id').val() == '0') {
//                            if($('#area-id').val() == '1'){
//                                return true;
//                            }else {
//                                return false;
//                            }
//                        }
//                        return false;
//                }"
            ],
            [['company_organization', 'address', 'city', 'postal_code', 'web_site', 'contact_person', 'phone', 'email', 'sector','technology_interest', 'organization_presentation', 'information_request'], 'required',
                'when' => function($attribute){
                    if($this->is_request_more_info == 0) {
                        return ($this->een_network_node_id == 1);
                    }
                    else return false;
                },
                'whenClient' => "function(attribute, value){
                     if($('#request-more-info-id').val() == '0') {
                            if($('#area-id').val() == '1'){
                                return true;
                            }else {
                                return false;
                            }
                     }
                     return false;
                }"
            ],
//            [['sub_status'], 'required', 'on' => self::SCENARIO_STAFF_EEN]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => AmosEen::t('amoseen', 'id'),
            'user_id' => AmosEen::t('amoseen', 'User'),
            'een_partnership_proposal_id' => AmosEen::t('amoseen', 'Een Partnership Proposal'),
            'een_network_node_id' => AmosEen::t('amoseen', '#region'),
            'een_staff_id' => AmosEen::t('amoseen', '#member_staff_een'),
            'company_organization' => AmosEen::t('amoseen', 'Company organization'),
            'sector' => AmosEen::t('amoseen', 'Sectors/Activities'),
            'address' => AmosEen::t('amoseen', 'Address'),
            'city' => AmosEen::t('amoseen', 'City'),
            'postal_code' => AmosEen::t('amoseen', 'Postal code'),
            'web_site' => AmosEen::t('amoseen', 'Web site'),
            'contact_person' => AmosEen::t('amoseen', 'Contact Person'),
            'phone' => AmosEen::t('amoseen', 'Phone'),
            'fax' => AmosEen::t('amoseen', 'Fax'),
            'email' => AmosEen::t('amoseen', 'Email'),
            'technology_interest' => AmosEen::t('amoseen', 'Technology interest'),
            'organization_presentation' => AmosEen::t('amoseen', 'Organization presentation'),
            'privacy' => AmosEen::t('amoseen', 'Privacy'),
            'status' => AmosEen::t('amoseen', 'Status'),
            'sub_status' => AmosEen::t('amoseen', 'Sub-status'),
            'know_een' =>  AmosEen::t('amoseen', '#know_een'),
            'created_at' => AmosEen::t('amoseen', 'Created at'),
            'updated_at' => AmosEen::t('amoseen', 'Updated at'),
            'deleted_at' => AmosEen::t('amoseen', 'Deleted at'),
            'created_by' => AmosEen::t('amoseen', 'Created by'),
            'updated_by' => AmosEen::t('amoseen', 'Updated at'),
            'deleted_by' => AmosEen::t('amoseen', 'Deleted at'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEenPartnershipProposal()
    {
        return $this->hasOne(\open20\amos\een\models\EenPartnershipProposal::className(), ['id' => 'een_partnership_proposal_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEenNetworkNode()
    {
        return $this->hasOne(\open20\amos\een\models\EenNetworkNode::className(), ['id' => 'een_network_node_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEenStaff()
    {
        return $this->hasOne(\open20\amos\een\models\EenStaff::className(), ['id' => 'een_staff_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\open20\amos\core\user\User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEenExprOfInterestHistory()
    {
        return $this->hasMany(\open20\amos\een\models\EenExprOfInterestHistory::className(), ['een_expr_of_interest_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrganization()
    {
        return $this->hasOne(\openinnovation\organizations\models\Organizations::className(), ['id' => 'organization_id']);
    }
}
