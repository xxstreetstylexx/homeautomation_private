/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$(document).ready(function () {

    _init_lights();
    _init_weather();

    setInterval(_init_lights, 1000);
    setInterval(_init_weather, 10000);

    $('button#reload').click(function () {

        _init_lights();

    });

    $(document).on('click', 'div[data-light] div.card-body', function () {
        switch_state($(this).parent().data('light'));
    });

    $(document).on('click', 'div[data-light] div.card-footer', function () {
        create_alert($(this).parent().data('light'));
    });

    $(document).on('click', 'button[data-group]', function () {
        switch_grp_state($(this).data('group'));
    });

});

function switch_state(lightId) {
    $.ajax({
        url: '/api/switch/light/' + lightId
    }).done(function (res) {
        _init_lights();
    });
}

function switch_grp_state(groupId) {
    $.ajax({
        url: '/api/switch/group/' + groupId
    }).done(function (res) {
        _init_lights();
    });
}


function create_alert(lightId) {
    $.ajax({
        url: '/api/alert/light/' + lightId
    }).done(function (res) {
        _init_lights();
    });
}

function _init_lights() {
    $.ajax({
        url: '/api/get/lights'
    }).done(function (res) {
        for (var i = 0; i < res.length; i++) {
            var light = res[i];
            if ($('div#lightholder div[data-light="' + light.id + '"]').length == 1) {
                $('div[data-light="' + light.id + '"] span[data-state]').attr('data-state', light.state);
                $('div[data-light="' + light.id + '"] div.card-body').css('background-color', light.color);
                $status = '<i class="fa fa-exclamation-triangle"></i> Offline';
                if (light.reachable == true) {
                    $status = 'Online';
                }

                $('div[data-light="' + light.id + '"] div.card-footer').html($status);

            } else {
                $div = $('<div class="col">');
                $cardWrapper = $('<div class="card shadow position-relative" data-light="' + light.id + '">')
                        .appendTo($div);

                $indicator = $('<span class="position-absolute top-0 start-0 translate-middle p-2 border border-light rounded-circle" data-state="' + light.state + '">')
                        .appendTo($cardWrapper);

                $cardBodyTitle = $('<div class="card-header">')
                        .html(light.name)
                        .appendTo($cardWrapper);

                $cardBody = $('<div class="card-body">')
                        .css('background-color', light.color)
                        .appendTo($cardWrapper);



                //color = $('<span class="position-absolute top-50 start-0 translate-middle p-2 border border-light rounded-circle" data-color="1">')
                //        .css('background-color', light.color)
                //        .appendTo($cardWrapper);

                $cardBodySubtitme = $('<div class="card-subtitle mb-2 text-muted">')
                        .html('')
                        .appendTo($cardBody);

                $status = '<i class="fa fa-exclamation-triangle"></i> Offline';
                if (light.reachable == true) {
                    $status = 'Online';
                }
                $footer = $('<div class="card-footer text-muted">')
                        .html($status)
                        .appendTo($cardWrapper);


                $div.appendTo('#lightholder');
            }
        }

        _init_groups();
    });
}

function _init_groups() {
    $.ajax({
        url: '/api/get/groups'
    }).done(function (res) {

        for (var i = 0; i < res.length; i++) {
            var group = res[i];

            if (group.type != 'Zone' && group.type != 'LightGroup') {
                if ($('div[data-group="' + group.id + '"]').length == 1) {

                    if (group.state == true) {

                        $('button[data-group="' + group.id + '"]')
                                .removeClass('btn-danger')
                                .addClass('btn-success')
                                .html('An');

                    } else {

                        $('button[data-group="' + group.id + '"]')
                                .removeClass('btn-success')
                                .addClass('btn-danger')
                                .html('Aus');

                    }
                } else {
                    $div = $('<div class="col col-md-3 col-xs-6">');
                    $cardWrapper = $('<div class="card shadow position-relative" data-group="' + group.id + '">')
                            .appendTo($div);

                    $cardHeader = $('<div class="card-header">')
                            .html('<strong>' + group.name + '</strong>')
                            .appendTo($cardWrapper);

                    $grpBtn = $('<button class="btn btn-sm float-end" type="button" data-group="' + group.id + '">An/Aus</button>')
                            .appendTo($cardHeader);

                    //$grpSwt = $('<div class="form-check form-switch">')
                    //        .appendTo($cardHeader);

                    //$grpBtn = $('<input class="form-check-input" type="checkbox" data-group="' + group.id + '">')
                    //        .appendTo($grpSwt);

                    if (group.state == true) {

                        $grpBtn
                                .addClass('btn-success')
                                .html('An');

                    } else {

                        $grpBtn
                                .addClass('btn-danger')
                                .html('Aus');

                    }

                    for (var y = 0; y < group.lights.length; y++) {
                        $lightdiv = $('<div class="col">');
                        $lightCardWrapper = $('<div class="card shadow position-relative" data-light="' + group.lights[y] + '">')
                                .appendTo($lightdiv);

                        $indicator = $('<span class="position-absolute top-0 start-0 translate-middle p-2 border border-light rounded-circle" data-state="false">')
                                .appendTo($lightCardWrapper);

                        $cardBodyTitle = $('<div class="card-body">')
                                .html($('div#lightholder div[data-light="' + group.lights[y] + '"] div.card-header').html())
                                .appendTo($lightCardWrapper);


                        $lightdiv.appendTo($cardWrapper);
                    }

                    $div.appendTo('#groupholder');
                }
            }

        }

    });

}

