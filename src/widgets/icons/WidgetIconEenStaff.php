<?php
namespace lispa\amos\een\widgets\icons;

use lispa\amos\core\widget\WidgetIcon;
use lispa\amos\een\AmosEen;
use Yii;
use yii\helpers\ArrayHelper;

class WidgetIconEenStaff extends WidgetIcon {

    public function init() {
        parent::init();

        $this->setLabel(AmosEen::t('amoseen' ,  'Staff Een'));
        $this->setDescription(AmosEen::t('amoseen' , 'Staff Een'));

        $this->setIcon('proposte-een');
        $this->setIconFramework('dash');


        $this->setUrl(Yii::$app->urlManager->createUrl(['/een/een-expr-of-interest/staff-een']));
        $this->setModuleName('een');
        $this->setNamespace(__CLASS__);
        $this->setClassSpan(ArrayHelper::merge($this->getClassSpan(), [
            'bk-backgroundIcon',
            'color-primary'
        ]));
    }

}