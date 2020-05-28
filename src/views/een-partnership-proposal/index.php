<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\een\views\een-partnership-proposal
 * @category   CategoryName
 */

use open20\amos\core\views\DataProviderView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var \open20\amos\een\models\EenPartnershipProposal $model
 * @var string $currentView
 */
$currentUrl = explode("?", \yii\helpers\Url::current());
$isArchived = false;
if($currentUrl[0] == '/een/een-partnership-proposal/archived') {
    $isArchived = true;
}

$this->params['textHelp']['filename'] = 'description';
$profile = \open20\amos\admin\models\UserProfile::find()->andWhere(['user_id' => \Yii::$app->user->id])->one();
if($profile && $profile->validato_almeno_una_volta) {
    echo \yii\helpers\Html::a(\open20\amos\een\AmosEen::t('amoseen', "Crea una proposta"), 'create-proposal', ['class' => 'btn btn-navigation-primary']);
}
else {
    echo \yii\helpers\Html::a(\open20\amos\een\AmosEen::t('amoseen', "Crea una proposta"), 'javascript:void(0)', [
            'class' => 'btn btn-navigation-primary',
        'data-target'=> "#modal-een-alert",
        'data-toggle'=>"modal"

    ]);
    \yii\bootstrap\Modal::begin([
            'id' => 'modal-een-alert'
    ]);
     echo "<p>". \open20\amos\een\AmosEen::t('amoseen', "Solo i partecipanti con un profilo completo e validato possono pubblicare proposte di collaborazione segui <a href='{link}'>questo link</a> per completare il profilo.",
             [
                     'link' => '/admin/user-profile/update?id='.$profile->id
             ])."</p>";
    \yii\bootstrap\Modal::end();
}
?>
<div class="proposte-collaborazione-een-index">
    <?= $this->render('_search', ['model' => $model, 'countryTypes' => $countryTypes]); ?>
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
                    'class' => 'open20\amos\core\views\grid\ActionColumn',
                    'template' => '{view}{expr_of_interest}',
                    'buttons' => [
                        'expr_of_interest' => function($url, $model)use($isArchived){
                            if(!$model->isExprOfInterestSended() && !$isArchived) {
                                return \open20\amos\core\helpers\Html::a(\open20\amos\core\icons\AmosIcons::show('thumb-up'), "#interestPopup-{$model->id}", [
                                        'class' => ['btn btn-tools-secondary'],
                                        'title' => \open20\amos\een\AmosEen::t('amoseen', '#expr_of_interest_verb'),
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
