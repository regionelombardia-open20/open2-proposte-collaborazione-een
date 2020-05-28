<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    Open20Package
 * @category   CategoryName
 */

use open20\amos\een\AmosEen;
use open20\amos\core\icons\AmosIcons;
use yii\helpers\Html;

/**@var $dataProviderProfiles \yii\data\ActiveDataProvider
 * */
$this->title = \open20\amos\een\AmosEen::t('amoseen', 'Staff EEN');
$this->params['breadcrumbs'][] = $this->title;
\open20\amos\layout\assets\SpinnerWaitAsset::register($this);
$js = <<<JS
    var new_records= [];
    var deleted_records= [];
    var searchName = '';


/** send the 2 array( new, deleted ) with a pjax call and reaload the page
     *
     * @param pjaxGrid
     */
    function pjaxReloadPage(pjaxGrid) {
        $('.loading').show();
        $.pjax.reload({
            'container': pjaxGrid,
            timeout: 20000,
            data: {
                'new_records': new_records,
                'deleted_records': deleted_records,
                'searchName': searchName
                },
            method: 'post'
        }).done(function(){
            $('.loading').hide();
        });
    }
    
// if you check an element, remove the checjed option from other element checked before
    $('.default-staff-radio').click(function(){
        $('.default-staff-radio').each(function(){
            $(this).val(0);
            $(this).removeAttr("checked");

        });

        $(this).val(1);
        $(this).prop("checked",true);
    });

    
    //event on click on checkbox ( add new staff user )
    $(document).on('click', 'input[name="attrMembers[]"]', function(){
        var tr = $(this).closest('tr');
         if(this.checked) {
                $(tr).remove();
                new_records.push($(this).val());
                pjaxReloadPage('#pjax-staff-een')
            }
    });
    
    // event on the delete button
      $(document).on('click', '.disassociate-staff-btn', function(){
        var tr = $(this).closest('tr');
        var idProfile = $(tr).attr('data-key');
        if(new_records.indexOf(idProfile) >= 0){
             new_records.splice(new_records.indexOf(idProfile), 1);
        }
        else {
            deleted_records.push(idProfile);
        }
        $(tr).remove();
        pjaxReloadPage('#pjax-all-user')
    });
    
    // set error required
    $('body').on('beforeValidate', '#form-staff-een', function (e) {
        var isValid = true;
        $('.required-field').each(function(){
            if($(this).val().length == 0){
                var parent = $(this).parents('.required');
                $(parent).addClass('has-error');
                $(parent).find('.tooltip-error-field .help-block-error').text('Nodo rete EEN non può essere vuoto');
                isValid =  false;
            }
        });
        
        return isValid;
    });

    $('body').on('afterValidate', '#form-staff-een', function (e) {
         for(var i= 0; i < deleted_records.length; i++){
            console.log(deleted_records[i]);
            $('<input>').attr({
                type: 'hidden',
                id: 'element-to-delete',
                name: 'staffEenToDelete[]',
                value: deleted_records[i]
            }).appendTo('#form-staff-een');
        }
    });
    
    
    // search button
    $('#search-all-users-btn').click(function(){
        searchName = $('#search-all-users').val();
        pjaxReloadPage('#pjax-all-user');
        
    });
    
     // reset search
    $('#reset-search-btn').click(function(){
        searchName = '';
        $('#search-all-users').val('');
        pjaxReloadPage('#pjax-all-user');
        
    });
    
    $('#add-new-member').click(function(){
        if($('.container-search-all-users').is( ":hidden" )){
            $('.container-search-all-users').show();
        }
        else {
            $('.container-search-all-users').hide();
        }
    });
    
    
    // mantain search with pagination
    $(document).on('click', '#grid-all-users .pagination a', function(e){
        e.preventDefault();
         searchName = $('#search-all-users').val();
         var url = $(this).attr('href');
          $('.loading').show();
          $.pjax.reload({
            'url': url,
            'container': '#pjax-all-user',
            timeout: 20000,
            data: {
                'new_records': new_records,
                'deleted_records': deleted_records,
                'searchName': searchName
                },
            method: 'post'
        }).done(function(){
            $('.loading').hide();
        });
    });
JS;


$this->registerJs($js);

?>

<div class="loading" hidden></div>
<?php
$form = \open20\amos\core\forms\ActiveForm::begin(['id' => 'form-staff-een']); ?>
<div class="container-tools">
    <div class="search-recipients">
        <div class="col-xs-12">
            <div class="col-sm-6">
                <?= Html::button(AmosEen::t('amoseen','Aggiungi membro staff EEN'), [
                     'class' => 'btn btn-navigation-primary',
                     'id' => 'add-new-member',
                     'data-toggle' => 'collapse',
                     'data-target' => "#container-grid-all-user"
                ]) ?>
            </div>
            <div class="col-sm-6 container-search-all-users btn-search-admin"style="display:none">
                <?= Html::input('text', null, null, [
                    'id' => 'search-all-users',
                    'class' => 'form-control',
                    'placeholder' => AmosEen::t('amoseen', 'Search ...')
                ]) ?>
                <?= Html::a(AmosIcons::show('search'),
                    null,
                    [
                        'id' => 'search-all-users-btn',
                        'class' => 'btn btn btn-tools-secondary',
                    ])
                ?>
                <?= Html::a(AmosIcons::show('close'),
                    null,
                    [
                        'id' => 'reset-search-btn',
                        'class' => 'btn btn-danger-inverse',
                        'alt' => AmosEen::t('amoseen', 'Cancel')
                    ])
                ?>
            </div>
        </div>
    </div>
