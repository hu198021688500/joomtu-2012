<?php

/**
 * 2012-3-28 11:46:28 UTF-8
 * @package protected.models
 * @version 1.0
 *
 * @author hadoop <hu198021688500@163.com>
 * @copyright (c) 2011 http://www.test.com
 * @license (http://www.apache.org/licenses/LICENSE-2.0)
 *
 * @$Id$
 * 
 */

/**
 * This is the model class for table "{{user}}".
 *
 * The followings are the available columns in table '{{user}}':
 * @property string $uid
 * @property string $email
 * @property string $password
 * @property string $salt
 * @property string $name
 * @property integer $sex
 * @property string $mobile
 * @property string $birthday
 * @property integer $address
 * @property integer $rid
 * @property string $nid
 * @property integer $status
 * @property string $config
 * @property string $signature
 * @property string $integral
 * @property string $reg_time
 * @property string $reg_ip
 * @property string $reg_src
 * @property string $login_time
 * @property string $login_ip
 * @property string $update_time
 */
class User extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return User the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{user}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('email, password, salt', 'required'),
            array('sex, address, rid, status', 'numerical', 'integerOnly' => true),
            array('email', 'length', 'max' => 30),
            array('password', 'length', 'max' => 32),
            array('salt, name', 'length', 'max' => 15),
            array('mobile', 'length', 'max' => 11),
            array('birthday', 'length', 'max' => 12),
            array('nid, config, integral, reg_time, login_time, update_time', 'length', 'max' => 10),
            array('signature', 'length', 'max' => 50),
            array('reg_ip, login_ip', 'length', 'max' => 16),
            array('reg_src', 'length', 'max' => 5),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('uid, email, password, salt, name, sex, mobile, birthday, address, rid, nid, status, config, signature, integral, reg_time, reg_ip, reg_src, login_time, login_ip, update_time', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'uid' => 'Uid',
            'email' => 'Email',
            'password' => 'Password',
            'salt' => 'Salt',
            'name' => 'Name',
            'sex' => 'Sex',
            'mobile' => 'Mobile',
            'birthday' => 'Birthday',
            'address' => 'Address',
            'rid' => 'Rid',
            'nid' => 'Nid',
            'status' => 'Status',
            'config' => 'Config',
            'signature' => 'Signature',
            'integral' => 'Integral',
            'reg_time' => 'Reg Time',
            'reg_ip' => 'Reg Ip',
            'reg_src' => 'Reg Src',
            'login_time' => 'Login Time',
            'login_ip' => 'Login Ip',
            'update_time' => 'Update Time',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('uid', $this->uid, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('password', $this->password, true);
        $criteria->compare('salt', $this->salt, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('sex', $this->sex);
        $criteria->compare('mobile', $this->mobile, true);
        $criteria->compare('birthday', $this->birthday, true);
        $criteria->compare('address', $this->address);
        $criteria->compare('rid', $this->rid);
        $criteria->compare('nid', $this->nid, true);
        $criteria->compare('status', $this->status);
        $criteria->compare('config', $this->config, true);
        $criteria->compare('signature', $this->signature, true);
        $criteria->compare('integral', $this->integral, true);
        $criteria->compare('reg_time', $this->reg_time, true);
        $criteria->compare('reg_ip', $this->reg_ip, true);
        $criteria->compare('reg_src', $this->reg_src, true);
        $criteria->compare('login_time', $this->login_time, true);
        $criteria->compare('login_ip', $this->login_ip, true);
        $criteria->compare('update_time', $this->update_time, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    /**
     * 验证密码
     * @param string $password
     * @return boolean
     */
    public function validatePassword($password) {
        return $this->hashPassword($password, $this->salt) === $this->password;
    }

    /**
     * 获取加密后的密码
     * @param string $password
     * @param string $salt
     * @return string 32位字符串
     */
    public function hashPassword($password, $salt) {
        return md5($salt . $password);
    }

    /**
     * 生成随机码
     * @return string
     */
    public function generateSalt() {
        return uniqid('');
    }

    /**
     * 根据Uid查找用户
     * @param int $uid
     * @return object
     */
    public function getUserByUid($uid) {
        if (!is_numeric($uid)) {
            return false;
        }
        return $this->findByPk($uid);
    }

}