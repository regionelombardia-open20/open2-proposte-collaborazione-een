<?php

namespace lispa\amos\een\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "een_expr_of_interest_history".
 */
class EenExprOfInterestHistory extends \lispa\amos\een\models\base\EenExprOfInterestHistory
{
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
                'slug' => 'een_expr_of_interest_id',
                'label' => $labels['een_expr_of_interest_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'start_status',
                'label' => $labels['start_status'],
                'type' => 'string'
            ],
            [
                'slug' => 'end_status',
                'label' => $labels['end_status'],
                'type' => 'string'
            ],
            [
                'slug' => 'sub_status',
                'label' => $labels['sub_status'],
                'type' => 'integer'
            ],
        ];
    }
}
