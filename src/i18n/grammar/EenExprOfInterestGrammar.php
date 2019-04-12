<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\een\i18n\grammar
 * @category   CategoryName
 */

namespace lispa\amos\een\i18n\grammar;

use lispa\amos\core\interfaces\ModelGrammarInterface;
use lispa\amos\een\AmosEen;

/**
 * Class EenExprOfInterestGrammar
 * @package lispa\amos\een\i18n\grammar
 */
class EenExprOfInterestGrammar implements ModelGrammarInterface
{
    /**
     * @inheritdoc
     */
    public function getModelSingularLabel()
    {
        return AmosEen::t('amoseen', '#expressions_of_interest_singular');
    }

    /**
     * @inheritdoc
     */
    public function getModelLabel()
    {
        return AmosEen::t('amoseen', '#expressions_of_interest_plural');
    }

    /**
     * @inheritdoc
     */
    public function getArticleSingular()
    {
        return AmosEen::t('amoseen', '#expressions_of_interest_article_singular');
    }

    /**
     * @inheritdoc
     */
    public function getArticlePlural()
    {
        return AmosEen::t('amoseen', '#expressions_of_interest_article_plural');
    }

    /**
     * @inheritdoc
     */
    public function getIndefiniteArticle()
    {
        return AmosEen::t('amoseen', '#expressions_of_interest_indefinite_article');
    }
}
