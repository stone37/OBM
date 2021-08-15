$(document).ready(function() {
    let $city = document.querySelector('#app-city-name'),
        $zone = $('#app-advert-search-zone'),
        $zoneSubjection = $('.search-advert-zone-suggestion-box');

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

        $.ajax({
            url: Routing.generate('app_location_latlng_session', {
                lng: e.suggestion.latlng.lng,
                lat: e.suggestion.latlng.lat
            }),
            type: 'GET',
        });
    });

    placesAutocomplete.on('clear', function() {
        $city.textContent = 'none';
    });

    $zone.keyup(function() {
        $.ajax({
            type: "POST",
            url: Routing.generate('app_location_zone', {'city': $city.textContent}),
            data: {'q': $(this).val()},
            success: function (data) {
                let $result = $.parseJSON(data);

                if ($result.length && $zone.val()) {
                    console.log($result)
                    let $container = $('<ul class="list-unstyled pb-0 mb-0"></ul>'),
                        $title = $('<div class="title small-8 font-weight-stone-500 dark-grey-text pt-2 pl-1 mb-1">Suggestion de lieu</div>');

                    $.each($result, function (index, element) {
                        let $content = $('<li class="item-pin"><i class="fas fa-map-pin mx-2"></i>' + element.zone + '</li>')

                        $container.append($content);
                    });

                    $zoneSubjection.html("");
                    $zoneSubjection.append($title);
                    $zoneSubjection.append($container);
                    $zoneSubjection.removeClass('d-none');
                    $zone.css("background", "#FFF")

                    $('.item-pin').click(function () {
                        let $this = $(this);
                        $zone.val($this.text());
                        $zoneSubjection.addClass('d-none');
                    })
                } else {
                    $zoneSubjection.html("");
                    $zone.css("background", "#FFF")
                }
            }
        });
    });

});

