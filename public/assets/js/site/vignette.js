$(document).ready(function() {

    /**
     * #########################
     * Gestion des bouton radio
     * #########################
     */
    let $inputRadio  = $('.form-check-input[type="radio"]'), $tarif = $('.vignette-tarif'),
        $inputProduct = $('input#vignette_productId'),
        $inputRadioChecked = $($('.form-check-input[type="radio"]:checked')[0]);

    $inputProduct.val($inputRadioChecked.val());

    $inputRadio.click(function () {
        let $this = $(this);

        if ($this.prop('checked')) {
            let $inputRadioBlockParent = $($this.parents('.app-radio-block-parent')[0]);
            let $inputRadioBlock = $($this.parents('.app-radio-block')[0]);
            let $children = $inputRadioBlockParent.find('.app-radio-block');

            $children.addClass('white').removeClass('light-color text-default active');
            $inputRadioBlock.removeClass('white').addClass('light-color text-default active');

            $tarif.removeClass('active');
            $('#data_'+$this.val()).addClass('active');

            $inputProduct.val($this.val());
        }
    });

    /**
     * ##############################
     * Categories et sous categories
     * ##############################
     */
    let $category = $('select.app-vignette-category'),
        $subCategory = $('select.app-vignette-subcategory');

    $category.change(function () {
        $.ajax({
            url: Routing.generate('app_category_by_parent_slug', {slug: $(this).val()}),
            type: 'GET',
            success: function(data){
                let $result = $.parseJSON(data);

                $subCategory.empty().html(" ");

                let $title = $("<option>").attr({value: "", selected: "selected"})
                                            .text("Sous catégories");

                $subCategory.append($title);

                $.each($result, function(i, obj) {
                    let $content = $("<option>").attr({value: obj}).text(i);

                    $subCategory.append($content);
                });

                $subCategory.materialSelect();
            }
        });
    });

    /**
     * Textarea
     */
    $('textarea.md-textarea').characterCounter();

    /**
     * ##############
     * Upload File
     * ##############
     */
    function readURL(input) {

        let url = input.value;
        let ext = url.substring(url.lastIndexOf('.')+1).toLowerCase();

        console.log(url+' ' + ext)

        if (input.files && input.files[0] && (ext === 'gif' || ext === 'png' || ext === 'jpeg' || ext === 'jpg')) {
            let reader = new FileReader();

            reader.onload = function (e) {
                $('#image-view').attr('src', e.target.result).show();
            };

            reader.readAsDataURL(input.files[0])
        }
    }

    $('input#entity-image').change(function () { readURL(this)});
});

