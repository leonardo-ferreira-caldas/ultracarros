
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MeuPossante - Procure e compare dentre carros de todo o Brasil.</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" type="text/css" href="/css/bootstrap.css">
    <!--Main styles-->
    <link rel="stylesheet" type="text/css" href="/css/main.css">
    <!--Adaptive styles-->
    <link rel="stylesheet" type="text/css" href="/css/adaptive.css">
    <!--Swipe menu-->
    <link rel="stylesheet" type="text/css" href="/css/pushy.css">
    <!--fonts-->
    <link href='https://fonts.googleapis.com/css?family=Montserrat:400,700|Roboto:400,300|Open+Sans:400,300' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <!--animation css-->
    <link rel="stylesheet" type="text/css" href="/css/animate.css">
    <link rel="stylesheet" type="text/css" href="/css/vendor/select2.min.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="/css/settings.css">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="/https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="/https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.13/vue.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue-resource/0.6.0/vue-resource.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="/js/vendor/select2.full.min.js"></script>
    <script src="//cdn.jsdelivr.net/algoliasearch/3/algoliasearch.min.js"></script>
</head>
<body class="promo" id="app">
    <div class="site-wrapper-border"></div>

    <div id="container">

        <a href="#" v-bind:class="{'searched': searched}" class="log_btn color-primary">Entrar</a>

        <div v-show="searched">
            <div id="top-search">
                <img src="/img/logo_symbol.png" width="120">

                <div class="topbar-search">
                    <div class="topbar-search-wrapper">
                        <form action="" id="search-form" v-on:submit.prevent v-bind:class="{'active': search, 'autocomplete': search_items.length}" role="form">

                            <input
                                    v-el:search-top
                                    autocomplete="off"
                                    aria-autocomplete="off"
                                    v-on:keyup.down="searchSelectNext"
                                    v-on:keyup.enter="searchEnter"
                                    v-on:keyup.up="searchSelectPrev"
                                    v-on:keyup="searchRequest"
                                    v-model="search"
                                    type="text"
                                    id="search-field"
                                    class="form-control autocomplete-searchinput">

                            <button role="button" id="search-form-btn"><i class="fa fa-search"></i></button>
                            <button v-on:click="cleanSearch" role="button" v-show="search" id="cleansearch-form-btn"><i class="fa fa-times"></i></button>

                            <div v-show="search_items.length" id="autocomplete">
                                <ul>
                                    <li  v-bind:class="{'active': $index == search_selected}" v-for="item in search_items" v-on:click="autocompleteSelect(item.descricao)">
                                        @{{ item.descricao }}
                                    </li>
                                </ul>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


            <div id="content-wrapper">
                <div class="filter-sidebar">
                    <div class="filter-sidebar-header">
                        <div class="filter-sidebar-header-align">
                            <i class="stm-icon-car_search"></i>
                            <h4>Filtros</h4>
                        </div>
                    </div>
                    <div class="filter-body">
                        <div class="filter-item">
                            <h5>Cidade</h5>
                            <select name="cidade" class="use-select2">
                                <option value="">Selecione</option>
                                <option value="">teste</option>
                                <option value="">teste</option>
                                <option value="">teste</option>
                                <option value="">teste</option>
                                <option value="">teste</option>
                                <option value="">teste</option>
                            </select>
                            <div class="filter-used"></div>
                        </div>

                        <div class="filter-item">
                            <h5>Carroceria</h5>
                            <select name="cidade" class="use-select2">
                                <option value="">Selecione</option>
                                <option value="">teste</option>
                                <option value="">teste</option>
                                <option value="">teste</option>
                                <option value="">teste</option>
                                <option value="">teste</option>
                                <option value="">teste</option>
                            </select>
                            <div class="filter-used"></div>
                        </div>

                        <div class="filter-item">
                            <h5>Marca</h5>
                            <select name="cidade" class="use-select2">
                                <option value="">Selecione</option>
                                <option value="">teste</option>
                                <option value="">teste</option>
                                <option value="">teste</option>
                                <option value="">teste</option>
                                <option value="">teste</option>
                                <option value="">teste</option>
                            </select>
                            <div class="filter-used"></div>
                        </div>

                        <div class="filter-item">
                            <h5>Modelo</h5>
                            <select name="cidade" class="use-select2">
                                <option value="">Selecione</option>
                                <option value="">teste</option>
                                <option value="">teste</option>
                                <option value="">teste</option>
                                <option value="">teste</option>
                                <option value="">teste</option>
                                <option value="">teste</option>
                            </select>
                            <div class="filter-used"></div>
                        </div>

                        <div class="filter-item">
                            <h5>Modelo</h5>
                            <select name="cidade" class="use-select2">
                                <option value="">Selecione</option>
                                <option value="">teste</option>
                                <option value="">teste</option>
                                <option value="">teste</option>
                                <option value="">teste</option>
                                <option value="">teste</option>
                                <option value="">teste</option>
                            </select>
                            <div class="filter-used"></div>
                        </div>

                        <div class="filter-item">
                            <h5>Câmbio</h5>
                            <select name="cidade" class="use-select2">
                                <option value="">Selecione</option>
                                <option value="">teste</option>
                                <option value="">teste</option>
                                <option value="">teste</option>
                                <option value="">teste</option>
                                <option value="">teste</option>
                                <option value="">teste</option>
                            </select>
                            <div class="filter-used"></div>
                        </div>

                        <div class="filter-item">
                            <h5>Cor</h5>
                            <select name="cidade" class="use-select2">
                                <option value="">Selecione</option>
                                <option value="">teste</option>
                                <option value="">teste</option>
                                <option value="">teste</option>
                                <option value="">teste</option>
                                <option value="">teste</option>
                                <option value="">teste</option>
                            </select>
                            <div class="filter-used"></div>
                        </div>

                        <div class="filter-item">
                            <h5>Opcional</h5>
                            <select name="cidade" class="use-select2">
                                <option value="">Selecione</option>
                                <option value="">teste</option>
                                <option value="">teste</option>
                                <option value="">teste</option>
                                <option value="">teste</option>
                                <option value="">teste</option>
                                <option value="">teste</option>
                            </select>
                            <div class="filter-used"></div>
                        </div>

                        <div class="filter-item">
                            <h5>Documentação</h5>
                            <select name="cidade" class="use-select2">
                                <option value="">Selecione</option>
                                <option value="">teste</option>
                                <option value="">teste</option>
                                <option value="">teste</option>
                                <option value="">teste</option>
                                <option value="">teste</option>
                                <option value="">teste</option>
                            </select>
                            <div class="filter-used"></div>
                        </div>

                    </div>
                </div>
                <div class="content-body">

                    <ul class="car-search-list">
                        <li v-for="carro in result">
                            <a href="#">
                                <div class="car-thumb-img">
                                    <img src="https://s3-sa-east-1.amazonaws.com/fotoscarros/@{{ carro.foto_capa }}" alt="">
                                </div>
                                <div class="car-resume-info">
                                    <div class="car-resume-name">
                                        @{{ carro.nome_carro }}
                                    </div>
                                    <div class="car-resume-price">
                                        <span>R$ @{{ carro.preco }}</span>
                                    </div>
                                </div>
                                <div class="clear"></div>
                                <div class="car-resume-features">
                                    <div class="car-resume-single-feature">
                                        <i class="stm-icon-road"></i> <span>@{{ carro.kilometragem }} KM</span>
                                    </div>
                                    <div class="car-resume-single-feature">
                                        <i class="stm-icon-fuel"></i> <span>@{{ carro.ano }}</span>
                                    </div>
                                    <div class="car-resume-single-feature">
                                        <i class="stm-icon-transmission_fill"></i> <span>manual</span>
                                    </div>
                                </div>
                            </a>
                        </li>
                    </ul>

                </div>
            </div>

        </div>

        <div class="top_promo_block" v-show="!searched" id="promo_head">

            <div class="start_descrition">
                <div style="align: center"><img src="/img/logo.png" alt="#"></div>
                <div class="search_promo">
                    <form id="search-form" v-on:submit.prevent v-bind:class="{'active': search, 'autocomplete': search_items.length}" role="form">

                        <input
                                v-el:search-input
                                autocomplete="off"
                                aria-autocomplete="off"
                                v-on:keyup.down="searchSelectNext"
                                v-on:keyup.enter="searchEnter"
                                v-on:keyup.up="searchSelectPrev"
                                v-on:keyup="searchRequest"
                                v-model="search"
                                type="text"
                                id="search-field"
                                class="form-control autocomplete-searchinput">

                        <button role="button" id="search-form-btn"><i class="fa fa-search"></i></button>
                        <button v-on:click="cleanSearch" role="button" v-show="search" id="cleansearch-form-btn"><i class="fa fa-times"></i></button>

                        <div v-show="search_items.length" id="autocomplete">
                            <ul>
                                <li v-bind:class="{'active': $index == search_selected}" v-for="item in search_items" v-on:click="autocompleteSelect(item.descricao)">
                                    @{{ item.descricao }}
                                </li>
                            </ul>
                        </div>
                    </form>
                </div>

                <span>Procure e compare dentre carros de todo o Brasil.</span>

                <div class="input-group-btn btn_promo_search">
                    <button type="button" class="btn btn-success bg-primary btn-navegar" onclick="window.location.href='01.html'">
                        <i class="stm-icon-car_search"></i>
                        Navegar
                    </button>
                </div>
            </div>
        </div>

    </div>

    <script src="/js/app.js" type="text/javascript"></script>
</body>
</html>