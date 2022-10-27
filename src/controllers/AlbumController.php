<?php

namespace MusicApp\Controllers;

use MusicApp\Core\Controller;
use MusicApp\Models\Album;
use MusicApp\Models\Song;

use function MusicApp\Core\back;
use function MusicApp\Core\get;
use function MusicApp\Core\has;
use function MusicApp\Core\route;
use function MusicApp\Core\view;

class AlbumController extends Controller
{
    public function daftarAlbum()
    {
        view('album.daftar-album');
    }

    public function detailAlbum($id)
    {
        if (has('user')) {
            if (get('user')->isAdmin)
                $listSong = Song::find('album_id = ?', [$id]);
                $album = Album::get($id);
                view('album.daftar-album-admin', ['lagu' => $listSong, 'album' => $album]);
        }
        view('album.daftar-album-user');
    }

 

    public function formAlbum()
    {
        view('album.form-album');
    }

    public function formLagu($album_id)
    {
        $penyanyi = Album::get($album_id)->penyanyi;
        $lagu = Song::find('album_id = ?', [$album_id]);
        view('album.form-lagu', ['penyanyi' => $penyanyi, 'lagu' => $lagu]);
    }

    public function showListAlbum($page)
    {
        view('album.show-list-album', ['page' => $page]);
    }

    public function changeData($album_id)
    {
        $namaFile = $_FILES['berkas']['name'];
        $fileLocation = "/album/" . 'img/' . $namaFile;
        $album = Album::get($album_id);
        $album->penyanyi = $_POST['penyanyi'];
        $album->judul = $_POST['judul'];
        $album->img_path = $fileLocation;
        $album->save();
        route('album.daftar-album')->with(['success' => 'Album berhasil diubah']);
    }
    public function hapusAlbum($album_id)
    {
        $album = Album::get($album_id);
        $album->delete();
        route('album.daftar-album')->with(['success' => 'Album berhasil dihapus']);
        exit;
        
    }

    public function tambahAlbum()
    {
        $album = new Album();
        $album->penyanyi = $_POST['penyanyi'];
        $album->judul = $_POST['judul'];
        $album->img_path = "/album/" . 'img/' . $_FILES['berkas']['name'];
        $album->save();
        route('album.daftar-album')->with(['success' => 'Album berhasil ditambahkan']);
    }

    public function tambahLagu($album_id, $song_id)
    {
        $lagu = Song::get($song_id);
        $lagu->album_id = $album_id;
        $lagu->save();
        back()->with(['success' => 'Lagu berhasil ditambahkan']);
    }

    public function hapusLagu($album_id, $song_id)
    {
        $lagu = Song::get($song_id);
        $lagu->album_id = null;
        $lagu->save();
        back()->with(['success' => 'Lagu berhasil dihapus']);
        }
    }
