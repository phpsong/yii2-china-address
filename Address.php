<?php
/**
 * Created by PhpStorm.
 * User: dggug
 * Date: 2015/7/21
 * Time: 16:21
 */

namespace iit\api\jd;


use Yii;
use yii\base\InvalidParamException;
use yii\db\ActiveRecord;
use yii\db\Connection;
use yii\helpers\ArrayHelper;

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
        return self::find()->where(['level' => 2, 'parent' => $provinceId])->all();
    }

    public static function getCity($cityId)
    {
        return self::find()->where(['level' => 2, 'id' => $cityId])->one();
    }

    public static function getCountyList($cityId)
    {
        return self::find()->where(['level' => 3, 'parent' => $cityId])->all();
    }

    public static function getCounty($countyId)
    {
        return self::find()->where(['level' => 3, 'id' => $countyId])->one();
    }

    public static function getTownList($countyId)
    {
        return self::find()->where(['level' => 4, 'parent' => $countyId])->all();
    }

    public static function getTown($townId)
    {
        return self::find()->where(['level' => 4, 'id' => $townId])->one();
    }

    public static function getBreadCrumbs($id)
    {
        $return = self::getBreadCrumbsInternal($id);
        sort($return);
        return $return;
    }

    protected static function getBreadCrumbsInternal($id)
    {
        $parents = [];
        if ($address = self::findOne($id)) {
            $parents[] = $address;
            if ($address['parent'] != 0) {
                $parents = ArrayHelper::merge($parents, self::getBreadCrumbsInternal($address['parent']));
            }
        }
        return $parents;
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