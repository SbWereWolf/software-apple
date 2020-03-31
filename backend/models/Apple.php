<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "apple".
 *
 * @property int $id
 * @property string|null $color
 * @property int|null $created_at
 * @property int|null $fell_at
 * @property string|null $status
 * @property float|null $used_percentage
 */
class Apple extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'apple';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'fell_at'], 'integer'],
            [['used_percentage'], 'number'],
            [['color', 'status'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'color' => 'Color',
            'created_at' => 'Created At',
            'fell_at' => 'Fell At',
            'status' => 'Status',
            'used_percentage' => 'Used Percentage',
        ];
    }
}
