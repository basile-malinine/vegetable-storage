<?php

namespace app\models\Documents\Shipment;

use app\models\Assortment\Assortment;
use app\models\Base;
use app\models\Documents\Acceptance\Acceptance;
use app\models\Documents\Delivery\Delivery;
use app\models\Documents\Moving\Moving;
use app\models\Documents\Order\Order;
use app\models\LegalSubject\LegalSubject;
use app\models\Stock\Stock;
use DateTime;
use Yii;

/**
 * This is the model class for table "shipment".
 *
 * @property int $id
 * @property int $type_id Тип отгрузки
 * @property int|null $delivery_id Поставка
 * @property int $parent_doc_id Старший документ
 * @property int $company_own_id Предприятие
 * @property int $stock_id Склад
 * @property string|null $shipment_date Дата закрытия
 * @property string|null $date_close Дата закрытия
 * @property string|null $comment Комментарий
 * @property int|null $created_by Создатель
 * @property string|null $created_at Дата создания
 * @property string|null $updated_at Дата обновления
 *
 * @property mixed $parentDoc
 * @property Acceptance[] $acceptances
 * @property Assortment[] $assortments
 * @property LegalSubject $companyOwn
 * @property Delivery $delivery
 * @property ShipmentAcceptance[] $shipmentAcceptances
 * @property Stock $stock
 */
class Shipment extends Base
{
    const TYPE_ORDER = 1;
    const TYPE_MOVING = 2;
    const TYPE_LIST = [
        self::TYPE_ORDER => 'Заказ',
        self::TYPE_MOVING => 'Перемещение',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shipment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'delivery_id',
                'date_close',
                'comment',
                'created_by',
                'created_at',
                'updated_at'], 'default', 'value' => null
            ],

            [[
                'type_id',
                'parent_doc_id',
                'company_own_id',
                'stock_id',
                'shipment_date'], 'required'
            ],

            [[
                'type_id',
                'delivery_id',
                'parent_doc_id',
                'company_own_id',
                'stock_id',
                'created_by'], 'integer'
            ],

            [[
                'shipment_date',
                'date_close',
                'created_at',
                'updated_at'], 'safe'
            ],

            [['comment'], 'string'],

            [['company_own_id'], 'exist', 'skipOnError' => true, 'targetClass' => LegalSubject::class, 'targetAttribute' => ['company_own_id' => 'id']],
            [['delivery_id'], 'exist', 'skipOnError' => true, 'targetClass' => Delivery::class, 'targetAttribute' => ['delivery_id' => 'id']],
            [['stock_id'], 'exist', 'skipOnError' => true, 'targetClass' => Stock::class, 'targetAttribute' => ['stock_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type_id' => 'Тип отгрузки',
            'delivery_id' => 'Поставка',
            'parent_doc_id' => 'По документу',
            'company_own_id' => 'Предприятие',
            'stock_id' => 'Склад',
            'shipment_date' => 'Дата отгрузки',
            'date_close' => 'Дата закрытия',
            'comment' => 'Комментарий',
            'created_by' => 'Создатель',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    public function afterFind()
    {
        parent::afterFind();

        $this->shipment_date = $this->shipment_date
            ? date('d.m.Y', strtotime($this->shipment_date)) : null;
    }

    public function beforeSave($insert)
    {
        $this->shipment_date = $this->shipment_date
            ? date('Y-m-d H:i', strtotime($this->shipment_date)) : null;

        $now = (new DateTime('now'))->format('Y-m-d H:i');
        if ($insert) {
            $this->created_by = Yii::$app->user->id;
            $this->created_at = $now;
        }
        $this->updated_at = $now;

        return true;
    }

    /**
     * ------------------------------------------- Старший документ
     * Gets query for [[ParentDoc]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParentDoc()
    {
        switch ($this->type_id) {
            case self::TYPE_ORDER:
                return $this->hasOne(Order::class, ['id' => 'parent_doc_id']);
                break;
            case self::TYPE_MOVING:
                return $this->hasOne(Moving::class, ['id' => 'parent_doc_id']);
                break;
            default:
                return null;
        }
    }
    /**
     * Gets query for [[Acceptances]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAcceptances()
    {
        return $this->hasMany(Acceptance::class, ['id' => 'acceptance_id'])->viaTable('shipment_acceptance', ['shipment_id' => 'id']);
    }

    /**
     * Gets query for [[Assortments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAssortments()
    {
        return $this->hasMany(Assortment::class, ['id' => 'assortment_id'])->viaTable('shipment_item', ['shipment_id' => 'id']);
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
     * Gets query for [[Delivery]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDelivery()
    {
        return $this->hasOne(Delivery::class, ['id' => 'delivery_id']);
    }

    /**
     * Gets query for [[ShipmentAcceptances]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getShipmentAcceptances()
    {
        return $this->hasMany(ShipmentAcceptance::class, ['shipment_id' => 'id']);
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
}
