<?php

/**
 * 2012-3-9 11:52:35
 * @package protected.admin.behaviors
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
 * This is the model class for table "{{area}}".
 *
 * The followings are the available columns in table '{{area}}':
 * @property string $aid
 * @property string $pid
 * @property integer $order
 * @property string $lid
 * @property string $rid
 * @property integer $depth
 * @property string $name
 */
class Area extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Area the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{area}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('order, lid, rid, depth, name', 'required'),
            array('order, depth', 'numerical', 'integerOnly' => true),
            array('pid, lid, rid, name', 'length', 'max' => 10),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('aid, pid, order, lid, rid, depth, name', 'safe', 'on' => 'search'),
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
            'aid' => 'Aid',
            'pid' => 'Pid',
            'order' => 'Order',
            'lid' => 'Lid',
            'rid' => 'Rid',
            'depth' => 'Depth',
            'name' => 'Name',
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

        $criteria->compare('aid', $this->aid, true);
        $criteria->compare('pid', $this->pid, true);
        $criteria->compare('order', $this->order);
        $criteria->compare('lid', $this->lid, true);
        $criteria->compare('rid', $this->rid, true);
        $criteria->compare('depth', $this->depth);
        $criteria->compare('name', $this->name, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}