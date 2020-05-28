<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\widgets\graphics\views
 * @category   CategoryName
 */
use open20\amos\partnershipprofiles\Module;
use open20\amos\core\forms\WidgetGraphicsActions;
use open20\amos\core\helpers\Html;
use yii\data\ActiveDataProvider;
use yii\web\View;
use yii\widgets\Pjax;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\forms\ItemAndCardHeaderWidget;
use open20\amos\admin\widgets\UserCardWidget;
use open20\amos\core\forms\PublishedByWidget;

/**
 * @var View $this
 * @var ActiveDataProvider $communitiesList
 * @var \open20\amos\partnershipprofiles\widgets\graphics\WidgetGraphicsLatestPartnershipProfiles $widget
 * @var string $toRefreshSectionId
 */
\open20\amos\partnershipprofiles\assets\PartnershipProfilesAsset::register($this);
$modulePartenershipProfiles = \Yii::$app->getModule(Module::getModuleName());
?>
<div class="box-widget-header">
    <div class="box-widget-wrapper">
        <h2 class="box-widget-title">
            <?= AmosIcons::show('proposte-een', ['class' => 'am-2'],'dash') ?>
            <?= Module::tHtml('amoseen', 'Ultime Proposte dal mondo') ?>
        </h2>
    </div>

    <?php

        $textReadAll = Module::t('amoseen', '#showAll').AmosIcons::show('chevron-right');
        $linkReadAll = ['/een/een-partnership-proposal/own-interest'];
    ?>
    <div class="read-all"><?= Html::a($textReadAll, $linkReadAll, ['class' => '', 'title' => Yii::t('amoseen', 'In collaborazione con Enterprise Europe Network')]); ?></div>
</div>
<div class="box-widget box-widget-column latest-partnership-profiles">
    <section>
        <?php Pjax::begin(['id' => $toRefreshSectionId]); ?>
        <?php if (count($listaPartnership->getModels()) == 0): ?>
            <div class="list-items list-empty">
                <h3><?= Module::tHtml('amoseen', 'Nessuna Proposta dal mondo'); ?></h3></div>
        <?php endif; ?>
        <div class="list-items">
            <?php
            foreach ($listaPartnership->getModels() as $partnership):
                /** @var \open20\amos\een\models\EenPartnershipProposal $partnership */
                ?>
                <div class="widget-listbox-option" role="option">
                    <article class="wrap-item-box wrap-item-box-row">
                        <div class="box-widget-info-top">
                            <?=
                            PublishedByWidget::widget([
                                'model' => $partnership,
                                'layout' => '{publisher}'
                            ])
                            ?>
                            <?php /*echo \open20\amos\notificationmanager\forms\NewsWidget::widget([model' => $partnership]);*/ ?>
                        </div>
                        <div class="container-text">
                            <h2 class="box-widget-subtitle">
                                <?php
                                if (strlen($partnership->content_title) > 200) {
                                    $stringCut = substr($partnership->content_title, 0, 200);
                                    $stringCut = substr($stringCut, 0, strrpos($stringCut, ' ')).'... ';
                                } else {
                                    $stringCut = $partnership->content_title;
                                }
                                ?>

                                <?=
                                Html::a($stringCut,
                                    ['../een/een-partnership-proposal/view', 'id' => $partnership->id]);
                                ?>
                            </h2>

                            <div class="box-widget-text">

                                <?php
                                $stringNoTags = $partnership->content_description;
                                //remove table from editor
                                //$stringNoTags = preg_replace('/<table(.*?)>(.*?)<\/table>/s', '', $stringNoTags);
                                $stringNoTags = preg_replace('/<table(.*$)/s', '', $stringNoTags);
                                // remove iframe from editor
                                //$stringNoTags = preg_replace('/<iframe(.*?)>(.*?)<\/iframe>/s', '', $stringNoTags);
                                $stringNoTags = preg_replace('/<iframe(.*$)/s', '', $stringNoTags);
                                // remove images from editor
                                //$stringNoTags = preg_replace('/<img(.*?)\/>/s', '', $stringNoTags);
                                $stringNoTags = preg_replace('/<p><img(.*$)/s', '', $stringNoTags);
                                // remove empty paragraph
                                $stringNoTags = preg_replace('/<p><\/p>/s', '', $stringNoTags);
                                // remove &nbsp; space
                                $stringNoTags = str_replace('&nbsp;', '', $stringNoTags);
                                if (strlen($stringNoTags) > 120) {
                                    $stringCut = substr(strip_tags($stringNoTags), 0, 120);
                                    echo substr($stringCut, 0, strrpos($stringCut, ' ')).'... ';
                                } else {
                                    echo $stringNoTags;
                                }
                                ?>
                            </div>

                            <div class="box-widget-info-bottom">
                                <?php
                                $module                    = \Yii::$app->getModule('een');
                                $moduleCwh                 = \Yii::$app->getModule('cwh');
                                $communityConfigurationsId = null;
                                if (isset($moduleCwh) && !empty($moduleCwh->getCwhScope())) {
                                    $scope = $moduleCwh->getCwhScope();
                                    if (isset($scope['community'])) {
                                        $communityConfigurationsId = 'communityId-'.$scope['community'];
                                    }
                                }
                                    ?>
                                    <span class="pull-left"><strong><?= Module::t('amoseen', 'Data scadenza: ') .' '?><?= \Yii::$app->formatter->asDate($partnership->datum_deadline) ?></strong></span>
                            </div>
                        </div>
                    </article>
                </div>
                <?php
            endforeach;
            ?>
        </div>
<?php Pjax::end(); ?>
    </section>
</div>