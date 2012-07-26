<?php

/**
 * 2012-7-25 14:06:23 UTF-8
 * @package
 * @version 1.0
 *
 * @author hugb <hu198021688500@163.com>
 * @copyright (c) 2011-2015
 * @license ()
 *
 * $Id$
 *
 */

/**
 * Description of Mysql
 */
class Mysql {

    private $__pdo;
    private $__pdoStatement;
    private $__tablePrefix;
    private static $__instance;

    public function __construct($config) {
        try {
            $this->__pdo = new PDO($config['dsn'], $config['username'], $config['password']);
        } catch (PDOException $e) {
            exit('connect faild:' . $e->getMessage());
        }
        $this->__pdo->query('SET NAMES UTF8');
    }

    public static function getInstance() {
        if (self::$__instance === null) {
            self::$__instance = new include_database();
        }
        return self::$__instance;
    }

    public function getFields($table) {
        $table = empty($this->__tablePrefix) ? $table : $this->__tablePrefix . $table;
        $this->__pdoStatement = $this->__pdo->query("DESCRIBE $table");
        $this->getPDOError();
        $this->__pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $result = $this->__pdoStatement->fetchAll();
        $this->__pdoStatement = null;
        return $result;
    }

    private function getCode($table, $args) {
        $allTables = require_once(DOCUMENT_ROOT . '/cache/tables.php');
        if (!is_array($allTables[$table])) {
            exit('表名错误或未更新缓存!');
        }
        $tables = array_flip($allTables[$table]);
        $unarr = array_diff_key($args, $tables);
        if (is_array($unarr)) {
            foreach ($unarr as $k => $v) {
                unset($args[$k]);
            }
        }
        $code = '';
        if (is_array($args)) {
            foreach ($args as $k => $v) {
                if ($v == '') {
                    continue;
                }
                $code .= "`$k`='$v',";
            }
        }
        $code = substr($code, 0, -1);
        return $code;
    }

    //插入数据
    public function insert($table, $args, $debug = null) {
        $sql = "INSERT INTO `$table` SET ";
        $code = $this->getCode($table, $args);
        $sql .= $code;
        if ($debug)
            echo $sql;
        if ($this->__pdo->exec($sql)) {
            $this->getPDOError();
            return $this->__pdo->lastInsertId();
        }
        return false;
    }

    //查询数据
    public function fetch($table, $condition = '', $sort = '', $limit = '', $field = '*', $debug = false) {
        $sql = "SELECT {$field} FROM `{$table}`";
        if (false !== ($con = $this->getCondition($condition))) {
            $sql .= $con;
        }
        if ($sort != '') {
            $sql .= " ORDER BY $sort";
        }
        if ($limit != '') {
            $sql .= " LIMIT $limit";
        }
        if ($debug)
            echo $sql;
        $this->__pdoStatement = $this->__pdo->query($sql);
        $this->getPDOError();
        $this->__pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $result = $this->__pdoStatement->fetchAll();
        $this->__pdoStatement = null;
        return $result;
    }

    //查询数据
    public function fetchOne($table, $condition = null, $field = '*', $debug = false) {
        $sql = "SELECT {$field} FROM `{$table}`";
        if (false !== ($con = $this->getCondition($condition))) {
            $sql .= $con;
        }
        if ($debug)
            echo $sql;
        $this->__pdoStatement = $this->__pdo->query($sql);
        $this->getPDOError();
        $this->__pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        $result = $this->__pdoStatement->fetch();
        $this->__pdoStatement = null;
        return $result;
    }

    //获取查询条件
    public function getCondition($condition = '') {
        if ($condition != '') {
            $con = ' WHERE';
            if (is_array($condition)) {
                $i = 0;
                foreach ($condition as $k => $v) {
                    if ($i != 0) {
                        $con .= " AND $k = '$v'";
                    } else {
                        $con .= " $k = '$v'";
                    }
                    $i++;
                }
            } elseif (is_string($condition)) {
                $con .= " $condition";
            } else {
                return false;
            }
            return $con;
        }
        return false;
    }

    //获取记录总数
    public function counts($table, $condition = '', $debug = false) {
        $sql = "SELECT COUNT(*) AS num FROM `$table`";
        if (false !== ($con = $this->getCondition($condition))) {
            $sql .= $con;
        }
        if ($debug)
            echo $sql;
        $count = $this->__pdo->query($sql);
        $this->getPDOError();
        return $count->fetchColumn();
    }

    //按SQL语句查询
    public function doSql($sql, $model = 'many', $debug = false) {
        if ($debug)
            echo $sql;
        $this->__pdoStatement = $this->__pdo->query($sql);
        $this->getPDOError();
        $this->__pdoStatement->setFetchMode(PDO::FETCH_ASSOC);
        if ($model == 'many') {
            $result = $this->__pdoStatement->fetchAll();
        } else {
            $result = $this->__pdoStatement->fetch();
        }
        $this->__pdoStatement = null;
        return $result;
    }

    //修改数据
    public function update($table, $args, $condition, $debug = null) {
        $code = $this->getCode($table, $args);
        $sql = "UPDATE `$table` SET ";
        $sql .= $code;
        if (false !== ($con = $this->getCondition($condition))) {
            $sql .= $con;
        }
        if ($debug)
            echo $sql;
        if (($rows = $this->__pdo->exec($sql)) > 0) {
            $this->getPDOError();
            return $rows;
        }
        return false;
    }

    //字段递增
    public function increase($table, $condition, $field, $debug = false) {
        $sql = "UPDATE `$table` SET $field = $field + 1";
        if (false !== ($con = $this->getCondition($condition))) {
            $sql .= $con;
        }
        if ($debug)
            echo $sql;
        if (($rows = $this->__pdo->exec($sql)) > 0) {
            $this->getPDOError();
            return $rows;
        }
        return false;
    }

    //删除记录
    public function del($table, $condition, $debug = false) {
        $sql = "DELETE FROM `$table`";
        if (false !== ($con = $this->getCondition($condition))) {
            $sql .= $con;
        } else {
            exit('条件错误!');
        }
        if ($debug)
            echo $sql;
        if (($rows = $this->__pdo->exec($sql)) > 0) {
            $this->getPDOError();
            return $rows;
        } else {
            return false;
        }
    }

    public function execute($sql) {
        $this->__pdo->exec($sql);
        $this->getPDOError();
    }

    private function getError() {
        if ($this->__pdo->errorCode() != '00000') {
            $error = $this->__pdo->errorInfo();
            exit($error[2]);
        }
    }

    public function __destruct() {
        $this->__pdo = null;
    }

}

