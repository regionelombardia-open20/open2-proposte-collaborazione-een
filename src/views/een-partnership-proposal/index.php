<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\een\views\een-partnership-proposal
 * @category   CategoryName
 */

use lispa\amos\core\views\DataProviderView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var \lispa\amos\een\models\EenPartnershipProposal $model
 * @var string $currentView
 */
$currentUrl = explode("?", \yii\helpers\Url::current());
$isArchived = false;
if($currentUrl[0] == '/een/een-partnership-proposal/archived') {
    $isArchived = true;
}

$this->params['textHelp']['filename'] = 'description'
?>
<div class="proposte-collaborazione-een-index">
    <?= $this->render('_search', ['model' => $model]); ?>
    <?= DataProviderView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $model,
        'currentView' => $currentView,
        'gridView' => [
            'columns' => [
                'content_title',
                'reference_external',
                'reference_type',
                /*
                'tipologiaProposteEen' => [
                    'attribute' => 'tipologiaProposteEen',
                    'format' => 'html',
                    'label' => $model->getAttributeLabel('tipologiaProposteEen'),
                    'value' => function ($model) {
                        return strip_tags($model->getAttrTipologiaProposteEenMm());
                    }
                ],
                */
                //'paese',
                'datum_submit:date',
                'datum_deadline:date',
                [
                    'class' => 'lispa\amos\core\views\grid\ActionColumn',
                    'template' => '{view}{expr_of_interest}',
                    'buttons' => [
                        'expr_of_interest' => function($url, $model)use($isArchived){
                            if(!$model->isExprOfInterestSended() && !$isArchived) {
                                return \lispa\amos\core\helpers\Html::a(\lispa\amos\core\icons\AmosIcons::show('thumb-up'), "#interestPopup-{$model->id}", [
                                        'class' => ['btn btn-tools-secondary'],
                                        'title' => \lispa\amos\een\AmosEen::t('amoseen', '#expr_of_interest_verb'),
                                        'data-target' => "#interestPopup-{$model->id}",
                                        'data-toggle' => "modal",
                                    ]) . $this->render('_modal_expr_of_interest', ['model' => $model]);
                            }
                            else return '';
                        }
                    ]
                ],
            ],
        ],
        'listView' => [
            'itemView' => '_item',
            'masonry' => FALSE,
            
            // Se masonry settato a TRUE decommentare e settare i parametri seguenti
            // nel CSS settare i seguenti parametri necessari al funzionamento tipo
            // .grid-sizer, .grid-item {width: 50&;}
            // Per i dettagli recarsi sul sito http://masonry.desandro.com
            
            //'masonrySelector' => '.grid',
            //'masonryOptions' => [
            //    'itemSelector' => '.grid-item',
            //    'columnWidth' => '.grid-sizer',
            //    'percentPosition' => 'true',
            //    'gutter' => '20'
            //]
        ],
    ]); ?>
</div>
