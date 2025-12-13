<?php

namespace app\controllers;

use app\models\Opf\Opf;
use app\models\Opf\OpfSearch;

class OpfController extends BaseCrudController
{

    protected function getModel()
    {
        return new Opf();
    }

    protected function getSearchModel()
    {
        return new OpfSearch();
    }

    protected function getTwoId()
    {
        // TODO: Implement getTwoId() method.
    }
}