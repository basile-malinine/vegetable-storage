<?php

namespace app\models\Documents\Increase;

use app\models\Documents\Acceptance\AcceptanceItem;
use DateTime;

use Yii;

use app\models\Assortment\Assortment;
use app\models\Base;
use app\models\Documents\Acceptance\Acceptance;
use app\models\Documents\Remainder\Remainder;
use app\models\LegalSubject\LegalSubject;
use app\models\Stock\Stock;
use app\models\User\User;

/**
 * This is the model class for table "increase".
 *
 * @property int $id
 * @property int $type_id Тип оприходования
 * @property int $acceptance_id Приёмка
 * @property int $company_own_id Предприятие
 * @property int $stock_id Склад
 * @property string|null $date Дата оприходования
 * @property string|null $date_close Дата закрытия
 * @property string|null $comment Комментарий
 * @property int|null $created_by Создатель
 * @property string|null $created_at Дата создания
 * @property string|null $updated_at Дата обновления
 *
 * @property Remainder $sourceAcceptance Исходная Приёмка на остатке (по которой делаем Оприходование)
 * @property Acceptance $acceptance Изначальная Приёмка на приходе
 * @property Acceptance $newAcceptance Ссылка на новую Приёмку Оприходования
 * @property Assortment[] $assortments
 * @property LegalSubject $companyOwn
 * @property User $createdBy
 * @property IncreaseItem[] $items
 * @property Stock $stock
 */
class Increase extends Base
{
    const TYPE_INVENTORY = 1;
    const TYPE_LIST = [
        self::TYPE_INVENTORY => 'Инвентарка',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'increase';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date', 'date_close', 'comment', 'created_by', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['type_id', 'acceptance_id', 'company_own_id', 'stock_id'], 'required'],
            [['type_id', 'acceptance_id', 'company_own_id', 'stock_id', 'created_by'], 'integer'],
            [['date', 'date_close', 'created_at', 'updated_at'], 'safe'],
            [['comment'], 'string'],
            [['acceptance_id'], 'exist', 'skipOnError' => true, 'targetClass' => Acceptance::class, 'targetAttribute' => ['acceptance_id' => 'id']],
            [['company_own_id'], 'exist', 'skipOnError' => true, 'targetClass' => LegalSubject::class, 'targetAttribute' => ['company_own_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
            [['stock_id'], 'exist', 'skipOnError' => true, 'targetClass' => Stock::class, 'targetAttribute' => ['stock_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type_id' => 'Тип оприходования',
            'acceptance_id' => 'Приёмка',
            'company_own_id' => 'Предприятие',
            'stock_id' => 'Склад',
            'date' => 'Дата оприходования',
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
            // В Оприходовании создаём позицию
            $increaseItem = new IncreaseItem();
            $increaseItem->increase_id = $this->id;
            $increaseItem->assortment_id = $remainder->assortment_id;
            $increaseItem->pallet_type_id = $remainder->pallet_type_id;
            $increaseItem->quantity = .0;
            $increaseItem->quantity_pallet = 0;
            $increaseItem->quantity_paks = 0;
        } else {
            $increaseItem = $this->items[0];
        }
        $increaseItem->save();

        // Отыскиваем Приёмку по Оприходованию
        $newAcceptance = $this->newAcceptance;
        // Если ещё не создана
        if (!$newAcceptance) {
            // Создаём Приёмку с типом Оприходование
            $newAcceptance = new Acceptance();
            $newAcceptance->type_id = Acceptance::TYPE_INCREASE;
            $newAcceptance->parent_doc_id = $this->id;
            $newAcceptance->company_own_id = $this->company_own_id;
            $newAcceptance->stock_id = $this->stock_id;
            $newAcceptance->acceptance_date = $this->date;
            $newAcceptance->date_close = null;
            $newAcceptance->comment = 'Created automatically';
            $newAcceptance->save();

            // В Приёмке создаём позицию
            $newAcceptanceItem = new AcceptanceItem();
            $newAcceptanceItem->acceptance_id = $newAcceptance->id;
            $newAcceptanceItem->assortment_id = $increaseItem->assortment_id;
            $newAcceptanceItem->pallet_type_id = $increaseItem->pallet_type_id;
        } else {
            $newAcceptanceItem = $newAcceptance->items[0];
        }
        $newAcceptanceItem->quantity = $increaseItem->quantity;
        $newAcceptanceItem->quantity_pallet = $increaseItem->quantity_pallet;
        $newAcceptanceItem->quantity_paks = $increaseItem->quantity_paks;
        $newAcceptanceItem->save();
    }

    public function beforeDelete()
    {
        if ($this->date_close) {
            return false;
        }
        $this->newAcceptance->delete();

        return true;
    }

    // Закрытие Приёмки по Оприходованию (Провести)
    public function apply()
    {
        $newAcceptance = $this->newAcceptance;
        if (!$newAcceptance) {
            return false;
        }

        if ($newAcceptance->applayIncrease()) {
            $this->date_close = $newAcceptance->date_close;
            $this->save();
            return true;
        }

        return false;
    }

    // Снять с остатка
    public function cancel()
    {
        $newAcceptance = $this->newAcceptance;
        if (!$newAcceptance) {
            return false;
        }

        $newAcceptance->cancelIncrease();
        $this->date_close = null;
        $this->save();

        return true;
    }

    /**
     * ------------------------------------------- Исходная Приёмка на остатке (по которой делаем Оприходование)
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
     * ------------------------------------------- Ссылка на новую Приёмку Оприходования
     * @return \yii\db\ActiveQuery
     */
    public function getNewAcceptance()
    {
        return $this->hasOne(Acceptance::class, ['parent_doc_id' => 'id'])
            ->where(['type_id' => Acceptance::TYPE_INCREASE]);
    }

    /**
     * Gets query for [[Assortments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAssortments()
    {
        return $this->hasMany(Assortment::class, ['id' => 'assortment_id'])->viaTable('increase_item', ['increase_id' => 'id']);
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
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    /**
     * Gets query for [[IncreaseItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItems()
    {
        return $this->hasMany(IncreaseItem::class, ['increase_id' => 'id']);
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
        $assortment = $this->items
            ? $this->items[0]->label
            : 'Нет состава';

        return '№' . $this->id
            . ' ' . $this->date
            . ', ' . $this->companyOwn->name
            . ', склад ' . $this->stock->name
            . ', ' . $assortment;
    }

    public function getShortLabel()
    {
        return '№' . $this->id
            . ' ' . $this->date;
    }
}
