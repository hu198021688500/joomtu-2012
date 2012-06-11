<?php

/**
 * 左右值无限级分类
 * 2012-3-12 10:55:18
 * @package protected.components
 * @version 1.0
 *
 * @author hugb <hu198021688500@163.com>
 * @copyrid (c) 2011 http://www.test.com
 * @license (http://www.apache.org/licenses/LICENSE-2.0)
 *
 * @$Id$
 * 
 */
class LRTreeComponent extends CComponent {

    protected $_db;
    protected $_table;
    protected $_fields = array(
        'id' => 'id',
        'pid' => 'pid',
        'order' => 'order',
        'lid' => 'lid',
        'rid' => 'rid',
        'depth' => 'depth'
    );

    public function getTable() {
        return $this->_table;
    }

    /**
     * 数据初始化
     * @param string $table
     * @param array $fields
     */
    public function __construct($table, $fields = array()) {
        $this->_table = $table;
        if (!empty($fields)) {
            foreach ($fields as $key => $field) {
                if (isset($this->_fields[$key])) {
                    $this->_fields[$key] = $field;
                }
            }
        }
        $this->_db = Yii::app()->db;
    }

    /**
     * 获取节点
     * @param int $id 节点ID
     * @param array $fields
     * @return false|array 节点数组
     */
    public function getNode($id, $fields = array()) {
        $fields = empty($fields) ? $this->_fields : $fields;
        $sql = 'SELECT `' . implode('`,`', $fields) . '` FROM `{{' . $this->_table . '}}` WHERE `' . $this->_fields['id'] . '` = :id';
        return $this->_db->createCommand($sql)->bindParam(':id', $id, PDO::PARAM_INT)->queryRow();
    }

    /**
     * 获取子节点
     * @param int $id 节点ID
     * @param array $fields
     * @param boolean $recursive 是否获取所有子节点
     * @return array
     */
    public function getChildrenNodes($id, $fields = array(), $recursive = false) {
        $children = array();
        $fields = empty($fields) ? $this->_fields : $fields;
        if (!in_array($this->_fields['id'], $fields)) {
            $fields[] = $this->_fields['id'];
        }
        if ($recursive) {
            $node = $this->getNode($id, array($this->_fields['lid'], $this->_fields['rid']));
            if ($node === false) {
                return $children;
            }
            //$sql = 'SELECT `' . implode('`,`', $fields) . '` FROM `{{' . $this->_table . '}}` WHERE `' . $this->_fields['lid'] . '` >= ' . $node[$this->_fields['lid']] . ' AND `' . $this->_fields['rid'] . '` <= ' . $node[$this->_fields['rid']] . ' ORDER BY `' . $this->_fields['lid'] . '` ASC';
            $sql = 'SELECT `' . implode('`,`', $fields) . '` FROM `{{' . $this->_table . '}}` WHERE `' . $this->_fields['lid'] . '` > ' . $node[$this->_fields['lid']] . ' AND `' . $this->_fields['rid'] . '` < ' . $node[$this->_fields['rid']] . ' ORDER BY `' . $this->_fields['lid'] . '` ASC';
            $dataReader = $this->_db->createCommand($sql)->query();
        } else {
            $sql = 'SELECT `' . implode('`,`', $fields) . '` FROM `{{' . $this->_table . '}}` WHERE `' . $this->_fields['pid'] . '` = :id ORDER BY `' . $this->_fields['order'] . '` ASC';
            $dataReader = $this->_db->createCommand($sql)->bindParam(':id', $id, PDO::PARAM_INT)->query();
        }
        while ($row = $dataReader->read()) {
            $children[$row[$this->_fields['id']]] = $row;
        }
        return $children;
    }

