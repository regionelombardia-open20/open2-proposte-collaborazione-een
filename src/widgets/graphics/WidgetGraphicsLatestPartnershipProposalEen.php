<?php

namespace open20\amos\een\widgets\graphics;

use open20\amos\core\widget\WidgetGraphic;

use open20\amos\een\AmosEen;

use open20\amos\een\models\EenPartnershipProposal;
use open20\amos\een\models\search\EenPartnershipProposalSearch;
use Yii;
use yii\helpers\ArrayHelper;
use open20\amos\notificationmanager\base\NotifyWidgetDoNothing;

class WidgetGraphicsLatestPartnershipProposalEen extends WidgetGraphic {

  /**
   * 
   */
  public function init() {
    parent::init();

    $this->setLabel(\Yii::t('amoseen', 'Proposte dal mondo'));
    $this->setDescription(Yii::t('amoseen', 'In collaborazione con Enterprise Europe Network'));
  }

  /**
   * rendering of the view ultime_discussioni
   *
   * @return string
   */
  public function getHtml() {
    $modelSearch = new EenPartnershipProposalSearch();
    $modelSearch->setNotifier(new NotifyWidgetDoNothing());
    $listaPartenership = $modelSearch->latestPartenershipProposalSearch($_GET, AmosEen::MAX_LAST_PARTNERSHIP_ON_DASHBOARD);

    $viewToRender = 'latest_partenership_profiles';


    return $this->render($viewToRender, [
        'listaPartnership' => $listaPartenership,
        'widget' => $this,
        'toRefreshSectionId' => 'widgetGraphicLatestThreads'
    ]);
  }

}
