<?php

namespace app\controllers;

use app\models\Quality\Quality;
use app\models\Quality\QualitySearch;

class QualityController extends BaseCrudController
{

    protected function getModel()
    {
        return new Quality();
    }

    protected function getSearchModel()
    {
        return new QualitySearch();
    }

    protected function getTwoId()
    {
        // TODO: Implement getTwoId() method.
    }
}