    /**
     * 获取节点的字节点ID集合
     * @param int $id
     * @param boolean $recursive
     * @return array 
     */
    public function getChildrenNodesIds($id, $recursive = false) {
        if ($recursive) {
            $node = $this->getNode($id, array($this->fields['lid'], $this->fields['rid']));
            //$sql = 'SELECT `' . $this->fields['id'] . '` FROM `{{' . $this->table . '}}` WHERE `' . $this->fields['lid'] . '` >= ' . (int) $node[$this->fields['lid']] . ' AND `' . $this->fields['rid'] . '` <= ' . (int) $node[$this->fields['rid']] . ' ORDER BY `' . $this->fields['lid'] . '` ASC';
            $sql = 'SELECT `' . $this->fields['id'] . '` FROM `{{' . $this->table . '}}` WHERE `' . $this->fields['lid'] . '` > ' . (int) $node[$this->fields['lid']] . ' AND `' . $this->fields['rid'] . '` < ' . (int) $node[$this->fields['rid']] . ' ORDER BY `' . $this->fields['lid'] . '` ASC';
            return $this->db->createCommand($sql)->queryColumn();
        } else {
            $sql = 'SELECT `' . $this->fields['id'] . '` FROM `{{' . $this->table . '}}` WHERE `' . $this->fields['pid'] . '` = :id ORDER BY `' . $this->fields['order'] . '` ASC';
            return $this->db->createCommand($sql)->bindParam(':id', $id, PDO::PARAM_INT)->queryColumn();
        }
    }

    /**
     * 获取节点路径
     * @param int $id 节点ID
     * @param array $fields
     * @return array 返回每个节点组成的数组
     */
    public function getNodePath($id, $fields = array()) {
        $node = $this->getNode($id, array($this->_fields['lid'], $this->_fields['rid']));
        if ($node === false) {
            return array();
        }
        $fields = empty($fields) ? $this->_fields : $fields;
        $sql = 'SELECT `' . implode('`,`', $fields) . '` FROM `{{' . $this->_table . '}}` WHERE `' . $this->_fields['lid'] . '` <= ' . $node[$this->_fields['lid']] . ' AND `' . $this->_fields['rid'] . '` >= ' . $node[$this->_fields['rid']];
        return $this->_db->createCommand($sql)->queryAll();
    }

    /**
     * 创建节点
     * @param int $pid 父级节点ID
     * @param int $order 节点位置
     * @return boolean 是否创建成功
     */
    public function createNode($pid, $order) {
        return $this->moveNode(0, $pid, $order);
    }

    /**
     * 更新节点数据
     * @param $id
     * @param array $data
     * @return string
     */
    public function setNodeData($id, $data) {
        $sql = 'UPDATE `{{' . $this->_table . '}}` SET `' . $this->_fields['id'] . '` = `' . $this->_fields['id'] . '` ';
        foreach ($this->_fields as $k => $v) {
            if (isset($data[$k])) {
                $sql .= ', `' . $this->_fields[$v] . '` = \'' . addslashes($data[$k]) . '\' ';
            } else {
                $sql .= ', `' . $this->_fields[$v] . '` = `' . $this->_fields[$v] . '` ';
            }
        }
        $sql .= 'WHERE `' . $this->_fields['id'] . '` = :id';
        return $this->_db->createCommand($sql)->bindParam(':id', $id, PDO::PARAM_INT)->execute();
    }

    /**
     * 删除节点
     * @param int $id 节点ID
     * @param boolea $isRMChild 是否删除其子节点
     * @return boolean 删除是否成功
     */
    public function removeNode($id, $isRMChild = true) {
        if ((int) $id === 1) {
            return false;
        }
        $childrenNodes = $this->getChildrenNodes($id, array($this->_fields['id']));
        if (!$isRMChild && !empty($childrenNodes)) {
            return false;
        }
        $data = $this->getNode($id, array($this->_fields['pid'], $this->_fields['order'], $this->_fields['lid'], $this->_fields['rid']));
        $lft = $data[$this->_fields['lid']];
        $rgt = $data[$this->_fields['rid']];
        $dif = $rgt - $lft + 1;
        // deleting node and its children
        $sql1 = 'DELETE FROM {{' . $this->_table . '}} WHERE ' . $this->_fields['lid'] . ' >= ' . $lft . ' AND ' . $this->_fields['rid'] . ' <= ' . $rgt;
        $this->_db->createCommand($sql1)->execute();
        // shift lid indexes of nodes rid of the node
        $sql2 = 'UPDATE {{' . $this->_table . '}} SET ' . $this->_fields['lid'] . ' = ' . $this->_fields['lid'] . ' - ' . $dif . ' WHERE ' . $this->_fields['lid'] . ' > ' . $rgt;
        $this->_db->createCommand($sql2)->execute();
        // shift rid indexes of nodes rid of the node and the node's parents
        $sql3 = 'UPDATE {{' . $this->_table . '}} SET ' . $this->_fields['rid'] . ' = ' . $this->_fields['rid'] . ' - ' . $dif . ' WHERE ' . $this->_fields['rid'] . ' > ' . $lft;
        $this->_db->createCommand($sql3)->execute();
        $pid = $data[$this->_fields['pid']];
        $pos = $data[$this->_fields['order']];
        // Update order of siblings below the deleted node
        $sql4 = 'UPDATE `{{' . $this->_table . '}}` SET `' . $this->_fields['order'] . '` = `' . $this->_fields['order'] . '` - 1 WHERE `' . $this->_fields['pid'] . '` = ' . $pid . ' AND `' . $this->_fields['order'] . '` > ' . $pos;
        $this->_db->createCommand($sql4)->execute();
        return true;
    }

