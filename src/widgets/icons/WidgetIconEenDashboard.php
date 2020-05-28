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

use open20\amos\core\icons\AmosIcons;
use open20\amos\core\widget\WidgetIcon;
use open20\amos\een\AmosEen;
use yii\helpers\ArrayHelper;
use open20\amos\core\widget\WidgetAbstract;


/**
 * Class WidgetIconEenDashboard
 *
 * @package open20\amos\een\widgets\icons
 */
class WidgetIconEenDashboard extends WidgetIcon
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->setLabel(AmosEen::tHtml('amoseen', '#plugin_partnership_proposal_een'));
        $this->setDescription(AmosEen::t('amoseen', 'Plugin per l\'accesso alle proposte di collaborazione EEN'));

        if (!empty(\Yii::$app->params['dashboardEngine']) && \Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
            $this->setIconFramework(AmosIcons::IC);
            $this->setIcon('een-world');
            $paramsClassSpan = [];
        } else {
            $this->setIcon('proposte-een');
        }

        $this->setUrl(['/een/een-partnership-proposal/index']);
        $this->setCode('EEN');
        $this->setModuleName('een');
        $this->setNamespace(__CLASS__);

        $this->setClassSpan(
            ArrayHelper::merge(
                $this->getClassSpan(),
                $paramsClassSpan
            )
        );
    }

}
