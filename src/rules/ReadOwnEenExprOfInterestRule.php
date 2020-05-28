<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news
 * @category   CategoryName
 */

namespace open20\amos\een\rules;

use open20\amos\core\rules\DefaultOwnContentRule;
use open20\amos\een\models\EenExprOfInterest;

class ReadOwnEenExprOfInterestRule extends DefaultOwnContentRule
{
    public $name = 'ReadOwnEenExprOfInterest';
    /**
     * @inheritdoc
     */
}
