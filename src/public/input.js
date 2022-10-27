let input = document.querySelectorAll('input');

input.forEach((item) => {
    function hideHelpOnInput () {
        let name = item.getAttribute('name');
        let help = document.getElementById('inp-help-' + name)
        if (help) help.remove();
        item.classList.remove('error');
        item.removeEventListener('input', hideHelpOnInput);
    };
    item.addEventListener('input', hideHelpOnInput);
});
