<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\een\views\email
 * @category   CategoryName
 */

use lispa\amos\een\AmosEen;

/**
 * @var integer $contents_number
 */

?>

<div style="box-sizing:border-box;color:#000000;">
    <p style="font-size:1em;margin:0;margin-top:5px;">
        <?php
        $url = "<a href='" . Yii::$app->urlManager->createAbsoluteUrl(['/']) . "'>" . AmosEen::t('amoseen', '#Access_to_platform') . "</a>";
        echo AmosEen::t('amoseen', '#Receive_this_notify', [$url]) ?>
    </p>
</div>