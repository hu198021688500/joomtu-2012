<?php

/**
 * This is the model class for table "{{user}}".
 *
 * The followings are the available columns in table '{{user}}':
 * @property string $uid
 * @property string $email
 * @property string $password
 * @property string $salt
 * @property string $old_password
 * @property string $nickname
 * @property integer $sex
 * @property string $avatar
 * @property string $mobile
 * @property string $birthday
 * @property string $address
 * @property integer $rid
 * @property string $nid
 * @property integer $status
 * @property string $config
 * @property string $signature
 * @property string $integral
 * @property string $reg_time
 * @property string $reg_ip
 * @property string $reg_source
 * @property string $last_login_time
 * @property string $last_login_ip
 * @property integer $login_fail_times
 * @property integer $reset_pwd_time
 * @property string $update_time
 *
 * The followings are the available model relations:
 * @property FriendGroup[] $friendGroups
 * @property FriendGroupRel[] $friendGroupRels
 * @property MsgInbox[] $msgInboxes
 * @property MsgInbox[] $msgInboxes1
 * @property MsgOutbox[] $msgOutboxes
 * @property UserExt $userExt
 * @property UserMerchantExt[] $userMerchantExts
 * @property UserPhoto[] $userPhotos
 * @property UserPhotoPic[] $userPhotoPics
 * @property UserRel[] $userRels
 * @property UserRel[] $userRels1
 * @property UserStoresExt[] $userStoresExts
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
            array('email, password, salt, nickname', 'required'),
            array('sex, rid, status, login_fail_times, reset_pwd_time', 'numerical', 'integerOnly' => true),
            array('email', 'length', 'max' => 30),
            array('password, old_password', 'length', 'max' => 32),
            array('salt, nickname', 'length', 'max' => 15),
            array('avatar', 'length', 'max' => 255),
            array('mobile', 'length', 'max' => 11),
            array('birthday', 'length', 'max' => 12),
            array('address', 'length', 'max' => 100),
            array('nid, config, integral, reg_time, last_login_time, update_time', 'length', 'max' => 10),
            array('signature', 'length', 'max' => 50),
            array('reg_ip, last_login_ip', 'length', 'max' => 16),
            array('reg_source', 'length', 'max' => 5),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('uid, email, password, salt, old_password, nickname, sex, avatar, mobile, birthday, address, rid, nid, status, config, signature, integral, reg_time, reg_ip, reg_source, last_login_time, last_login_ip, login_fail_times, reset_pwd_time, update_time', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'friendGroups' => array(self::HAS_MANY, 'FriendGroup', 'create_uid'),
            'friendGroupRels' => array(self::HAS_MANY, 'FriendGroupRel', 'uid'),
            'msgInboxes' => array(self::HAS_MANY, 'MsgInbox', 'from_uid'),
            'msgInboxes1' => array(self::HAS_MANY, 'MsgInbox', 'uid'),
            'msgOutboxes' => array(self::HAS_MANY, 'MsgOutbox', 'uid'),
            'userExt' => array(self::HAS_ONE, 'UserExt', 'uid'),
            'userMerchantExts' => array(self::HAS_MANY, 'UserMerchantExt', 'uid'),
            'userPhotos' => array(self::HAS_MANY, 'UserPhoto', 'uid'),
            'userPhotoPics' => array(self::HAS_MANY, 'UserPhotoPic', 'uid'),
            'userRels' => array(self::HAS_MANY, 'UserRel', 'from_uid'),
            'userRels1' => array(self::HAS_MANY, 'UserRel', 'to_uid'),
            'userStoresExts' => array(self::HAS_MANY, 'UserStoresExt', 'uid'),
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
            'old_password' => 'Old Password',
            'nickname' => 'Nickname',
            'sex' => 'Sex',
            'avatar' => 'Avatar',
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
            'reg_source' => 'Reg Source',
            'last_login_time' => 'Last Login Time',
            'last_login_ip' => 'Last Login Ip',
            'login_fail_times' => 'Login Fail Times',
            'reset_pwd_time' => 'Reset Pwd Time',
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
        $criteria->compare('old_password', $this->old_password, true);
        $criteria->compare('nickname', $this->nickname, true);
        $criteria->compare('sex', $this->sex);
        $criteria->compare('avatar', $this->avatar, true);
        $criteria->compare('mobile', $this->mobile, true);
        $criteria->compare('birthday', $this->birthday, true);
        $criteria->compare('address', $this->address, true);
        $criteria->compare('rid', $this->rid);
        $criteria->compare('nid', $this->nid, true);
        $criteria->compare('status', $this->status);
        $criteria->compare('config', $this->config, true);
        $criteria->compare('signature', $this->signature, true);
        $criteria->compare('integral', $this->integral, true);
        $criteria->compare('reg_time', $this->reg_time, true);
        $criteria->compare('reg_ip', $this->reg_ip, true);
        $criteria->compare('reg_source', $this->reg_source, true);
        $criteria->compare('last_login_time', $this->last_login_time, true);
        $criteria->compare('last_login_ip', $this->last_login_ip, true);
        $criteria->compare('login_fail_times', $this->login_fail_times);
        $criteria->compare('reset_pwd_time', $this->reset_pwd_time);
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

    /**
     *
     * @param int $pk
     * @param string $condition
     * @param array $params
     * @return null|object
     */
    public function findByPk($pk, $condition = '', $params = array()) {
        $user = parent::findByPk($pk, $condition, $params);
        if ($user != null && !$user->nid) {
            $userRel = new UserRel();
            $nid = $userRel->createNode(array('nickname' => $user->nickname));
            if ($nid > 0) {
                User::model()->updateByPk($user->uid, array('nid' => $nid));
            }
        }
        return $user;
    }

}