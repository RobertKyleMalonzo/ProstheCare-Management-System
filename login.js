document.addEventListener('DOMContentLoaded', function () {
    var input = document.getElementById('password');
    var toggle = document.getElementById('togglePassword');

    if (!input || !toggle) return;

    toggle.addEventListener('click', function () {
        var show = input.type === 'password';
        input.type = show ? 'text' : 'password';
        toggle.setAttribute('aria-pressed', show ? 'true' : 'false');
        toggle.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
    });
});