<?php

namespace app\models\Documents\Packing;

use DateTime;

use Yii;
use yii\helpers\ArrayHelper;

use app\models\Assortment\Assortment;
use app\models\Assortment\AssortmentGroup;
use app\models\Base;
use app\models\Documents\Acceptance\Acceptance;
use app\models\Documents\Acceptance\AcceptanceItem;
use app\models\Documents\Remainder\Remainder;
use app\models\Documents\Shipment\Shipment;
use app\models\LegalSubject\LegalSubject;
use app\models\Stock\Stock;

/**
 * This is the model class for table "packing".
 *
 * @property int $id
 * @property int $company_own_id Номенклатура
 * @property int $stock_id Предприятие
 * @property int $assortment_id Склад
 * @property string $date Дата объединения
 * @property string|null $date_close Дата закрытия
 * @property string|null $comment Комментарий
 * @property int|null $created_by Создатель
 * @property string|null $created_at Дата создания
 * @property string|null $updated_at Дата обновления
 *
 * @property Acceptance[] $sourceAcceptances
 * @property Acceptance $resultAcceptance
 * @property Shipment[] $shipments
 * @property Assortment $assortment
 * @property LegalSubject $companyOwn
 * @property PackingItem[] $items
 * @property Stock $stock
 */
class Packing extends Base
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
        return 'packing';
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
                'company_own_id',
                'stock_id',
                'assortment_id',
                'date'], 'required'],

            [[
                'company_own_id',
                'stock_id',
                'assortment_id',
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
        if (!$this->items) {
            $this->addError('errorItems', 'Необходимо добавить минимум 1 позицию для Фасовки.');
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
            $this->quantity = array_sum(ArrayHelper::getColumn($this->items, 'weight'));
            $this->quantity_pallet = array_sum(ArrayHelper::getColumn($this->items, 'quantity_pallet'));
            $this->quantity_paks = array_sum(ArrayHelper::getColumn($this->items, 'quantity_paks'));
            $this->weight = $this->quantity * $this->assortment->weight;
        }
    }

    public function beforeSave($insert)
    {
        if ($insert) {
            // Получаем Ids подгрупп для основной группы.
            $assortmentGroupIds = AssortmentGroup::find()
                ->select('id')
                ->where(['parent_id' => $this->assortment->parent_id])
                ->column();
            $assortmentIds = Assortment::find()->select('assortment.id')
                ->where(['assortment_group_id' => $assortmentGroupIds])
                ->column();
            // Проверяем есть ли Приёмки на остатке
            $acceptanceRemainder = Remainder::getListAcceptance(
                $this->company_own_id,
                $this->stock_id,
                $assortmentIds,
                [],
                true
            );
            if (!$acceptanceRemainder) {
                Yii::$app->session->setFlash('error', 'На складе нет Приёмок для фасовки.');
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
        if ($insert) {
            // Создаём новую Приёмку по Фасовке
            $newAcceptance = new Acceptance();
            $newAcceptance->type_id = Acceptance::TYPE_PACKING;
            $newAcceptance->parent_doc_id = $this->id;
            $newAcceptance->company_own_id = $this->company_own_id;
            $newAcceptance->stock_id = $this->stock_id;
            $newAcceptance->date = $this->date;
            $newAcceptance->comment = 'Created automatically';
            $newAcceptance->save();
            // Создаём новую позицию по Приёмке
            $newAcceptanceItem = new AcceptanceItem();
            $newAcceptanceItem->acceptance_id = $newAcceptance->id;
            $newAcceptanceItem->assortment_id = $this->assortment_id;
            // quantity для Приёмки обязательное поле
            $newAcceptanceItem->quantity = .0;
            $newAcceptanceItem->save();
            return true;
        }
        $newAcceptance = $this->resultAcceptance;
        $newAcceptanceItem = $newAcceptance->items[0];

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
        $newAcceptanceItem->pallet_type_id = $pallet_type_id;
        $newAcceptanceItem->quality_id = $quality_id;
        $newAcceptanceItem->quantity = $this->quantity;
        $newAcceptanceItem->quantity_pallet = $this->quantity_pallet;
        $newAcceptanceItem->quantity_paks = $this->quantity_paks;
        $newAcceptanceItem->save();

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

        // Удаляем, если есть
        $this->resultAcceptance?->delete();

        foreach ($this->items as $item) {
            $item->delete();
        }

        return true;
    }

    /**
     * --------------------------------------------------------------- Исходные Приёмки для Фасовки
     * Gets query for [[Acceptances]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSourceAcceptances()
    {
        return $this->hasMany(Acceptance::class, ['id' => 'acceptance_id'])->viaTable('packing_item', ['packing_id' => 'id']);
    }

    /**
     * --------------------------------------------------------------- Новая Приёмка для Фасовки
     * Gets query for [[Acceptance]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getResultAcceptance()
    {
        return $this->hasOne(Acceptance::class, ['parent_doc_id' => 'id'])
            ->where(['type_id' => Acceptance::TYPE_PACKING]);
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
            ->where(['type_id' => Shipment::TYPE_PACKING]);
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
     * Gets query for [[PackingItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItems()
    {
        return $this->hasMany(PackingItem::class, ['packing_id' => 'id']);
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
            Yii::$app->session->remove('packing.old_values');
        }
        return Yii::$app->session->has('packing.old_values');
    }
}
