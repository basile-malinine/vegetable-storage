<?php

namespace app\models\Documents\Delivery;

use DateTime;

use Yii;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

use app\models\Base;
use app\models\Currency\Currency;
use app\models\Documents\Acceptance\Acceptance;
use app\models\Documents\Order\Order;
use app\models\LegalSubject\LegalSubject;
use app\models\Manager\Manager;
use app\models\PaymentMethod\PaymentMethod;
use app\models\Stock\Stock;

/**
 * This is the model class for table "delivery".
 *
 * @property int $id
 * @property int $type_id Тип поставки
 * @property int $supplier_id Поставщик
 * @property int $company_own_id Предприятие
 * @property int|null $stock_id Склад
 * @property int|null $executor_id Исполнитель
 * @property int $purchasing_mng_id Менеджер по закупкам
 * @property int $purchasing_agent_id Агент по закупкам
 * @property int $sales_mng_id Менеджер по реализации
 * @property int $support_mng_id Отдел сопровождения
 * @property int $currency_id Валюта
 * @property int $payment_method_id Способ оплаты
 * @property int $transport_affiliation_id Ставит транспорт
 * @property string|null $shipment_date Дата отгрузки
 * @property string|null $unloading_date Дата выгрузки
 * @property string|null $payment_term Срок оплаты
 * @property string|null $date_close Дата закрытия
 * @property string|null $comment Комментарий
 * @property int|null $created_by Создатель
 * @property string|null $created_at Дата создания
 * @property string|null $updated_at Дата обновления
 *
 * @property Order[] $orders
 * @property LegalSubject $companyOwn
 * @property Currency $currency
 * @property DeliveryItem[] $deliveryItems
 * @property Manager $executor
 * @property PaymentMethod $paymentMethod
 * @property Manager $purchasingAgent
 * @property Manager $purchasingMng
 * @property Manager $salesMng
 * @property Stock $stock
 * @property LegalSubject $supplier
 * @property Manager $supportMng
 * @property string $label
 * @property Acceptance[] $acceptance
 */
class Delivery extends Base
{
    // Типы Заказа -------------------------------------------------------------
    const TYPE_STOCK = 1;
    const TYPE_EXECUTOR = 2;
    const TYPE_LIST = [
        self::TYPE_STOCK => 'Склад',
        self::TYPE_EXECUTOR => 'Исполнитель',
    ];

    // Способ оплаты -----------------------------------------------------------
    const TRANSPORT_AFFILIATION_SUPPLIER = 1;
    const TRANSPORT_AFFILIATION_BUYER = 2;
    const TRANSPORT_AFFILIATION_LIST = [
        self::TRANSPORT_AFFILIATION_SUPPLIER => 'Поставщик',
        self::TRANSPORT_AFFILIATION_BUYER => 'Покупатель',
    ];

    public mixed $price_total = null;

    public static function tableName()
    {
        return 'delivery';
    }

