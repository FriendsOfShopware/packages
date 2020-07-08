const searchResults = document.getElementById('search');
const searchField = document.getElementById('searchField');
const content = document.getElementById('content');

let doSearch = function (el, searchString) {

    let term = searchField.value;
    if (typeof searchString !== 'undefined') {
        term = searchString;
    }
    let params = {};

    if (el.target && el.target.getAttribute('data-filter-name')) {
        el.target.classList.toggle('active');
    }

    const activeFilter = document.querySelectorAll("[data-filter-name].active");

    activeFilter.forEach(function(me) {

        let name = me.getAttribute('data-filter-name');

        if (params[name] === undefined) {
            params[name] = me.getAttribute('data-filter-value');
        } else {
            params[name] = params[name] + '|' + me.getAttribute('data-filter-value');
        }
    });

    if (term.length) {
        params.term = term;
    } else if(window.location.pathname !== '/search') {
        searchResults.style.display = 'none';
        content.style.display = '';
        return;
    }

    if (Object.keys(params).length) {
        history.replaceState(params, 'Search', '?' + formData(params));
    } else {
        history.replaceState(params, 'Search', '');
    }

    let request = new XMLHttpRequest();
    request.open('GET', '/api/search?' + formData(params), true);

    request.onload = function() {
        if (this.status >= 200 && this.status < 400) {
            searchResults.style.display = '';
            content.style.display = 'none';
            searchResults.innerHTML = this.response;
            bindToRefresh();
        } else {
            // no success
        }
    };

    request.send();
};

searchField.addEventListener("input", function(event) {
    doSearch(event)
});

let formData = (obj) => {
    return Object.keys(obj).map((k) => encodeURIComponent(k) + '=' + encodeURIComponent(obj[k])).join('&');
}
let bindToRefresh = () => {
    const dataFilterName = document.querySelector('[data-filter-name]');

    if(dataFilterName) {
        dataFilterName.addEventListener("click", function(event) {
            doSearch(event)
        });
    }
};
bindToRefresh();

const searchTerm = new URLSearchParams(window.location.search).get('term');
if (searchTerm) {
    doSearch(searchField, searchTerm);
}
