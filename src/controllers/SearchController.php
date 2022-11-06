<?php
namespace MusicApp\Controllers;

use MusicApp\Core\Controller;
use MusicApp\Core\Database;
use MusicApp\Models\Song;

use function MusicApp\Core\view;

class SearchController extends Controller {
    public function search() {
        $flash = [
            'values' => [
                'judul' => $_GET['judul'] ?? null,
                'penyanyi' => $_GET['penyanyi'] ?? null,
                'tahun' => $_GET['tahun'] ?? null,
                'genre' => $_GET['genre'] ?? null,
                'sort_judul' => $_GET['sort_judul'] ?? null,
                'sort_tahun' => $_GET['sort_tahun'] ?? null,
            ]
        ];
        $cond = '(judul LIKE ? AND penyanyi LIKE ? AND YEAR(tanggal_terbit) LIKE ?)';
        $params = [$_GET['judul'] ?? '', $_GET['penyanyi'] ?? '', $_GET['tahun'] ?? ''];
        $params = array_map((fn($param) => '%' . $param . '%'), $params);
        if ($_GET['genre'] ?? null) {
            $cond .= ' AND genre = ?';
            $params[] = $_GET['genre'];
        }
        $sort = ['judul' => $_GET['sort_judul'] ?? null, 'tanggal_terbit' => $_GET['sort_tahun'] ?? null];
        $sort = array_map(function($k) {
            if ($k === 'ASC' || $k === 'DESC') return $k;
            return null;
        }, $sort);
        $sort['judul'] = $sort['judul'] ?? 'ASC';
        $sort = array_filter($sort);
        $songs = Song::find($cond, $params,
            'ORDER BY ' . implode(', ', array_map(fn($k) => $k . ' ' . $sort[$k] , array_keys($sort))) 
        );
        $genre = Database::get()->query('SELECT DISTINCT genre FROM song')->fetchAll();
        $genre = array_values(array_map(fn($g) => $g['genre'], $genre));
        view('search', ['songs' => $songs, 'genre' => $genre])->flash($flash);
    }
}
?>