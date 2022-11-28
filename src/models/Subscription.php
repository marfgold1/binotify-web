<?php
namespace MusicApp\Models;

use MusicApp\Core\Models\Field;
use MusicApp\Core\Models\Model;
use MusicApp\Core\Models\ForeignKey;
use PDO;

class Subscription extends Model {
    protected static string $table = 'penyanyi';

    #[Field([Field::C_NULLABLE => false, Field::C_PRIMARY => true], PDO::PARAM_INT)]
    protected ?int $creator_id= null; // creator int NOT NULL PRIMARY KEY 
    #[Field([Field::C_NULLABLE => false, Field::C_PRIMARY => true], PDO::PARAM_INT)]
    protected ?int $subscriber_id= null; // subscriber_id int NOT NULL PRIMARY KEY 
    #[Field([Field::C_NULLABLE => false])]
    protected ?string $status = null; // Status NOT NULL DEFAULT = PENDING
    #[Field([], PDO::PARAM_INT)]
    #[ForeignKey('user', 'user_id', 'ON DELETE CASCADE')]
    protected ?int $user_id = null; // Subscriber_id int REFERENCES user(user_id)
}
User::load();
Subscription::load();
?>