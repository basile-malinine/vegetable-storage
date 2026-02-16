<?php

namespace app\models\Documents\Increase;

use Yii;

use app\models\Assortment\Assortment;
use app\models\Base;
use app\models\Documents\Remainder\Remainder;

/**
 * This is the model class for table "increase_item".
 *
 * @property int $increase_id Оприходование
 * @property int $assortment_id Номенклатура
 * @property int $quality_id Качество
 * @property int|null $pallet_type_id Тип паллета
 * @property float $quantity Количество
 * @property int|null $quantity_pallet Количество паллет
 * @property int|null $quantity_paks Количество тары
 * @property string|null $comment Комментарий
 *
 * @property Assortment $assortment
 * @property Increase $increase
 * @property string $label
 */
class IncreaseItem extends Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'increase_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pallet_type_id', 'quantity_pallet', 'quantity_paks', 'comment'], 'default', 'value' => null],
            [['increase_id', 'assortment_id', 'quantity'], 'required'],
            [['increase_id', 'assortment_id', 'quality_id', 'pallet_type_id', 'quantity_pallet', 'quantity_paks'], 'integer'],
            [['quantity'], 'number'],
            [['comment'], 'string'],
            [['increase_id', 'assortment_id'], 'unique', 'targetAttribute' => ['increase_id', 'assortment_id']],
            [['assortment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Assortment::class, 'targetAttribute' => ['assortment_id' => 'id']],
            [['increase_id'], 'exist', 'skipOnError' => true, 'targetClass' => Increase::class, 'targetAttribute' => ['increase_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'increase_id' => 'Оприходование',
            'assortment_id' => 'Номенклатура',
            'quality_id' => 'Качество',
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
        if ($session->has('increase.old_values')) {
            $session->remove('increase.old_values');
        }
        if (!$insert) {
            // Если есть изменения, пишем в сессию старые значения.
            if ($this->oldAttributes['quantity'] != $this->quantity
                || $this->oldAttributes['quantity_pallet'] != $this->quantity_pallet
                || $this->oldAttributes['quantity_paks'] != $this->quantity_paks) {
                $session->set('increase.old_values', [
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
     * Gets query for [[Increase]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIncrease()
    {
        return $this->hasOne(Increase::class, ['id' => 'increase_id']);
    }

    public function getLabel()
    {
        $quantity = $this->quantity ? $this->quantity : .0;
        if (!$this->assortment->unit->is_weight) {
            $quantity = number_format($quantity, 0, '.', '');
        }
        $accepted = .0;
        $newAcceptance = $this->increase->newAcceptance ?? null;
        if ($newAcceptance && $newAcceptance->date_close) {
            $accepted = $newAcceptance->items[0]->quantity;
        }

        return $this->assortment->name
            . ' ' . $quantity
            . ' (' . $this->assortment->unit->name . ')'
            . ', Принято: ' . $accepted;
    }

    // Возвращает true, если есть изменения.
    public function isChanges(): bool
    {
        return Yii::$app->session->has('increase.old_values');
    }
}
