new Vue({
    el: '#app',
    data: {
        search: '',
        search_items: [],
        search_selected: 0,
        searched: false,
        clientIndex: null,
        result: []
    },
    ready: function() {
        var client = algoliasearch("7BIVCWV1UN", "7d0b4b697a90cc3f7a1e2b9ae5fa13e8"); // public credentials
        this.clientIndex = client.initIndex('carros');
        this.$els.searchInput.focus();
        $(".use-select2").select2();
    },
    methods: {
        cleanSearch: function() {
            this.search = '';
            this.search_items = [];
            this.search_selected = 0;
        },
        autocompleteSelect: function(value) {
            this.search = value;
            this.search_items = [];
            this.search_selected = 0;
        },
        searchEnter: function() {
          if (this.search_items.length) {
              
              this.search = this.search_items[this.search_selected].descricao;
              this.searched = true;
              this.search_items = [];
              this.search_selected = 0;

              setTimeout(function() {
                  this.$els.searchTop.focus();
              }.bind(this), 50);

              this.$http.get('/buscar').then(function (response) {

                  // set data on vm
                  this.result = response.data;

              });
          }
        },
        searchSelectNext: function() {
            if (this.search_selected < (this.search_items.length-1)) {
                this.search_selected++;
            }
        },
        searchSelectPrev: function() {
            if (this.search_selected > 0) {
                this.search_selected--;
            }
        },
        searchRequest: function(event) {

            if ([40, 38, 13].indexOf(event.keyCode) !== -1) {
                return;
            } else if (!this.search) {
                this.search_items = [];
                this.search_selected = 0;
                return;
            }

            this.clientIndex.search(this.search, {hitsPerPage: 5}, function(err, content) {
                if (err) {
                    return err;
                }

                this.search_items = content.hits;
            }.bind(this));

        }
    }
});