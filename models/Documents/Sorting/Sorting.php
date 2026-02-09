<?php

namespace app\models\Documents\Sorting;

use DateTime;
use Yii;

use app\models\Assortment\Assortment;
use app\models\Base;
use app\models\Documents\Acceptance\Acceptance;
use app\models\Documents\Acceptance\AcceptanceItem;
use app\models\Documents\Remainder\Remainder;
use app\models\Documents\Shipment\Shipment;
use app\models\Documents\Shipment\ShipmentAcceptance;

/**
 * This is the model class for table "sorting".
 *
 * @property int $id
 * @property int $acceptance_id Приёмка
 * @property string|null $date Дата
 * @property string|null $date_close Дата закрытия
 * @property string|null $comment Комментарий
 * @property int|null $created_by Создатель
 * @property string|null $created_at Дата создания
 * @property string|null $updated_at Дата обновления
 *
 * @property Remainder $sourceAcceptance Исходная Приёмка на остатке (по которой делаем Переборку)
 * @property Acceptance $acceptance Изначальная Приёмка на приходе
 * @property Acceptance $newAcceptance Ссылка на новую Приёмку по Переборке
 * @property Shipment $shipment Ссылка на Отгрузку по Переборке
 * @property Assortment[] $assortments
 * @property SortingItem[] $items
 * @property string $label
 * @property string $shortLabel
 *
 * @property string|null $error Ошибка на форме
 */
