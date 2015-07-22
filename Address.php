<?php
/**
 * Created by PhpStorm.
 * User: dggug
 * Date: 2015/7/21
 * Time: 16:21
 */

namespace iit\api\address;


use Yii;
use yii\base\InvalidParamException;
use yii\db\ActiveRecord;
use yii\db\Connection;

class Address extends ActiveRecord
{
    public static function getDb()
    {
        return new Connection(['dsn' => 'sqlite:' . Yii::getAlias('@vendor/iit/yii2-china-address/database.db'), 'enableSchemaCache' => true]);
    }

    public static function getProvinceList()
    {
        return self::find()->where(['level' => 1])->all();
    }

    public static function getProvince($provinceId)
    {
        return self::find()->where(['level' => 1, 'id' => $provinceId])->one();
    }

    public static function getCityList($provinceId)
    {
        return self::find()->where(['level' => 2, 'parent_id' => $provinceId])->all();
    }

    public static function getCity($cityId)
    {
        return self::find()->where(['level' => 2, 'id' => $cityId])->one();
    }

    public static function getCountyList($cityId)
    {
        return self::find()->where(['level' => 3, 'parent_id' => $cityId])->all();
    }

    public static function getCounty($countyId)
    {
        return self::find()->where(['level' => 3, 'id' => $countyId])->one();
    }

    public static function getTownList($countyId)
    {
        return self::find()->where(['level' => 4, 'parent_id' => $countyId])->all();
    }

    public static function getTown($townId)
    {
        return self::find()->where(['level' => 4, 'id' => $townId])->one();
    }

    public static function getBreadCrumbs($id)
    {

    }

    public static function getParent($id)
    {
        $address = self::find()->where(['id' => $id])->one();
        if (empty($address)) {
            throw new InvalidParamException("ID does not exist");
        }
        return self::find()->where(['id' => $address['parent']])->one();
    }
}