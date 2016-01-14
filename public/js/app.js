Vue.filter('filter_km', function (value) {
    return value.toString().length > 6 ? value.toString().substring(0, 6) : value;
});

Vue.filter('filter_descricao', function (value) {
    return value.toString().length > 64 ? (value.toString().substring(0, 55) + "...") : value;
});

Vue.directive('demo', {
    bind: function () {
        this.el.onload = function() {
            this.parentNode.className = this.parentNode.className + " loaded";
        };
    }
});

new Vue({
    el: '#app',
    data: {
        search: '',
        search_items: [],
        search_selected: 0,
        searched: true,
        clientIndex: null,
        loading: false,
        result_rows: [{"id_carro":13,"nome_carro":"VOLKSWAGEN PARATI 1.0 MI 16V GASOLINA 4P MANUAL G.III 16V GASOLINA 4P MANUAL G.III","kilometragem":100,"preco":"9999.00","ano":1998,"foto_capa":"veiculo13_568c4450a122f-255x135.jpg"},{"id_carro":14,"nome_carro":"FORD KA 1.0 MPI GL 8V GASOLINA 2P MANUAL","kilometragem":89000,"preco":"10500.00","ano":2000,"foto_capa":"veiculo14_568c4455a33e8-255x135.jpg"},{"id_carro":15,"nome_carro":"VOLKSWAGEN GOL 1.0 MI SPECIAL 8V GASOLINA 2P MANUAL","kilometragem":100000,"preco":"10500.00","ano":2001,"foto_capa":"veiculo15_568c4459cea29-255x135.jpg"},{"id_carro":16,"nome_carro":"FIAT FIORINO 1.5 IE FURG\u00c3O 8V GASOLINA 2P MANUAL","kilometragem":264000,"preco":"13490.00","ano":2003,"foto_capa":"veiculo16_568c4462ed12d-255x135.jpg"},{"id_carro":17,"nome_carro":"FIAT FIORINO 1.5 IE FURG\u00c3O 8V GASOLINA 2P MANUAL","kilometragem":264000,"preco":"13490.00","ano":2003,"foto_capa":"veiculo17_568c4462f01a8-255x135.jpg"},{"id_carro":18,"nome_carro":"DODGE DAKOTA 2.5 4X2 CS 8V GASOLINA 2P MANUAL","kilometragem":237000,"preco":"12500.00","ano":1998,"foto_capa":"veiculo18_568c44631439b-255x135.jpg"},{"id_carro":19,"nome_carro":"PEUGEOT 206 1.6 CC 16V GASOLINA 2P MANUAL","kilometragem":29847,"preco":"39800.00","ano":2004,"foto_capa":"veiculo19_568c44630d1ab-255x135.jpg"},{"id_carro":20,"nome_carro":"FIAT FIORINO 1.5 IE FURG\u00c3O 8V GASOLINA 2P MANUAL","kilometragem":264000,"preco":"13490.00","ano":2003,"foto_capa":"veiculo20_568c446315d78-255x135.jpg"},{"id_carro":21,"nome_carro":"FIAT DOBL\u00d2 1.3 MPI FIRE CARGO 16V 80CV GASOLINA 4P MANUAL","kilometragem":238709,"preco":"15980.00","ano":2003,"foto_capa":"veiculo21_568c4466f035d-255x135.jpg"},{"id_carro":22,"nome_carro":"FIAT DOBL\u00d2 1.3 MPI FIRE CARGO 16V 80CV GASOLINA 4P MANUAL","kilometragem":238709,"preco":"15980.00","ano":2003,"foto_capa":"veiculo22_568c446716b91-255x135.jpg"},{"id_carro":23,"nome_carro":"FIAT DOBL\u00d2 1.3 MPI FIRE CARGO 16V 80CV GASOLINA 4P MANUAL","kilometragem":238709,"preco":"15980.00","ano":2003,"foto_capa":"veiculo23_568c446780b5c-255x135.jpg"},{"id_carro":24,"nome_carro":"FORD ESCORT 1.6 XR3 CONVERS\u00cdVEL 8V GASOLINA 2P MANUAL","kilometragem":80000,"preco":"25000.00","ano":1985,"foto_capa":"veiculo24_568c4468a04f0-255x135.jpg"}],
        result_current_page: 1,
        result_total_pages: 1,
        result_total_rows: 110
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

              this.loading = true;

              this.$http.get('/buscar').then(function (response) {

                  this.loading = false;

                  // set data on vm
                  this.result_rows = response.data.rows;
                  this.result_current_page = response.data.current_page;
                  this.result_total_pages = response.data.total_pages;
                  this.result_total_rows = response.data.total;

              });
          }
        },
        changePage: function(page) {
            if (page == this.result_current_page) {
                return;
            }
            this.$http.get('/buscar', {'page': page}).then(function (response) {
                this.result_rows = response.data.rows;
                this.result_current_page = response.data.current_page;
                this.result_total_pages = response.data.total_pages;
                this.result_total_rows = response.data.total;
            });
        },
        nextPage: function() {
            var nextpage = this.result_current_page + 1;
            if (nextpage <= this.result_total_pages) {
                this.changePage(nextpage);
            }
        },
        prevPage: function() {
            if (this.result_current_page > 1) {
                this.changePage(this.result_current_page - 1);
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