<?php
namespace MusicApp\Core\Models;

include_once __DIR__ . '/../database.php';
include_once 'field.php';
include_once 'pagination.php';

use JsonSerializable;
use MusicApp\Core\Database;
use ReflectionClass;
use PDO;
use PDOStatement;

class ModelCache {
    public array $_fields = [];
    public array $_primaryKeys = [];
    public array $_foreignKeys = [];
    public bool $_isInit = false;
    public string $_qCondPK = "";

    public function getFieldsWithAttr(string $filter, bool $map=false) : array {
        $res = $this->getFields(fn($_, $a) => in_array($filter, array_keys($a)));
        if ($map) $res = array_map(fn($a) => $a[Field::T_ATTR][$filter], $res);
        return $res;
    }

    public function getFieldsWithOpt(string $filter, mixed $val=null) : array {
        return $this->getFields(fn($f, $_) => $f->$filter !== null && ($val == null || $f->$filter == $val));
    }

    public function getFields(callable $callback) : array {
        return array_filter($this->_fields, fn($f) => $callback($f[Field::T_FIELD], $f[Field::T_ATTR]));
    }

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
                        $fieldprops[$fprop->getName()] = $fprop->newInstance();
                    }
                }
                $this->_fields[$key] = [
                    Field::T_FIELD => $attributes[0]->newInstance(),
                    Field::T_ATTR => $fieldprops
                ];
                $this->_fields[$key][Field::T_FIELD]->name = $key;
            }
        }
        // Cache models
        $this->_fieldKeys = array_keys($this->_fields);
        $this->_primaryKeys = array_keys($this->getFieldsWithOpt(Field::C_PRIMARY, true));
        $this->_foreignKeys = $this->getFieldsWithAttr(ForeignKey::class, true);
        $this->_qCondPK = implode(" AND ", array_map(fn($pk) => "`$pk` = ?", $this->_primaryKeys));
    }
}

abstract class Model implements JsonSerializable {
    protected bool $isPersisted = false;
    protected bool $isDirty = true;

