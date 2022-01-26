$(document).ready(function() {
    // SideNav Button Initialization
    $('.button-collapse').sideNav({
        edge: 'left', // Choose the horizontal origin
        closeOnClick: false, // Closes side-nav on &lt;a&gt; clicks, useful for Angular/Meteor
        breakpoint: 1440, // Breakpoint for button collapse
        menuWidth: 250, // Width for sidenav
        timeDurationOpen: 300, // Time duration open menu
        timeDurationClose: 200, // Time duration open menu
        timeDurationOverlayOpen: 50, // Time duration open overlay
        timeDurationOverlayClose: 200, // Time duration close overlay
        easingOpen: 'easeOutQuad', // Open animation
        easingClose: 'easeOutCubic', // Close animation
        showOverlay: true, // Display overflay
        showCloseButton: false // Append close button into siednav
    });

    // SideNav Scrollbar Initialization
    var sideNavScrollbar = document.querySelector('.custom-scrollbar');
    var ps = new PerfectScrollbar(sideNavScrollbar);


    // Gestion des checkbox dans la liste
    let $principalCheckbox = $('#principal-checkbox'),
        $listCheckbook = $('.list-checkbook'),
        $listCheckbookLength = $listCheckbook.length,
        $listCheckbookNumber = 0,
        $btnBulkDelete = $('#entity-list-delete-bulk-btn'),
        $btnClassBulkDelete = $('.entity-list-delete-bulk-btn');

    $principalCheckbox.on('click', function () {
        let $this = $(this);

        if ($this.prop('checked')) {
            $('.list-checkbook').prop('checked', true);

            $listCheckbookNumber = $listCheckbookLength;

            if ($listCheckbookLength > 0) {
                $btnBulkDelete.removeClass('d-none');
                $btnClassBulkDelete.removeClass('d-none');
            }

        } else {
            $('.list-checkbook').prop('checked', false);
            $btnBulkDelete.addClass('d-none');
            $btnClassBulkDelete.addClass('d-none');

            $listCheckbookNumber = 0;
        }
    });

    $listCheckbook.on('click', function () {
        let $this = $(this);

        if ($this.prop('checked')) {
            $listCheckbookNumber++;
            $btnBulkDelete.removeClass('d-none');
            $btnClassBulkDelete.removeClass('d-none');

            if ($listCheckbookNumber === $listCheckbookLength)
                $principalCheckbox.prop('checked', true)
        } else {
            $listCheckbookNumber--;

            if ($listCheckbookNumber === 0) {
                $btnBulkDelete.addClass('d-none');
                $btnClassBulkDelete.addClass('d-none');
            }

            if ($listCheckbookNumber < $listCheckbookLength)
                $principalCheckbox.prop('checked', false)
        }
    });

    let $container = $('#modal-container'),
        $checkbookContainer = $('#list-checkbook-container');

    // Gestion des validation
    simpleModals($('.entity-advert-validate'), 'app_admin_advert_validate', $container);
    bulkModals($('.entity-advert-validate-bulk-btn a.btn-success'), $checkbookContainer,
        'app_admin_advert_bulk_validate', $container);

    // Gestion des refus
    simpleModals($('.entity-advert-denied'), 'app_admin_advert_denied', $container);
    bulkModals($('.entity-advert-denied-bulk-btn a.btn-amber'), $checkbookContainer,
        'app_admin_advert_bulk_denied', $container);

    // Gestion des suppression partielle
    simpleModals($('.entity-advert-soft-delete'), 'app_admin_advert_soft_delete', $container);

    // Gestion des bannissement
    simpleModals($('.entity-advert-bannir'), 'app_admin_advert_banned', $container);


    // Gestion des suppression

    // Category
    simpleModals($('.entity-category-delete'), 'app_admin_category_delete', $container);
    bulkModals($('.entity-category-delete-bulk-btn a.btn-danger'), $checkbookContainer,
        'app_admin_category_bulk_delete', $container);

    // Category premium
    simpleModals($('.entity-category-premium-delete'), 'app_admin_category_premium_delete', $container);
    bulkModals($('.entity-category-premium-delete-bulk-btn a.btn-danger'), $checkbookContainer,
        'app_admin_category_premium_bulk_delete', $container);

    // Advert
    simpleModals($('.entity-advert-delete'), 'app_admin_advert_delete', $container);
    bulkModals($('.entity-advert-delete-bulk-btn a.btn-danger'), $checkbookContainer,
        'app_admin_advert_bulk_delete', $container);

    // Option
    simpleModals($('.entity-option-delete'), 'app_admin_option_delete', $container);
    bulkModals($('.entity-option-delete-bulk-btn a.btn-danger'), $checkbookContainer,
        'app_admin_option_bulk_delete', $container);

    // User
    simpleModals($('.entity-user-delete'), 'app_admin_user_delete', $container);
    bulkModals($('.entity-user-delete-bulk-btn a.btn-danger'), $checkbookContainer,
        'app_admin_user_bulk_delete', $container);

    // Administrateur
    simpleModals($('.entity-admin-delete'), 'app_admin_admin_delete', $container);
    bulkModals($('.entity-admin-delete-bulk-btn a.btn-danger'), $checkbookContainer,
        'app_admin_admin_bulk_delete', $container);

    // Credit
    simpleModals($('.entity-credit-delete'), 'app_admin_credit_delete', $container);
    bulkModals($('.entity-credit-delete-bulk-btn a.btn-danger'), $checkbookContainer,
        'app_admin_credit_bulk_delete', $container);

    // Discount
    simpleModals($('.entity-discount-delete'), 'app_admin_discount_delete', $container);
    bulkModals($('.entity-discount-delete-bulk-btn a.btn-danger'), $checkbookContainer,
        'app_admin_discount_bulk_delete', $container);

    // Pack
    simpleModals($('.entity-pack-delete'), 'app_admin_pack_delete', $container);
    bulkModals($('.entity-pack-delete-bulk-btn a.btn-danger'), $checkbookContainer,
        'app_admin_pack_bulk_delete', $container);

    // Pub
    simpleModals($('.entity-pub-delete'), 'app_admin_pub_delete', $container);
    bulkModals($('.entity-pub-delete-bulk-btn a.btn-danger'), $checkbookContainer,
        'app_admin_pub_bulk_delete', $container);

    // Order
    simpleModals($('.entity-order-delete'), 'app_admin_order_delete', $container);
    bulkModals($('.entity-order-delete-bulk-btn a.btn-danger'), $checkbookContainer,
        'app_admin_order_bulk_delete', $container);

    // Payment
    simpleModals($('.entity-payment-delete'), 'app_admin_payment_delete', $container);
    bulkModals($('.entity-payment-delete-bulk-btn a.btn-danger'), $checkbookContainer,
        'app_admin_payment_bulk_delete', $container);

    // Taxe
    simpleModals($('.entity-tax-delete'), 'app_admin_taxe_delete', $container);
    bulkModals($('.entity-tax-delete-bulk-btn a.btn-danger'), $checkbookContainer,
        'app_admin_taxe_bulk_delete', $container);

    // City
    simpleModals($('.entity-city-delete'), 'app_admin_city_delete', $container);
    bulkModals($('.entity-city-delete-bulk-btn a.btn-danger'), $checkbookContainer,
        'app_admin_city_bulk_delete', $container);

    // Zone
    simpleModals($('.entity-zone-delete'), 'app_admin_zone_delete', $container);
    bulkModals($('.entity-zone-delete-bulk-btn a.btn-danger'), $checkbookContainer,
        'app_admin_zone_bulk_delete', $container);

    // Review
    simpleModals($('.entity-review-delete'), 'app_admin_review_delete', $container);
    bulkModals($('.entity-review-delete-bulk-btn a.btn-danger'), $checkbookContainer,
        'app_admin_review_bulk_delete', $container);

    // Vignette
    simpleModals($('.entity-vignette-delete'), 'app_admin_vignette_delete', $container);
    bulkModals($('.entity-vignette-delete-bulk-btn a.btn-danger'), $checkbookContainer,
        'app_admin_vignette_bulk_delete', $container);

    // Vignette list
    simpleModals($('.entity-vignette-list-delete'), 'app_admin_vignette_list_delete', $container);
    bulkModals($('.entity-vignette-list-delete-bulk-btn a.btn-danger'), $checkbookContainer,
        'app_admin_vignette_list_bulk_delete', $container);

    // Help
    simpleModals($('.entity-help-delete'), 'app_admin_help_delete', $container);
    bulkModals($('.entity-help-delete-bulk-btn a.btn-danger'), $checkbookContainer,
        'app_admin_help_bulk_delete', $container);

    // Help category
    simpleModals($('.entity-help-category-delete'), 'app_admin_help_category_delete', $container);
    bulkModals($('.entity-help-category-delete-bulk-btn a.btn-danger'), $checkbookContainer,
        'app_admin_help_category_bulk_delete', $container);

    // Advert clean
    $('.advert-clean').click(function (e) {
        e.preventDefault();

        $.ajax({
            url: Routing.generate('app_admin_advert_clean', {'type': $(this).attr('data-type')}),
            type: 'GET',
            success: function(data) {
                if (data.status) {
                    $(elementRacine).html(data.html);
                    $('#confirmClean').modal()
                } else {
                    notification('error', 'Aucune annonce disponible', {}, 'error')
                }
            }
        });
    });

    // Advert reload
    $('#advert-reload').click(function (e) {
        e.preventDefault();

        $.ajax({
            url: Routing.generate('app_admin_advert_reload'),
            type: 'GET',
            success: function(data) {
                if (data.status) {
                    $(elementRacine).html(data.html);
                    $('#confirmReload').modal()
                } else {
                    notification('Not data', 'Aucune annonce disponible', {}, 'info')
                }
            }
        });
    });

    // user clean
    $('#user-clean').click(function (e) {
        e.preventDefault();

        $.ajax({
            url: Routing.generate('app_admin_user_clean'),
            type: 'GET',
            success: function(data) {
                if (data.status) {
                    $(elementRacine).html(data.html);
                    $('#confirmClean').modal()
                } else {
                    notification('Not data', 'Aucune annonce disponible', {}, 'info')
                }
            }
        });
    });

    /**
     * ###########################
     * Gestion des categories
     * ###########################
     */
    let $categorySelect = $('select.app-advert-category'),
        $subCategorySelect = $('select.app-advert-sub-category'),
        $subDivisionSelect = $('select.app-advert-sub-division');

    $categorySelect.on("change", function() {
        let $this = $(this);

        if ($this.val()) {
            $.ajax({
                url: Routing.generate('app_category_by_parent', {id: $this.val()}),
                type: 'GET',
                success: function(data){
                    let $result = $.parseJSON(data);

                    let $subCategoryTitle = $("<option>").attr({value: "", selected: "selected"})
                            .text("Sous catégories"),
                        $subDivisionTitle = $("<option>").attr({value: "", selected: "selected"})
                            .text("Sous divisions");

                    $subCategorySelect.empty().html(" ");
                    $subDivisionSelect.empty().html(" ");

                    $subCategorySelect.append($subCategoryTitle);
                    $subDivisionSelect.append($subDivisionTitle);

                    $.each($result, function(i, obj) {
                        let $content = $("<option>").attr({value: obj.id}).text(obj.name);

                        $subCategorySelect.append($content);
                    });

                    $subCategorySelect.materialSelect();
                }
            });
        }
    });

    $subCategorySelect.on("change", function() {
        let $this = $(this);

        if ($this.val()) {
            $.ajax({
                url: Routing.generate('app_category_by_parent', {id: $this.val()}),
                type: 'GET',
                success: function(data){
                    let $result = $.parseJSON(data);

                    $subDivisionSelect.empty().html(" ");

                    let $title = $("<option>").attr({
                        value: "",
                        selected: "selected"
                    }).text("Sous divisions");

                    $subDivisionSelect.append($title);

                    $.each($result, function(i, obj) {
                        let $content = $("<option>").attr({value: obj.id}).text(obj.name);

                        $subDivisionSelect.append($content);
                    });

                    $subDivisionSelect.materialSelect();
                }
            });
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

    // Gestion des notification serveur
    flashes($('.notify'));
});

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






