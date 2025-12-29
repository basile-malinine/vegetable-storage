<?php

namespace app\models\Documents\Order;

use app\models\Documents\Shipment\Shipment;
use DateTime;

use Yii;
use yii\helpers\ArrayHelper;

use app\models\Base;
use app\models\DistributionCenter\DistributionCenter;
use app\models\Documents\Delivery\Delivery;
use app\models\LegalSubject\LegalSubject;
use app\models\Manager\Manager;
use app\models\Stock\Stock;

/**
 * This is the model class for table "order".
 *
 * @property int $id
 * @property int $type_id Tип заказа
 * @property int $delivery_id Поставка
 * @property int $company_own_id Предприятие
 * @property int $buyer_id Сеть
 * @property int $distribution_center_id Распределительный центр
 * @property int $stock_id Склад
 * @property int $executor_id Исполнитель
 * @property int $sales_mng_id Менеджер по реализации
 * @property int $sales_agent_id Агент по реализации
 * @property string $date Дата
 * @property string $date_close Дата закрытия
 * @property float|null $shipped Отгружено
 * @property float|null $accepted_dist_center Принято РЦ
 * @property float|null $price Сумма
 * @property int|null $weight Вес
 * @property string|null $comment Комментарий
 * @property int $created_by Создатель
 * @property string $created_at Дата создания
 * @property string $updated_at Дата обновления
 *
 * @property Delivery $delivery
 * @property Shipment $shipment Отгрузка
 * @property LegalSubject $buyer
 * @property DistributionCenter $distributionCenter
 * @property Stock $stock
 * @property Manager $executor
 * @property Manager $salesMng
 * @property LegalSubject $companyOwn
 *
 * @property Stock $stock_executor Склад / Исполнитель
 * @property array $items Состав Заказа
 * @property string $label
 *
 */
class Order extends Base
{
    public $shipped = null;
    public $accepted_dist_center = null;

    // Типы Заказа -------------------------------------------------------------
    const TYPE_STOCK = 1;
    const TYPE_EXECUTOR = 2;
    const TYPE_LIST = [
        self::TYPE_STOCK => 'Склад',
        self::TYPE_EXECUTOR => 'Исполнитель',
    ];

