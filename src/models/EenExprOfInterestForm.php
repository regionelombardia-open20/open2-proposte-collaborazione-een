<?php

namespace open20\amos\een\models;

use open20\amos\een\AmosEen;
use yii\helpers\ArrayHelper;


class EenExprOfInterestForm extends  \open20\amos\core\record\Record
{


    public $userprofile_id;
    public $name;
    public $email;
    public $phone;
    public $address;
    public $note;
    public $user_type;
    public $een_partnership_proposal_id;

    const CERCO_PARTNER = 'cerco un partner di progetto';
    const OFFRO_PRODOTTO = 'voglio offrire un prodotto';
    const CERCO_PRODOTTO = 'cerco un prodotto';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[ 'name', 'email','note','user_type'], 'required'],
            [['name', 'address','phone', 'fax', 'email'], 'string', 'max' => 255],
            [['userprofile_id', 'een_partnership_proposal_id',], 'integer'],
            [['note'], 'string',  'max' => 1200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => AmosEen::t('amoseen', 'Nome'),
            'email' => AmosEen::t('amoseen', 'Mail'),
            'address' => AmosEen::t('amoseen', 'Indirizzo'),
            'phone' => AmosEen::t('amoseen', 'Telefono'),
            'note' => AmosEen::t('amoseen', 'Note'),
            'user_type' => AmosEen::t('amoseen', 'Tipologia utenti interessati'),
        ];
    }

}
