function helpInput (item, show=false, text="") {
    let name = item.getAttribute('name');
    let help = document.getElementById('inp-help-' + name);
    if (help === null) return;
    help.querySelector('p').innerHTML = text;
    if (show && help.classList.contains('hide')) {
        help.classList.remove('hide');
        item.classList.add('error');
        item.removeEventListener('input', () => helpInput(item, false));
    } else if (!show && !help.classList.contains('hide')) {
        help.classList.add('hide');
        item.classList.remove('error');
        item.addEventListener('input', () => helpInput(item, false));
    }
}

const debounce = (func, delay) => {
    let debounceTimer
    return function() {
        clearTimeout(debounceTimer)
        debounceTimer = setTimeout(func, delay)
    }
}

var uniqueCheck = [false, false];
var uniqueHandler = [];
function checkInput(specname='') {
    let input = document.querySelectorAll('input');
    input.forEach((item) => {
        let name = item.getAttribute('name');
        if (name == specname || specname == '') {
            let value = item.value;
            if (name == 'username' && !/^[a-zA-Z0-9_]{8,255}$/.test(value)) {
                helpInput(item, true, 'Username must be 8-255 characters long and contain only letters, numbers and underscore.');
            } else if (name == 'email' && !/^\w+@\w+\.\w+$/.test(value)) {
                helpInput(item, true, 'Please enter a valid email address (format in example@example.com).');
            } else if (name == 'password' && value.length < 8) {
                helpInput(item, true, 'Password must be at least 8 characters long.');
            } else if (name == 'password_confirm' && value != document.querySelector('input[name=password]').value) {
                helpInput(item, true, 'Passwords do not match.');
            }
            if (specname == '' && ['username', 'email'].includes(name)) {
                if (!uniqueCheck[name == 'username' ? 0 : 1]) {
                    uniqueHandler[name == 'username' ? 0 : 1]();
                }
            }
        }
    });
}

var form = document.querySelector('form');
if (form.getAttribute('action') == '/register') {
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        checkInput();
        if (document.querySelectorAll('.error').length == 0 && uniqueCheck.every((item) => item))
            form.submit();
    });
    let input = document.querySelectorAll('input');
    input.forEach((item) => {
        let name = item.getAttribute('name');
        item.addEventListener('input', () => helpInput(item, false));
        if (['username', 'email'].includes(name)) {
            const req = new XMLHttpRequest();
            req.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    if (this.responseText === 'false') {
                        helpInput(item, true, item.getAttribute('placeholder') + ' is already taken!');
                    } else {
                        uniqueCheck[name == 'username' ? 0 : 1] = true;
                        helpInput(item, false);
                        item.classList.add('success');
                    }
                }
            };
            uniqueHandler[name == 'username' ? 0 : 1] = function() {
                item.classList.remove('success');
                uniqueCheck[name == 'username' ? 0 : 1] = false;
                req.open("POST", "/check/" + name + "/" + item.value);
                req.send();
            };
            item.addEventListener('input', debounce(uniqueHandler[name == 'username' ? 0 : 1], 1000));
        }
        if (name === 'email') {
            item.addEventListener('focusout', () => checkInput('email') );
        } else if (name === 'username') {
            item.addEventListener('focusout', () => checkInput('username') );
        }
    });
}
