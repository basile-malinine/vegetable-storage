<?php

namespace app\controllers;

use app\models\Documents\Acceptance\Acceptance;
use app\models\Remainder\Remainder;
use app\models\Remainder\RemainderSearch;
use yii\db\Exception;

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