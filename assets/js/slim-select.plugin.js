require('../../node_modules/slim-select/dist/slimselect.min.css');
require('../css/slim-select.plugin.scss');

import SlimSelect from 'slim-select';

const selects = document.getElementsByTagName('select');

for (let select of selects) {
    if (select.slim) {
        continue;
    }

    const elementId = select.getAttribute('id');
    if (!elementId) {
        continue;
    }

    if (select.children.length < 7) {
        continue;
    }

    new SlimSelect(
        {
            select: '#' + elementId
        }
    );
}
