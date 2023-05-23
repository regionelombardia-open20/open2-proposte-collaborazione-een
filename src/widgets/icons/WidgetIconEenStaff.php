<?php

namespace open20\amos\een\widgets\icons;

use open20\amos\core\widget\WidgetIcon;
use open20\amos\een\AmosEen;
use Yii;
use yii\helpers\ArrayHelper;

class WidgetIconEenStaff extends WidgetIcon
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->setLabel(AmosEen::t('amoseen', 'Staff Een'));
        $this->setDescription(AmosEen::t('amoseen', 'Staff Een'));
        $this->setIcon('proposte-een');
        $this->setIconFramework('dash');
        $this->setUrl(Yii::$app->urlManager->createUrl(['/een/een-expr-of-interest/staff-een']));
        $this->setModuleName('een');
        $this->setNamespace(__CLASS__);

        $this->setClassSpan(
            ArrayHelper::merge(
                $this->getClassSpan(),
                [
                    'bk-backgroundIcon',
                    'color-primary'
                ]
            )
        );
    }

}
