const $ = require('jquery');
const searchResults = $('#search');
const content = $('#content');

let doSearch = function (searchString) {
    let term = $('#searchField').val();
    let params = {};
    let el = $(this);

    if (el.attr('data-filter-name')) {
        el.toggleClass('active');
    }

    $('[data-filter-name].active').each(function() {
       let me = $(this);

       let name = me.attr('data-filter-name');

       if (params[name] === undefined) {
           params[name] = me.attr('data-filter-value');
       } else {
           params[name] = params[name] + '|' + me.attr('data-filter-value');
       }
    });

    if (term.length) {
        params.term = term;
    } else if(window.location.pathname !== '/search') {
        searchResults.hide();
        content.show();
        return;
    }

    if (Object.keys(params).length) {
        history.replaceState(params, 'Search', '?' + $.param(params));
    } else {
        history.replaceState(params, 'Search', '');
    }

    $.get('/api/search' + (typeof searchString === 'string' ? searchString : ''), params, (response) => {
        searchResults.show();
        content.hide();
        searchResults.html(response);
        bindToRefresh();
    });
};

$('#searchField').on('input', doSearch);
let bindToRefresh = () => {
    $('[data-filter-name]').on('click', doSearch);
};

bindToRefresh();

if (window.location.pathname === '/search') {
    doSearch(window.location.search);
}
