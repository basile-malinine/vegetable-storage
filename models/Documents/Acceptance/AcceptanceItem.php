<?php

namespace app\models\Documents\Acceptance;

use app\models\Assortment\Assortment;
use app\models\Base;
use app\models\Documents\Remainder\Remainder;
use app\models\Documents\Shipment\ShipmentAcceptance;
use app\models\Quality\Quality;
use Yii;

/**
 * This is the model class for table "acceptance_item".
 *
 * @property int $acceptance_id Приёмка
 * @property int $assortment_id Номенклатура
 * @property int $quality_id Качество
 * @property int $pallet_type_id Тип паллета
 * @property float $quantity Количество
 * @property int $quantity_pallet Количество паллет
 * @property int $quantity_paks Количество тары
 * @property string|null $comment Комментарий
 *
 * @property Acceptance $acceptance
 * @property Assortment $assortment
 * @property Quality $quality
 * @property string $label
 */
class AcceptanceItem extends Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'acceptance_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['comment'], 'default', 'value' => null],

            [[
                'acceptance_id',
                'assortment_id'], 'required'
            ],

            [[
                'acceptance_id',
                'assortment_id',
                'pallet_type_id',
                'quality_id',
                'quantity_pallet',
                'quantity_paks'], 'integer'
            ],


            [['quantity'], 'number', 'numberPattern' => '/^\d+(\.\d{1})?$/'],

            [['comment'], 'string'],

            [[
                'acceptance_id',
                'assortment_id'], 'unique', 'targetAttribute' => ['acceptance_id', 'assortment_id']
            ],

            [['acceptance_id'], 'exist', 'skipOnError' => true, 'targetClass' => Acceptance::class, 'targetAttribute' => ['acceptance_id' => 'id']],
            [['assortment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Assortment::class, 'targetAttribute' => ['assortment_id' => 'id']],

            [[
                'quantity',
                'quantity_pallet',
                'quantity_paks'], 'testQuantity', 'skipOnEmpty' => true],
        ];
    }

    public function testQuantity($attribute, $params)
    {
        $qntShipment = ShipmentAcceptance::getQuantityByAcceptance($this->acceptance_id, $attribute);
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
        if ($qnt < $qntShipment) {
            $this->addError($attribute, 'Минимум ' . $qntShipment);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'acceptance_id' => 'Приёмка',
            'assortment_id' => 'Номенклатура',
            'pallet_type_id' => 'Тип паллет',
            'quantity' => 'Количество',
            'quantity_pallet' => 'Кол-во паллет',
            'quantity_paks' => 'Кол-во тары',
            'comment' => 'Комментарий',
        ];
    }

    public function beforeSave($insert)
    {
        if ($this->acceptance->type_id === Acceptance::TYPE_DELIVERY) {
            $session = Yii::$app->session;
            if ($session->has('old_values')) {
                $session->remove('old_values');
            }
            if (!$insert) {
                // Если есть изменения, пишем в сессию старые значения.
                if ($this->oldAttributes['quantity'] != $this->quantity
                    || $this->oldAttributes['quantity_pallet'] != $this->quantity_pallet
                    || $this->oldAttributes['quantity_paks'] != $this->quantity_paks) {
                    $session->set('old_values', [
                        'quantity' => $this->oldAttributes['quantity'],
                        'quantity_pallet' => $this->oldAttributes['quantity_pallet'],
                        'quantity_paks' => $this->oldAttributes['quantity_paks'],
                    ]);
                }
            }
        }

        return true;
    }

    /**
     * Gets query for [[Acceptance]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAcceptance()
    {
        return $this->hasOne(Acceptance::class, ['id' => 'acceptance_id']);
    }

    /**
     * Gets query for [[Assortment]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAssortment()
    {
        return $this->hasOne(Assortment::class, ['id' => 'assortment_id']);
    }

    public function getQuality()
    {
        return $this->hasOne(Quality::class, ['id' => 'quality_id']);
    }

    public function getLabel()
    {
        $quantity = (bool)$this->assortment->unit->is_weight
            ? number_format($this->quantity, 1, '.', '')
            : number_format($this->quantity, 0, '.', '');

        return $this->assortment->name
            . ' ' . $quantity
            . ' (' . $this->assortment->unit->name . ')';
    }

    // Возвращает true, если есть изменения.
    public function isChanges(): bool
    {
        return Yii::$app->session->has('old_values');
    }
}
