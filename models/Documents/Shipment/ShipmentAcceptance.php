<?php

namespace app\models\Documents\Shipment;

use app\models\Base;
use app\models\Documents\Acceptance\Acceptance;
use app\models\Documents\Remainder\Remainder;

/**
 * This is the model class for table "shipment_acceptance".
 *
 * @property int $shipment_id Отгрузка
 * @property int $acceptance_id Приёмка
 * @property int|null $pallet_type_id Тип паллета
 * @property float $quantity Количество
 * @property int|null $quantity_pallet Количество паллет
 * @property int|null $quantity_paks Количество тары
 * @property string|null $comment Комментарий
 *
 * @property Acceptance $acceptance
 * @property Shipment $shipment
 */
class ShipmentAcceptance extends Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shipment_acceptance';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'pallet_type_id',
                'quantity_pallet',
                'quantity_paks',
                'comment'], 'default', 'value' => null
            ],

            [[
                'shipment_id',
                'acceptance_id'], 'required'
            ],

            [[
                'shipment_id',
                'acceptance_id',
                'pallet_type_id',
                'quantity_pallet',
                'quantity_paks'], 'integer'
            ],

            [['quantity'], 'number', 'numberPattern' => '/^\d+(\.\d{1})?$/'],

            [['comment'], 'string'],

            [['shipment_id', 'acceptance_id'], 'unique', 'targetAttribute' => ['shipment_id', 'acceptance_id']],

            [['acceptance_id'], 'exist', 'skipOnError' => true, 'targetClass' => Acceptance::class, 'targetAttribute' => ['acceptance_id' => 'id']],
            [['shipment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Shipment::class, 'targetAttribute' => ['shipment_id' => 'id']],

            [[
                'quantity',
                'quantity_pallet',
                'quantity_paks'], 'testQuantity', 'skipOnEmpty' => true],
        ];
    }

    public function testQuantity($attribute, $params)
    {
        $qntFree = Remainder::getFreeByAcceptance($this->acceptance_id, $attribute);
        if (isset($this->oldAttributes[$attribute])
            && $this->oldAttributes['acceptance_id'] == $this->acceptance_id) {
            $qntFree += $this->oldAttributes[$attribute];
        }
        $qnt = 0;
        switch ($attribute) {
            case 'quantity':
                $qnt = $this->quantity;
                break;
            case 'quantity_pallet':
                $qnt = $this->quantity_pallet;
                break;
            case 'quantity_paks':
                $qnt = $this->quantity_paks;
                break;
        }
        if ($qnt > $qntFree) {
            $this->addError($attribute, 'Максимум ' . $qntFree);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'shipment_id' => 'Отгрузка',
            'acceptance_id' => 'Приёмка',
            'pallet_type_id' => 'Тип паллет',
            'quantity' => 'Кол-во',
            'quantity_pallet' => 'Кол-во паллет',
            'quantity_paks' => 'Кол-во тары',
            'comment' => 'Комментарий',
        ];
    }

    /**
     * ------------------------------------------------------------------------- Приёмка
     * Gets query for [[Acceptance]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAcceptance()
    {
        return $this->hasOne(Acceptance::class, ['id' => 'acceptance_id']);
    }

    /**
     * ------------------------------------------------------------------------- Отгрузка
     * Gets query for [[Shipment]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getShipment()
    {
        return $this->hasOne(Shipment::class, ['id' => 'shipment_id']);
    }

    /**
     * ------------------------------------------------------------------------- Всего выписано по Приёмке
     * @param $acceptance_id int Id Приёмки
     * @param $attr string Атрибут ('quantity' | 'quantity_pallet' | 'quantity_paks')
     */
    public static function getQuantityByAcceptance(int $acceptance_id, string $attr)
    {
        $qnt = self::find()
            ->where(['acceptance_id' => $acceptance_id])
            ->sum($attr);

        return $qnt ?? 0;
    }

    /**
     * ------------------------------------------------------------------------- Выписано, но не закрыто по Приёмке
     * @param $acceptance_id int Id Приёмки
     * @param $attr string Атрибут ('quantity' | 'quantity_pallet' | 'quantity_paks')
     */
    public static function getOpenByAcceptance(int $acceptance_id, string $attr)
    {
        $qnt = self::find()
            ->joinWith('shipment')
            ->where(['acceptance_id' => $acceptance_id])
            ->andWhere(['shipment.date_close' => null])
            ->sum($attr);

        return $qnt ?? 0;
    }
}