    protected static string $table = '';
    protected static array $_models = [];

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
        $c = static::getCache();
        $foreignKeys = $c->_foreignKeys;
        $fields = $c->_fields;
        $primaryKeys = $c->_primaryKeys;
        $sqlCreate = "CREATE TABLE IF NOT EXISTS `" . static::$table . "` (" .
            implode(", ", array_map(fn($f) => $f[Field::T_FIELD], $fields)) .  // field definitions
            (count($primaryKeys) > 0 ? ', PRIMARY KEY (' . implode(", ", $primaryKeys) . ')' : '') . // primary keys
            implode('', array_map(  // foreign keys
                fn($f) =>
                    ", FOREIGN KEY (`$f`) REFERENCES `" . $foreignKeys[$f]->refModel .
                    "`(`" . $foreignKeys[$f]->refColumn . "`) " . $foreignKeys[$f]->opt,
                array_keys($foreignKeys)
            )) .
        ")";
        static::$db->exec($sqlCreate);
    }

    public static function getCache() : ModelCache {
        return static::$_models[static::class];
    }

    public static function get(mixed $condPK) : ?object {
        // Usage: Model::get(1); or Model::get([1, 2]);
        $c = static::getCache();
        $primaryKeys = $c->_primaryKeys;
        $qCondPK = $c->_qCondPK;
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
                ' ' . implode(' ', array_filter([$cond ? 'WHERE ' . $cond : '', $opts])), // array_filter to remove empty cond or opts
            $params,
            true
        );
    }

    public static function paginate(string $cond, array $params, string $opts=null, array $paginate=null) : Pagination {
        // Usage: Model::paginate("name LIKE %?% AND age > ?", ['Anjay', 13], "ORDER BY name ASC", new Pagination(["limit" => 5, "page" => 2]));
        // DO NOT SET LIMIT IN THE $opts!!!
        $paginate = $paginate ?? [Pagination::LIMIT => 10, Pagination::PAGE => 1];
        $paginate = new Pagination($paginate);

        $sql = 'SELECT %s FROM ' . static::$table . 
            ' ' . implode(' ', array_filter([$cond ? 'WHERE ' . $cond : '', $opts]));
        $models = static::fetch(
            sprintf($sql, '*') .
            ' LIMIT ' . $paginate->offset . ', ' . $paginate->limit,
            $params,
            true
        );
        $res = static::$db->prepare(sprintf($sql, 'COUNT(*)'));
        $res->execute($params);
        return new Pagination([
            Pagination::LIMIT => $paginate->limit,
            Pagination::PAGE => $paginate->page,
            Pagination::TOTAL => intval($res->fetch()[0]),
        ], $models);
    }

    public function delete() : void {
        $c = static::getCache();
        $stmt = static::$db->prepare(
            'DELETE' .
            ' FROM ' . static::$table .
            ' WHERE ' . $c->_qCondPK
        );
        $this->bind($stmt, $c->_primaryKeys)->execute();
        $this->isPersisted = false;
        $this->isDirty = true;
    }

    public function save() : void {
        $c = static::getCache();
        $fieldKeys = array_keys($c->_fields);
        if ($this->isDirty) {
            if ($this->isPersisted) {
                $stmt = static::$db->prepare(
                    'UPDATE ' . static::$table .
                    ' SET ' . implode(', ', array_map(fn($pk) => "`$pk` = ?", array_keys($c->_fields))) .
                    ' WHERE ' . $c->_qCondPK
                );
                $this->bind($stmt, array_merge($fieldKeys, $c->_primaryKeys))->execute();
            } else {
                $reqFields = array_keys($c->getFields(fn($f, $a) =>
                    $this->{$f->name} || ($f->{Field::C_DEFAULT} === null &&
                    !($f->{Field::C_NULLABLE} ?? true) && !($f->{Field::C_AUTO_INCREMENT} ?? false))
                )); // var is set/not empty OR [default not set, not (nullable (or not set)), not autoIncrement (or not set)]
                $stmt = static::$db->prepare(
                    'INSERT INTO ' . static::$table . ' (' . implode(',', $reqFields) .
                    ') VALUES (' . implode(',', array_fill(0, count($reqFields), '?')) . ')'
                );
                $this->bind($stmt, $reqFields)->execute();
                // Repopulate model
                $idf = $c->getFieldsWithOpt(Field::C_AUTO_INCREMENT, true); // lastInsertId
                $idf_keys = array_keys($idf);
                if ($idf && !in_array($idf[$idf_keys[0]][Field::T_FIELD]->name, $reqFields))
                    $this->{$idf[$idf_keys[0]][Field::T_FIELD]->name} = intval(static::$db->lastInsertId());
                $idf = $c->getFieldsWithOpt(Field::C_DEFAULT); // default field that was unset
                foreach ($idf as $f)
                    if (!in_array($f[Field::T_FIELD]->name, $reqFields))
                        $this->{$f[Field::T_FIELD]->name} = $f[Field::T_FIELD]->{Field::C_DEFAULT};
                $this->isPersisted = true;
            }
            $this->isDirty = false;
        }
    }

    protected function getValues(?array $keys=null) : array {
        return array_map(fn($k) => $this->$k, $keys ?? static::getCache()->_fieldKeys);
    }

    public function set(array $map) : void {
        foreach ($map as $key => $value) $this->$key = $value;
    }

    protected function bind(?PDOStatement &$stmt, ?array $keys=null) : ?PDOStatement {
        $fields = static::getCache()->_fields;
        $keys = $keys ?? array_keys($fields);
        $values = $this->getValues($keys);
        $i = 1;
        foreach ($keys as $key) {
            $stmt->bindParam($i, $values[$i - 1], $fields[$key][Field::T_FIELD]->type);
            $i++;
        }
        return $stmt;
    }

    public function validate(array $validator) : array {
        $errors = [];
        $fields = static::getCache()->_fields;
        foreach (array_keys($validator) as $key) {
            $v = Validation::validate(
                $validator[$key], // the validator options
                $key, // the field name
                $this->$key,  // the field value
                $fields[$key] // field key
            );
            if ($v) $errors[$key] = ucfirst($v);
        }
        return $errors;
    }

    public function __get(string $key) : mixed {
        if (in_array($key, static::getCache()->_fieldKeys)) return $this->$key;
        else throw new \Exception("Property $key does not exist");
    }

    public function __set(string $key, mixed $value) : void {
        $c = static::getCache();
        $this->__get($key); // let throw except from get
        if ($this->$key != $value) {
            // Use setter if available
            $setter = $c->_fields[$key][Field::T_ATTR];
            if (in_array(Setter::class, array_keys($setter))) {
                $this->{$setter[Setter::class]->methodName}($value);
            } else $this->$key = $value;
            $this->isDirty = true;
        }
    }

    public function jsonSerialize() : mixed {
        return array_map(fn($k) => $this->{$k[Field::T_FIELD]->name}, static::getCache()->_fields);
    }
}

// Make sure you always load() the model.

?>
