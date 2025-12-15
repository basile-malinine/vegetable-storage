<?php

namespace app\models\Documents\Delivery;

use app\models\Assortment\Assortment;
use app\models\Base;

/**
 * This is the model class for table "delivery_item".
 *
 * @property int $id
 * @property int $delivery_id Поставка
 * @property int $assortment_id Номенклатура
 * @property float $shipped Отправлено
 * @property float $price Цена
 * @property int $unloading_type_id Тип выгрузки
 * @property int|null $quality_id Качество
 * @property float|null $cost_before_stock Себестоимость до склада
 * @property float|null $price_total Общая стоимость в поставке
 * @property float|null $profit_expected Ожидаемая прибыль
 * @property string|null $work_plan План по работе
 *
 * @property Assortment $assortment
 * @property Delivery $delivery
 */
class DeliveryItem extends Base
{
    // Тип выгрузки ------------------------------------------------------------
    const UNLOADING_TYPE_PALLET = 1;
    const UNLOADING_TYPE_BULK_WITHOUT_PACK = 2;
    const UNLOADING_TYPE_BULK_WITH_PACK = 3;
    const UNLOADING_TYPE_LIST = [
        self::UNLOADING_TYPE_PALLET => 'На паллетах',
        self::UNLOADING_TYPE_BULK_WITHOUT_PACK => 'Навалом, без тары',
        self::UNLOADING_TYPE_BULK_WITH_PACK => 'Навалом, в таре',
    ];

    // Качество ----------------------------------------------------------------
    const QUALITY_AGREED = 1;
    const QUALITY_NOT_AGREED = 2;
    const QUALITY_LIST = [
        self::QUALITY_AGREED => 'Согласовано',
        self::QUALITY_NOT_AGREED => 'Не согласовано',
    ];

    public static function tableName()
    {
        return 'delivery_item';
    }

    public function rules()
    {
        return [
            [[
                'quality_id',
                'cost_before_stock',
                'price_total',
                'profit_expected',
                'work_plan'], 'default', 'value' => null
            ],

            [[
                'delivery_id',
                'assortment_id',
                'shipped',
                'price',
                'price_total',
                'unloading_type_id'], 'required'
            ],

            [[
                'delivery_id',
                'assortment_id',
                'unloading_type_id',
                'quality_id'], 'integer'
            ],

            [['shipped'], 'number', 'numberPattern' => '/^\d+(\.\d{1})?$/'],

            [[
                'price',
                'cost_before_stock',
                'price_total',
                'profit_expected'], 'number', 'numberPattern' => '/^\d+(\.\d{2})?$/'
            ],

            [['work_plan'], 'safe'],

            [['assortment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Assortment::class, 'targetAttribute' => ['assortment_id' => 'id']],
            [['delivery_id'], 'exist', 'skipOnError' => true, 'targetClass' => Delivery::class, 'targetAttribute' => ['delivery_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'delivery_id' => 'Поставка',
            'assortment_id' => 'Номенклатура',
            'shipped' => 'Отправлено',
            'price' => 'Цена',
            'unloading_type_id' => 'Тип выгрузки',
            'quality_id' => 'Качество',
            'cost_before_stock' => 'Себестоимость до склада',
            'price_total' => 'Общая стоимость',
            'profit_expected' => 'Ожидаемая прибыль',
            'work_plan' => 'План по работе',
        ];
    }

    public function getAssortment()
    {
        return $this->hasOne(Assortment::class, ['id' => 'assortment_id']);
    }

    public function getDelivery()
    {
        return $this->hasOne(Delivery::class, ['id' => 'delivery_id']);
    }
}
