<?php
namespace MusicApp\Controllers;

use MusicApp\Core\Controller;

use function MusicApp\Core\view;

class SearchController extends Controller {
    public function search() {
        view('search');
    }
}
?>