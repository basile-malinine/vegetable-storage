<?php

namespace app\models\Documents\Moving;

use Yii;

use app\models\Assortment\Assortment;
use app\models\Base;
use app\models\Documents\Remainder\Remainder;

/**
 * This is the model class for table "moving_item".
 *
 * @property int $moving_id Перемещение
 * @property int $assortment_id Номенклатура
 * @property int $pallet_type_id Тип паллета
 * @property float $quantity Количество
 * @property int $quantity_pallet Количество паллет
 * @property int $quantity_paks Количество тары
 * @property string|null $comment Комментарий
 *
 * @property Assortment $assortment
 * @property Moving $moving
 * @property string $label
 */
class MovingItem extends Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'moving_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['comment'], 'default', 'value' => null],
            [[
                'moving_id',
                'assortment_id',
                'quantity'], 'required'
            ],

            [[
                'moving_id',
                'assortment_id',
                'pallet_type_id',
                'quantity_pallet',
                'quantity_paks'], 'integer'
            ],

            [['quantity'], 'number'],

            [['comment'], 'string'],

            [[
                'moving_id',
                'assortment_id'], 'unique', 'targetAttribute' => ['moving_id', 'assortment_id']
            ],

            [['assortment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Assortment::class, 'targetAttribute' => ['assortment_id' => 'id']],
            [['moving_id'], 'exist', 'skipOnError' => true, 'targetClass' => Moving::class, 'targetAttribute' => ['moving_id' => 'id']],

            [[
                'quantity',
                'quantity_pallet',
                'quantity_paks'], 'testQuantity', 'skipOnEmpty' => true],
        ];
    }

    public function testQuantity($attribute, $params)
    {
        $qntFree = Remainder::getFreeByAcceptance($this->moving->acceptance_id, $attribute);
        if ($this->moving->shipment) {
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
            'moving_id' => 'Перемещение',
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

        return true;
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

    /**
     * Gets query for [[Moving]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMoving()
    {
        return $this->hasOne(Moving::class, ['id' => 'moving_id']);
    }

    public function getLabel()
    {
        $quantity = $this->quantity ? $this->quantity : .0;
        if (!$this->assortment->unit->is_weight) {
            $quantity = number_format($quantity, 0, '.', '');
        }
        $shipped = .0;
        $shipment = $this->moving->shipment ?? null;
        if ($shipment && $shipment->date_close) {
                $shipped = $shipment->shipmentAcceptances[0]->quantity;
        }

        return $this->assortment->name
            . ' ' . $quantity
            . ' (' . $this->assortment->unit->name . ')'
            . ', Отгружено: ' . $shipped;
    }

    // Возвращает true, если есть изменения.
    public function isChanges(): bool
    {
        return Yii::$app->session->has('old_values');
    }
}
