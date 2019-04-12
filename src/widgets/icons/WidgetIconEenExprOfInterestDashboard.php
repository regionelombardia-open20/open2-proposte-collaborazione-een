<?php
namespace lispa\amos\een\widgets\icons;

use lispa\amos\core\widget\WidgetIcon;
use lispa\amos\een\AmosEen;
use Yii;
use yii\helpers\ArrayHelper;

class WidgetIconEenExprOfInterestDashboard extends WidgetIcon {

    public function init() {
        parent::init();

        $this->setLabel(AmosEen::t('amoseen' , '#expression_of_interest_een_plural'));
        $this->setDescription(AmosEen::t('amoseen' , '#expression_of_interest_een_plural'));

        $this->setIcon('proposte-een');
        $this->setIconFramework('dash');


        $this->setUrl(Yii::$app->urlManager->createUrl(['/een/een-expr-of-interest']));
        $this->setModuleName('een');
        $this->setNamespace(__CLASS__);
        $this->setClassSpan(ArrayHelper::merge($this->getClassSpan(), [
            'bk-backgroundIcon',
            'color-primary'
        ]));
    }

    public function getOptions() {
        $options = parent::getOptions();

        //aggiunge all'oggetto container tutti i widgets recuperati dal controller del modulo
        return ArrayHelper::merge($options, ["children" => $this->getWidgetsIcon()]);
    }

    /**
    * Recupera i widget figli da far visualizzare nella dashboard secondaria
    * @return [lispa\amos\core\widget\WidgetIcon] Array con i widget della dashboard
    */
    public function getWidgetsIcon() {
        $widgets = [];

        $widget = \lispa\amos\dashboard\models\AmosWidgets::find()->andWhere(['module' => 'partecipanti'])->andWhere(['type' => 'ICON'])->andWhere(['!=', 'child_of', NULL])->all();

        foreach ($widget as $Widget) {
        $className = (strpos($Widget['classname'], '\\') === 0)? $Widget['classname'] : '\\' . $Widget['classname'];
        $widgetChild = new $className;
        if($widgetChild->isVisible()){
            $widgets[] = $widgetChild->getOptions();
        }
    }
    return $widgets;
    }

}