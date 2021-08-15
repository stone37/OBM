$(document).ready(function() {
    let $el = $('div.app-payment-type.method'), $btn = $('button.payment-btn');

    $el.click(function() {
        let $this = $(this), $input = $this.find('input.with-gap');

        $input.prop('checked', true);
        $el.removeClass('active');
        $this.addClass('active');

        $btn.removeClass('disabled');
    })
});

function flashes (selector) {
    selector.each(function (index, element) {
        if ($(element).html() !== undefined) {
            toastr[$(element).attr('app-data')]($(element).html());
        }
    })
}
