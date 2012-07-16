<?php

/**
 * This is the model class for table "{{msg_inbox}}".
 *
 * The followings are the available columns in table '{{msg_inbox}}':
 * @property string $id
 * @property string $uid
 * @property string $from_uid
 * @property string $msg_id
 * @property string $msg_title
 * @property string $update_time
 * @property integer $status
 *
 * The followings are the available model relations:
 * @property User $u
 * @property User $fromU
 * @property Msg $msg
 */
class MessageInbox extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return MessageInbox the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{msg_inbox}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('uid, from_uid, msg_id, update_time', 'required'),
            array('status', 'numerical', 'integerOnly' => true),
            array('uid, from_uid, msg_id, update_time', 'length', 'max' => 10),
            array('msg_title', 'length', 'max' => 50),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, uid, from_uid, msg_id, msg_title, update_time, status', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'u' => array(self::BELONGS_TO, 'User', 'uid'),
            'fromU' => array(self::BELONGS_TO, 'User', 'from_uid'),
            'msg' => array(self::BELONGS_TO, 'Msg', 'msg_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'uid' => 'Uid',
            'from_uid' => 'From Uid',
            'msg_id' => 'Msg',
            'msg_title' => 'Msg Title',
            'update_time' => 'Update Time',
            'status' => 'Status',
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

        $criteria->compare('id', $this->id, true);
        $criteria->compare('uid', $this->uid, true);
        $criteria->compare('from_uid', $this->from_uid, true);
        $criteria->compare('msg_id', $this->msg_id, true);
        $criteria->compare('msg_title', $this->msg_title, true);
        $criteria->compare('update_time', $this->update_time, true);
        $criteria->compare('status', $this->status);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}