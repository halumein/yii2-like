<?php

namespace halumein\like\models;

use Yii;

/**
 * This is the model class for table "like".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $model
 * @property integer $item_id
 */
class Like extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'like';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'model', 'item_id'], 'required'],
            [['user_id', 'item_id'], 'integer'],
            [['model'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'model' => 'Model',
            'item_id' => 'Item ID',
        ];
    }




}
