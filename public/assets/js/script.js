$(document).ready(function() {

    /**
     * --------------
     * Nav bar
     * --------------
     */
    $('.dropdown-menu.mega-menu').mouseenter(function () {
        $(this).parent('li.mega-dropdown').addClass('selected');
    }).mouseleave(function () {
        $(this).parent('li.mega-dropdown').removeClass('selected');
    });

    $('.not-child').mouseenter(function () {
        $(this).parents('.row').find('.app-menu-second').html("");
    });

    $('.has-child').mouseenter(function () {
        let $container = $(this).parents('.row').find('.app-menu-second');

        $.ajax({
            url: Routing.generate('app_category_by_parent', {id: $(this).attr('id')}),
            type: 'GET',
            success: function(data){
                let $result = $.parseJSON(data),
                    $ul = $('<ul class="list-unstyled"></ul>');

                $container.html("");

                let $title = $('<h6 class="sub-title">' +
                    '<a href="">Tout afficher dans '+$result[0].parent.name+'</a>' +
                    '</h6>');

                $.each($result, function(i, obj) {
                    let route = Routing.generate('app_advert_index_ss', {
                        category_slug: obj.parent.parent.slug,
                        sub_category_slug: obj.parent.slug,
                        sub_division_slug: obj.slug
                    });

                    $ul.append($('<li class="mb-2"><a class="menu-item" href="'+route+'">'+obj.name+'</a></li>'));
                });

                $container.append($title);
                $container.append($ul);
            }
        });
    });

    /** Gestion des favoris **/
    $('.ad-favorite').click(function (e) {
        e.preventDefault();
        e.stopPropagation();

        let $this = $(this);

        if (!$this.hasClass('btn-favoris')) {
            notification("Favoris","Vous devez vous connecter avant de pouvoir" +
                " ajouter une annonce à vos favoris",
                {"timeOut": "10000", "closeButton": true}, "warning");
            e.stopPropagation();
            return;
        }

        if ($this.hasClass('active')) {
            $.ajax({
                url: Routing.generate('app_favorite_delete', {id: $(this).attr('id')}),
                type: 'GET',
                success: function(data){
                    let $result = data;

                    if ($result.data.type === 'success') {
                        $this.removeClass('active');
                        $this.children('i')
                            .removeClass('fas text-danger')
                            .addClass('far dark-grey-text');

                        notification("Gestion des favoris", $result.data.message,
                            {"timeOut": "10000", "closeButton": true}, $result.data.type);
                    } else {
                        notification("Gestion des favoris", $result.data.message,
                            {"timeOut": "10000", "closeButton": true}, $result.data.type);
                    }
                }
            });
        } else {
            $.ajax({
                url: Routing.generate('app_favorite_add', {id: $(this).attr('id')}),
                type: 'GET',
                success: function(data){
                    let $result = data;

                    if ($result.data.type === 'success') {
                        $this.addClass('active');
                        $this.children('i').removeClass('far dark-grey-text').addClass('fas text-danger');

                        notification(
                            "Gestion des favoris", $result.data.message,
                            {"timeOut": "10000", "closeButton": true}, $result.data.type);
                    } else {
                        notification(
                            "Gestion des favoris", $result.data.message,
                            {"timeOut": "10000", "closeButton": true}, $result.data.type);
                    }
                }
            });
        }
    });

    // Gestion des notification serveur
    flashes($('.notify'));

    // Bouton top scroll
    $(window).scroll(function() {
        let scroll = $(window).scrollTop();
        let currScrollTop = $(this).scrollTop();

        if (scroll >= 200)
            $('#btn-smooth-scroll').removeClass('d-none').addClass('animated fadeInRight');
        else
            $('#btn-smooth-scroll').addClass('d-none').removeClass('animated fadeInRight');

        lastScrollTop = currScrollTop;
    });


    // ------------------------------>
    // Global search
    let $inputSearchGlobal = $('.search-box'),  $searchSubjection = $('.suggestion-box');

    $inputSearchGlobal.keyup(function(){
        let $q = $(this).val();

        $(document).click(function() {
            $searchSubjection.addClass('d-none');
        })

        $.ajax({
            type: "POST",
            url: Routing.generate('app_advert_search'),
            data: {'q': $q},
            beforeSend: function(){
                //$background = "#FFF url("+$loading+") 403px 8px no-repeat"
                //$zone.css("background", $background);
            },
            success: function(data){
                let $result = $.parseJSON(data);

                if ($result.length && $inputSearchGlobal.val()) {
                    let $container = $('<ul class="list-unstyled pb-0 mb-0"></ul>');

                    $.each($result, function(index, element){
                        let $content = $('<li class="item-pin"><a href="'+element.route+'" class="">'+element.title+'</a></li>')
                        $container.append($content);
                    });

                    $searchSubjection.html("");
                    $searchSubjection.append($container);
                    $searchSubjection.removeClass('d-none');

                    $('.item-pin a').click(function(){
                        let $this = $(this);
                        $inputSearchGlobal.val($this.text());
                        $searchSubjection.addClass('d-none');
                    })
                } else {
                    $searchSubjection.html("");
                }
            }
        });
    });

    // Localisation
    let $city = document.querySelector('#app-global-city-name'),
        $zone = $("#app-global-zone-name"), $zoneSubjection = $('#suggesstion-zone-box');

    let placesAutocomplete = places({
        appId: 'plAFU47KNT9R',
        apiKey: '82df8d2dee045f2337d35a00c7a868af',
        container: $city,
        type: 'city',
        aroundLatLngViaIP: false,
        language: 'fr',
        countries: ['CI'],
        templates: {
            value: function(suggestion) {
                return suggestion.name;
            }
        }
    });

    placesAutocomplete.on('change', e => {
        $city.textContent = e.suggestion.value;
        $inputLocation.val(e.suggestion.value);
        $('#app-location-mobile-input').val(e.suggestion.value);

        $zone.val("");
        $('#app-search-zone-global').removeClass('d-none');

        $.ajax({
            url: Routing.generate('app_location_session', {
                name: e.suggestion.value,
                type: 1
            }),
            type: 'GET',
        });
    });

    placesAutocomplete.on('clear', function() {
        $city.textContent = 'none';
    });

    let $placeBulk = $('#algolia-location .algolia-places'),
        $label = $('<label for="app-global-city-name">Ville</label>');

    $placeBulk.addClass('d-block md-form md-outline form-lg');
    $placeBulk.append($label)
    $('#algolia-location .algolia-places input.ap-input').addClass('form-control form-control-lg');

    $zone.keyup(function(){
        $.ajax({
            type: "POST",
            url: Routing.generate('app_location_zone', {'city': $city.textContent}),
            data: {'q': $(this).val()},
            beforeSend: function(){
                //$background = "#FFF url("+$loading+") 403px 8px no-repeat"
                //$zone.css("background", $background);
            },
            success: function(data){
                let $result = $.parseJSON(data);

                if ($result.length && $zone.val()) {
                    let $container = $('<ul class="list-unstyled pb-0 mb-0"></ul>'),
                        $title = $('<div class="title small-9 font-weight-stone-500 dark-grey-text pt-3 pl-3 mb-1">Suggestion de lieu</div>');

                    $.each($result, function(index, element){
                        let $content = $('<li class="item-pin"><i class="fas fa-map-pin mx-2"></i>'+element.zone+'</li>')

                        $container.append($content);
                    });

                    $zoneSubjection.html("");
                    $zoneSubjection.append($title);
                    $zoneSubjection.append($container);
                    $zoneSubjection.removeClass('d-none');
                    $zone.css("background","#FFF")

                    $('.item-pin').click(function(){
                        let $this = $(this);
                        $zone.val($this.text());
                        $zoneSubjection.addClass('d-none');

                        $.ajax({
                            url: Routing.generate('app_location_session', {
                                name: $this.text(),
                                type: 0,
                            }),
                            type: 'GET',
                        });
                    })
                } else {
                    $zoneSubjection.html("");
                    $zone.css("background","#FFF")
                }
            }
        });
    });

    $(document).click(function(){$zoneSubjection.addClass('d-none');});

    // Gestion du formulaire de recherche global
    let $inputLocation = $('#app-location-input'),
        $inputMobileLocation = $('#app-location-mobile-input');

    $inputLocation.click(function (e) {
        e.preventDefault();

        $('.app-advert-search-global-modal').modal()
    });

    $inputMobileLocation.click(function (e) {
        e.preventDefault();

        $('.app-advert-search-global-modal').modal()
    });
    // <------------------------------

    // Password show
    let $iconEye = $('.input-prefix.fa-eye');

    $iconEye.click(function () {
        let $this = $(this);

        if ($this.hasClass('fa-eye')) {
            $this.removeClass('fa-eye').addClass('fa-eye-slash view');

            $this.siblings('input').get(0).type = 'text';
        } else {
            $this.removeClass('fa-eye-slash view').addClass('fa-eye');

            $this.siblings('input').get(0).type = 'password';
        }
    });

    // Time js
    const terms = [
        {
            time: 45,
            divide: 60,
            text: "moins d'une minute"
        },
        {
            time: 90,
            divide: 60,
            text: 'environ une minute'
        },
        {
            time: 45 * 60,
            divide: 60,
            text: '%d minutes'
        },
        {
            time: 90 * 60,
            divide: 60 * 60,
            text: 'environ une heure'
        },
        {
            time: 24 * 60 * 60,
            divide: 60 * 60,
            text: '%d heures'
        },
        {
            time: 42 * 60 * 60,
            divide: 24 * 60 * 60,
            text: 'environ un jour'
        },
        {
            time: 30 * 24 * 60 * 60,
            divide: 24 * 60 * 60,
            text: '%d jours'
        },
        {
            time: 45 * 24 * 60 * 60,
            divide: 24 * 60 * 60 * 30,
            text: 'environ un mois'
        },
        {
            time: 365 * 24 * 60 * 60,
            divide: 24 * 60 * 60 * 30,
            text: '%d mois'
        },
        {
            time: 365 * 1.5 * 24 * 60 * 60,
            divide: 24 * 60 * 60 * 365,
            text: 'environ un an'
        },
        {
            time: Infinity,
            divide: 24 * 60 * 60 * 365,
            text: '%d ans'
        }
    ];

    let $dataTime = $('[data-time]');

    $dataTime.each(function (index, element) {
        const timestamp = parseInt(element.getAttribute('data-time'), 10) * 1000;
        const date = new Date(timestamp);

        updateText(date, element, terms);
    });


    // Carousel
    $('.carousel .carousel-inner.vv-3 .carousel-item').each(function () {
        var next = $(this).next();
        if (!next.length) {
            next = $(this).siblings(':first');
        }
        next.children(':first-child').clone().appendTo($(this));

        for (var i = 0; i < 4; i++) {
            next = next.next();
            if (!next.length) {
                next = $(this).siblings(':first');
            }

            next.children(':first-child').clone().appendTo($(this));
        }

        $('.carousel').carousel('cycle');
    });



    // Popover
    $('[data-toggle="popover-click"]').popover({
        html: true,
        trigger: 'hover',
        placement: 'top',
        content: function () {
            return $('#popover_content_wrapper').html();
        }
    });
});

