<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\een\views\email
 * @category   CategoryName
 */

/**
 * @var lispa\amos\een\models\EenPartnershipProposal $model
 */

?>
<div style="border:1px solid #cccccc;padding:10px;margin-bottom: 10px;background-color: #ffffff;">

    <div>
        <!-- getImage universal code-->
    </div>

    <!--    <div style="padding:0;margin:0">-->
    <!--        <h3 style="font-size:2em;line-height: 1;margin:0;padding:10px 0;">-->
    <!--            < ?= Html::a($model->getTitle(), Yii::$app->urlManager->createAbsoluteUrl($model->getFullViewUrl()), ['style' => 'color: #297A38;']) ?>-->
    <!--        </h3>-->
    <!--    </div>-->

    <div style="box-sizing:border-box;font-size:13px;font-weight:normal;color:#000000;">
        <?php
        echo $model->getDescription(true);
        ?>
    </div>
    <div style="box-sizing:border-box;padding-bottom: 5px;">
        <div style="margin-top:20px; display: flex; padding: 10px;">
            <div style="width: 50px; height: 50px; overflow: hidden;-webkit-border-radius: 50%; -moz-border-radius: 50%; border-radius: 50%;float: left;">
                <?php $content ?>
            </div>
        </div>
    </div>
</div>