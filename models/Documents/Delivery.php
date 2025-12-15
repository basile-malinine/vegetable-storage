<?php

namespace app\models\Documents;

use app\models\Documents\Delivery\DeliveryItem;
use Yii;

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
 */
class Delivery extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'delivery';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['stock_id', 'executor_id', 'shipment_date', 'unloading_date', 'payment_term', 'date_close', 'comment', 'created_by', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['type_id', 'supplier_id', 'company_own_id', 'purchasing_mng_id', 'purchasing_agent_id', 'sales_mng_id', 'support_mng_id', 'currency_id', 'payment_method_id', 'transport_affiliation_id'], 'required'],
            [['type_id', 'supplier_id', 'company_own_id', 'stock_id', 'executor_id', 'purchasing_mng_id', 'purchasing_agent_id', 'sales_mng_id', 'support_mng_id', 'currency_id', 'payment_method_id', 'transport_affiliation_id', 'created_by'], 'integer'],
            [['shipment_date', 'unloading_date', 'payment_term', 'date_close', 'created_at', 'updated_at'], 'safe'],
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
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type_id' => 'Type ID',
            'supplier_id' => 'Supplier ID',
            'company_own_id' => 'Company Own ID',
            'stock_id' => 'Stock ID',
            'executor_id' => 'Executor ID',
            'purchasing_mng_id' => 'Purchasing Mng ID',
            'purchasing_agent_id' => 'Purchasing Agent ID',
            'sales_mng_id' => 'Sales Mng ID',
            'support_mng_id' => 'Support Mng ID',
            'currency_id' => 'Currency ID',
            'payment_method_id' => 'Payment Method ID',
            'transport_affiliation_id' => 'Transport Affiliation ID',
            'shipment_date' => 'Shipment Date',
            'unloading_date' => 'Unloading Date',
            'payment_term' => 'Payment Term',
            'date_close' => 'Date Close',
            'comment' => 'Comment',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[CompanyOwn]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCompanyOwn()
    {
        return $this->hasOne(LegalSubject::class, ['id' => 'company_own_id']);
    }

    /**
     * Gets query for [[Currency]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency()
    {
        return $this->hasOne(Currency::class, ['id' => 'currency_id']);
    }

    /**
     * Gets query for [[DeliveryItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDeliveryItems()
    {
        return $this->hasMany(DeliveryItem::class, ['delivery_id' => 'id']);
    }

    /**
     * Gets query for [[Executor]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExecutor()
    {
        return $this->hasOne(Manager::class, ['id' => 'executor_id']);
    }

    /**
     * Gets query for [[PaymentMethod]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentMethod()
    {
        return $this->hasOne(PaymentMethod::class, ['id' => 'payment_method_id']);
    }

    /**
     * Gets query for [[PurchasingAgent]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPurchasingAgent()
    {
        return $this->hasOne(Manager::class, ['id' => 'purchasing_agent_id']);
    }

    /**
     * Gets query for [[PurchasingMng]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPurchasingMng()
    {
        return $this->hasOne(Manager::class, ['id' => 'purchasing_mng_id']);
    }

    /**
     * Gets query for [[SalesMng]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSalesMng()
    {
        return $this->hasOne(Manager::class, ['id' => 'sales_mng_id']);
    }

    /**
     * Gets query for [[Stock]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStock()
    {
        return $this->hasOne(Stock::class, ['id' => 'stock_id']);
    }

    /**
     * Gets query for [[Supplier]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSupplier()
    {
        return $this->hasOne(LegalSubject::class, ['id' => 'supplier_id']);
    }

    /**
     * Gets query for [[SupportMng]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSupportMng()
    {
        return $this->hasOne(Manager::class, ['id' => 'support_mng_id']);
    }
}
