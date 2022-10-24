<?php
declare(strict_types=1);
namespace MusicApp\Core\Models;

include_once __DIR__ . '/../database.php';
include_once 'field.php';
include_once 'pagination.php';

use MusicApp\Core\Database;
use ReflectionClass;
use PDO;
use PDOStatement;

class ModelCache {
    public array $_fields = [];
    public array $_fieldKeys = [];
    public array $_reqFields = [];
    public array $_primaryKeys = [];
    public array $_foreignKeys = [];
    public bool $_isInit = false;
    public string $_qSetVal = "";
    public string $_qCondPK = "";
    public string $_qReqBinds = "";
    public string $_qReqFields = "";

    public function __construct(string $cls) {
        $class = new ReflectionClass($cls);
        foreach ($class->getProperties() as $prop) {
            $attributes = $prop->getAttributes(Field::class);
            if (count($attributes) == 1) {
                $key = $prop->getName();
                $fieldprops = [];
                foreach ($prop->getAttributes() as $fprop) {
                    $impl = class_implements($fprop->getName(), false);
                    if (in_array(FieldProperty::class, $impl)) {
                        if ($fprop->getName() == PrimaryKey::class) {
                            $this->_primaryKeys[] = $key;
                        } else if ($fprop->getName() == ForeignKey::class) {
                            $this->_foreignKeys[$key] = $fprop->newInstance();
                        }
                    }
                }
                $this->_fields[$key] = [
                    'field' => $attributes[0]->newInstance(),
                    'attributes' => $fieldprops
                ];
                $this->_fields[$key]['field']->name = $key;
            }
        }
        // Cache models
        $this->_fieldKeys = array_keys($this->_fields);
        $asstr = fn($pk) => "`$pk` = ?";
        $this->_qCondPK = implode(" AND ", array_map($asstr, $this->_primaryKeys));
        $this->_qSetVal = implode(", ", array_map($asstr, $this->_fieldKeys));
        $this->_reqFields = array_keys(array_filter(
            $this->_fields,
            fn($f) =>
                !($f['field']->nullable ?? true) &&
                !($f['field']->autoIncrement ?? false) &&
                $f['field']->default == null
        ));
        $this->_qReqBinds = implode(", ", array_map(fn() => "?", $this->_reqFields));
        $this->_qReqFields = implode(", ", $this->_reqFields);
    }
}

abstract class Model {
    protected bool $isPersisted = false;
    protected bool $isDirty = true;
    protected array $_optDirtyFields = [];

    protected static string $table = '';
    public static array $_models = [];

    protected static Database $db;

    public function __toString() : string {
        return static::$table;
    }

