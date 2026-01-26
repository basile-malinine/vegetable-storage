<?php

namespace app\models\Remainder;

use app\models\Documents\Shipment\ShipmentAcceptance;
use yii\db\Exception;

use app\models\Assortment\Assortment;
use app\models\Base;
use app\models\Documents\Acceptance\Acceptance;
use app\models\LegalSubject\LegalSubject;
use app\models\PalletType\PalletType;
use app\models\Stock\Stock;

/**
 * This is the model class for table "remainder".
 *
 * @property int|null $acceptance_id Приёмка
 * @property int|null $company_own_id Предприятие
 * @property int|null $stock_id Склад
 * @property int|null $assortment_id Номенклатура
 * @property int|null $pallet_type_id Тип паллета
 * @property float $quantity Количество
 * @property int|null $quantity_pallet Количество паллет
 * @property int|null $quantity_paks Количество тары
 * @property string|null $comment Комментарий
 * @property int|null $created_by Создатель
 * @property string|null $created_at Дата создания
 * @property string|null $updated_at Дата обновления
 *
 * @property Acceptance $acceptance
 * @property Assortment $assortment
 * @property LegalSubject $companyOwn
 * @property PalletType $palletType
 * @property Stock $stock
 * @property string $label
 */
class Remainder extends Base
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'remainder';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['acceptance_id', 'company_own_id', 'stock_id', 'assortment_id', 'pallet_type_id', 'quantity_pallet', 'quantity_paks', 'comment', 'created_by', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['acceptance_id', 'company_own_id', 'stock_id', 'assortment_id', 'pallet_type_id', 'quantity_pallet', 'quantity_paks', 'created_by'], 'integer'],
            [['quantity'], 'required'],
            [['quantity'], 'number'],
            [['comment'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['acceptance_id'], 'exist', 'skipOnError' => true, 'targetClass' => Acceptance::class, 'targetAttribute' => ['acceptance_id' => 'id']],
            [['assortment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Assortment::class, 'targetAttribute' => ['assortment_id' => 'id']],
            [['company_own_id'], 'exist', 'skipOnError' => true, 'targetClass' => LegalSubject::class, 'targetAttribute' => ['company_own_id' => 'id']],
            [['pallet_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => PalletType::class, 'targetAttribute' => ['pallet_type_id' => 'id']],
            [['stock_id'], 'exist', 'skipOnError' => true, 'targetClass' => Stock::class, 'targetAttribute' => ['stock_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'acceptance_id' => 'Приёмка',
            'company_own_id' => 'Предприятие',
            'stock_id' => 'Склад',
            'assortment_id' => 'Номенклатура',
            'pallet_type_id' => 'Тип паллет',
            'quantity' => 'Кол-во',
            'quantity_pallet' => 'Кол-во паллет',
            'quantity_paks' => 'Кол-во тары',
            'comment' => 'Комментарий',
            'created_by' => 'Создатель',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
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
     * Gets query for [[PalletType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPalletType()
    {
        return $this->hasOne(PalletType::class, ['id' => 'pallet_type_id']);
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
     * Gets query for [[ShipmentAcceptance]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getShipments()
    {
        return $this->hasMany(ShipmentAcceptance::class, ['acceptance_id' => 'acceptance_id']);
    }

    // Добавить Поставку на остаток.
    public static function addAcceptance(Acceptance $acceptance): bool
    {
        $remainder = new Remainder();
        $remainder->acceptance_id = $acceptance->id;
        $remainder->company_own_id = $acceptance->company_own_id;
        $remainder->stock_id = $acceptance->stock_id;
        $acceptanceItem = $acceptance->items[0];
        $remainder->assortment_id = $acceptanceItem->assortment_id;
        $remainder->quantity = $acceptanceItem->quantity;
        $remainder->pallet_type_id = $acceptanceItem->pallet_type_id;
        $remainder->quantity_pallet = $acceptanceItem->quantity_pallet;
        $remainder->quantity_paks = $acceptanceItem->quantity_paks;
        $remainder->comment = $acceptanceItem->comment;

        try {
            $remainder->save();
        } catch (Exception $e) {
            \Yii::$app->session->setFlash('error', 'Приёмку не удалось добавить на остаток.');
            return false;
        }
        \Yii::$app->session->setFlash('success', 'Приёмка добавлена на остаток.');

        return true;
    }

    // Снять Поставку с остатка.
    public static function removeAcceptance(Acceptance $acceptance): bool
    {
        if ($acceptance->shipments) {
            \Yii::$app->session->setFlash('error',
                'По Приёмке есть Отгрузки. Снять с остатка не возможно.');
            return false;
        }

        $remainder = self::findOne(['acceptance_id' => $acceptance->id]);
        if ($remainder) {
            if ($remainder->delete()) {
                \Yii::$app->session->setFlash('success', 'Приёмка снята с остатка.');
                return true;
            }
        }

        \Yii::$app->session->setFlash('error',
            'Приёмка на остатке не найдена.');

        return false;
    }

    /**
     * Список Поставок
     *
     * @param $company_own_id integer Предприятие
     * @param $stock_id integer Склад
     * @param $assortment_ids integer|array Номенклатура
     * @param $exceptIds integer[]|null Исключить ID Поставок
     * @return array Список Поставок
     */
    public static function getListAcceptance(
        int $company_own_id, int $stock_id, int|array $assortment_ids, array $exceptIds = []): array
    {
        $listIds = self::find()
            ->select(['acceptance_id'])
            ->where([
                'company_own_id' => $company_own_id,
                'stock_id' => $stock_id,
                'assortment_id' => $assortment_ids,
            ])
            ->andWhere(['NOT IN', 'acceptance_id', $exceptIds])
            ->indexBy('acceptance_id')
            ->column();

        $list = [];
        foreach ($listIds as $id) {
            $model = self::findOne(['acceptance_id' => $id]);
            $list[$id] = $model->acceptance->label
                . ' остаток: ' . $model->quantity;
        }

        return $list;
    }

    // Отгрузить с Приёмки
    public static function shippedFromAcceptance(ShipmentAcceptance $shipmentAcceptance): bool
    {
        $remainder = Remainder::findOne(['acceptance_id' => $shipmentAcceptance->acceptance->id]);
        $remainder->quantity -= $shipmentAcceptance->quantity;
        if ($remainder->quantity < .1) {
            $remainder->delete();
            return true;
        }
        $remainder->quantity_pallet -= $shipmentAcceptance->quantity_pallet;
        if ($remainder->quantity_pallet < 1) {
            $remainder->pallet_type_id = null;
            $remainder->quantity_pallet = null;
        }
        $remainder->quantity_paks -= $shipmentAcceptance->quantity_paks;
        if ($remainder->quantity_paks < 1) {
            $remainder->quantity_paks = null;
        }
        $remainder->save();

        return true;
    }

    // Вернуть с Отгрузки
    public static function acceptanceFromShipped(ShipmentAcceptance $shipmentAcceptance): bool
    {
        $remainder = Remainder::findOne(['acceptance_id' => $shipmentAcceptance->acceptance->id]);
        if (!$remainder) {
            $remainder = new Remainder();
            $remainder->acceptance_id = $shipmentAcceptance->acceptance->id;
            $remainder->company_own_id = $shipmentAcceptance->acceptance->company_own_id;
            $remainder->stock_id = $shipmentAcceptance->acceptance->stock_id;
            $remainder->assortment_id = $shipmentAcceptance->shipment->parentDoc->items[0]->assortment_id;
            $remainder->quantity = $shipmentAcceptance->quantity;
            $remainder->pallet_type_id = $shipmentAcceptance->pallet_type_id;
            $remainder->quantity_pallet = $shipmentAcceptance->quantity_pallet;
            $remainder->quantity_paks = $shipmentAcceptance->quantity_paks;
            $remainder->comment = $shipmentAcceptance->comment;

            try {
                $remainder->save();
            } catch (Exception $e) {
                \Yii::$app->session->setFlash('error', 'Приёмку не удалось добавить на остаток.');
                return false;
            }
            \Yii::$app->session->setFlash('success', 'Приёмка добавлена на остаток.');

            return true;
        }
        $remainder->quantity += $shipmentAcceptance->quantity;
        $remainder->pallet_type_id = $shipmentAcceptance->pallet_type_id;
        $remainder->quantity_pallet += $shipmentAcceptance->quantity_pallet;
        $remainder->quantity_paks += $shipmentAcceptance->quantity_paks;
        $remainder->save();

        return true;
    }

    public function getLabel()
    {
        $quantity = (bool)$this->assortment->unit->is_weight
            ? number_format($this->quantity, 1, '.', '')
            : number_format($this->quantity, 0, '.', '');

        $assortment = $this->assortment->name
            . ' ' . $quantity
            . ' (' . $this->assortment->unit->name . ')';

        return '№' . $this->acceptance_id
            . ' ' . $this->acceptance->acceptance_date
            . ', ' . $this->companyOwn->name
            . ', ' . $this->stock->name
            . ', ' . $assortment;
    }

    public static function getListForMoving(): array
    {
        $listIds = self::find()
            ->select(['acceptance_id'])
            ->indexBy('acceptance_id')
            ->column();

        $list = [];
        foreach ($listIds as $id) {
            $model = self::findOne(['acceptance_id' => $id]);
            $list[$id] = $model->label;
        }

        return $list;
    }
}
