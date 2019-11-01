require('bootstrap/dist/css/bootstrap.css');
require('../css/app.scss');

const $ = require('jquery');

require('bootstrap/js/src/collapse');
require('bootstrap/js/src/modal');

$('[data-toggle="collpase"]').collapse();

import { searchBox, hits, refinementList, poweredBy } from 'instantsearch.js/es/widgets'

let startSearch = () => {
    const algoliasearch = require('algoliasearch/lite');
    const instantsearch = require('instantsearch.js').default;

    const searchClient = algoliasearch('BO8TQO6LWR', 'df8999983cb484a333b7ac3b91aa4f9c');

    const searchResults = $('#search');
    const content = $('#content');
    const search = instantsearch({
        indexName: 'prod_packages',
        searchClient,
        searchFunction: function(helper) {
            if (helper.state.query === '' && window.location.pathname !== '/search') {
                searchResults.hide();
                content.show();
                return;
            }
            helper.search();
            content.hide();
            searchResults.show();
        }
    });

    search.addWidgets([
        searchBox({
            container: '#searchContainer',
            placeholder: 'Search packages...'
        }),

        hits({
            container: '#hits',
            templates: {
                item: `<div class="package-item row">
   <div class="col-9">
       <h4><a href="/packages/{{ name }}/">{{ name }}</a></h4>
       <p>{{ package.description }}</p>
   </div>
</div>`
            }
        }),

        refinementList({
            container: '#search-sidebar-type',
            attribute: 'types',
            operator: 'or',
            limit: 10,
            templates: {
                header: 'Package-Type'
            }
        }),

        refinementList({
            container: '#search-sidebar-producer',
            attribute: 'producerName',
            operator: 'or',
            limit: 10,
            templates: {
                header: 'Producer'
            }
        }),

        poweredBy({
            container: '#powered-by'
        })
    ]);

    search.start();
};

if (document.querySelector('.search-wrapper')) {
    startSearch();
}