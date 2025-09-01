document.addEventListener('DOMContentLoaded', function () {
    var input = document.getElementById('site_base_url');
    if (input) {
        try {
            input.value = window.location.origin;
        } catch (e) {
            input.value = '';
        }
    }
});

