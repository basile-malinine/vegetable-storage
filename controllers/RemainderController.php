<?php

namespace app\controllers;

use app\models\Documents\Remainder\Remainder;
use app\models\Documents\Remainder\RemainderSearch;

class RemainderController extends BaseCrudController
{

    protected function getModel()
    {
        return new Remainder();
    }

    protected function getSearchModel()
    {
        return new RemainderSearch();
    }

    protected function getTwoId()
    {
        // TODO: Implement getTwoId() method.
    }
}