    protected static function fetch(string $sql, array $params, bool $all=false) : array | object | null {
        $res = static::$db->prepare($sql);
        $res->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, static::class);
        $res->execute($params);
        $models = $all ? $res->fetchAll() :  $res->fetch();
        if ($models) {
            if ($all) {
                foreach ($models as $model) {
                    $model->isPersisted = true;
                    $model->isDirty = false;
                }
            } else {
                $models->isPersisted = true;
                $models->isDirty = false;
            }
            return $models;
        } else
            return null;
    }

    public static function load() : void {
        if (in_array(static::class, array_keys(static::$_models)))
            return;
        # Cache model
        static::$_models[static::class] = new ModelCache(static::class);
        # Cache database and create table if not exist
        static::$db = Database::get();
        $foreignKeys = static::getCache('_foreignKeys');
        $fields = static::getCache('_fields');
        $primaryKeys = static::getCache('_primaryKeys');
        $sqlCreate = "CREATE TABLE IF NOT EXISTS `" . static::$table . "` (" .
            implode(", ", array_map(fn($f) => $f['field'], $fields)) .
            (count($primaryKeys) > 0 ? ', PRIMARY KEY (' . implode(", ", $primaryKeys) . ')' : '') .
            implode('', array_map(
                fn($f) =>
                    ", FOREIGN KEY (`$f`) REFERENCES `" . $foreignKeys[$f]->refModel .
                    "`(`" . $foreignKeys[$f]->refColumn . "`)",
                array_keys($foreignKeys)
            )) .
        ")";
        static::$db->exec($sqlCreate);
    }

    protected static function getCache(string $key) {
        return static::$_models[static::class]->$key;
    }

    public static function get(mixed $condPK) : ?object {
        // Usage: Model::get(1); or Model::get([1, 2]);
        $primaryKeys = static::getCache('_primaryKeys');
        $qCondPK = static::getCache('_qCondPK');
        if (!is_array($condPK)) {
            $condPK = [$condPK];
            if (count($condPK) != count($primaryKeys))
                throw new \Exception("Invalid number of primary keys");
        }
        return static::fetch(
            'SELECT * FROM `' . static::$table .
            '` WHERE ' . $qCondPK . ' LIMIT 1',
            $condPK,
            false
        );
    }

    public static function find(string $cond, array $params, string $opts=null) : ?array {
        // Usage: Model::find("name LIKE %?% AND age > ?", ['Anjay', 13], "ORDER BY name ASC");
        return static::fetch(
            'SELECT * FROM ' . static::$table . 
                ' WHERE ' . implode(' ', array_filter([$cond, $opts])),
            $params,
            true
        );
    }

    public static function paginate(string $cond, array $params, string $opts=null, Pagination $paginate=null) : Pagination {
        // Usage: Model::paginate("name LIKE %?% AND age > ?", ['Anjay', 13], "ORDER BY name ASC", new Pagination(["limit" => 5, "page" => 2]));
        // DO NOT SET LIMIT IN THE $opts!!!
        $paginate = $paginate ?? new Pagination(['limit' => 10, 'page' => 1]);
        $sql = 'SELECT %s FROM . ' . static::$table . 
            ' WHERE ' . implode(' ', array_filter([$cond, $opts]));
        $models = static::fetch(
            sprintf($sql, '*') .
            ' LIMIT ' . $paginate->offset . ', ' . $paginate->nextOffset,
            $params,
            true
        );
        $res = static::$db->prepare(sprintf($sql, 'COUNT(*)'));
        $res->execute($params);
        return new Pagination([
            'limit' => $paginate->limit,
            'page' => $paginate->page,
            'total' => $res->rowCount(),
        ], $models);
    }

    public function delete() : void {
        $qCondPK = static::getCache('_qCondPK');
        $primaryKeys = static::getCache('_primaryKeys');
        $stmt = static::$db->prepare(
            'DELETE' .
            ' FROM ' . static::$table .
            ' WHERE ' . $qCondPK
        );
        $this->bind($stmt, $primaryKeys)->execute();
        $this->isPersisted = false;
        $this->isDirty = true;
    }

    public function save() : void {
        $qSetVal = static::getCache('_qSetVal');
        $qCondPK = static::getCache('_qCondPK');
        $fieldKeys = static::getCache('_fieldKeys');
        $primaryKeys = static::getCache('_primaryKeys');
        $qReqFields = static::getCache('_qReqFields');
        $qReqBinds = static::getCache('_qReqBinds');
        $qReqFields = static::getCache('_qReqFields');
        $reqFields = static::getCache('_reqFields');
        if ($this->isDirty) {
            if ($this->isPersisted) {
                $stmt = static::$db->prepare(
                    'UPDATE ' . static::$table .
                    ' SET ' . $qSetVal .
                    ' WHERE ' . $qCondPK
                );
                $this->bind($stmt, array_merge(
                    $fieldKeys,
                    $primaryKeys
                ))->execute();
            } else {
                $stmt = static::$db->prepare(
                    'INSERT INTO ' . static::$table . ' (' . $qReqFields .
                    ') VALUES (' . $qReqBinds . ')'
                );
                $this->bind($stmt, $reqFields)->execute();
                $this->isPersisted = true;
            }
            $this->isDirty = false;
        }
    }

    public function getValues(?array $keys=null) : array {
        $keys = $keys ?? static::getCache('_fieldKeys');
        $values = [];
        foreach ($keys as $key) {
            $values[] = $this->$key;
        }
        return $values;
    }

    public function set(array $map) : void {
        foreach ($map as $key => $value) {
            if (property_exists($this, $key))
                if ($this->$key != $value)
                    $this->$key = $value;
        }
    }

    protected function bind(?PDOStatement &$stmt, ?array $keys=null) : ?PDOStatement {
        $fields = static::getCache('_fields');
        $keys = $keys ?? static::getCache('_fieldKeys');
        $values = $this->getValues($keys);
        $i = 1;
        foreach ($keys as $key) {
            $stmt->bindParam($i, $values[$i - 1], $fields[$key]['field']->type);
            $i++;
        }
        return $stmt;
    }

    public function __get(string $key) : mixed {
        if (in_array($key, static::getCache('_fieldKeys'))) {
            return $this->$key;
        } else {
            throw new \Exception("Property $key does not exist");
        }
    }

    public function __set(string $key, mixed $value) : void {
        if (in_array($key, static::getCache('_fieldKeys'))) {
            $this->$key = $value;
            $this->isDirty = true;
        } else {
            throw new \Exception("Property $key does not exist");
        }
    }
}

// Make sure you always load() the model.

?>