class Sorting extends Base
{
    public string $error = '';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sorting';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date', 'date_close', 'comment', 'created_by', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['acceptance_id'], 'required'],
            [['acceptance_id', 'created_by'], 'integer'],
            [['date', 'date_close', 'created_at', 'updated_at'], 'safe'],
            [['comment'], 'string'],
            [['acceptance_id'], 'exist', 'skipOnError' => true, 'targetClass' => Acceptance::class, 'targetAttribute' => ['acceptance_id' => 'id']],

            [['id'], 'testItem']  // Проверка корректности значений у позиции при сохранении Переборки
        ];
    }

    public function testItem($attribute)
    {
        if (!$this->items[0]->quality || $this->items[0]->quantity < 0.1) {
            $this->addError('error', 'Необходимо установить Качество и Количество.');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'acceptance_id' => 'Приёмка',
            'date' => 'Дата',
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

        $this->date = $this->date ? date('d.m.Y', strtotime($this->date)) : null;
        $this->date_close = $this->date_close ? date('d.m.Y', strtotime($this->date_close)) : null;
    }

    public function beforeValidate()
    {
        if (isset($this->oldAttributes['acceptance_id'])) {
            $this->acceptance_id = $this->oldAttributes['acceptance_id'];
        }

        return parent::beforeValidate();
    }

    public function beforeSave($insert)
    {
        $this->date = $this->date ? date('Y-m-d H:i', strtotime($this->date)) : null;
        $this->date_close = $this->date_close ? date('Y-m-d H:i', strtotime($this->date_close)) : null;

        $now = (new DateTime('now'))->format('Y-m-d');
        if ($insert) {
            $this->created_by = Yii::$app->user->id;
            $this->created_at = $now;
        }
        $this->updated_at = $now;

        return true;
    }

    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            // Отыскиваем Приёмку на остатке
            $remainder = Remainder::findOne(['acceptance_id' => $this->acceptance_id]);
            // В Переборке создаём позицию
            $sortingItem = new SortingItem();
            $sortingItem->sorting_id = $this->id;
            $sortingItem->assortment_id = $remainder->assortment_id;
            $sortingItem->quality_id = $remainder->acceptance->items[0]->quality_id;
            $sortingItem->pallet_type_id = $remainder->pallet_type_id;
            $sortingItem->quantity = .0;
            $sortingItem->quantity_pallet = 0;
            $sortingItem->quantity_paks = 0;
        } else {
            $sortingItem = $this->items[0];
        }
        $sortingItem->save();

        // Отыскиваем Отгрузку по Переборке
        $shipment = $this->shipment;
        // Если ещё не создана
        if (!$shipment) {
            // Создаём Отгрузку с типом Переборка
            $shipment = new Shipment();
            $shipment->type_id = Shipment::TYPE_SORTING;
            $shipment->parent_doc_id = $this->id;
            $shipment->company_own_id = $this->acceptance->company_own_id;
            $shipment->stock_id = $this->acceptance->stock_id;
            $shipment->date = $this->date;
            $shipment->date_close = null;
            $shipment->comment = 'Created automatically';
            $shipment->save();

            // В Отгрузке создаём позицию
            $shipmentAcceptance = new ShipmentAcceptance();
            $shipmentAcceptance->shipment_id = $shipment->id;
            $shipmentAcceptance->acceptance_id = $this->acceptance_id;
            $shipmentAcceptance->pallet_type_id = $sortingItem->pallet_type_id;
        } else {
            $shipmentAcceptance = $shipment->shipmentAcceptances[0];
        }
        $shipmentAcceptance->quantity = $sortingItem->quantity;
        $shipmentAcceptance->quantity_pallet = $sortingItem->quantity_pallet;
        $shipmentAcceptance->quantity_paks = $sortingItem->quantity_paks;
        $shipmentAcceptance->save();

        // Отыскиваем Приёмку по Переборке
        $newAcceptance = $this->newAcceptance;
        // Если ещё не создана
        if (!$newAcceptance) {
            // Создаём Приёмку с типом Оприходование
            $newAcceptance = new Acceptance();
            $newAcceptance->type_id = Acceptance::TYPE_SORTING;
            $newAcceptance->parent_doc_id = $this->id;
            $newAcceptance->company_own_id = $this->acceptance->company_own_id;
            $newAcceptance->stock_id = $this->acceptance->stock_id;
            $newAcceptance->date = $this->date;
            $newAcceptance->date_close = null;
            $newAcceptance->comment = 'Created automatically';
            $newAcceptance->save();

            // В Приёмке создаём позицию
            $newAcceptanceItem = new AcceptanceItem();
            $newAcceptanceItem->acceptance_id = $newAcceptance->id;
            $newAcceptanceItem->assortment_id = $sortingItem->assortment_id;
            $newAcceptanceItem->pallet_type_id = $sortingItem->pallet_type_id;
        } else {
            $newAcceptanceItem = $newAcceptance->items[0];
        }
        $newAcceptanceItem->quality_id = $sortingItem->quality_id;
        $newAcceptanceItem->quantity = $sortingItem->quantity;
        $newAcceptanceItem->quantity_pallet = $sortingItem->quantity_pallet;
        $newAcceptanceItem->quantity_paks = $sortingItem->quantity_paks;
        $newAcceptanceItem->save();
    }

    public function beforeDelete()
    {
        if ($this->date_close) {
            return false;
        }
        $this->shipment->delete();
        $this->newAcceptance->delete();

        return true;
    }

    // Закрытие Приёмки по Переборке (Провести)
    public function apply()
    {
        $shipment = $this->shipment;
        $shipment->applay();

        $newAcceptance = $this->newAcceptance;
        if (!$newAcceptance) {
            return false;
        }

        if ($newAcceptance->applaySorting()) {
            $this->date_close = $newAcceptance->date_close;
            $this->save();
            return true;
        }

        return false;
    }

    // Снять с остатка
    public function cancel()
    {
        if (!$this->newAcceptance->cancelSorting()) {
            return false;
        }
        $this->shipment->cancel();
        $this->date_close = null;
        $this->save();

        return true;
    }

    /**
     * ------------------------------------------- Исходная Приёмка на остатке (по которой делаем Переборку)
     * Gets query for [[Acceptance]].
     *
     * @return \yii\db\ActiveQuery
     */
    public
    function getSourceAcceptance()
    {
        return $this->hasOne(Remainder::class, ['acceptance_id' => 'acceptance_id']);
    }

    /**
     * ------------------------------------------- Изначальная Приёмка на приходе
     * Gets query for [[Acceptance]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAcceptance()
    {
        return $this->hasOne(Acceptance::class, ['id' => 'acceptance_id']);
    }

    /**
     * ------------------------------------------- Ссылка на Отгрузку Переборки
     * @return \yii\db\ActiveQuery
     */
    public function getShipment()
    {
        return $this->hasOne(Shipment::class, ['parent_doc_id' => 'id'])
            ->where(['type_id' => Shipment::TYPE_SORTING]);
    }

    /**
     * ------------------------------------------- Ссылка на новую Приёмку Переборки
     * @return \yii\db\ActiveQuery
     */
    public function getNewAcceptance()
    {
        return $this->hasOne(Acceptance::class, ['parent_doc_id' => 'id'])
            ->where(['type_id' => Acceptance::TYPE_SORTING]);
    }

    /**
     * Gets query for [[Assortments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAssortments()
    {
        return $this->hasMany(Assortment::class, ['id' => 'assortment_id'])->viaTable('sorting_item', ['sorting_id' => 'id']);
    }

    /**
     * Gets query for [[SortingItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItems()
    {
        return $this->hasMany(SortingItem::class, ['sorting_id' => 'id']);
    }

    // ------------------------------------------- Label
    public function getLabel(): string
    {
        $assortment = $this->items
            ? $this->items[0]->label
            : 'Нет состава';

        return '№' . $this->id
            . ' ' . $this->date
            . ', ' . $this->acceptance->companyOwn->name
            . ', склад ' . $this->acceptance->stock->name
            . ', ' . $assortment;
    }

    public function getShortLabel()
    {
        return '№' . $this->id
            . ' от ' . $this->date;
    }
}
