const submitButtons = document.querySelectorAll('form[method="post"]');

for (let me of submitButtons) {
    me.addEventListener('submit', function(e) {
        const btn = e.target.querySelector('button[type="submit"]');

        if (btn) {
            btn.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
            btn.setAttribute('disabled', 'disabled');
        }
    });
}


