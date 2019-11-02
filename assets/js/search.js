const $ = require('jquery');
const searchResults = $('#search');
const content = $('#content');

let doSearch = () => {
    let term = $('#searchField').val();
    let params = {};

    $('[data-filter]').each(function() {
       let me = $(this);

       if (!me.is(':checked')) {
           return;
       }

       let name = me.attr('name');

       if (params[name] === undefined) {
           params[name] = me.attr('value');
       } else {
           params[name] = params[name] + '|' + me.attr('value');
       }
    });

    if (term.length) {
        params.term = term;
    } else if(window.location.pathname !== '/search') {
        searchResults.hide();
        content.show();
        return;
    }

    $.get('/api/search', params, (response) => {
        searchResults.show();
        content.hide();
        searchResults.html(response);
        bindToRefresh();
    });
};

$('#searchField').on('input', doSearch);
let bindToRefresh = () => {
    $('[data-filter]').on('change', doSearch);
};

bindToRefresh();

if (window.location.pathname === '/search') {
    doSearch();
}
