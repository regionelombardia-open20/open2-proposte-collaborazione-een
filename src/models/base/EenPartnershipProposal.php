<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\een\models\base
 * @category   CategoryName
 */

namespace lispa\amos\een\models\base;

use lispa\amos\een\AmosEen;
use lispa\amos\notificationmanager\record\NotifyAuditRecord;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class EenPartnershipProposal
 *
 * This is the base-model class for table "een_partnership_proposal".
 *
 * @property integer $id
 * @property string $company_certifications_list
 * @property string $company_country_key
 * @property string $company_country_label
 * @property string $company_experience
 * @property string $company_kind
 * @property string $company_languages_list
 * @property string $company_since
 * @property string $company_transnational
 * @property string $company_turnover
 * @property string $contact_consortium
 * @property string $contact_consortiumcountry_key
 * @property string $contact_consortiumcountry_label
 * @property string $contact_email
 * @property string $contact_fullname
 * @property string $contact_organization
 * @property string $contact_organizationcountry_key
 * @property string $contact_organizationcountry_label
 * @property string $contact_partnerid
 * @property string $contact_phone
 * @property string $content_description
 * @property string $content_summary
 * @property string $content_title
 * @property string $cooperation_exploitation_list
 * @property string $cooperation_ipr_comment
 * @property string $cooperation_ipr_status
 * @property string $cooperation_partner_area
 * @property string $cooperation_partner_sought
 * @property string $cooperation_partner_task
 * @property string $cooperation_plusvalue
 * @property string $cooperation_stagedev_comment
 * @property string $cooperation_stagedev_stage
 * @property string $datum_deadline
 * @property string $datum_submit
 * @property string $datum_update
 * @property string $reference_external
 * @property string $reference_internal
 * @property string $reference_type
 * @property string $tags_not_found
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @package lispa\amos\een\models\base
 */
class EenPartnershipProposal extends NotifyAuditRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'een_partnership_proposal';
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'company_certifications_list',
                    'company_experience',
                    'company_languages_list',
                    'contact_email',
                    'contact_fullname',
                    'contact_organization',
                    'content_description',
                    'content_summary',
                    'content_title',
                    'cooperation_exploitation_list',
                    'cooperation_ipr_comment',
                    'cooperation_ipr_status',
                    'cooperation_partner_area',
                    'cooperation_partner_sought',
                    'cooperation_partner_task',
                    'cooperation_plusvalue',
                    'cooperation_stagedev_comment',
                    'cooperation_stagedev_stage',
                    'reference_external',
                    'reference_internal',
                    'tags_not_found',
                ],
                'string'
            ],
            [['datum_deadline', 'datum_submit', 'datum_update', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['created_by', 'updated_by', 'deleted_by'], 'integer'],
            [
                [
                    'company_country_key',
                    'contact_consortiumcountry_key',
                    'contact_organizationcountry_key',
                    'reference_type'
                ],
                'string',
                'max' => 5
            ],
            [
                [
                    'company_country_label',
                    'company_kind',
                    'contact_consortium',
                    'contact_consortiumcountry_label',
                    'contact_organizationcountry_label',
                    'contact_partnerid',
                    'contact_phone'
                ],
                'string',
                'max' => 255
            ],
            [['company_since', 'company_transnational', 'company_turnover'], 'string', 'max' => 10],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'id' => AmosEen::t('amoseen', 'ID'),
            'company_certifications_list' => AmosEen::t('amoseen', 'Company Certifications List'),
            'company_country_key' => AmosEen::t('amoseen', 'Company Country Key'),
            'company_country_label' => AmosEen::t('amoseen', 'Company Country Label'),
            'company_experience' => AmosEen::t('amoseen', 'Company Experience'),
            'company_kind' => AmosEen::t('amoseen', 'Company Kind'),
            'company_languages_list' => AmosEen::t('amoseen', 'Company Languages List'),
            'company_since' => AmosEen::t('amoseen', 'Company Since'),
            'company_transnational' => AmosEen::t('amoseen', 'Company Transnational'),
            'company_turnover' => AmosEen::t('amoseen', 'Company Turnover'),
            'contact_consortium' => AmosEen::t('amoseen', 'Contact Consortium'),
            'contact_consortiumcountry_key' => AmosEen::t('amoseen', 'Contact Consortiumcountry Key'),
            'contact_consortiumcountry_label' => AmosEen::t('amoseen', 'Country'),
            'contact_email' => AmosEen::t('amoseen', 'Contact Email'),
            'contact_fullname' => AmosEen::t('amoseen', 'Contact Fullname'),
            'contact_organization' => AmosEen::t('amoseen', 'Contact Organization'),
            'contact_organizationcountry_key' => AmosEen::t('amoseen', 'Contact Organizationcountry Key'),
            'contact_organizationcountry_label' => AmosEen::t('amoseen', 'Contact Organizationcountry Label'),
            'contact_partnerid' => AmosEen::t('amoseen', 'Contact Partnerid'),
            'contact_phone' => AmosEen::t('amoseen', 'Contact Phone'),
            'content_description' => AmosEen::t('amoseen', 'Content Description'),
            'content_summary' => AmosEen::t('amoseen', 'Content Summary'),
            'content_title' => AmosEen::t('amoseen', 'Content Title'),
            'cooperation_exploitation_list' => AmosEen::t('amoseen', 'Cooperation Exploitation List'),
            'cooperation_ipr_comment' => AmosEen::t('amoseen', 'Cooperation Ipr Comment'),
            'cooperation_ipr_status' => AmosEen::t('amoseen', 'Cooperation Ipr Status'),
            'cooperation_partner_area' => AmosEen::t('amoseen', 'Cooperation Partner Area'),
            'cooperation_partner_sought' => AmosEen::t('amoseen', 'Cooperation Partner Sought'),
            'cooperation_partner_task' => AmosEen::t('amoseen', 'Cooperation Partner Task'),
            'cooperation_plusvalue' => AmosEen::t('amoseen', 'Cooperation Plusvalue'),
            'cooperation_stagedev_comment' => AmosEen::t('amoseen', 'Cooperation Stagedev Comment'),
            'cooperation_stagedev_stage' => AmosEen::t('amoseen', 'Cooperation Stagedev Stage'),
            'datum_deadline' => AmosEen::t('amoseen', 'Datum Deadline'),
            'datum_submit' => AmosEen::t('amoseen', 'Datum Submit'),
            'datum_update' => AmosEen::t('amoseen', 'Datum Update'),
            'reference_external' => AmosEen::t('amoseen', 'Reference External'),
            'reference_internal' => AmosEen::t('amoseen', 'Reference Internal'),
            'reference_type' => AmosEen::t('amoseen', 'Reference Type'),
            'created_at' => AmosEen::t('amoseen', 'Creato il'),
            'updated_at' => AmosEen::t('amoseen', 'Aggiornato il'),
            'deleted_at' => AmosEen::t('amoseen', 'Cancellato il'),
            'created_by' => AmosEen::t('amoseen', 'Creato da'),
            'updated_by' => AmosEen::t('amoseen', 'Aggiornato da'),
            'deleted_by' => AmosEen::t('amoseen', 'Cancellato da'),
        ]);
    }
}
