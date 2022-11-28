<?php
namespace MusicApp\Controllers;

use MusicApp\Core\Controller;
use MusicApp\Models\Subscription;
use MusicApp\Models\Song;

use function MusicApp\Core\back;
use function MusicApp\Core\view;
use function MusicApp\Core\get;

class PenyanyiController extends Controller {

    public function listPenyanyi() {
        $listPenyanyi = Song::find('penyanyi IS NOT NULL', [], 'ORDER BY penyanyi');
        $listSubscribe = Subscription::find('subscriber_id = ?', [get('user')->user_id]);
        view('penyanyi.list-penyanyi', ['listPenyanyi' => $listPenyanyi, 'listSubscribe' => $listSubscribe]);
    }

    public function listLagu($penyanyi) {
        $listLagu = Song::find('penyanyi = ?', [$penyanyi]);
        view('penyanyi.list-lagu', ['listLagu' => $listLagu]);
    }

    public function Subscribe($id) {
        $subscriber = new Subscription();
        $subscriber->creator_id = $id;
        $subscriber->subscriber_id = get('user')->user_id;
        $subscriber->status = "PENDING";
        $subscriber->save();
        back();
    }

}
?>