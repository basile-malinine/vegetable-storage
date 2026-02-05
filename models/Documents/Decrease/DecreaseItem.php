<?php

namespace app\models\Documents\Decrease;

use Yii;

use app\models\Assortment\Assortment;
use app\models\Base;
use app\models\Documents\Remainder\Remainder;

/**
 * This is the model class for table "decrease_item".
 *
 * @property int $decrease_id Списание
 * @property int $assortment_id Номенклатура
 * @property int|null $pallet_type_id Тип паллета
 * @property float $quantity Количество
 * @property int|null $quantity_pallet Количество паллет
 * @property int|null $quantity_paks Количество тары
 * @property string|null $comment Комментарий
 *
 * @property Assortment $assortment
 * @property Decrease $decrease
 * @property string $label
 */
class DecreaseItem extends Base
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'decrease_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pallet_type_id', 'quantity_pallet', 'quantity_paks', 'comment'], 'default', 'value' => null],
            [['decrease_id', 'assortment_id', 'quantity'], 'required'],
            [['decrease_id', 'assortment_id', 'pallet_type_id', 'quantity_pallet', 'quantity_paks'], 'integer'],
            [['quantity'], 'number'],
            [['comment'], 'string'],
            [['decrease_id', 'assortment_id'], 'unique', 'targetAttribute' => ['decrease_id', 'assortment_id']],
            [['assortment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Assortment::class, 'targetAttribute' => ['assortment_id' => 'id']],
            [['decrease_id'], 'exist', 'skipOnError' => true, 'targetClass' => Decrease::class, 'targetAttribute' => ['decrease_id' => 'id']],

            [[
                'quantity',
                'quantity_pallet',
                'quantity_paks'], 'testQuantity', 'skipOnEmpty' => true],
        ];
    }

    public function testQuantity($attribute, $params)
    {
        $qntFree = Remainder::getFreeByAcceptance($this->decrease->acceptance_id, $attribute);
        $qnt = 0;
        $session = Yii::$app->session;
        switch ($attribute) {
            case 'quantity':
                $qntFree = $session->has('free-qnt') ? $session->get('free-qnt')['quantity'] : $qntFree;
                $qnt = $this->quantity;
                break;
            case 'quantity_pallet':
                $qntFree = $session->has('free-qnt') ? $session->get('free-qnt')['quantity_pallet'] : $qntFree;
                $qnt = $this->quantity_pallet;
                break;
            case 'quantity_paks':
                $qntFree = $session->has('free-qnt') ? $session->get('free-qnt')['quantity_paks'] : $qntFree;
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
            'decrease_id' => 'Списание',
            'assortment_id' => 'Номенклатура',
            'pallet_type_id' => 'Тип паллета',
            'quantity' => 'Количество',
            'quantity_pallet' => 'Количество паллет',
            'quantity_paks' => 'Количество тары',
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
     * Gets query for [[Decrease]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDecrease()
    {
        return $this->hasOne(Decrease::class, ['id' => 'decrease_id']);
    }

    public function getLabel()
    {
        $quantity = $this->quantity ? $this->quantity : .0;
        if (!$this->assortment->unit->is_weight) {
            $quantity = number_format($quantity, 0, '.', '');
        }
        $shipped = .0;
        $shipment = $this->decrease->shipment ?? null;
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
