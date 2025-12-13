<?php

namespace app\controllers;


use app\models\PaymentMethod\PaymentMethod;
use app\models\PaymentMethod\PaymentMethodSearch;

class PaymentMethodController extends BaseCrudController
{

    protected function getModel()
    {
        return new PaymentMethod();
    }

    protected function getSearchModel()
    {
        return new PaymentMethodSearch();
    }

    protected function getTwoId()
    {
        // TODO: Implement getTwoId() method.
    }
}