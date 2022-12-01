<?php
namespace MusicApp\Models;

use MusicApp\Core\Models\Field;
use MusicApp\Core\Models\ForeignKey;
use MusicApp\Core\Models\Model;
use PDO;

class Subscription extends Model {
    protected static string $table = 'subscription';

    #[Field([Field::C_NULLABLE => false, Field::C_PRIMARY => true], PDO::PARAM_INT)]
    protected ?int $creator_id = null; // creator_id int NOT NULL PRIMARY KEY
    #[Field([Field::C_NULLABLE => false, Field::C_PRIMARY => true], PDO::PARAM_INT)]
    #[ForeignKey('user', 'user_id', 'ON DELETE CASCADE')]
    protected ?int $subscriber_id = null; // subscriber_id INT NOT NULL PRIMARY KEY
    #[Field([Field::C_NULLABLE => false, Field::C_DEFAULT => 'PENDING'], "ENUM('PENDING', 'ACCEPTED', 'REJECTED')")]
    protected ?string $status = null; // Status ENUM
}
User::load();
Subscription::load();
?>