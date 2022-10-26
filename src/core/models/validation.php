<?php
namespace MusicApp\Core\Models;

use MusicApp\Core\Database;

class Validation {
    const TYPE = 'validationType';
    const MIN = 'min';
    const MAX = 'max';
    const REGEX = 'regex';
    const REQUIRED = 'required';
    const UNIQUE = 'unique';
    const FOREIGN = 'foreign';

    const T_EMAIL = 'email';
    const T_USERNAME = 'username';
    const T_STRING = 'string';
    const T_INT = 'int';
    const T_BOOL = 'bool';

    const ERROR_MESSAGE = [
        Validation::REGEX => 'Invalid format for field %s',
        Validation::MIN => '%s is too short',
        Validation::MAX => '%s is too long',
        Validation::REQUIRED => '%s is required',
        Validation::UNIQUE => '%s is already taken',
        Validation::FOREIGN => '%s is not exist in %s',
        Validation::T_EMAIL => '%s is not a valid email',
        Validation::T_USERNAME => '%s is not a valid username (consist of alphanumeric characters or underscores only and must be between 8 and 255 characters long)',
        Validation::T_STRING => '%s is not a valid string',
        Validation::T_INT => '%s is not a valid integer',
        Validation::T_BOOL => '%s is not a valid boolean',
    ];

    public static function validate(array $opts, string $fieldName, mixed $value, array $field) : ?string {
        $validators = array_keys($opts);
        if (in_array(Validation::REQUIRED, $opts)) { // only for value
            if ($value === null || (is_string($value) && strlen(trim($value)) === 0))
                return sprintf(Validation::ERROR_MESSAGE[Validation::REQUIRED], $fieldName);
        }
        if ($value === null) return null; // ignore if value is not set
        if (in_array(Validation::UNIQUE, $validators)) { // only for key
            $model = $opts[Validation::UNIQUE];
            $ins = $model::find("$fieldName = ?", [$value], 'LIMIT 1');
            if ($ins && count($ins) > 0) { // already exists
                return sprintf(Validation::ERROR_MESSAGE[Validation::UNIQUE], $fieldName);
            }
        }
        if (in_array(Validation::FOREIGN, $opts)) { // Only for value
            if (in_array(ForeignKey::class, array_keys($field[Field::T_ATTR]))) {
                $foreign = $field[Field::T_ATTR][ForeignKey::class];
                $res = Database::get()->prepare(
                    'SELECT ' . $foreign->refColumn . ' FROM ' . $foreign->refModel .
                    ' WHERE ' . $foreign->refColumn . ' = ? LIMIT 1'
                );
                $res->execute([$value]);
                if (!$res->fetch()) { // not exists
                    return sprintf(Validation::ERROR_MESSAGE[Validation::FOREIGN], $fieldName, $foreign->refModel);
                }
            }
        }
        if (in_array(Validation::TYPE, $validators)) { // only for key
            switch ($opts[Validation::TYPE]) {
                case Validation::T_EMAIL:
                    if (!filter_var($value, FILTER_VALIDATE_EMAIL))
                        return sprintf(Validation::ERROR_MESSAGE[Validation::T_EMAIL], $fieldName);
                    break;
                case Validation::T_USERNAME:
                    // username consist of alphanumeric characters, underscores
                    // min 8 and max 255 characters
                    if (!preg_match('/^[a-zA-Z0-9_]{8,255}$/', $value))
                        return sprintf(Validation::ERROR_MESSAGE[Validation::T_USERNAME], $value);
                    break;
                case Validation::T_STRING:
                    if (!is_string($value))
                        return sprintf(Validation::ERROR_MESSAGE[Validation::T_STRING], $fieldName);
                    if (in_array(Validation::MIN, $validators) && strlen($value) < $opts[Validation::MIN])
                        return sprintf(Validation::ERROR_MESSAGE[Validation::MIN], $fieldName);
                    if (in_array(Validation::MAX, $validators) && strlen($value) > $opts[Validation::MAX])
                        return sprintf(Validation::ERROR_MESSAGE[Validation::MAX], $fieldName);
                    if (in_array(Validation::REGEX, $validators) && !preg_match($opts[Validation::REGEX], $value))
                        return sprintf(Validation::ERROR_MESSAGE[Validation::REGEX], $fieldName);
                    break;
                case Validation::T_INT:
                    if (!is_int($field))
                        return sprintf(Validation::ERROR_MESSAGE[Validation::T_INT], $fieldName);
                    if (in_array(Validation::MIN, $validators) && $field < $opts[Validation::MIN])
                        return sprintf(Validation::ERROR_MESSAGE[Validation::MIN], $fieldName);
                    if (in_array(Validation::MAX, $validators) && $field > $opts[Validation::MAX])
                        return sprintf(Validation::ERROR_MESSAGE[Validation::MAX], $fieldName);
                    break;
                case Validation::T_BOOL:
                    if (!is_bool($field))
                        return sprintf(Validation::ERROR_MESSAGE[Validation::T_BOOL], $fieldName);
                    break;
            }
        }
        return null;
    }
}

?>