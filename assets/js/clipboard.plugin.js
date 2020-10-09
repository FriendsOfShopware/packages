import ClipboardJS from 'clipboard';

const clipboard = new ClipboardJS('.btn-copy');

clipboard.on('success', function(e) {
    e.clearSelection();

    const saveText = e.trigger.innerText;
    e.trigger.innerText = 'Copied!';
    e.trigger.setAttribute('disabled', 'disabled');

    setTimeout(function() {
        e.trigger.removeAttribute('disabled');
        e.trigger.innerText = saveText;
    }, 2000);
});
