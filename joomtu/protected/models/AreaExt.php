<?php

/**
 * 2012-3-28 11:46:28 UTF-8
 * @package protected.models
 * @version 1.0
 *
 * @author hugb <hu198021688500@163.com>
 * @copyright (c) 2011 http://www.test.com
 * @license (http://www.apache.org/licenses/LICENSE-2.0)
 *
 * @$Id$
 * 
 */

/**
 * Description of AreaExt
 *
 * @author hadoop
 */
class AreaExt extends LRTreeComponent {

    private $_addFields;

    /**
     * 初始化数据
     * @param string $table 表名称
     * @param array $fields 覆盖默认的基本表字段
     * @param array $addFields 添加的新的附加字段
     */
    public function __construct($table = 'area', $fields = array('id' => 'aid'), $addFields = array('name' => 'name')) {
        parent::__construct($table, $fields);
        $this->_addFields = $addFields;
        $this->_fields = array_merge($this->_fields, $addFields);
    }

    /**
     * 创建区域
     * @param int $pid
     * @param int $order
     * @param array $data
     * 	array(
     *      'other key' => 'other value'
     * 	)
     * @return boolean
     */
    public function createArea($pid, $order, $data) {
        $aid = parent::createNode($pid, $order);
        if ($aid > 0) {
            return $this->setAreaData($aid, $data);
        } else {
            return false;
        }
    }

    /**
     * 更新区域数据
     * @param int $aid
     * @param array $data
     *  array(
     *      'other key'=>'value'
     *  )
     * @return int 返回更新的记录数
     */
    public function setAreaData($aid, $data) {
        if (count($this->_addFields) == 0) {
            return false;
        }
        $sql = 'UPDATE `{{' . $this->_table . '}}` SET `' . $this->_fields['id'] . '` = `' . $this->_fields['id'] . '` ';
        foreach ($this->_addFields as $k => $v) {
            if (isset($data[$k])) {
                $sql .= ', `' . $this->_fields[$v] . '` = \'' . addslashes($data[$k]) . '\' ';
            } else {
                $sql .= ', `' . $this->_fields[$v] . '` = `' . $this->_fields[$v] . '` ';
            }
        }
        $sql .= 'WHERE `' . $this->_fields['id'] . '` = :id';
        return $this->_db->createCommand($sql)->bindParam(':id', $aid, PDO::PARAM_INT)->execute();
    }

    /**
     * 删除区域
     * @param array $ids
     * @return int
     */
    public function removeArea($aid) {
        return parent::removeNode($aid);
    }

    /**
     * 创建商品分类表
     * @return boolean 
     */
    public function createTable() {
        $this->_db->createCommand('DROP TABLE IF EXISTS `{{' . $this->_table . '}}`')->execute();
        $sql = "CREATE TABLE IF NOT EXISTS `{{" . $this->_table . "}}` (
            `aid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '地区ID',
            `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父级ID',
            `order` smallint(5) unsigned NOT NULL COMMENT '同级下所在位置',
            `lid` int(10) unsigned NOT NULL COMMENT '节点左ID',
            `rid` int(10) unsigned NOT NULL COMMENT '右节点ID',
            `depth` smallint(5) unsigned NOT NULL COMMENT '节点所在树的深度',
            `name` varchar(10) NOT NULL COMMENT '名称',
            PRIMARY KEY (`aid`),
            KEY `pid` (`pid`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='地区信息' AUTO_INCREMENT=1;";
        return $this->_db->createCommand($sql)->execute();
    }

    /**
     * 清空数据，默认创建根节点
     * @return int
     */
    public function reset() {
        $this->_db->createCommand('TRUNCATE TABLE `{{' . $this->_table . '}}`')->execute();
        return $this->_db->createCommand('INSERT INTO `{{' . $this->_table . '}}` (`aid`, `pid`, `order`, `lid`, `rid`, `depth`, `name`, `type`) VALUES (1, 0, 0, 1, 2, 0, "中国", 0)')->execute();
    }

}

?>