    // Итоги по документу ------------------------------------------------------
    public mixed $price = null;
    public mixed $weight = null;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'delivery_id',
                'stock_id',
                'executor_id',
                'comment'], 'default', 'value' => null
            ],

            [[
                'shipped',
                'accepted_dist_center'], 'safe'],

            [[
                'date',
                'type_id',
                'company_own_id',
                'buyer_id',
                'distribution_center_id',
                'sales_mng_id'], 'required'
            ],

            [[
                'type_id',
                'delivery_id',
                'company_own_id',
                'buyer_id',
                'distribution_center_id',
                'stock_id',
                'executor_id',
                'sales_mng_id',
                'sales_agent_id',
                'created_by'], 'integer'
            ],

            [['date', 'created_at', 'updated_at'], 'safe'],

            [['comment'], 'string'],

            [['buyer_id'], 'exist', 'skipOnError' => true, 'targetClass' => LegalSubject::class, 'targetAttribute' => ['buyer_id' => 'id']],
            [['distribution_center_id'], 'exist', 'skipOnError' => true, 'targetClass' => DistributionCenter::class, 'targetAttribute' => ['distribution_center_id' => 'id']],
            [['stock_id'], 'exist', 'skipOnError' => true, 'targetClass' => Stock::class, 'targetAttribute' => ['stock_id' => 'id']],
            [['executor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Manager::class, 'targetAttribute' => ['executor_id' => 'id']],
            [['sales_agent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Manager::class, 'targetAttribute' => ['sales_agent_id' => 'id']],
            [['sales_mng_id'], 'exist', 'skipOnError' => true, 'targetClass' => Manager::class, 'targetAttribute' => ['sales_mng_id' => 'id']],
            [['company_own_id'], 'exist', 'skipOnError' => true, 'targetClass' => LegalSubject::class, 'targetAttribute' => ['company_own_id' => 'id']],

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
            'type_id' => 'Tип заказа',
            'delivery_id' => 'Поставка',
            'company_own_id' => 'Предприятие',
            'buyer_id' => 'Покупатель (Сеть)',
            'distribution_center_id' => 'Распределительный центр',
            'stock_id' => 'Склад',
            'executor_id' => 'Исполнитель',
            'stock_executor' => 'Склад / Исполнитель',
            'price' => 'Сумма',
            'weight' => 'Вес',
            'sales_mng_id' => 'Менеджер по реализации',
            'sales_agent_id' => 'Агент по реализации',
            'date' => 'Дата отгрузки',
            'date_close' => 'Дата закрытия',
            'shipped' => 'Отгружено',
            'accepted_dist_center' => 'Принято РЦ',
            'comment' => 'Комментарий',
            'created_by' => 'Создатель',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    public function afterFind()
    {
        parent::afterFind();

        $this->date = $this->date ? date('d.m.Y', strtotime($this->date)) : null;
        $this->created_at = $this->created_at ? date('Y-m-d H:i', strtotime($this->created_at)) : null;
        $this->updated_at = $this->updated_at ? date('Y-m-d H:i', strtotime($this->updated_at)) : null;

        if (!$this->price) {
            $items = $this->items;
            $prices = ArrayHelper::getColumn($items, 'price_total');
            $this->price = array_sum($prices);
        }

        if (!$this->weight) {
            $items = $this->items;
            $weights = ArrayHelper::getColumn($items, 'weight');
            $this->weight = array_sum($weights);
        }

        $this->shipped = array_sum(ArrayHelper::getColumn($this->items, 'shipped'));
        $this->shipped = number_format($this->shipped, 1, '.', ' ');
        $this->accepted_dist_center = array_sum(ArrayHelper::getColumn($this->items, 'accepted_dist_center'));
        $this->accepted_dist_center = number_format($this->accepted_dist_center, 1, '.', ' ');
    }

    public function beforeSave($insert)
    {
        $this->date = $this->date ? date('Y-m-d', strtotime($this->date)) : null;

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
     * ------------------------------------------- Поставка
     * Gets query for [[Delivery]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDelivery()
    {
        return $this->hasOne(Delivery::class, ['id' => 'delivery_id']);
    }

    /**
     * ------------------------------------------- Отгрузка
     * Gets query for [[Shipment]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getShipment()
    {
        return $this->hasOne(Shipment::class, ['parent_doc_id' => 'id'])
            ->andWhere(['type_id' => Shipment::TYPE_ORDER]);
    }

    /**
     * ------------------------------------------- Поставщик
     * Gets query for [[LegalSubject]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCompanyOwn()
    {
        return $this->hasOne(LegalSubject::class, ['id' => 'company_own_id']);
    }

    /**
     * ------------------------------------------- Сеть
     * Gets query for [[LegalSubject]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBuyer()
    {
        return $this->hasOne(LegalSubject::class, ['id' => 'buyer_id']);
    }

    /**
     * ------------------------------------------- Распределительный центр
     * Gets query for [[DistributionCenter]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDistributionCenter()
    {
        return $this->hasOne(DistributionCenter::class, ['id' => 'distribution_center_id']);
    }

    /**
     * ------------------------------------------- Склад
     * Gets query for [[Stock]].
     *
     * @return \yii\db\ActiveQuery
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
     * ------------------------------------------- Менеджер по реализации
     * Gets query for [[SalesMng]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSalesMng()
    {
        return $this->hasOne(Manager::class, ['id' => 'sales_mng_id']);
    }

    /**
     * ------------------------------------------- Агент по реализации
     * Gets query for [[SalesAgent]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSalesAgent()
    {
        return $this->hasOne(Manager::class, ['id' => 'sales_agent_id']);
    }

    // ------------------------------------------- Состав документа DeliveryItem[]
    public function getItems()
    {
        return $this->hasMany(OrderItem::class, ['order_id' => 'id']);
    }

    // ------------------------------------------- Label
    public function getLabel(): string
    {
        $assortment = $this->items
            ? $this->items[0]->label
            : 'Нет состава';

        return '№' . $this->id
            . ' ' . $this->date
            . ', ' . $this->buyer->name
            . ', ' . $this->distributionCenter->name
            . ', ' . $assortment;
    }

    // ------------------------------------------- Short Label
    public function getShortLabel(): string
    {
        return '№' . $this->id
            . ' ' . $this->date;
    }

    public static function getList($condition = null): array
    {
        $list = self::find()
            ->select(['id'])
            ->where($condition)
            ->indexBy('id')
            ->column();

        $orderList = [];
        foreach ($list as $item) {
            $model = self::findOne($item);
            $orderList[$item] = $model->label;
        }

        return $orderList;
    }

    public static function getListForRefundExecutor()
    {
        return self::getList('type_id = ' . self::TYPE_EXECUTOR .
            'delivery_id IS NOT NULL');
    }
}
