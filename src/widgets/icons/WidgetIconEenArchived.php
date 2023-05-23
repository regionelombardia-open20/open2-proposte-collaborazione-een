<?php

namespace open20\amos\een\widgets\icons;

use open20\amos\core\widget\WidgetIcon;

use open20\amos\dashboard\models\AmosWidgets;

use open20\amos\een\AmosEen;
use open20\amos\een\models\EenPartnershipProposal;
use open20\amos\een\models\search\EenPartnershipProposalSearch;

use Yii;
use yii\helpers\ArrayHelper;

class WidgetIconEenArchived extends WidgetIcon
{

    /**
     * 
     */
    public function init()
    {
        parent::init();

        $this->setLabel(AmosEen::tHtml('amoseen', 'Archived'));
        $this->setDescription(AmosEen::t('amoseen', 'Archived Partnership Proposal'));

        $this->setIcon('proposte-een');
        $this->setCode('EEN');
        $this->setUrl(['/een/een-partnership-proposal/archived']);
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

        $search = new EenPartnershipProposalSearch();
//        $this->setBulletCount(
//            $this->makeBulletCounter(
//                Yii::$app->getUser()->getId(),
//                EenPartnershipProposal::className(),
//                $search->buildQuery([], 'archived')
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