function _init_weather() {
    $.ajax({
        url: '/api/get/weather'
    }).done(function (res) {
        if ($('div[data-weather="1"]').length == 1) {
            // Update
            $('div[data-weather="1"] div.card-body p.temp')
                    .html(res.current.temp + ' °C <span class="weatherdescription text-muted text-sm"></span>');

            $('div[data-weather="1"] div.card-body p.temp span.weatherdescription')
                    .html(res.current.weather[0].description);

            $('div[data-weather="1"] div.card-body img.wicon')
                    .attr('src', '/image/weather/icons/' + res.current.weather[0].icon + '.png');

            $cardHeader = $('<div class="card-footer text-muted">')
                    .html(res.current.dt)
            // ALERTS

            if (res.alerts == false) {
                // REMOVE BOX
                if ($('div[data-alert]').length > 0) {

                    $('div[data-alert]').remove();

                    $('link[href="/lcars/css/lcars-red-alert.min.css"]').attr('href', '/lcars/css/lcars-tng-legacy-2.min.css');

                }
            } else {
                if ($('div[data-alert]').length > 0) {

                    $('div[data-alert]').remove();

                }

                for (i = 0; i < res.alerts.length; i++) {

                    alert = res.alerts[i];

                    $alertdiv = $('<div class="col col-md-4 col-xs-12" data-alert="1">');

                    $alertcardWrapper = $('<div class="card bg-danger text-white shadow position-relative">')
                            .appendTo($alertdiv);

                    $alertcardHeader = $('<div class="card-header">')
                            .html(alert.sender_name)
                            .appendTo($alertcardWrapper);

                    $alertcardBody = $('<div class="card-body">')
                            .appendTo($alertcardWrapper);

                    $('<p class="alerttime">')
                            .html('Gültigkeit: ' + alert.start + ' - ' + alert.end)
                            .appendTo($alertcardBody);

                    $('<p class="alerttopic">')
                            .html('<strong>' + alert.sender_name + '</strong><br /><i>' + alert.description + '</i>')
                            .appendTo($alertcardBody);

                    for (x = 0; x < alert.tags.length; x++) {
                        $('<span class="badge bg-warning"> ')
                                .html(alert.tags[x])
                                .appendTo($alertcardHeader);
                    }

                    $alertdiv.appendTo('#weatherholder');
                }
                $('link[href="/lcars/css/lcars-tng-legacy-2.min.css"]').attr('href', '/lcars/css/lcars-red-alert.min.css');
            }

            
        } else {
            // New
            $div = $('<div class="col col-md-4 col-xs-12">');
            $cardWrapper = $('<div class="card shadow position-relative" data-weather="1">')
                    .appendTo($div);

            $cardHeader = $('<div class="card-header">')
                    .html('Wetter')
                    .appendTo($cardWrapper);

            $cardBody = $('<div class="card-body">')
                    .appendTo($cardWrapper);

            $cardBodySubtitme = $('<div class="card-subtitle mb-2 text-muted">')
                    .html('')
                    .appendTo($cardBody);

            $cardText = $('<p class="temp">')
                    .html(res.current.temp + ' °C ')
                    .appendTo($cardBody);

            $icon = $('<img class="wicon float-md-start">')
                    .attr('src', '/image/weather/icons/' + res.current.weather[0].icon + '.png')
                    .prependTo($cardBody);

            $span = $('<span class="weatherdescription text-muted text-muted">')
                    .html(res.current.weather[0].description)
                    .appendTo($cardText);

            $cardHeader = $('<div class="card-footer text-muted">')
                    .html(res.current.dt)
                    .appendTo($cardWrapper);

            $div.appendTo('#weatherholder');

            if (res.alerts == false) {
                // REMOVE BOX
                
                $('link[href="/lcars/css/lcars-red-alert.min.css"]').attr('href', '/lcars/css/lcars-tng-legacy-2.min.css');

            } else {

                for (i = 0; i < res.alerts.length; i++) {

                    alert = res.alerts[i];

                    $alertdiv = $('<div class="col col-md-4 col-xs-12" data-alert="1">');

                    $alertcardWrapper = $('<div class="card bg-danger text-white shadow position-relative">')
                            .appendTo($alertdiv);

                    $alertcardHeader = $('<div class="card-header">')
                            .html(alert.sender_name)
                            .appendTo($alertcardWrapper);

                    $alertcardBody = $('<div class="card-body">')
                            .appendTo($alertcardWrapper);

                    $('<p class="alerttime">')
                            .html('Gültigkeit: ' + alert.start + ' - ' + alert.end)
                            .appendTo($alertcardBody);

                    $('<p class="alerttopic">')
                            .html('<strong>' + alert.sender_name + '</strong><br /><i>' + alert.description + '</i>')
                            .appendTo($alertcardBody);

                    for (x = 0; x < alert.tags.length; x++) {
                        $('<span class="badge bg-warning"> ')
                                .html(alert.tags[x])
                                .appendTo($alertcardHeader);
                    }

                    $alertdiv.appendTo('#weatherholder');
                }
                
                $('link[href="/lcars/css/lcars-tng-legacy-2.min.css"]').attr('href', '/lcars/css/lcars-red-alert.min.css');
            }
           

        }
    });
}
