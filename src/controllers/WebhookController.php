<?php
namespace MusicApp\Controllers;

use MusicApp\Core\Controller;
use MusicApp\Models\Subscription;

class WebhookController extends Controller {
    public function subscribe(int $creator_id, int $subscriber_id) {
        $sub = Subscription::get([$creator_id, $subscriber_id]);
        $result = $_POST['result'] ?? null;
        $auth = getallheaders()['Authorization'];
        if ($auth !== "Bearer {$_ENV['PHP_API_KEY']}") {
            http_response_code(401);
            echo "invalid API key";
            return;
        }
        if (!in_array($result, ['ACCEPTED', 'REJECTED'])) {
            http_response_code(400);
            echo "invalid request of result type '$result' (should be 'ACCEPTED' or 'REJECTED')";
            return;
        }
        die(var_dump($creator_id, $subscriber_id, $_POST['result'], $sub, $auth, $_ENV['PHP_API_KEY']));
        if ($sub) {
            $sub->status = $result;
            $sub->save();
            echo "success change subscription of sub_id $subscriber_id for creator_id $creator_id to $result";
        } else {
            $sub = new Subscription();
            $sub->set([
                'creator_id' => $creator_id,
                'subscriber_id' => $subscriber_id,
                'status' => $result,
            ]);
            $sub->save();
            echo "success adding new subscription of sub_id $subscriber_id for creator_id $creator_id to $result";
        }
    }
}
?>