<?php

namespace app\models\Assortment;

use yii\db\ActiveQuery;

use app\models\Base;

/**
 * @property int id ID
 * @property int parent_id Родительская группа
 * @property string name Группа
 * @property ActiveQuery parent Magic getParent()
 * @property ActiveQuery child Magic getChild()
 */
class AssortmentGroup extends Base
{
    public static function tableName()
    {
        return 'assortment_group';
    }

    public function rules()
    {
        return [
            [['parent_id'], 'integer'],
            [['name'], 'required'],
            [['name'], 'string', 'max' => 50],
            [['name'], 'trim'],
            [['name'], 'testUnique'],
        ];
    }

    public function testUnique()
    {
        $err = false;
        if ($this->parent_id === null) {
            $err = self::find()
                ->where(['parent_id' => null])
                ->andWhere(['name' => $this->name])
                ->exists();
            if ($err) {
                $this->addError('name', 'В группах значение "' . $this->name . '" уже занято.');
            }
        } else {
            $err = self::find()
                ->where('parent_id')
                ->andWhere(['name' => $this->name])
                ->exists();
            if ($err) {
                $this->addError('name', 'В подгруппах значение "' . $this->name . '" уже занято.');
            }
        }
    }


    public
    function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => 'Родительская группа',
            'name' => 'Группа',
            'child' => 'Подгруппы',
        ];
    }

    public
    function getParent()
    {
        return $this->hasOne(AssortmentGroup::class, ['id' => 'parent_id']);
    }

    public
    function getChild()
    {
        return $this->hasMany(AssortmentGroup::class, ['parent_id' => 'id']);
    }

    public
    static function getParentList()
    {
        $query = self::find()
            ->select(['name', 'id'])
            ->where('parent_id is null')
            ->indexBy('id')
            ->orderBy(['name' => SORT_ASC])
            ->column();

        $list[0] = 'Все';
        foreach ($query as $key => $value) {
            $list[$key] = $value;
        }

        return $list;
    }

    public
    static function getChildListByParentId($id)
    {
        if ($id == 0) {
            return self::getChildList();
        }

        return self::find()
            ->select(['name', 'id'])
            ->where(['parent_id' => $id])
            ->indexBy('id')
            ->orderBy(['name' => SORT_ASC])
            ->column();
    }

    public
    static function getChildList()
    {
        return self::find()
            ->select(['name', 'id'])
            ->where('parent_id')
            ->indexBy('id')
            ->orderBy(['name' => SORT_ASC])
            ->column();
    }
}
