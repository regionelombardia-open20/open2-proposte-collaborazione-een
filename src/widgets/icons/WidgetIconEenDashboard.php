<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\een\widgets
 * @category   CategoryName
 */

namespace lispa\amos\een\widgets\icons;

use lispa\amos\core\widget\WidgetIcon;
use lispa\amos\een\AmosEen;

use yii\helpers\ArrayHelper;

/**
 * Class WidgetIconEenDashboard
 *
 * @package lispa\amos\een\widgets\icons
 */
class WidgetIconEenDashboard extends WidgetIcon {

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();

        $this->setLabel(AmosEen::tHtml('amoseen', 'Partnership Proposal EEN'));
        $this->setDescription(AmosEen::t('amoseen', 'Plugin per l\'accesso alle proposte di collaborazione EEN'));
        $this->setIcon('proposte-een');
        $this->enableDashboardModal();
        $this->setUrl(['/een/een-partnership-proposal/index']);
        $this->setCode('EEN');
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
