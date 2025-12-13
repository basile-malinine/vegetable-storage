<?php

namespace app\controllers;

use app\models\Currency\Currency;
use app\models\Currency\CurrencySearch;

class CurrencyController extends BaseCrudController
{

    protected function getModel()
    {
        return new Currency();
    }

    protected function getSearchModel()
    {
        return new CurrencySearch();
    }

    protected function getTwoId()
    {
        // TODO: Implement getTwoId() method.
    }
}
