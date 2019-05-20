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
class WidgetIconEenExprOfInterestReceived extends WidgetIcon {

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();

        $this->setLabel(AmosEen::tHtml('amoseen', 'Ricevute'));
        $this->setDescription(AmosEen::t('amoseen', 'Ricevute'));
        $this->setIcon('proposte-een');
        $this->setUrl(['/een/een-expr-of-interest/index-received']);
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
        
        $this->setBulletCount(
            $this->makeBulletCounter(Yii::$app->getUser()->id)
        );
    }

    /**
     * 
     * @param type $user_id
     */
    public function makeBulletCounter($user_id = null) {
            return 0;
        

        $search = new EenPartnershipProposalSearch();
        $notifier = Yii::$app->getModule('notify');
        $count = 0;
        if ($notifier) {
            $count = $notifier
                ->countNotRead(
                    $user_id, 
                    EenPartnershipProposal::className(), 
                    $search->buildQuery([], 'all')
                );
        }
    }
    
    /**
     * Aggiunge all'oggetto container tutti i widgets recuperati dal controller del modulo
     * 
     * @inheritdoc
     */
    public function getOptions() {
        return ArrayHelper::merge(
            parent::getOptions(), 
            ['children' => $this->getWidgetsIcon()]
        );
    }

    /**
     * 
     * @return type
     */
    public function getWidgetsIcon() {
        return AmosWidgets::find()
            ->andWhere(['child_of' => self::className()])
            ->all();
    }

}
