<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    Open20Package
 * @category   CategoryName
 */

namespace open20\amos\een\assets;

use yii\web\AssetBundle;

/**
 * Class ProposteCollaborazioneEenAsset
 * @package open20\amos\een\assets
 */
class ProposteCollaborazioneEenAsset extends AssetBundle
{
    public $sourcePath = '@vendor/open20/amos-proposte-collaborazione-een/src/assets/web';

    public $js = [
    ];

    public $css = [
        'less/proposte-een.less',
    ];

    public $depends = [

    ];


    public function init()
    {
        $moduleL = \Yii::$app->getModule('layout');
        if(!empty($moduleL))
        { $this->depends [] = 'open20\amos\layout\assets\BaseAsset'; }
        else
        { $this->depends [] = 'open20\amos\core\views\assets\AmosCoreAsset'; }
        parent::init();
    }
}