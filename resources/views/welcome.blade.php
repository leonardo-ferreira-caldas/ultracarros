
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
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <!--animation css-->
    <link rel="stylesheet" type="text/css" href="/css/animate.css">
    <!-- Slider Revolution -->
    <link rel="stylesheet" type="text/css" href="/rs-plugin/css/settings.css" media="screen" />
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="/https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="/https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.13/vue.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue-resource/0.6.0/vue-resource.min.js"></script>
    <script src="//cdn.jsdelivr.net/algoliasearch/3/algoliasearch.min.js"></script>
</head>
<body class="promo" id="app">
    <div class="site-wrapper-border"></div>

    <!--autorization-->
    <div class="add_place none" id="autorized">
        <div class="place_form login_form">
            <i class="fa fa-times close_window" id="closeau"></i>
            <h3>Autorization<span></span></h3>
            <form>
                <label>Login:<input type="text"></label>
                <label>Password:<input type="text"></label>
                <a href="#" class="btn btn-success">Log in</a>
                <a href="#" class="btn btn-primary"><i class="icon-facebook"></i>Log in with Facebook</a>
            </form>
        </div>
    </div>

    <!-- Site Overlay -->
    <div class="site-overlay"></div>

    <div id="container">
        <div class="top_promo_block" id="promo_head">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="header_promo">
                            <a href="#" class="log_btn color-primary">Log in</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="start_descrition">
                <div style="align: center"><img src="/img/logo.png" alt="#"></div>
                <div class="search_promo">
                    <form action="" id="search-form" v-on:submit.prevent v-bind:class="{'active': search, 'autocomplete': search_items.length}" role="form">
                        <input autocomplete="off" aria-autocomplete="off" v-on:keyup.down="searchSelectNext" v-on:keyup.enter="searchEnter" v-on:keyup.up="searchSelectPrev" v-on:keyup="searchRequest" v-model="search" type="text" id="search-field" class="form-control">
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