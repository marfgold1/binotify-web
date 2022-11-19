<?php
namespace MusicApp\Core;
function echoSidebar () {
    echo <<<SBEGIN
    <nav class="sidebar">
        <h2>Binotify</h2>
        <ul class="nav">
    SBEGIN;
    // User: home, search, daftar album, logout
    // admin: home, search, daftar album, tambah lagu, tambah album, logout
    // Non-user: home, search, daftar album, login, register
    echo <<<SBITEM
        <li>
        <a href="/home">
            <span>Home</span>
        </a>
        </li>
        <li>
        <a href="/search">
            <span>Search</span>
        </a>
        </li>
        <li>
        <a href="/album">
            <span>Daftar Album</span>
        </a>
        </li>
    SBITEM;
    if (has('user')) {
        if (get('user')->isAdmin) {
            echo <<<SBITEM
            <li>
            <a href="/lagu/create">
                <span>Tambah Lagu</span>
            </a>
            </li>
            <li>
            <a href="/album/create">
                <span>Tambah Album</span>
            </a>
            </li>
            <li>
            <a href="/user">
                <span>Daftar User</span>
            </a>
            </li>
            SBITEM;
        }
        echo <<<SBITEM
            <li>
            <form id="form-logout" action="/logout" method="post">
                <a onclick="document.getElementById('form-logout').submit()" style="cursor: pointer;" >
                    <span>Logout</span>
                </a>
            </form>
            </li>
            SBITEM;
    } else {
        echo <<<SBITEM
            <li>
            <a href="/login">
                <span>Login</span>
            </a>
            </li>
            <li>
            <a href="/register">
                <span>Register</span>
            </a>
            </li>
        SBITEM;
    }
    echo <<<SBEND
        </ul>
    </nav>
    SBEND;
}
?>