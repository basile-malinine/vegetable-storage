<?php

namespace app\models\Documents\Merging;

use app\models\Documents\Acceptance\AcceptanceItem;
use app\models\Documents\Remainder\Remainder;
use app\models\Documents\Shipment\Shipment;
use DateTime;

use Yii;
use yii\helpers\ArrayHelper;

use app\models\Assortment\Assortment;
use app\models\Base;
use app\models\Documents\Acceptance\Acceptance;
use app\models\LegalSubject\LegalSubject;
use app\models\Stock\Stock;

/**
 * This is the model class for table "merging".
 *
 * @property int $id
 * @property int $assortment_id Номенклатура
 * @property int $company_own_id Предприятие
 * @property int $stock_id Склад
 * @property string $date Дата объединения
 * @property string|null $date_close Дата закрытия
 * @property string|null $comment Комментарий
 * @property int|null $created_by Создатель
 * @property string|null $created_at Дата создания
 * @property string|null $updated_at Дата обновления
 *
 * @property Acceptance[] $sourceAcceptances
 * @property Acceptance $resultAcceptance
 * @property Assortment $assortment
 * @property LegalSubject $companyOwn
 * @property MergingItem[] $items
 * @property Shipment[] $shipments
 * @property Stock $stock
 *
 * @property string $label
 * @property string $shortLabel
 *
 */
