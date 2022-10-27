<?
    include_once __DIR__ . '/template.inc.php';
    startauth('Register');
?>
<div class="fullw">
    <form action="/register" method="POST">
        <?
            echoInput('Name', false, 'name', 'text', 'User');
            echoInput('Username', true);
            echoInput('Email', true);
            echoInput('Password', true, null, 'password');
            echoInput('Retype Password', true, 'password_confirm', 'password', 'Retype Password');
        ?>
        <input type="submit" value="Register" class="btn" />
    </form>
</div>
<hr />
<div class="footer-section fullw">
    <p class="text">Already have an account?</p>
    <a class="btn btn-invert" id="signup-link" href="/login">Login</a>
</div>
<? endauth() ?>
