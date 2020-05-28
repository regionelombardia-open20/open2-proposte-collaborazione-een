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

use open20\amos\dashboard\models\AmosWidgets;

use open20\amos\een\AmosEen;
use open20\amos\een\models\EenPartnershipProposal;
use open20\amos\een\models\search\EenPartnershipProposalSearch;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class WidgetIconEen
 * @package open20\amos\news\widgets\icons
 */
class WidgetIconEen extends WidgetIcon
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->setLabel(AmosEen::tHtml('amoseen', 'Proposal Een of my interest'));
        $this->setDescription(AmosEen::t('amoseen', 'Show Een of my interest'));
        $this->setIcon('proposte-een');
        $this->setUrl(['/een/een-partnership-proposal/own-interest']);
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

//        $search = new EenPartnershipProposalSearch();
//        $this->setBulletCount(
//            $this->makeBulletCounter(
//                Yii::$app->getUser()->getId(),
//                EenPartnershipProposal::className(),
//                $search->buildQuery([], 'own-interest')
//            )
//        );
    }

    /**
     * Aggiunge all'oggetto container tutti i widgets recuperati dal controller del modulo
     * 
     * @inheritdoc
     */
    public function getOptions()
    {
        return ArrayHelper::merge(
            parent::getOptions(),
            ['children' => $this->getWidgetsIcon()]
        );
    }

    /**
     * 
     * @return type
     */
    public function getWidgetsIcon()
    {
        return AmosWidgets::find()
            ->andWhere(['child_of' => self::className()])
            ->all();
    }

}