class Merging extends Base
{
    public string $assortmentInfo = '';
    public float|null $quantity = null;
    public float|null $quantity_pallet = null;
    public float|null $quantity_paks = null;
    public float|null $weight = null;
    public string $errorItems = ''; // Поле для вывода ошибки

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'merging';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'date_close',
                'comment',
                'created_by',
                'created_at',
                'updated_at'], 'default', 'value' => null],

            [[
                'assortment_id',
                'company_own_id',
                'stock_id',
                'date'], 'required'],

            [[
                'assortment_id',
                'company_own_id',
                'stock_id',
                'created_by'], 'integer'],

            [[
                'date',
                'date_close',
                'created_at',
                'updated_at'], 'safe'],

            [['comment'], 'string'],

            [['assortment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Assortment::class, 'targetAttribute' => ['assortment_id' => 'id']],
            [['company_own_id'], 'exist', 'skipOnError' => true, 'targetClass' => LegalSubject::class, 'targetAttribute' => ['company_own_id' => 'id']],
            [['stock_id'], 'exist', 'skipOnError' => true, 'targetClass' => Stock::class, 'targetAttribute' => ['stock_id' => 'id']],

            [['id'], 'testItem']  // Проверка корректности состава Объединения
        ];
    }

    public function testItem($attribute)
    {
        if (count($this->items) < 2) {
            $this->addError('errorItems', 'Необходимо добавить минимум 2 позиции для Объединения.');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'assortment_id' => 'Номенклатура',
            'company_own_id' => 'Предприятие',
            'stock_id' => 'Склад',
            'date' => 'Дата',
            'date_close' => 'Дата закрытия',
            'comment' => 'Комментарий',
            'created_by' => 'Создатель',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',

            'assortmentInfo' => 'Единица / Вес',
            'quantity' => 'Кол-во',
            'quantity_pallet' => 'Кол-во паллет',
            'quantity_paks' => 'Кол-во тары',
            'weight' => 'Вес позиции',
        ];
    }

    public function afterFind()
    {
        $this->date = $this->date
            ? date('d.m.Y', strtotime($this->date)) : null;
        // Если выбрана Номенклатурная позиция
        if ($this->assortment) {
            $this->assortmentInfo = $this->assortment->unit->name . ' / ' . $this->assortment->weight;
        }
        // Если есть состав
        if ($this->items) {
            $this->quantity = array_sum(ArrayHelper::getColumn($this->items, 'quantity'));
            $this->quantity_pallet = array_sum(ArrayHelper::getColumn($this->items, 'quantity_pallet'));
            $this->quantity_paks = array_sum(ArrayHelper::getColumn($this->items, 'quantity_paks'));
            $this->weight = $this->quantity * $this->assortment->weight;
        }
    }

    public function beforeSave($insert)
    {
        if ($insert) {
            $assortmentIds = Assortment::find()->select('assortment.id')
                ->joinWith('unit')
                ->where(['assortment_group_id' => $this->assortment->assortment_group_id])
                ->andWhere(['unit.is_weight' => $this->assortment->unit->is_weight])
                ->andWhere(['assortment.weight' => $this->assortment->weight])
                ->column();
            // Проверяем есть ли Приёмки на остатке (хотя бы 2)
            $acceptanceRemainder = Remainder::getListAcceptance(
                $this->company_own_id,
                $this->stock_id,
                $assortmentIds,
                [],
                true
            );
            if (count($acceptanceRemainder) < 2) {
                Yii::$app->session->setFlash('error', 'На складе недостаточно Приёмок для объединения.');
                return false;
            }
        }

        $this->date = $this->date
            ? date('Y-m-d H:i', strtotime($this->date)) : null;

        $now = (new DateTime('now'))->format('Y-m-d H:i');
        if ($insert) {
            $this->created_by = Yii::$app->user->id;
            $this->created_at = $now;
        }
        $this->updated_at = $now;

        return true;
    }

    public function afterSave($insert, $changedAttributes)
    {
        if (!$insert) {
            // Если итоговая Приёмка по Объединению ещё не создана
            if (!$this->resultAcceptance) {
                // Создаём итоговую Приёмку
                $newAcceptance = new Acceptance();
                $newAcceptance->type_id = Acceptance::TYPE_MERGING;
                $newAcceptance->parent_doc_id = $this->id;
                $newAcceptance->company_own_id = $this->company_own_id;
                $newAcceptance->stock_id = $this->stock_id;
                $newAcceptance->date = $this->date;
                $newAcceptance->comment = 'Created automatically';
                $newAcceptance->save();
                // Создаём позицию по Приёмке
                $acceptanceItem = new AcceptanceItem();
                $acceptanceItem->acceptance_id = $newAcceptance->id;
                $acceptanceItem->assortment_id = $this->assortment_id;
            } else {
                $newAcceptance = $this->resultAcceptance;
                $acceptanceItem = $newAcceptance->items[0];
            }
            // Устанавливаем параметры для Приёмки
            $quality_id = 100000; // Для Качества заведомо большой ID
            $pallet_type_id = null;
            $maxPalletTypePriority = 0; // Приоритет заведомо меньший
            foreach ($this->items as $item) {
                // Вычисляем приоритетный Тип паллет
                $palletType = $item->acceptance->items[0]->palletType;
                if ($palletType) {
                    if ($palletType->priority > $maxPalletTypePriority) {
                        $pallet_type_id = $palletType->id;
                        $maxPalletTypePriority = $palletType->priority;
                    }
                }
                // Устанавливаем качество
                if (!$item->acceptance->items[0]->quality_id) {
                    $quality_id = null;
                } elseif ($quality_id) {
                    $quality_id = $item->acceptance->items[0]->quality_id;
                }
            }
            // Устанавливаем количество для Приёмки
            $acceptanceItem->pallet_type_id = $pallet_type_id;
            $acceptanceItem->quality_id = $quality_id;
            $acceptanceItem->quantity = $this->quantity;
            $acceptanceItem->quantity_pallet = $this->quantity_pallet;
            $acceptanceItem->quantity_paks = $this->quantity_paks;
            $acceptanceItem->save();
        }
        if ($this->isChanges()) {
            $this->isChanges(true);
        }

        return true;
    }

    public function close()
    {
        foreach ($this->shipments as $shipment) {
            $shipment->applay();
        }

        $acceptance = $this->resultAcceptance;
        $acceptance->applayMerging();
        $this->date_close = $acceptance->date_close;
        $this->save();
    }

    public function open()
    {
        foreach ($this->shipments as $shipment) {
            $shipment->cancel();
        }

        $acceptance = $this->resultAcceptance;
        $acceptance->cancelMerging();
        $this->date_close = null;
        $this->save();
    }

    public function beforeDelete()
    {
        if ($this->date_close) {
            Yii::$app->session->setFlash('error', 'Документ закрыт. Удаление не возможно.');
            return false;
        }

        $acceptance = $this->resultAcceptance;
        $acceptance->delete();

        foreach ($this->shipments as $shipment) {
            $shipment->delete();
        }

        return true;
    }

    /**
     * --------------------------------------------------------------- Исходные Приёмки для Объединения
     * Gets query for [[Acceptances]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSourceAcceptances()
    {
        return $this->hasMany(Acceptance::class, ['id' => 'acceptance_id'])->viaTable('merging_item', ['merging_id' => 'id']);
    }

    /**
     * --------------------------------------------------------------- Новые Отгрузки для Объединения
     * Gets query for [[Shipments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getShipments()
    {
        return $this->hasMany(Shipment::class, ['parent_doc_id' => 'id'])
            ->where(['type_id' => Shipment::TYPE_MERGING]);
    }

    /**
     * Gets query for [[Acceptance]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getResultAcceptance()
    {
        return $this->hasOne(Acceptance::class, ['parent_doc_id' => 'id'])
            ->where(['type_id' => Acceptance::TYPE_MERGING]);
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
     * Gets query for [[MergingItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItems()
    {
        return $this->hasMany(MergingItem::class, ['merging_id' => 'id']);
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

    // ------------------------------------------- Label
    public function getLabel(): string
    {
        return '№' . $this->id
            . ' ' . $this->date
            . ', ' . $this->companyOwn->name
            . ', склад ' . $this->stock->name
            . ', ' . $this->assortment->name
            . ' ' . $this->quantity
            . ' (' . $this->assortment->unit->name . ')';
    }

    public function getShortLabel()
    {
        return '№' . $this->id
            . ' ' . $this->date;
    }

    // Возвращает true, если есть изменения.
    public function isChanges(bool $cancel = false): bool
    {
        if ($cancel) {
            Yii::$app->session->remove('merging.old_values');
        }
        return Yii::$app->session->has('merging.old_values');
    }
}