    public function rules()
    {
        return [
            [[
                'stock_id',
                'executor_id',
                'purchasing_agent_id',
                'support_mng_id',
                'shipment_date',
                'unloading_date',
                'payment_term',
                'date_close',
                'comment',
                'created_by',
                'created_at',
                'updated_at'], 'default', 'value' => null
            ],

            [[
                'type_id',
                'supplier_id',
                'company_own_id',
                'purchasing_mng_id',
                'sales_mng_id',
                'currency_id',
                'payment_method_id',
                'transport_affiliation_id'], 'required'
            ],

            [[
                'type_id',
                'supplier_id',
                'company_own_id',
                'stock_id',
                'executor_id',
                'purchasing_mng_id',
                'purchasing_agent_id',
                'sales_mng_id',
                'support_mng_id',
                'currency_id',
                'payment_method_id',
                'transport_affiliation_id',
                'created_by'], 'integer'
            ],

            [[
                'shipment_date',
                'unloading_date',
                'payment_term',
                'date_close',
                'created_at',
                'updated_at'], 'safe'
            ],

            [['comment'], 'string'],

            [['company_own_id'], 'exist', 'skipOnError' => true, 'targetClass' => LegalSubject::class, 'targetAttribute' => ['company_own_id' => 'id']],
            [['currency_id'], 'exist', 'skipOnError' => true, 'targetClass' => Currency::class, 'targetAttribute' => ['currency_id' => 'id']],
            [['executor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Manager::class, 'targetAttribute' => ['executor_id' => 'id']],
            [['payment_method_id'], 'exist', 'skipOnError' => true, 'targetClass' => PaymentMethod::class, 'targetAttribute' => ['payment_method_id' => 'id']],
            [['purchasing_agent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Manager::class, 'targetAttribute' => ['purchasing_agent_id' => 'id']],
            [['purchasing_mng_id'], 'exist', 'skipOnError' => true, 'targetClass' => Manager::class, 'targetAttribute' => ['purchasing_mng_id' => 'id']],
            [['sales_mng_id'], 'exist', 'skipOnError' => true, 'targetClass' => Manager::class, 'targetAttribute' => ['sales_mng_id' => 'id']],
            [['stock_id'], 'exist', 'skipOnError' => true, 'targetClass' => Stock::class, 'targetAttribute' => ['stock_id' => 'id']],
            [['supplier_id'], 'exist', 'skipOnError' => true, 'targetClass' => LegalSubject::class, 'targetAttribute' => ['supplier_id' => 'id']],
            [['support_mng_id'], 'exist', 'skipOnError' => true, 'targetClass' => Manager::class, 'targetAttribute' => ['support_mng_id' => 'id']],

            [[
                'stock_id',
                'executor_id'], 'testStockExecutor', 'skipOnEmpty' => false
            ],
        ];
    }

    public function testStockExecutor($attribute, $params)
    {
        switch ($this->type_id) {
            case Order::TYPE_STOCK:
                if (!$this->stock_id) {
                    $this->addError('stock_id', 'Обязательно');
                }
                break;
            case Order::TYPE_EXECUTOR:
                if (!$this->executor_id) {
                    $this->addError('executor_id', 'Обязательно');
                }
                break;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type_id' => 'Тип поставки',
            'supplier_id' => 'Поставщик',
            'company_own_id' => 'Предприятие',
            'stock_id' => 'Склад',
            'executor_id' => 'Исполнитель',
            'stock_executor' => 'Склад / Исполнитель',
            'purchasing_mng_id' => 'Менеджер по закупкам',
            'purchasing_agent_id' => 'Агент по закупкам',
            'sales_mng_id' => 'Менеджер по реализации',
            'support_mng_id' => 'Отдел сопровождения',
            'currency_id' => 'Валюта',
            'payment_method_id' => 'Способ оплаты',
            'transport_affiliation_id' => 'Ставит транспорт',
            'shipment_date' => 'Дата отгрузки',
            'unloading_date' => 'Дата выгрузки',
            'payment_term' => 'Срок оплаты',
            'date_close' => 'Дата закрытия',
            'price_total' => 'Общая стоимость',
            'comment' => 'Комментарий',
            'created_by' => 'Создатель',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    public function afterFind()
    {
        parent::afterFind();

        $this->shipment_date = $this->shipment_date ? date('d.m.Y', strtotime($this->shipment_date)) : null;
        $this->unloading_date = $this->unloading_date ? date('d.m.Y', strtotime($this->unloading_date)) : null;
        $this->date_close = $this->date_close ? date('Y-m-d H:i', strtotime($this->date_close)) : null;
        $this->created_at = $this->created_at ? date('Y-m-d H:i', strtotime($this->created_at)) : null;

        //
        if (!$this->price_total) {
            $items = $this->deliveryItems;
            $prices = ArrayHelper::getColumn($items, 'price_total');
            $this->price_total = array_sum($prices);
        }
    }

    public function beforeSave($insert)
    {
        $this->shipment_date = $this->shipment_date ? date('Y-m-d H:i', strtotime($this->shipment_date)) : null;
        $this->unloading_date = $this->unloading_date ? date('Y-m-d H:i', strtotime($this->unloading_date)) : null;
        $this->date_close = $this->date_close ? date('Y-m-d H:i', strtotime($this->date_close)) : null;

        $now = (new DateTime('now'))->format('Y-m-d');
        if ($insert) {
            $this->created_by = Yii::$app->user->id;
            $this->created_at = $now;
        }
        $this->updated_at = $now;

        switch ($this->type_id) {
            case self::TYPE_STOCK:
                $this->executor_id = null;
                break;
            case self::TYPE_EXECUTOR:
                $this->stock_id = null;
                break;
        }

        return true;
    }

    /**
     * Gets query for [[Manager]].
     *
     * @return ActiveQuery
     */
    public function getManager()
    {
        return $this->hasOne(Manager::class, ['id' => 'manager_id']);
    }

    /**
     * Gets query for [[Own]].
     *
     * @return ActiveQuery
     */
    public function getCompanyOwn()
    {
        return $this->hasOne(LegalSubject::class, ['id' => 'company_own_id']);
    }

    /**
     * Gets query for [[Stock]].
     *
     * @return ActiveQuery
     */
    public function getStock()
    {
        return $this->hasOne(Stock::class, ['id' => 'stock_id']);
    }

    /**
     * ------------------------------------------- Исполнитель
     * Gets query for [[Executor]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExecutor()
    {
        return $this->hasOne(Manager::class, ['id' => 'executor_id']);
    }

    /**
     * Gets query for [[Supplier]].
     *
     * @return ActiveQuery
     */
    public function getSupplier()
    {
        return $this->hasOne(LegalSubject::class, ['id' => 'supplier_id']);
    }

    // Состав документа DeliveryItem[]
    public function getDeliveryItems()
    {
        return $this->hasMany(DeliveryItem::class, ['delivery_id' => 'id']);
    }

    // Привязанные заказы Order[]
    public function getOrders()
    {
        return $this->hasMany(Order::class, ['delivery_id' => 'id']);
    }

    public function getLabel()
    {
        $assortment = $this->deliveryItems
            ? $this->deliveryItems[0]->label
            : 'Нет состава';

        return '№' . $this->id
            . ' ' . $this->shipment_date
            . ', ' . $this->supplier->name
            . ', ' . $assortment;
    }

    public function getShortLabel()
    {
        return '№' . $this->id
            . ' ' . $this->shipment_date;
    }

    // Привязанные заказы Order[]
    public function getAcceptance()
    {
        $ret = $this->hasMany(Acceptance::class, ['parent_doc_id' => 'id'])
            ->where(['type_id' => Acceptance::TYPE_DELIVERY]);

        return $ret;
    }

    public static function getListForAcceptance()
    {
        $list = self::find()
            ->select(['id'])
            ->where([
                'date_close' => null,
                'type_id' => Delivery::TYPE_STOCK,
            ])
            ->indexBy('id')
            ->column();

        $notAcceptedList = [];
        foreach ($list as $item) {
            $model = self::findOne($item);
            if (!$model->acceptance && $model->deliveryItems) {
                $notAcceptedList[$item] = $model->label;
            }
        }

        return $notAcceptedList;
    }
}
