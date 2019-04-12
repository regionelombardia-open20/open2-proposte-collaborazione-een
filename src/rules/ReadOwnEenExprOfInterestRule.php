<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\news
 * @category   CategoryName
 */

namespace lispa\amos\een\rules;

use lispa\amos\core\rules\DefaultOwnContentRule;
use lispa\amos\een\models\EenExprOfInterest;

class ReadOwnEenExprOfInterestRule extends DefaultOwnContentRule
{
    public $name = 'ReadOwnEenExprOfInterest';
    /**
     * @inheritdoc
     */
}