    /**
     * 移动节点，比较繁琐的操作
     * @param int $formId 移动的节点ID
     * @param int $toId 目标节点ID
     * @param int $order 在同级节点的位置
     * @param boolean $isCopy 移动还是复制 true复制，false移动
     * @return boolean 移动是否成功
     */
    public function moveNode($formId, $toId, $order = 0, $isCopy = false) {
        if ($formId === 1 || $toId === 0) {
            return false;
        }
        $ndif = 2;
        $sqls = array();
        $nodeIds = array(-1);
        $fromNode = $this->getNode($formId, array($this->_fields['pid'], $this->_fields['order'], $this->_fields['depth'], $this->_fields['lid'], $this->_fields['rid']));

        $toNode = $this->getNode($toId, array($this->_fields['rid'], $this->_fields['depth']));
        $toNodechildrenNodes = $this->getChildrenNodes($toId, array($this->_fields['order'], $this->_fields['lid'], $this->_fields['rid']));
        $toNodechildrenNodesCount = count($toNodechildrenNodes);

        if ($fromNode !== false) {
            $nodeIds = array_keys($this->getChildrenNodes($formId, array($this->_fields['id']), true));
            if (in_array($toId, $nodeIds)) {
                return false;
            }
            $ndif = $fromNode[$this->_fields['rid']] - $fromNode[$this->_fields['lid']] + 1;
        }

        $order = $order >= $toNodechildrenNodesCount ? $toNodechildrenNodesCount : $order;

        if ($fromNode !== false && $isCopy === false) {
            $sqls[] = 'UPDATE `{{' . $this->_table . '}}` SET `' . $this->_fields['order'] . '` = `' . $this->_fields['order'] . '` - 1 WHERE `' . $this->_fields['pid'] . '` = ' . $fromNode[$this->_fields['pid']] . ' AND `' . $this->_fields['order'] . '` > ' . $fromNode[$this->_fields['order']];
            $sqls[] = 'UPDATE `{{' . $this->_table . '}}` SET `' . $this->_fields['lid'] . '` = `' . $this->_fields['lid'] . '` - ' . $ndif . ' WHERE `' . $this->_fields['lid'] . '` > ' . $fromNode[$this->_fields['rid']];
            $sqls[] = 'UPDATE `{{' . $this->_table . '}}` SET `' . $this->_fields['rid'] . '` = `' . $this->_fields['rid'] . '` - ' . $ndif . ' WHERE `' . $this->_fields['rid'] . '` > ' . $fromNode[$this->_fields['lid']] . ' AND `' . $this->_fields['id'] . '` NOT IN (' . implode(',', $nodeIds) . ')';
        }
        $sqls[] = 'UPDATE `{{' . $this->_table . '}}` SET `' . $this->_fields['order'] . '` = `' . $this->_fields['order'] . '` + 1 WHERE `' . $this->_fields['pid'] . '` = ' . $toId . ' AND `' . $this->_fields['order'] . '` >= ' . $order . ($isCopy ? '' : ' AND `' . $this->_fields['id'] . '` NOT IN (' . implode(',', $nodeIds) . ')');
        $refInd = $toId === 0 ? $toNodechildrenNodes[count($toNodechildrenNodes) - 1][$this->_fields['rid']] + 1 : $toNode[$this->_fields['rid']];
        $refInd = max($refInd, 1);
        $self = ($fromNode !== false && !$isCopy && $fromNode[$this->_fields['pid']] == $toId && $order > $fromNode[$this->_fields['order']]) ? 1 : 0;
        foreach ($toNodechildrenNodes as $node) {
            if ($node[$this->_fields['order']] - $self == $order) {
                $refInd = $node[$this->_fields['lid']];
                break;
            }
        }
        if ($fromNode !== false && !$isCopy && $fromNode[$this->_fields['lid']] < $refInd) {
            $refInd -= $ndif;
        }
        $sqls[] = 'UPDATE `{{' . $this->_table . '}}` SET `' . $this->_fields['lid'] . '` = `' . $this->_fields['lid'] . '` + ' . $ndif . ' WHERE `' . $this->_fields['lid'] . '` >= ' . $refInd . ($isCopy ? '' : ' AND `' . $this->_fields['id'] . '` NOT IN (' . implode(',', $nodeIds) . ') ');
        $sqls[] = 'UPDATE `{{' . $this->_table . '}}` SET `' . $this->_fields['rid'] . '` = `' . $this->_fields['rid'] . '` + ' . $ndif . ' WHERE `' . $this->_fields['rid'] . '` >= ' . $refInd . ($isCopy ? '' : ' AND `' . $this->_fields['id'] . '` NOT IN (' . implode(',', $nodeIds) . ') ');
        $ldif = $toId == 0 ? 0 : $toNode[$this->_fields['depth']] + 1;
        $idif = $refInd;
        if ($fromNode !== false) {
            $ldif = $fromNode[$this->_fields['depth']] - ($toNode[$this->_fields['depth']] + 1);
            $idif = $fromNode[$this->_fields['lid']] - $refInd;
            if ($isCopy) {
                $sqls[] = 'INSERT INTO `{{' . $this->_table . '}}` (`' . $this->_fields['pid'] . '`, `' . $this->_fields['order'] . '`, `' . $this->_fields['lid'] . '`, `' . $this->_fields['rid'] . '`, `' . $this->_fields['depth'] . '`) SELECT ' . $toId . ', `' . $this->_fields['order'] . '`, `' . $this->_fields['lid'] . '` - (' . ($idif + ($fromNode[$this->_fields['lid']] >= $refInd ? $ndif : 0)) . '), `' . $this->_fields['rid'] . '` - (' . ($idif + ($fromNode[$this->_fields['lid']] >= $refInd ? $ndif : 0)) . '), `' . $this->_fields['depth'] . '` - (' . $ldif . ') FROM `{{' . $this->_table . '}}` WHERE `' . $this->_fields['id'] . '` IN (' . implode(',', $nodeIds) . ') ORDER BY `' . $this->_fields['depth'] . '` ASC';
            } else {
                $sqls[] = 'UPDATE `{{' . $this->_table . '}}` SET `' . $this->_fields['pid'] . '` = ' . $toId . ', `' . $this->_fields['order'] . '` = ' . $order . ' WHERE `' . $this->_fields['id'] . '` = ' . $formId;
                $sqls[] = 'UPDATE `{{' . $this->_table . '}}` SET `' . $this->_fields['lid'] . '` = `' . $this->_fields['lid'] . '` - (' . $idif . '), `' . $this->_fields['rid'] . '` = `' . $this->_fields['rid'] . '` - (' . $idif . '), `' . $this->_fields['depth'] . '` = `' . $this->_fields['depth'] . '` - (' . $ldif . ') WHERE `' . $this->_fields['id'] . '` IN (' . implode(',', $nodeIds) . ') ';
            }
        } else {
            $sqls[] = 'INSERT INTO `{{' . $this->_table . '}}` (`' . $this->_fields['pid'] . '`, `' . $this->_fields['order'] . '`, `' . $this->_fields['lid'] . '`, `' . $this->_fields['rid'] . '`, `' . $this->_fields['depth'] . '`) VALUES (' . $toId . ', ' . $order . ', ' . $idif . ', ' . ($idif + 1) . ', ' . $ldif . ')';
        }
        foreach ($sqls as $sql) {
            $this->_db->createCommand($sql)->execute();
        }
        $ind = $this->_db->getLastInsertID();
        if ($isCopy) {
            $this->fixCopy($ind, $order);
        }
        return $fromNode === false || $isCopy ? $ind : true;
    }

