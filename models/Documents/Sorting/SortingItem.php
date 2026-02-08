<?php

namespace app\models\Documents\Sorting;

use app\models\Documents\Remainder\Remainder;
use Yii;

use app\models\Assortment\Assortment;
use app\models\Base;
use app\models\PalletType\PalletType;
use app\models\Quality\Quality;

/**
 * This is the model class for table "sorting_item".
 *
 * @property int $sorting_id Переработка
 * @property int $assortment_id Номенклатура
 * @property int $quality_id Качество
 * @property int $pallet_type_id Тип палет
 * @property float $quantity Количество
 * @property int|null $quantity_pallet Количество паллет
 * @property int|null $quantity_paks Количество тары
 * @property string|null $comment Комментарий
 *
 * @property Assortment $assortment
 * @property PalletType $palletType
 * @property Quality $quality
 * @property Sorting $sorting
 * @property string $label
 */
class SortingItem extends Base
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sorting_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['quantity_pallet', 'quantity_paks', 'comment'], 'default', 'value' => null],
            [['sorting_id', 'assortment_id', 'quantity'], 'required'],
            [['sorting_id', 'assortment_id', 'quality_id', 'pallet_type_id', 'quantity_pallet', 'quantity_paks'], 'integer'],
            [['quantity'], 'number'],
            [['comment'], 'string'],
            [['sorting_id', 'assortment_id'], 'unique', 'targetAttribute' => ['sorting_id', 'assortment_id']],
            [['assortment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Assortment::class, 'targetAttribute' => ['assortment_id' => 'id']],
            [['pallet_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => PalletType::class, 'targetAttribute' => ['pallet_type_id' => 'id']],
            [['quality_id'], 'exist', 'skipOnError' => true, 'targetClass' => Quality::class, 'targetAttribute' => ['quality_id' => 'id']],
            [['sorting_id'], 'exist', 'skipOnError' => true, 'targetClass' => Sorting::class, 'targetAttribute' => ['sorting_id' => 'id']],

            [[
                'quantity',
                'quantity_pallet',
                'quantity_paks'], 'testQuantity', 'skipOnEmpty' => true],
        ];
    }

    public function testQuantity($attribute, $params)
    {
        $qntFree = Remainder::getFreeByAcceptance($this->sorting->acceptance_id, $attribute);
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
            'sorting_id' => 'Переработка',
            'assortment_id' => 'Номенклатура',
            'quality_id' => 'Качество',
            'pallet_type_id' => 'Тип палет',
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
     * Gets query for [[PalletType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPalletType()
    {
        return $this->hasOne(PalletType::class, ['id' => 'pallet_type_id']);
    }

    /**
     * Gets query for [[Quality]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getQuality()
    {
        return $this->hasOne(Quality::class, ['id' => 'quality_id']);
    }

    /**
     * Gets query for [[Sorting]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSorting()
    {
        return $this->hasOne(Sorting::class, ['id' => 'sorting_id']);
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
        return Yii::$app->session->has('old_values');
    }
}
