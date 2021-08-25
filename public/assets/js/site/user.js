var modal = (element, route, elementRacine) => {
    element.click((e) => {
        e.preventDefault();

        let $id    = element.attr('data-id');
        let $modal = '#confirm'+$id;

        $.ajax({
            url: Routing.generate(route, {id: $id}),
            type: 'GET',
            success: function(data) {
                $(elementRacine).html(data.html);
                $($modal).modal()
            }
        });
    });
};

(function() {

    let $container = $('#modal-container');

    /**
     * Suppression des annonces
     */
    modal($('.app-user-advert-delete'), 'app_dashboard_advert_delete', $container);

    /**
     * Suppression des messages
     */
    modal($('.app-advert-message-delete'), 'app_dashboard_message_delete', $container);

    /**
     * Suppression des alertes
     */
    modal($('.app-advert-alert-delete'), 'app_dashboard_alert_delete', $container);


    // <---------
    // Gestion des credit
    let $creditBulk = $('.app-credit-bulk .credit'), $inputCredit = $('input#form_credit');

    $creditBulk.hover(function (){
        let $this = $(this), $el1 = $this.find('img.one'),
            $el2 = $this.find('img.two');
        $el1.attr('src', $devise)
        $el2.attr('src', $deviseWrite)

    }, function () {
        let $this = $(this), $el = $this.find('img');

        if (!$this.find('input.with-gap').prop('checked')) {
            $el.attr('src', $deviseDark)
        }
    })

    $creditBulk.on('click', function () {
        $creditBulk.removeClass('active');

        let $this = $(this), $input = $this.find('input.with-gap'), $id = null,
            $el1 = $this.find('img.one'), $el2 = $this.find('img.two');

        $('img.devise').attr('src', $deviseDark)
        $el1.attr('src', $devise)
        $el2.attr('src', $deviseWrite)

        $input.each(function (index, element) {
            let $el = $(element)
            $el.prop('checked', true);
            $id = $el.val();
        });

        $(this).addClass('active');
        $inputCredit.val($id);

        $('button').removeAttr('disabled');
    });

    let $inputRadio  = $('.form-check-input[type="radio"]');

    $inputRadio.click(function () {
        let $this = $(this);

        if ($this.prop('checked')) {
            let $inputRadioBlockParent = $($this.parents('.app-radio-block-parent')[0]);
            let $inputRadioBlock = $($this.parents('.app-radio-block')[0]);
            let $children = $inputRadioBlockParent.find('.app-radio-block');

            $children.addClass('white').removeClass('light-color text-primary');
            $inputRadioBlock.removeClass('white').addClass('light-color text-primary');
        }
    });
    // --------->


    // <---------
    //  Gestion des options des annonces
    let $inputOption = $('input.option-input'), $optionID = "";

    $inputOption.click(function () {

        let $this = $(this), $parent = $this.parents('#option-data'),
            $btn = $parent.find('a.btn-primary');

        $btn.removeClass('disabled');

        if (!($this.val() === $optionID)) {
            if ($optionID !== "") {
                $.ajax({
                    url: Routing.generate('app_cart_delete', {'id': $optionID}),
                    success: function(data) {
                        if (data.success) {
                            notification("Panier", "Precedent option retire du panier", {}, 'success')
                        }
                        loader(false);
                    }
                });
            }

            $optionID = $this.val();

            $.ajax({
                url: Routing.generate('app_cart_add', {'id': $optionID}),
                success: function(data) {
                    if (data.success) {
                        notification("Panier", "Option ajouter au panier", {}, 'success')
                    } else {
                        notification("Panier", "Erreur: Option déjà dans le panier", {}, 'error')
                    }
                }
            });
        } else {
            notification("Panier", "Cette option a deja été selectionnée pour une autre annonce", {}, 'info');
        }
    });
    // --------->

    /**
     * ##############
     * Upload File
     * ##############
     */
    function readURL(input) {

        let url = input.value;
        let ext = url.substring(url.lastIndexOf('.')+1).toLowerCase();

        if (input.files && input.files[0] && (ext === 'gif' || ext === 'png' || ext === 'jpeg' || ext === 'jpg')) {
            let reader = new FileReader();

            reader.onload = function (e) {
                $('#image-view').attr('src', e.target.result).removeClass('d-none');
            };

            reader.readAsDataURL(input.files[0])
        }
    }

    $('#entity-image').change(function () { readURL(this)});

    // Active advert show bouton
    let $advertBlock = $('.app-advert-list-block');

    $advertBlock.click(function () {
        let $this = $(this);
        let $link = $this.find('.btn-advert-show')[0];

        $link.click();
    });

    $('#app-parrainage-btn-link-copy').click(function(e) {
        e.preventDefault();

        navigator.clipboard.writeText($link);

        notification('', 'Lien copié', {}, 'success');
    });

    $('#app-parrainage-btn-generate').click(function(e) {
        e.preventDefault();

        $('#app-parrainage-gestion').removeClass('d-none');
        $(this).addClass('disabled');

        notification('', 'Lien générez', {}, 'success');
    });


})();
