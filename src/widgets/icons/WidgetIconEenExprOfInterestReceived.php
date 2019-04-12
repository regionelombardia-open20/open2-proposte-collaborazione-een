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
use lispa\amos\dashboard\models\AmosWidgets;
use lispa\amos\een\AmosEen;
use lispa\amos\een\models\EenPartnershipProposal;
use lispa\amos\een\models\search\EenPartnershipProposalSearch;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class WidgetIconEen
 * @package lispa\amos\news\widgets\icons
 */
class WidgetIconEenExprOfInterestReceived extends WidgetIcon
{
    /**
     * @inheritdoc
     */
    public function getOptions()
    {
        $options = parent::getOptions();
        
        // Aggiunge all'oggetto container tutti i widgets recuperati dal controller del modulo
        return ArrayHelper::merge($options, ["children" => $this->getWidgetsIcon()]);
    }
    
    public function getWidgetsIcon()
    {
        return AmosWidgets::find()
            ->andWhere([
                'child_of' => self::className()
            ])->all();
    }
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        
        $this->setLabel(AmosEen::tHtml('amoseen', 'Ricevute'));
        $this->setDescription(AmosEen::t('amoseen', 'Ricevute'));
        $this->setIcon('proposte-een');
        $this->setUrl(['/een/een-expr-of-interest/index-received']);
        $this->setCode('EEN');
        
        $this->setModuleName('een');
        $this->setNamespace(__CLASS__);
        
        $search = new EenPartnershipProposalSearch();
        $notifier = Yii::$app->getModule('notify');
        $count = 0;
//        if ($notifier) {
//            $count = $notifier->countNotRead(Yii::$app->getUser()->id, EenPartnershipProposal::className(), $search->buildQuery([], 'all'));
//        }
//        $this->setBulletCount($count);
//
        $this->setClassSpan(ArrayHelper::merge($this->getClassSpan(), [
            'bk-backgroundIcon',
            'color-primary'
        ]));
    }
}
