<?php

namespace app\controllers;

use app\models\Documents\Packing\PackingItem;
use app\models\Documents\Packing\PackingItemSearch;

class PackingItemController extends BaseCrudController
{

    protected function getModel()
    {
        return new PackingItem();
    }

    protected function getSearchModel()
    {
        return new PackingItemSearch();
    }

    protected function getTwoId()
    {
        return ['packing_id, acceptance_id'];
    }
}