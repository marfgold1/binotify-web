<?
    include_once __DIR__ . '/template.inc.php';
    startauth('Login');
?>
<div class="fullw">
    <form action="/login" method="POST">
        <?
            echoInput('Username', true);
            echoInput('Password', true, null, 'password');
        ?>
        <input type="submit" value="Login" class="btn" />
    </form>
</div>
<hr />
<div class="footer-section fullw">
    <p class="text">Don't have an account?</p>
    <a class="btn btn-invert" id="signup-link" href="/register">Sign up</a>
</div>
<? endauth() ?>