function updateText(date, element, terms) {
    const seconds = (new Date().getTime() - date.getTime()) / 1000;
    let term = null;
    const prefix = element.getAttribute('prefix');

    for (term of terms) {
        if (Math.abs(seconds) < term.time) {
            break
        }
    }

    if (seconds >= 0) {
        element.innerHTML = `${prefix || 'Il y a'} ${term.text.replace('%d', Math.round(seconds / term.divide))}`
    } else {
        element.innerHTML = `${prefix || 'Dans'} ${term.text.replace('%d', Math.round(Math.abs(seconds) / term.divide))}`
    }
}

/**
 * Affiche des notifications
 *
 * @param titre
 * @param message
 * @param options
 * @param type
 */
function notification(titre, message, options, type) {
    if(typeof options == 'undefined') options = {};
    if(typeof type == 'undefined') type = "info";

    toastr[type](message, titre, options);
}

let options = {
    "closeButton": false, // true/false
    "debug": false, // true/false
    "newestOnTop": false, // true/false
    "progressBar": true, // true/false
    "positionClass": "toast-top-right", // toast-top-right / toast-top-left / toast-bottom-right / toast-bottom-left
    "preventDuplicates": false, //true/false
    "onclick": null,
    "showDuration": "300", // in milliseconds
    "hideDuration": "1000", // in milliseconds
    "timeOut": "8000", // in milliseconds
    "extendedTimeOut": "1000", // in milliseconds
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
};

function simpleModals(element, route, elementRacine) {
    element.click(function (e) {
        e.preventDefault();

        let $id = $(this).attr('id'), $modal = '#confirm'+$id;

        $.ajax({
            url: Routing.generate(route, {id: $id}),
            type: 'GET',
            success: function(data) {
                $(elementRacine).html(data.html);
                $($modal).modal()
            }
        });
    });
}

function bulkModals(element, container, route, elementRacine) {
    element.click(function (e) {
        e.preventDefault();

        let ids = [];

        container.find('.list-checkbook').each(function () {
            if ($(this).prop('checked')) {
                ids.push($(this).val());
            }
        });

        if (ids.length) {
            let $modal = '#confirmMulti'+ids.length;

            $.ajax({
                url: Routing.generate(route),
                data: {'data': ids},
                type: 'GET',
                success: function(data) {
                    $(elementRacine).html(data.html);
                    $($modal).modal()
                }
            });
        }
    });
}

function flashes (selector) {
    selector.each(function (index, element) {
        if ($(element).html() !== undefined) {
            toastr[$(element).attr('app-data')]($(element).html());
        }
    })
}
