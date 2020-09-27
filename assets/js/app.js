require('bootstrap/scss/bootstrap.scss');
require('../css/app.scss');
require('bootstrap/js/src/collapse');
require('bootstrap/js/src/dropdown');
require('bootstrap/js/src/modal');
require('./search');
require('../css/loading.css');
require('./loading');

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
