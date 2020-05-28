<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\een\widgets
 * @category   CategoryName
 */

namespace open20\amos\een\widgets\icons;

use open20\amos\core\widget\WidgetIcon;
use open20\amos\een\AmosEen;
use yii\helpers\ArrayHelper;

/**
 * Class WidgetIconEenDashboard
 *
 * @package open20\amos\een\widgets\icons
 */
class WidgetIconEenDashboardGeneral extends WidgetIcon
{

    /**
     * @inheritdoc
     */
    public function init()
    {
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
