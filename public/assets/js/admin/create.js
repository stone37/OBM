$(document).ready(function() {
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
                $('#image-view').attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0])
        }
    }

    $('#entity-image').change(function () { readURL(this)});

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

    /**
     * Affiche des notifications
     *
     * @param titre
     * @param message
     * @param options
     * @param type
     */
    function notification (titre, message, options, type) {
        if(typeof options == 'undefined') options = {};
        if(typeof type == 'undefined') type = "info";

        toastr[type](message, titre, options);
    }


});