    /**
     * 修正复制的节点的位置
     * @param int $id 节点ID
     * @param int $order 节点位置
     */
    public function fixCopy($id, $order) {
        $node = $this->getNode($id, array($this->_fields['lid'], $this->_fields['rid']));
        $children = $this->getChildrenNodes($id, array($this->_fields['id'], $this->_fields['lid'], $this->_fields['rid']), true);
        $map = array();
        for ($i = $node[$this->_fields['lid']] + 1; $i < $node[$this->_fields['rid']]; $i++) {
            $map[$i] = $id;
        }
        foreach ($children as $child) {
            if ($child[$this->_fields['id']] == $id) {
                $sql = 'UPDATE `{{' . $this->_table . '}}` SET `' . $this->_fields['order'] . '` = ' . $order . ' WHERE `' . $this->_fields['id'] . '` = ' . $child[$this->_fields['id']];
                $this->_db->createCommand($sql)->execute();
                continue;
            }
            $sql = 'UPDATE `{{' . $this->_table . '}}` SET `' . $this->_fields['pid'] . '` = ' . $map[$child[$this->_fields['lid']]] . ' WHERE `' . $this->_fields['id'] . '` = ' . $child[$this->_fields['id']];
            $this->_db->createCommand($sql)->execute();
            for ($i = $child[$this->_fields['lid']] + 1; $i < $child[$this->_fields['rid']]; $i++) {
                $map[$i] = $cid;
            }
        }
    }

