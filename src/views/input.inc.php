<?php

use function MusicApp\Core\getFlash;

function echoInput ($label, $required=false, $name=null, $type='text', $placeholder=null) {
    $flash = getFlash() ?? [];
    $values = $flash['values'] ?? [];
    $errors = $flash['errors'] ?? [];

    $placeholder = $placeholder ?? $label;
    $name = $name ?? strtolower($label);
    $required = $required ? 'required' : '';
    $error = isset($errors[$name]) ? ' error' : '';
    $value = $values[$name] ?? '';
    echo '<div class="form-input">';
    echo "<label for=\"login-$name\" class=\"input label\">$label</label>";
    echo <<<EOT
        <input
            aria-invalid="false"
            $required
            id="login-$name"
            type="$type"
            name="$name"
            placeholder="$placeholder"
            class="text$error"
            value="$value"
        />
    EOT;

    $err = $errors[$name] ?? '';
    $errclass = $err ? '' : ' hide';
    // help
    echo "<div id=\"inp-help-$name\" class=\"input help$errclass\">";
    echo '<svg role="img" height="16" width="16" aria-label="Error:" viewBox="0 0 16 16">';
    echo '<path d="M2.343 2.343a8 8 0 1111.314 11.314A8 8 0 012.343 2.343zm5.099 8.738a.773.773 0 00-.228.558.752.752 0 00.228.552.75.75 0 00.552.229.773.773 0 00.558-.229.743.743 0 00.234-.552.76.76 0 00-.234-.558.763.763 0 00-.558-.234.743.743 0 00-.552.234zm-.156-7.23l.312 6.072h.804l.3-6.072H7.286z"></path>';
    echo '</svg>';
    echo "<p>$err</p>";
    echo '</div>';

    echo '</div>';
}
?>