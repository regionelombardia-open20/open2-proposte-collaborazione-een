<?php 


namespace lispa\amos\een\widgets\icons;

use lispa\amos\core\widget\WidgetIcon;
use lispa\amos\dashboard\models\AmosWidgets;
use lispa\amos\een\AmosEen;
use lispa\amos\een\models\EenPartnershipProposal;
use lispa\amos\een\models\search\EenPartnershipProposalSearch;
use Yii;
use yii\helpers\ArrayHelper;
class WidgetIconEenArchived extends WidgetIcon {

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


    public function init() {
        parent::init();

        $this->setLabel(AmosEen::tHtml('amoseen' , 'Archived'));
        $this->setDescription(AmosEen::t('amoseen', 'Archived Partnership Proposal'));

        $this->setIcon('proposte-een');
        $this->setCode('EEN');
        $this->setUrl(['/een/een-partnership-proposal/archived']);
        $this->setModuleName('een');
        $this->setNamespace(__CLASS__);
        $this->setClassSpan(ArrayHelper::merge($this->getClassSpan(), [
            'bk-backgroundIcon',
            'color-primary'
        ]));

        $search = new EenPartnershipProposalSearch();
        $notifier = Yii::$app->getModule('notify');
        $count = 0;
        if ($notifier) {
            $count = $notifier->countNotRead(Yii::$app->getUser()->id, EenPartnershipProposal::className(), $search->buildQuery([], 'archived'));
        }
        $this->setBulletCount($count);

    }

}