</div>

<div id="container-grid-all-user" hidden>
    <?php $dataProvider->pagination->pageSize= 5;?>
<?php \yii\widgets\Pjax::begin(['id' => 'pjax-all-user', 'timeout' => 2000, 'clientOptions' => ['data-pjax-container' => 'grid-all-users']]); ?>
<?php echo \open20\amos\core\views\AmosGridView::widget([
    'dataProvider' => $dataProvider,
    'id' => 'grid-all-users',
    'columns' => [
        [
            'class' => '\kartik\grid\CheckboxColumn',
            'name' => 'attrMembers',
            'rowSelectedClass' => \kartik\grid\GridView::TYPE_SUCCESS,
        ],
        'nome',
        'cognome',
        [
            'attribute' => 'user.email',
            'label' => 'Email'
        ],

    ],
]);
\yii\widgets\Pjax::end();
?>
</div>

    <?php
    $dataProviderProfiles->pagination = false;
    \yii\widgets\Pjax::begin(['id' => 'pjax-staff-een', 'timeout' => 2000, 'clientOptions' => ['data-pjax-container' => 'grid-staff-een']]); ?>
    <?php echo \open20\amos\core\views\AmosGridView::widget([
        'dataProvider' => $dataProviderProfiles,

        'columns' => [
            'nome',
            'cognome',
            'user.email',
            [
                'label' =>  \open20\amos\een\AmosEen::t('amoseen','#staff_default'),
                'value' => function($model)use (&$form) {
                    $staffMember = \open20\amos\een\models\EenStaff::find()->andWhere(['user_id' => $model->user_id])->one();
                    if(empty($staffMember)) {
                        $staffMember = new \open20\amos\een\models\EenStaff();
                    }
                    return $form->field($staffMember, "[$model->user_id]staff_default")->radio(['class' => 'default-staff-radio'])->label('');
                },
                'format' => 'raw'

            ],
            [
                'label' => \open20\amos\een\AmosEen::t('amoseen','#network_node_EEN'),
                'value' => function($model)use (&$form) {
                    $staffMember = \open20\amos\een\models\EenStaff::find()->andWhere(['user_id' => $model->user_id])->one();
                    if(empty($staffMember)) {
                        $staffMember = new \open20\amos\een\models\EenStaff();
                    }
                    return $form->field($staffMember, "[$model->user_id]een_network_node_id")->widget(\kartik\select2\Select2::className(),[
                        'data' => \yii\helpers\ArrayHelper::map(\open20\amos\een\models\base\EenNetworkNode::find()->all(),'id', 'name'),
                        'options' => ['placeholder' => \open20\amos\een\AmosEen::t('amoseen', 'Select...'), 'class' => 'required-field']
                    ])->label(false)
                        . "<div hidden>".$form->field($staffMember, "[$model->user_id]user_id")->hiddenInput(['value' => $model->user_id])->label(false)."</div>";
                },
                'format' => 'raw'
            ],
            [
                'class' => \yii\grid\ActionColumn::className(),
                'template' => '{disassociate}',
                'buttons' => [
                    'disassociate' => function ($url, $model) use($userStaffEenWithEOI){
                        if(!in_array($model->id, $userStaffEenWithEOI)) {
                            return \yii\helpers\Html::a(\open20\amos\core\icons\AmosIcons::show('delete'), null, [
                                'class' => 'btn btn-danger-inverse disassociate-staff-btn',
                                'data-pjax' => 0
                            ]);
                        }
                        else  {
                            return \yii\helpers\Html::a(\open20\amos\core\icons\AmosIcons::show('delete'), null, [
                                'class' => 'btn btn-danger-inverse',
                                'data-pjax' => 0,
                                'disabled' => true,
                                'title' => AmosEen::t('amoseen', "È necessario trasferire le manifestazioni assegnate ad un altro membro dello staff, prima di procedere con l'eliminazione.")
                            ]);
                        }
                    }
                ]
            ]
        ]
    ]);
    \yii\widgets\Pjax::end();

    echo \open20\amos\core\helpers\Html::submitButton(\open20\amos\een\AmosEen::t('amoseen', '#save'), ['class' => 'btn btn-navigation-primary pull-right']);

\open20\amos\core\forms\ActiveForm::end();
?>

