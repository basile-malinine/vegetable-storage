<?php

namespace app\models\Documents\Order;

use app\models\Base;
use app\models\DistributionCenter\DistributionCenter;
use app\models\LegalSubject\LegalSubject;
use app\models\Manager\Manager;
use app\models\Stock\Stock;
use DateTime;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "order".
 *
 * @property int $id
 * @property int $type_id Tип заказа
 * @property int $supplier_id Поставщик
 * @property int $buyer_id Сеть
 * @property int $distribution_center_id Распределительный центр
 * @property int $stock_id Склад
 * @property int $executor_id Исполнитель
 * @property int $sales_mng_id Менеджер по реализации
 * @property int $sales_agent_id Агент по реализации
 * @property int|null $status_main_id Основные статусы
 * @property int|null $status_additional_id Дополнительные статусы
 * @property string $date Дата
 * @property string $date_close Дата закрытия
 * @property float|null $accepted_dist_center Принято РЦ
 * @property float|null $price Сумма
 * @property int|null $weight Вес
 * @property string|null $comment Комментарий
 * @property int $created_by Создатель
 * @property string $created_at Дата создания
 * @property string $updated_at Дата обновления
 *
 * @property LegalSubject $buyer
 * @property DistributionCenter $distributionCenter
 * @property Stock $stock
 * @property Manager $executor
 * @property Manager $salesMng
 * @property LegalSubject $supplier
 *
 * @property Stock $stock_executor Склад / Исполнитель
 * @property array $orderItems Состав Заказа
 */
class Order extends Base
{
    // Типы Заказа -------------------------------------------------------------
    const TYPE_STOCK = 1;
    const TYPE_EXECUTOR = 2;
    const TYPE_LIST = [
        self::TYPE_STOCK => 'Склад',
        self::TYPE_EXECUTOR => 'Исполнитель',
    ];

    // Статусы Заказа основные -------------------------------------------------
    const STATUS_MAIN_CANCELED = 1;
    const STATUS_MAIN_SHIPPED = 2;
    const STATUS_MAIN_SHIPPED_REFUND = 3;
    const STATUS_MAIN_SHIPPED_COMPLETED = 4;
    const STATUS_MAIN_LINKED = 5;
    const STATUS_MAIN_LINKED_REFUND = 6;
    const STATUS_MAIN_LIKED_COMPLETED = 7;

    const STATUS_MAIN_LIST = [
        self::STATUS_MAIN_CANCELED => 'Отменён',
        self::STATUS_MAIN_SHIPPED => 'Отгружен',
        self::STATUS_MAIN_SHIPPED_REFUND => 'Возврат отгруженного',
        self::STATUS_MAIN_SHIPPED_COMPLETED => 'Выполнен отгруженный',
        self::STATUS_MAIN_LINKED => 'Привязан',
        self::STATUS_MAIN_LINKED_REFUND => 'Возврат привязанного',
        self::STATUS_MAIN_LIKED_COMPLETED => 'Выполнен привязанный',
    ];

    // Статусы Заказа дополнительные -------------------------------------------
    const STATUS_ADDITIONAL_MIGRATED = 1;
    const STATUS_ADDITIONAL_NOT_COMPLETED = 2;
    const STATUS_ADDITIONAL_BUYER_CANCELED = 3;
    const STATUS_ADDITIONAL_COMPLETED = 4;
    const STATUS_ADDITIONAL_PARTIALLY_COMPLETED = 5;
    const STATUS_ADDITIONAL_FULL_REFUND = 6;

    const STATUS_ADDITIONAL_LIST = [
        self::STATUS_ADDITIONAL_MIGRATED => 'Перенесён',
        self::STATUS_ADDITIONAL_NOT_COMPLETED => 'Не выполнен',
        self::STATUS_ADDITIONAL_BUYER_CANCELED => 'Отменён покупателем',
        self::STATUS_ADDITIONAL_COMPLETED => 'Выполнен',
        self::STATUS_ADDITIONAL_PARTIALLY_COMPLETED => 'Выполнен частично',
        self::STATUS_ADDITIONAL_FULL_REFUND => 'Полный возврат',
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
                'stock_id',
                'executor_id',
                'status_main_id',
                'status_additional_id',
                'accepted_dist_center',
                'comment'], 'default', 'value' => null
            ],

            [['accepted_dist_center'], 'safe'],

            [[
                'date',
                'type_id',
                'supplier_id',
                'buyer_id',
                'distribution_center_id',
                'sales_mng_id'], 'required'
            ],

            [[
                'type_id',
                'supplier_id',
                'buyer_id',
                'distribution_center_id',
                'stock_id',
                'executor_id',
                'sales_mng_id',
                'sales_agent_id',
                'status_main_id',
                'status_additional_id',
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
            [['supplier_id'], 'exist', 'skipOnError' => true, 'targetClass' => LegalSubject::class, 'targetAttribute' => ['supplier_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type_id' => 'Tип заказа',
            'supplier_id' => 'ЮЛ (Поставщик)',
            'buyer_id' => 'Сеть (Покупатель)',
            'distribution_center_id' => 'Распределительный центр',
            'stock_id' => 'Склад',
            'executor_id' => 'Исполнитель',
            'stock_executor' => 'Склад / Исполнитель',
            'price' => 'Сумма',
            'weight' => 'Вес',
            'sales_mng_id' => 'Менеджер по реализации',
            'sales_agent_id' => 'Агент по реализации',
            'status_main_id' => 'Основные статусы',
            'status_additional_id' => 'Дополнительные статусы',
            'date' => 'Дата',
            'date_close' => 'Дата закрытия',
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

        if ($this->accepted_dist_center) {
            $this->accepted_dist_center = number_format($this->accepted_dist_center, 1, '.', ' ');
        }
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

        if ($this->accepted_dist_center) {
            $this->accepted_dist_center = str_replace(' ', '', $this->accepted_dist_center);
            $this->accepted_dist_center = str_replace(',', '.', $this->accepted_dist_center);
        }

        return true;
    }

    /**
     * ------------------------------------------- Поставщик
     * Gets query for [[Supplier]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSupplier()
    {
        return $this->hasOne(LegalSubject::class, ['id' => 'supplier_id']);
    }

    /**
     * ------------------------------------------- Сеть
     * Gets query for [[Buyer]].
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

    // Возврат состава документа DeliveryItem[]
    public function getItems()
    {
        return $this->hasMany(OrderItem::class, ['order_id' => 'id']);
    }
}
