/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/*
 * Vars
 */
var $SecondsEven = true;
var $AlertCSS = '/lcars/css/lcars-red-alert.min.css';
var $NormalCSS = '/lcars/css/lcars-tng-legacy-2.min.css';
var $Config = [];
var $Name = 'LCARS';
var $Location = 'Unknown';
var $Init = false;
var $Messages = ['Komm mal her.', 'Essen.', 'Telefon.', 'Hilfe!'];
var $UniqueId = {};

$(document).ready(function () {

    __init();

    $(document).on('click', 'div[data-light] div.card-body', function () {
        switch_state($(this).parent().data('light'), $(this).parent().data('bridge'));
    });

    $(document).on('click', 'div[data-light] div.card-footer', function () {
        create_alert($(this).parent().data('light'), $(this).parent().data('bridge'));
    });

    $(document).on('click', 'button[data-group]', function () {
        switch_grp_state($(this).data('group'), $(this).data('bridge'));
    });

    $(document).on('click', '#forcereload', function () {
        reload();
    });

    $(document).on('click', '#disable-alert', function () {

        $('link[href="' + $AlertCSS + '"]').attr('href', $NormalCSS);
    });

});

function __init() {
    $.ajax({
        url: '/api/init'
    }).done(function (res) {

        //console.log(res.setting);
        for (var i = 0; i < res.setting.length; i++) {
            $Config.push(res.setting[i]);
        }

        $Name = res.name;
        $Location = res.location;

        if (inCfg('hide_name') == false) {
            $('#name').html($Name);
        }

        if ($Init == false) {
            _init_lights();
            _init_weather();
            _init_intercom();
            _init_websocket();
            setInterval(_init_lights, 1000);
            setInterval(_init_weather, 300000);
            $Init = true;
        }

        if (inCfg('hide_clock') == false) {
            updateTime();
            setInterval(updateTime, 500);
        }
    });
}



function _init_intercom() {
    for (i = 0; i < $Messages.length; i++) {
        $MsgBtn = $('<a class="c58 two-rows" data-msg="' + $Messages[i] + '">')
                .html($Messages[i]);

        $('#intercommessages').append($MsgBtn);
    }
}

function inCfg(str) {

    var Ret = false;

    for (var i = 0; i < $Config.length; i++) {
        if ($Config[i] == str) {
            Ret = true;
        }
    }

    return Ret;
}

function updateTime() {
    a = new Date();
    b = (a.getHours() < 10 ? '0' : '') + a.getHours();
    c = (a.getMinutes() < 10 ? '0' : '') + a.getMinutes();
    if ($SecondsEven == true) {
        $SecondsEven = false;
        $('#time').html(b + ':' + c);
    } else {
        $('#time').html(b + ' ' + c);
        $SecondsEven = true;
    }

}

function switch_state(lightId, bridgeId) {
    $.ajax({
        url: '/api/switch/light/' + bridgeId + '/' + lightId
    }).done(function (res) {
        _init_lights();
    });
}

function switch_grp_state(groupId, bridgeId) {
    $.ajax({
        url: '/api/switch/group/' + bridgeId + '/' + groupId
    }).done(function (res) {
        _init_lights();
    });
}

function create_alert(lightId, bridgeId) {
    $.ajax({
        url: '/api/alert/light/' + bridgeId + '/' + lightId
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
            if ($('div#lightholder div[data-uniqueid="' + light.uniqueid + '"]').length == 1) {
                if (typeof $('div[data-uniqueid="' + light.uniqueid + '"]').attr('data-websocket') == 'undefined') {
                    $('div[data-uniqueid="' + light.uniqueid + '"] span[data-state]').attr('data-state', light.state);
                    $('div[data-uniqueid="' + light.uniqueid + '"] div.card-body').css('background-color', light.color);
                    $status = '<i class="fa fa-exclamation-triangle"></i> Offline';
                    if (light.reachable == true) {
                        $status = 'Online';
                    }

                    $('div[data-uniqueid="' + light.uniqueid + '"] div.card-footer').html($status);
                } 
            } else {
                $div = $('<div class="col">');
                $cardWrapper = $('<div class="card shadow position-relative" data-uniqueid="' + light.uniqueid + '" data-light="' + light.id + '" data-bridge="' + light.bridge + '" data-lightname="' + light.id + '--' + light.bridge + '">')
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

                $UniqueId[light.id + '--' + light.bridge] = light.uniqueid;
            }
        }

        _init_groups();
    });
}

