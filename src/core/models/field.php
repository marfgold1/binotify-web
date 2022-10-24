<?php
declare(strict_types=1);
namespace MusicApp\Core\Models;

include_once 'model.php';

use Attribute, PDO;

#[Attribute]
class Field {
    public string $name;
    public int $type;
    protected string $sqltype="VARCHAR(255)";
    protected array $opts;

    public function __construct($opts=[], int $type=PDO::PARAM_STR) {
        // Usage #[Field(['nullable'=>true, 'autoIncrement'=>true, 'default'=>0], PDO::PARAM_INT)]
        $this->type = $type;
        $this->opts = $opts;
        switch ($type) {
            case PDO::PARAM_INT:
                $this->sqltype = "INT(" . ($opts['length'] ?? 11) . ")";
                break;
            case PDO::PARAM_STR:
                $this->sqltype = "VARCHAR(" . ($opts['length'] ?? 255) . ")";
                break;
            case PDO::PARAM_BOOL:
                $this->sqltype = "BOOLEAN";
                break;
            case PDO::PARAM_LOB:
                $this->sqltype = "BLOB";
                break;
        }
    }

    public function __get($name) {
        if (array_key_exists($name, $this->opts)) {
            return $this->opts[$name];
        } else {
            return null;
        }
    }

    public function __toString()
    {
        return $this->name . ' ' . $this->sqltype . (
            $this->nullable ? ' NULL' : ' NOT NULL'
        ) . (
            $this->autoIncrement ? ' AUTO_INCREMENT' : ''
        ) . (
            $this->default ? ' DEFAULT ' . $this->default : ''
        );
    }
}

interface FieldProperty {}

#[Attribute]
class PrimaryKey implements FieldProperty {}

#[Attribute]
class ForeignKey implements FieldProperty {
    public string $refModel;
    public string $refColumn;

    public function __construct(string $refModel, string $refColumn) {
        $this->refModel = $refModel;
        $this->refColumn = $refColumn;
    }
}
?>
