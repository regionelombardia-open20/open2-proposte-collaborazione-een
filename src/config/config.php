<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\een\config
 * @category   CategoryName
 */

return [
    'params' => [
        //active the search
        'searchParams' => [
            'een-partnership-proposal' => true,
            'een-expr-of-interest' => true,
        ],
        //active the order
        'orderParams' => [
            'een' => [
                'enable' => true,
                'default_field' => 'datum_update',
                'order_type' => SORT_DESC
            ]
        ],
    ]
];