function _init_websocket() {

    const host = '192.168.2.214';
    const port = 443;

    let ws = new WebSocket('ws://' + host + ':' + port);

    ws.onmessage = function (msg) {
        data = JSON.parse(msg.data);

        if (data.e == 'changed' && data.r == 'lights' && typeof data.state != 'undefined') {
            $('div[data-uniqueid="' + data.uniqueid + '"]').attr('data-websocket', true);
            $('div[data-uniqueid="' + data.uniqueid + '"]').attr('data-websocket', true);
            $('div[data-uniqueid="' + data.uniqueid + '"] span[data-state]').attr('data-state', data.state.on);
            $c = xyBriToRgb(data.state.xy[0], data.state.xy[1], data.state.bri);
            $('div[data-uniqueid="' + data.uniqueid + '"] div.card-body').css('background-color', $c);
            $status = '<i class="fa fa-exclamation-triangle"></i> Offline';
            if (data.state.reachable == true) {
                $status = 'Online';
            }
            $('div[data-uniqueid="' + data.uniqueid + '"] div.card-footer').html($status);
        }
        
        if (data.e == 'changed' && data.r == 'groups' && typeof data.state != 'undefined') {
            
        }
    }
}

function _init_groups() {
    $.ajax({
        url: '/api/get/groups'
    }).done(function (res) {

        for (var i = 0; i < res.length; i++) {
            var group = res[i];

            if (group.type != 'Zone' && group.type != 'Entertainment') {
                if ($('div[data-groupname="' + group.id + '--' + group.bridge + '"]').length == 1) {

                    if (group.state == true) {

                        $('button[data-groupname="' + group.id + '--' + group.bridge + '"]')
                                .removeClass('btn-danger')
                                .addClass('btn-success')
                                .html('An');

                    } else {

                        $('button[data-groupname="' + group.id + '--' + group.bridge + '"]')
                                .removeClass('btn-success')
                                .addClass('btn-danger')
                                .html('Aus');

                    }
                } else {

                    if (inCfg('hide_grp_' + group.id + '@' + group.bridge) == false) {
                        $div = $('<div class="col col-md-3 col-xs-6">');
                        $cardWrapper = $('<div class="card shadow position-relative" data-group="' + group.id + '" data-bridge="' + group.bridge + '" data-groupname="' + group.id + '--' + group.bridge + '">')
                                .appendTo($div);

                        $cardHeader = $('<div class="card-header">')
                                .html('<strong>' + group.name + '</strong>')
                                .appendTo($cardWrapper);

                        $grpBtn = $('<button class="btn btn-sm float-end" type="button" data-group="' + group.id + '" data-bridge="' + group.bridge + '" data-groupname="' + group.id + '--' + group.bridge + '">An/Aus</button>')
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
                            $lightCardWrapper = $('<div class="card shadow position-relative" data-light="' + group.lights[y] + '" data-uniqueid="' + $UniqueId[group.lights[y] + '--' + group.bridge] + '" data-bridge="' + group.bridge + '" data-lightname="' + group.lights[y] + '--' + group.bridge + '">')
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

                    if (inCfg('disable_alert') == false) {
                        $('link[href="' + $AlertCSS + '"]').attr('href', $NormalCSS);
                    }

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

                if (inCfg('disable_alert') == false) {
                    $('link[href="' + $NormalCSS + '"]').attr('href', $AlertCSS);
                }
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
                if (inCfg('disable_alert') == false) {
                    $('link[href="' + $AlertCSS + '"]').attr('href', $NormalCSS);
                }

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

                if (inCfg('disable_alert') == false) {
                    $('link[href="' + $NormalCSS + '"]').attr('href', $AlertCSS);
                }
            }


        }
    });
}

function reload() {
    location.reload(true);
}

function xyBriToRgb(x, y, bri)
{
    z = 1.0 - x - y;

    Y = bri / 255.0; // Brightness of lamp
    X = (Y / y) * x;
    Z = (Y / y) * z;
    r = X * 1.612 - Y * 0.203 - Z * 0.302;
    g = -X * 0.509 + Y * 1.412 + Z * 0.066;
    b = X * 0.026 - Y * 0.072 + Z * 0.962;
    r = r <= 0.0031308 ? 12.92 * r : (1.0 + 0.055) * Math.pow(r, (1.0 / 2.4)) - 0.055;
    g = g <= 0.0031308 ? 12.92 * g : (1.0 + 0.055) * Math.pow(g, (1.0 / 2.4)) - 0.055;
    b = b <= 0.0031308 ? 12.92 * b : (1.0 + 0.055) * Math.pow(b, (1.0 / 2.4)) - 0.055;
    maxValue = Math.max(r,g,b);
    r /= maxValue;
    g /= maxValue;
    b /= maxValue;
    r = r * 255;   if (r < 0) { r = 255 };
    g = g * 255;   if (g < 0) { g = 255 };
    b = b * 255;   if (b < 0) { b = 255 };

    r = Math.round(r).toString(16);
    g = Math.round(g).toString(16);
    b = Math.round(b).toString(16);

    if (r.length < 2)
        r="0"+r;        
    if (g.length < 2)
        g="0"+g;        
    if (b.length < 2)
        b="0"+r;        
    rgb = "#"+r+g+b;

    return rgb;             
}

