<?php
namespace MusicApp\Models;

use MusicApp\Core\Models\Field;
use MusicApp\Core\Models\Model;
use MusicApp\Core\Models\Setter;
use PDO;

class User extends Model {
    protected static string $table = 'users';

    public function setPassword ($value) {
        if ($value === null) {
            $this->password = null;
        } else {
            $this->password = password_hash($value, PASSWORD_DEFAULT);
        }
    }

    #[Field([Field::C_AUTO_INCREMENT => true, Field::C_NULLABLE => false, Field::C_PRIMARY => true], PDO::PARAM_INT)]
    protected ?int $user_id = null;
    #[Field([Field::C_NULLABLE => false, Field::C_UNIQUE => true])]
    protected ?string $username = null;
    #[Field([Field::C_NULLABLE => false])]
    #[Setter('setPassword')]
    protected ?string $password = null;
    #[Field([Field::C_NULLABLE => false])]
    protected ?string $email = null;
    #[Field([Field::C_NULLABLE => false, Field::C_DEFAULT => false], PDO::PARAM_BOOL)]
    protected ?bool $isAdmin = null;
}

?>