    /**
     * 清空数据，默认创建根节点
     * (按需求需要重写此方法)
     * @return int
     */
    public function reset() {
        $sql = 'TRUNCATE TABLE `{{' . $this->_table . '}}`';
        $this->_db->createCommand($sql)->execute();
        $sql = 'INSERT INTO `{{' . $this->_table . '}}` (`' . $this->_fields['id'] . '`, `' . $this->_fields['pid'] . '`, `' . $this->_fields['order'] . '`, `' . $this->_fields['lid'] . '`, `' . $this->_fields['rid'] . '`, `' . $this->_fields['depth'] . '` ' . ') VALUES (1, 0, 0, 1, 2, 0 )';
        return $this->_db->createCommand($sql)->execute();
    }

    /**
     * 创建表
     * (按需求需要重写此方法)
     * @return int
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
     * 刷新结构
     */
    public function reconstruct() {
        $sql1 = 'CREATE TEMPORARY TABLE `{{temp_tree}}` (`' . $this->_fields['id'] . '` INTEGER NOT NULL, `' . $this->_fields['pid'] . '` INTEGER NOT NULL, `' . $this->_fields['order'] . '` INTEGER NOT NULL) type=HEAP';
        $this->_db->createCommand($sql1)->execute();

        $sql2 = 'INSERT INTO `{{temp_tree}}` SELECT `' . $this->_fields['id'] . '`, `' . $this->_fields['pid'] . '`, `' . $this->_fields['order'] . '` FROM `{{' . $this->_table . '}}`';
        $this->_db->createCommand($sql2)->execute();

        $sql3 = 'CREATE TEMPORARY TABLE `{{temp_stack}}` (`' . $this->_fields['id'] . '` INTEGER NOT NULL, `' . $this->_fields['lid'] . '` INTEGER, `' . $this->_fields['rid'] . '` INTEGER, `' . $this->_fields['depth'] . '` INTEGER, `stack_top` INTEGER NOT NULL, `' . $this->_fields['pid'] . '` INTEGER, `' . $this->_fields['order'] . '` INTEGER) type=HEAP';
        $this->_db->createCommand($sql3)->execute();

        $counter = 2;
        $row = $this->_db->createCommand('SELECT COUNT(*) as num FROM {{temp_tree}}')->queryRow();

        $sql4 = 'INSERT INTO `{{temp_stack}}` SELECT `' . $this->_fields['id'] . '`, 1, NULL, 0, 1, `' . $this->_fields['pid'] . '`, `' . $this->_fields['order'] . '` FROM `{{temp_tree}}` WHERE `' . $this->_fields['pid'] . '` = 0';
        $this->_db->createCommand($sql4)->execute();

        $this->_db->createCommand('DELETE FROM `{{temp_tree}}` WHERE `' . $this->_fields['pid'] . '` = 0')->execute();

        $currenttop = 1;
        while ($counter <= (2 * $row['num'])) {
            $sql5 = 'SELECT `{{temp_tree}}`.`' . $this->_fields['id'] . '` AS tempmin, `{{temp_tree}}`.`' . $this->_fields['pid'] . '` AS pid, `{{temp_tree}}`.`' . $this->_fields['order'] . '` AS lid FROM `{{temp_stack}}`, `{{temp_tree}}` WHERE `{{temp_stack}}`.`' . $this->_fields['id'] . '` = `{{temp_tree}}`.`' . $this->_fields['pid'] . '` AND `{{temp_stack}}`.`stack_top` = ' . $currenttop . ' ORDER BY `{{temp_tree}}`.`' . $this->_fields['order'] . '` ASC LIMIT 1';
            $row = $this->_db->createCommand($sql5)->queryRow();
            if ($row !== false) {
                $tmp = stripslashes($row['tempmin']);
                $sql6 = 'INSERT INTO {{temp_stack }}(stack_top, `' . $this->_fields['id'] . '`, `' . $this->_fields['lid'] . '`, `' . $this->_fields['rid'] . '`, `' . $this->_fields['depth'] . '`, `' . $this->_fields['pid'] . '`, `' . $this->_fields['order'] . '`) VALUES(' . ($currenttop + 1) . ', ' . $tmp . ', ' . $counter . ', NULL, ' . $currenttop . ', ' . stripslashes($row['pid']) . ', ' . stripslashes($row['lid']) . ')';
                $this->_db->createCommand($sql6)->execute();
                $this->_db->createCommand('DELETE FROM `{{temp_tree}}` WHERE `' . $this->_fields['id'] . '` = ' . $tmp)->execute();
                $counter++;
                $currenttop++;
            } else {
                $this->_db->createCommand('UPDATE {{temp_stack}} SET `' . $this->_fields['rid'] . '` = ' . $counter . ', `stack_top` = -`stack_top` WHERE `stack_top` = ' . $currenttop)->execute();
                $counter++;
                $currenttop--;
            }
        }

        $tempFields = $this->_fields;
        unset($tempFields['pid']);
        unset($tempFields['order']);
        unset($tempFields['lid']);
        unset($tempFields['rid']);
        unset($tempFields['depth']);
        if (count($tempFields) > 1) {
            $this->_db->createCommand('CREATE TEMPORARY TABLE `{{temp_tree2}}` SELECT `' . implode('`, `', $tempFields) . '` FROM `{{' . $this->_table . '}}` ')->execute();
        }
        $this->_db->createCommand('TRUNCATE TABLE `{{' . $this->_table . '}}`')->execute();
        $sql7 = 'INSERT INTO {{' . $this->_table . '}} (`' . $this->_fields['id'] . '`, `' . $this->_fields['pid'] . '`, `' . $this->_fields['order'] . '`, `' . $this->_fields['lid'] . '`, `' . $this->_fields['rid'] . '`, `' . $this->_fields['depth'] . '`) SELECT `' . $this->_fields['id'] . '`, `' . $this->_fields['pid'] . '`, `' . $this->_fields['order'] . '`, `' . $this->_fields['lid'] . '`, `' . $this->_fields['rid'] . '`, `' . $this->_fields['depth'] . '` FROM {{temp_stack}} ORDER BY `' . $this->_fields['id'] . '`';
        $this->_db->createCommand($sql7)->execute();
        if (count($tempFields) > 1) {
            $sql8 = 'UPDATE `{{' . $this->_table . '}}` v, `{{temp_tree2}}` SET v.`' . $this->_fields['id'] . '` = v.`' . $this->_fields['id'] . '` ';
            foreach ($tempFields as $k => $v) {
                if ($k != 'id') {
                    $sql8 .= ', v.`' . $v . '` = `temp_tree2`.`' . $v . '` ';
                }
            }
            $sql8 .= ' WHERE v.`' . $this->_fields['id'] . '` = `temp_tree2`.`' . $this->_fields['id'] . '` ';
            $this->_db->createCommand($sql8)->execute();
        }
    }

