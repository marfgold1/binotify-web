<?php
namespace MusicApp\Controllers;

use MusicApp\Core\Controller;
use MusicApp\Models\Subscription;
use MusicApp\Models\Song;

use function MusicApp\Core\back;
use function MusicApp\Core\view;
use function MusicApp\Core\get;
use function MusicApp\Core\has;
use function MusicApp\Core\redirect;

class PenyanyiController extends Controller {

    public function listPenyanyi() {
        if (!has('user') || get('user')->isAdmin) {
            redirect('/');
        }
        $subscriber_id = get('user')->user_id;
        $url = "http://{$_ENV['REST_HOST']}/subscriptions/subscriber/{$subscriber_id}";
        $listSubscribe = json_decode(file_get_contents($url), true);

        $url = "http://{$_ENV['REST_HOST']}/users/penyanyi";
        $listPenyanyi = json_decode(file_get_contents($url), true);
        view('penyanyi.list-penyanyi', ['listPenyanyi' => $listPenyanyi, 'listSubscribe' => $listSubscribe]);
    }

    public function listLagu($penyanyi) {
        if (!has('user') || get('user')->isAdmin) {
            redirect('/');
        }
        $url = "http://{$_ENV['REST_HOST']}/songs/" . $penyanyi;
        $listLagu = json_decode(file_get_contents($url), true);
        view('penyanyi.list-lagu', ['listLagu' => $listLagu]);
    }

    public function Subscribe($id) {
        $subscriber = new Subscription();
        $subscriber->creator_id = $id;
        $subscriber->subscriber_id = get('user')->user_id;
        $subscriber->status = "PENDING";
        $subscriber->save();

        $options = [
            'http' => [
                'method' => 'POST',
                'header' => "Authorization: Bearer {$_ENV['PHP_API_KEY']}\r\n" .
                    "Content-Type: text/xml\r\n",
                'content' => <<<XML
                <?xml version='1.0' encoding='utf-8'?>
                <soap:Envelope xmlns:soap='http://schemas.xmlsoap.org/soap/envelope/'>
                    <soap:Body>
                        <ns:RequestSub xmlns:ns='http://service.soap.binotify.com/'>
                            <CreatorId>$subscriber->creator_id</CreatorId>
                            <SubscriberId>$subscriber->subscriber_id</SubscriberId>
                        </ns:RequestSub>
                    </soap:Body>
                </soap:Envelope>
                XML,
                'timeout' => 1.5,
            ],
        ];
        $context = stream_context_create($options);
        $res = file_get_contents("http://{$_ENV['SOAP_HOST']}", false, $context);
        $res = (
            (int) simplexml_load_string($res)
                ->children('S', true)
                ->children('ns2', true)
                ->children(null)
                ->return
        );

        back();
    }
 

}
?>