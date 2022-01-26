$(document).ready(function() {
    // Localisation
    let $advertCitySelect = $('select.app-advert-location-city'),
        $zone = $('#app-advert-search-zone'),
        $zoneSubjection = $('.search-advert-zone-suggestion-box');

    $zone.keyup(function(){
        if ($advertCitySelect.val()) {
            $.ajax({
                type: "POST",
                url: Routing.generate('app_location_zone', {'city': $advertCitySelect.val()}),
                data: {'q': $(this).val()},
                success: function(data){
                    let $result = $.parseJSON(data);

                    if ($result.length && $zone.val()) {
                        let $container = $('<ul class="list-unstyled pb-0 mb-0"></ul>'),
                            $title = $('<div class="title small-9 font-weight-stone-500 text-primary pt-3 pl-3 mb-1">Suggestion de lieu</div>');

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
        }
    });
});

