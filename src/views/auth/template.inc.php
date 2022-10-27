<?
    include_once __DIR__ . '/../input.inc.php';
    include_once __DIR__ . '/../template.inc.php';
    function startauth($title) {
        starthtml($title);
        echo '<div class="container top-logo">';
        showlogo();
        echo <<<EOT
        </div>
        <div class="container">
            <div class="container fluid">
        EOT;
    }
    function endauth() {
        echo <<<EOT
            </div>
        </div>
        EOT;
        endhtml();
    }
?>
