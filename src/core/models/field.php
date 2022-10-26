<?php
declare(strict_types=1);
namespace MusicApp\Core\Models;

include_once 'model.php';

use Attribute, PDO;

#[Attribute]
class Field {
    const C_NULLABLE = 'nullable';
    const C_AUTO_INCREMENT = 'autoIncrement';
    const C_PRIMARY = 'primary';
    const C_UNIQUE = 'unique';
    const C_DEFAULT = 'default';
    const C_CHECK = 'check';

    const T_FIELD = 'field';
    const T_ATTR = 'attributes';

    const O_LENGTH = 'length';

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
                $this->sqltype = "INT(" . ($opts[Field::O_LENGTH] ?? 11) . ")";
                break;
            case PDO::PARAM_STR:
                $this->sqltype = "VARCHAR(" . ($opts[Field::O_LENGTH] ?? 255) . ")";
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

    public function __toString() {
        return $this->name . ' ' . $this->sqltype . (
            $this->{Field::C_NULLABLE} === false ? ' NOT NULL' : ' NULL'
        ) . (
            $this->{Field::C_AUTO_INCREMENT} === true ? ' AUTO_INCREMENT' : ''
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