    /**
     * 分析结构是否正确
     * @return string
     */
    public function analyze() {
        $report = array();
        $nodes = $this->_db->createCommand('SELECT `' . $this->_fields['lid'] . '` FROM `{{' . $this->_table . '}}` WHERE `' . $this->_fields['pid'] . '` = 0')->queryAll();
        $row = $nodes == false ? null : $nodes[0];
        $rowNum = count($nodes);
        $report[] = $rowNum == 0 ? '[错误]没有根节点。' : ($rowNum > 1) ? '[错误]存在多个根节点。' : '[正确]只有一个根节点。';

        $report[] = (isset($row[$this->_fields['lid']]) && $row[$this->_fields['lid']] != 1) ? '[错误]根节点的lid不为1。' : '[正确]根节点的lid为1。';

        $sql1 = 'SELECT COUNT(*) AS num FROM `{{' . $this->_table . '}}` AS one WHERE one.`' . $this->_fields['pid'] . '` != 0 AND (SELECT COUNT(*) FROM `{{' . $this->_table . '}}` AS two WHERE two.`' . $this->_fields['id'] . '` = one.`' . $this->_fields['pid'] . '`) = 0 ';
        $row = $this->_db->createCommand($sql1)->queryRow();
        $report[] = ($row['num'] > 0) ? '[错误]缺少父级节点。' : '[正确]不缺父级节点。';

        $row1 = $this->_db->createCommand('SELECT MAX(`' . $this->_fields['rid'] . '`) as num FROM `{{' . $this->_table . '}}`')->queryRow();
        $row2 = $this->_db->createCommand('SELECT COUNT(*) as num FROM `{{' . $this->_table . '}}`')->queryRow();
        $report[] = ($row1['num'] / 2 != $row2['num']) ? '[错误]节点的rid和节点个数不一致。' : '[正确]节点的rid和节点个数一致。';

        $sql2 = 'SELECT COUNT(`' . $this->_fields['id'] . '`) as num FROM `{{' . $this->_table . '}}` s WHERE (SELECT COUNT(*) FROM `{{' . $this->_table . '}}` WHERE `' . $this->_fields['rid'] . '` < s . `' . $this->_fields['rid'] . '` AND `' . $this->_fields['lid'] . '` > s . `' . $this->_fields['lid'] . '` AND `' . $this->_fields['depth'] . '` = s . `' . $this->_fields['depth'] . '` + 1) != (SELECT COUNT(*) FROM `{{' . $this->_table . '}}` WHERE `' . $this->_fields['pid'] . '` = s . `' . $this->_fields['id'] . '`) ';
        $row3 = $this->_db->createCommand($sql2)->queryRow();
        $report[] = ($row3['num'] > 0) ? '[错误]邻接和嵌套集不匹配。' : '[正确]邻接和嵌套集匹配。';
        return implode('<br />', $report);
    }

    /**
     * 输出结构
     * @param boolean $output 是否输出结构
     * @return string|array 
     */
    public function dump($output = false) {
        $nodes = $this->_db->createCommand('SELECT * FROM {{' . $this->_table . '}} ORDER BY `' . $this->_fields['lid'] . '`')->queryAll();
        if ($output) {
            echo '<pre>';
            if ($nodes !== false) {
                foreach ($nodes as $node) {
                    echo str_repeat('&#160;', $node[$this->_fields['depth']] * 2);
                    echo $node[$this->_fields['id']] . ' (' . $node[$this->_fields['lid']] . ',' . $node[$this->_fields['rid']] . ',';
                    echo $node[$this->_fields['depth']] . ',' . $node[$this->_fields['pid']] . ',' . $node[$this->_fields['order']] . ')<br />';
                }
                echo str_repeat('-', 40);
            }
            echo '</pre>';
        }
        return $nodes;
    }

}