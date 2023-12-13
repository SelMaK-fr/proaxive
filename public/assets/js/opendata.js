$("#form_customer-zipcode").autocomplete({
    source: function (request, response) {
        $.ajax({
            url: "https://api-adresse.data.gouv.fr/search/?postcode="+$("input[name='form_customer[zipcode]']").val(),
            data: { q: request.term },
            dataType: "json",
            success: function (data) {
                var postcodes = [];
                response($.map(data.features, function (item) {
                    // Ici on est obligé d'ajouter les CP dans un array pour ne pas avoir plusieurs fois le même
                    if ($.inArray(item.properties.postcode, postcodes) == -1) {
                        postcodes.push(item.properties.postcode);
                        return { label: item.properties.postcode + " - " + item.properties.city,
                            city: item.properties.city,
                            value: item.properties.postcode
                        };
                    }
                }));
            }
        });
    },
    // On remplit aussi la ville
    select: function(event, ui) {
        $('#ville').val(ui.item.city);
    }
});
$("#form_customer-city").autocomplete({
    source: function (request, response) {
        $.ajax({
            url: "https://api-adresse.data.gouv.fr/search/?city="+$("input[name='form_customer[city]']").val(),
            data: { q: request.term },
            dataType: "json",
            success: function (data) {
                var cities = [];
                response($.map(data.features, function (item) {
                    // Ici on est obligé d'ajouter les villes dans un array pour ne pas avoir plusieurs fois la même
                    if ($.inArray(item.properties.postcode, cities) == -1) {
                        cities.push(item.properties.postcode);
                        return { label: item.properties.postcode + " - " + item.properties.city,
                            postcode: item.properties.postcode,
                            value: item.properties.city
                        };
                    }
                }));
            }
        });
    },
    // On remplit aussi le CP
    select: function(event, ui) {
        $('#cp').val(ui.item.postcode);
    }
});
$("#form_customer-address").autocomplete({
    source: function (request, response) {
        $.ajax({
            url: "https://api-adresse.data.gouv.fr/search/?q="+$("input[name='form_customer[address]']").val(),
            data: { q: request.term },
            dataType: "json",
            success: function (data) {
                response($.map(data.features, function (item) {
                    return {
                        label: item.properties.name + " - " + item.properties.city,
                        lon: item.geometry.coordinates[0],
                        lat: item.geometry.coordinates[1],
                        postalCode: item.properties.postcode,
                        cityName: item.properties.city,
                        department: item.properties.context,
                        value: item.properties.name
                    };
                }));
            }
        });
    },
    // On remplit aussi le LG
    select: function(event, ui) {
        $('#form_customer-addr_longitude').val(ui.item.lon)
        $('#form_customer-addr_latitude').val(ui.item.lat)
        $('#form_customer-zipcode').val(ui.item.postalCode)
        $('#form_customer-city').val(ui.item.cityName);
        $('#form_customer-department').val(ui.item.department);
    }
});