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
var $NormalCSS = '/lcars/css/lcars-nemesis-blue.min.css';
var $Config = [];
var $Name = 'LCARS';
var $Location = 'Unknown';
var $Init = false;
var $Messages = ['Komm mal her.', 'Essen.', 'Telefon.', 'Hilfe!'];
var $UniqueId = {};
var $WeatherWarning = true;
var $Sensors = {};
var $PowerInterval = null;

//const eventSource = new EventSource("http\u003A\/\/mercure.sm.local\u003A9000\/.well\u002Dknown\/mercure\u003Ftopic\u003Dhttp\u00253A\u00252F\u00252Fuser.sm.local\u00252Flights");

const eventSource = new EventSource("http\u003A\/\/mercure.sm.local\u003A9000\/.well\u002Dknown\/mercure\u003Ftopic\u003Dhttp\u00253A\u00252F\u00252Fuser.sm.local\u00252Flights\u0026topic\u003Dhttp\u00253A\u00252F\u00252Fuser.sm.local\u00252Fgroups");
        
eventSource.onmessage = event => {
    // Will be called every time an update is published by the server
    var data = JSON.parse(event.data);
    
    if(data.target == 'lights') {
        setLightState(data.light, data.bridge, data.status);
    } else if (data.target == 'groups') {
        setGroupState(data.groupname, data.status.all, data.status.any);        
    } else {
        console.log('unknown target', data);
    }    
}

function setGroupState(group, all, any) {
    if ($('button[data-groupname="' + group + '"]').length == 1) {
        $btn = $('button[data-groupname="' + group + '"]');        
        if (all == 'on') {
            $btn
                    .removeClass('btn-danger')
                    .removeClass('btn-warning')
                    .removeClass('disabled')
                    .addClass('btn-success')
                    .html('<i class="fa fa-toggle-on"></i>');
        } else {
            if (any == 'on') {
                $btn
                        .removeClass('btn-success')
                        .removeClass('btn-danger')
                        .removeClass('disabled')
                        .addClass('btn-warning')
                        .html('<i class="fa fa-toggle-off"></i>');
            } else {
                $btn
                        .removeClass('btn-success')
                        .removeClass('btn-warning')
                        .removeClass('disabled')
                        .addClass('btn-danger')
                        .html('<i class="fa fa-toggle-off"></i>');
            }
        }
    }    
}

$(document).ready(function () {
    __init();

    $(document).on('click', 'div[data-light] div.card-body', function () {
        switch_state($(this).parent().data('light'), $(this).parent().data('bridge'));
    });
    
    $(document).on('click', 'button[data-lightswitch]', function () {
        switch_state($(this).data('light'), $(this).data('bridge'));
    });

    $(document).on('click', 'div[data-light] div.card-footer', function () {
        create_alert($(this).parent().data('light'), $(this).parent().data('bridge'));
    });

    $(document).on('click', 'button[data-switchgroup]', function () {
        switch_grp_state($(this).data('group'), $(this).data('bridge'));
    });

    $(document).on('click', 'button[data-changescene]', function () {
        switchScene($(this).data('group'), $(this).data('bridge'), $(this).data('scene'));
    });

    $(document).on('click', 'button[data-automaticswitch]', function () {
        switchAction($(this).data('automaticswitch'));
    });
   
    $(document).on('click', 'button[data-roomsetting="true"]', function () {
        loadRoom($(this).data('settinggroup'), $(this).data('bridge'));
    });

    $(document).on('click', '#forcereload', function () {
        reload();
    });

    $(document).on('click', '#disable-alert', function () {
        $('link[href="' + $AlertCSS + '"]').attr('href', $NormalCSS);
    });

});

function __init() {
    var _time = new Date().getTime();
    $.ajax({
        url: '/api/init?_=' + _time
    }).done(function (res) {
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
            _init_power();
            _init_groups();
            _init_weather();
            _init_intercom();
            //_init_websocket();
            //setInterval(_init_lights, 28000);
            //setInterval(_init_groups, 1000);
            setInterval(_init_weather, 30000);
            $Init = true;
        }

        if (inCfg('hide_clock') == false) {
            updateTime();
            setInterval(updateTime, 500);
        }
    });
}

function switchScene(groupId, bridgeId, sceneId) {
    var _time = new Date().getTime();
    $.ajax({
        url: '/api/scene/group/' + sceneId + '/' + bridgeId + '/' + groupId + '?_=' + _time
    }).done(function (res) {

    });
}

function switchAction(actionId) {
    var _time = new Date().getTime();
    $.ajax({
        url: '/api/switch/action/' + actionId + '?_=' + _time
    }).done(function (res) {
        /*
        if (res.state) {
            $('button[data-automaticswitch="' + actionId + '"]')
                    .removeClass('btn-outline-danger')
                    .addClass('btn-outline-success')
                    .html('<i class="fa fa-toggle-on"></i>');
        } else {
            $('button[data-automaticswitch="' + actionId + '"]')
                    .removeClass('btn-outline-success')
                    .addClass('btn-outline-danger')
                    .html('<i class="fa fa-toggle-off"></i>');
        }
        */
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
    var _time = new Date().getTime();
    $.ajax({
        url: '/api/switch/light/' + bridgeId + '/' + lightId + '?_=' + _time
    }).done(function (res) {
        //_init_lights();
        //_init_groups();
        /*
        var Obj = $('button[data-light="'+res.light+'"][data-bridge="'+res.bridge+'"]');
        if (Obj.length == 1) {
            var state = res.api.state;
            setLightState(res.light, res.bridge, state);
            
        }
        */
    });
}

function switch_grp_state(groupId, bridgeId) {
    var _time = new Date().getTime();
    $.ajax({
        url: '/api/switch/group/' + bridgeId + '/' + groupId + '?_=' + _time
    }).done(function (res) {
        //_init_lights();
        //_init_groups();
    });
}

function create_alert(lightId, bridgeId) {
    var _time = new Date().getTime();
    $.ajax({
        url: '/api/alert/light/' + bridgeId + '/' + lightId + '?_=' + _time
    }).done(function (res) {
        _init_lights();
    });
}

function _init_lights() {
    return false;
    var _time = new Date().getTime();
    $.ajax({
        url: '/api/get/lights?_=' + _time
    }).done(function (res) {
        for (var i = 0; i < res.length; i++) {
            var light = res[i];

            if ($('div#lightholder div[data-uniqueid="' + light.uniqueid + '"]').length == 1) {
                if (typeof $('div[data-uniqueid="' + light.uniqueid + '"]').attr('data-websocket') == 'undefined') {
                    $('div[data-uniqueid="' + light.uniqueid + '"] span[data-state]').attr('data-state', light.state);
                    $('div[data-uniqueid="' + light.uniqueid + '"] div.card-body').css('background-color', light.color);
                    $status = '<i class="fa fa-exclamation-triangle"></i> Offline';
                    if (light.reachable == true) {
                        $status = light.type;
                    }
                    $('div[data-uniqueid="' + light.uniqueid + '"] div.card-footer').html($status);
                }
            } else {
                $div = $('<div class="col col-md-2">');
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
                    $status = light.type;
                }
                $footer = $('<div class="card-footer text-muted">')
                        .html($status)
                        .appendTo($cardWrapper);

                $div.appendTo('#lightholder');

                $UniqueId[light.id + '--' + light.bridge] = light.uniqueid;
            }
        }
    });
}

function _init_websocket() {
    const host = '192.168.2.214';
    const port = 443;
    let ws = new WebSocket('ws://' + host + ':' + port);
    ws.onmessage = function (msg) {
        
        
        data = JSON.parse(msg.data);
        if (data.e == 'changed' && data.r == 'lights' && typeof data.state != 'undefined') {
            if (typeof $('div[data-uniqueid="' + data.uniqueid + '"]').attr('data-websocket') == 'undefined') {
                $('div[data-uniqueid="' + data.uniqueid + '"]').attr('data-websocket', true);
                $('div[data-uniqueid="' + data.uniqueid + '"] div.card-header').prepend(' <i class="fa fa-satellite-dish color-green"></i> ');
            }
            $('div[data-uniqueid="' + data.uniqueid + '"] span[data-state]').attr('data-state', data.state.on);
            if ((typeof data.state.xy != 'undefined') && (typeof data.state.bri != 'undefined')) {
                $c = xyBriToRgb(data.state.xy[0], data.state.xy[1], data.state.bri);
                $('div[data-uniqueid="' + data.uniqueid + '"] div.card-body').css('background-color', $c);
            }
        }

        if (data.e == 'changed' && data.r == 'groups' && typeof data.state != 'undefined') {

        }
    }
}

function _init_groups() {
    var _time = new Date().getTime();
    $.ajax({
        url: '/api/get/groups?_=' + _time
    }).done(function (res) {
        for (var i = 0; i < res.length; i++) {
            var group = res[i];
            if (group.type != 'Zone' && group.type != 'Entertainment') {
                if ($('.id-cardholder[data-groupname="' + group.id + '--' + group.bridge + '"]').length == 1) {
                /*                    
                if (group.reachable == false) {
                        $('button[data-groupname="' + group.id + '--' + group.bridge + '"]')
                                .removeClass('btn-success')
                                .removeClass('btn-danger')
                                .removeClass('btn-warning')
                                .addClass('disabled')
                                .addClass('btn-danger')
                                .html('<i class="fa fa-eye-slash"></i>');

                    } else {
                        if (group.state == true) {
                            $('button[data-groupname="' + group.id + '--' + group.bridge + '"]')
                                    .removeClass('btn-danger')
                                    .removeClass('btn-warning')
                                    .removeClass('disabled')
                                    .addClass('btn-success')
                                    .html('<i class="fa fa-toggle-on"></i>');
                        } else {
                            if (group.state_any == true) {
                                $('button[data-groupname="' + group.id + '--' + group.bridge + '"]')
                                        .removeClass('btn-success')
                                        .removeClass('btn-danger')
                                        .removeClass('disabled')
                                        .addClass('btn-warning')
                                        .html('<i class="fa fa-toggle-off"></i>');
                            } else {
                                $('button[data-groupname="' + group.id + '--' + group.bridge + '"]')
                                        .removeClass('btn-success')
                                        .removeClass('btn-warning')
                                        .removeClass('disabled')
                                        .addClass('btn-danger')
                                        .html('<i class="fa fa-toggle-off"></i>');
                            }
                        }
                    }

                    if (group.automatic != false) {
                        var Automatic = false;
                        for (y = 0; y < group.automatic.length; y++) {
                            if (group.automatic[y].active == true)
                                Automatic = true;
                        }
                        if (Automatic) {
                            $('div[data-groupname="' + group.id + '--' + group.bridge + '"] span.automatic').html(' (A) ');
                        } else {
                            $('div[data-groupname="' + group.id + '--' + group.bridge + '"] span.automatic').html('');
                        }
                    } else {
                        $('div[data-groupname="' + group.id + '--' + group.bridge + '"] span.automatic').html('');
                    }
                    */
                } else {
                    if (inCfg('hide_grp_' + group.id + '@' + group.bridge) == false) {
                        $.ajax({
                            url: '/js/group?_=' + _time,
                            async: false
                        }).done(function(res) {
                            var Progress = $(res);
                            Progress.find('.id-name').text(group.name);
                            Progress.find('.id-cardholder').attr({
                               'data-group': group.id,
                               'data-bridge': group.bridge,
                               'data-groupname': group.id + '--' + group.bridge 
                            });
                            
                            var _disabled = true;
                            if (group.reachable == false) {
                                Progress.find('.id-status').addClass('disabled');
                            } else {
                                _disabled = false;
                            }

                            if (!_disabled) {
                                if (group.state == true) {
                                    Progress.find('.id-status')
                                            .addClass('btn-success')
                                            .html('<i class="fa fa-toggle-on"></i>');

                                } else {
                                    Progress.find('.id-status')
                                            .addClass('btn-danger')
                                            .html('<i class="fa fa-toggle-off"></i>');

                                }
                            }

                            Progress.find('.id-status').attr({
                                'data-group': group.id,
                                'data-switchgroup': group.id,
                                'data-bridge': group.bridge,
                                'data-groupname': group.id + '--' + group.bridge 
                            });
                            
                            Progress.find('.card-body').hide();
                            
                            Progress.find('.id-temp').attr({
                                'data-groupname': group.id + '--' + group.bridge 
                            });

                            Progress.find('.id-settings').attr({
                                'data-settinggroup': group.id,
                                'data-bridge': group.bridge
                            });
                            Progress.attr('data-loaded', 'true');
                            Progress.appendTo('#groupholder');
                            
                            _init_sensor(group.name, group.id + '--' + group.bridge);
                        });                        
                    }
                }
            }
        }
    });
}

function _init_power() {
    var _time = new Date().getTime();
    $.ajax({
        url: '/api/get/power?_=' + _time
    }).done(function (res) {
        if (res.sensors != false) {
            if ($PowerInterval === null ) {
                $PowerInterval = setInterval(function () {
                    _init_power();
                }, 10000);
            }
            for (var i=0; i < res.sensors.length; i++) {
                var Sensor = res.sensors[i];
                
                if ($('.id-powerholder[data-powerid="' + Sensor.id + '"]').length == 1) {
                    Progress = $('.id-powerholder[data-powerid="' + Sensor.id + '"]');
                    
                    Progress.find('.id-name').text(Sensor.name);
                    Progress.find('.current').text(Sensor.state.current);
                    Progress.find('.power').text(Sensor.state.power);
                    Progress.find('.voltage').text(Sensor.state.voltage);
                    
                } else {
                     $.ajax({
                        url: '/js/powerlevel?_=' + _time,
                        async: false
                    }).done(function(res) {
                        var Progress = $(res);
                        Progress.find('.id-powerholder').attr('data-powerid', Sensor.id);                        
                        Progress.find('.id-name').text(Sensor.name);
                        Progress.find('.current').text(Sensor.state.current);
                        Progress.find('.power').text(Sensor.state.power);
                        Progress.find('.voltage').text(Sensor.state.voltage);
                        Progress.appendTo('#power');
                    });
                }
                
            }            
        }
    });
}

function _init_sensor(name, target) {
    var _time = new Date().getTime();
    $.ajax({
        url: '/api/get/sensors/' + name + '?_=' + _time
    }).done(function (res) {
        if (res.sensors != false) {
            if (typeof $Sensors[name] == 'undefined') {
                $Sensors[name] = setInterval(function () {
                    _init_sensor(name, target);
                }, 10000);
            }

            var $faIcon;

            /*if (res.sensors.battery == 100) {
                $faIcon = 'fa-battery-full';
            } else if (res.sensors.battery > 75) {
                $faIcon = 'fa-battery-three-quarters';
            } else if (res.sensors.battery > 50) {
                $faIcon = 'fa-battery-half';
            } else if (res.sensors.battery > 25) {
                $faIcon = 'fa-battery-quarter';
            } else if (res.sensors.battery > 0) {
                $faIcon = 'fa-battery-empty';
            } else {
                $faIcon = 'fa-battery-empty red';
            }
            */
            var _Temp = 'undefined';
            
            for (var i=0; i < res.sensors.length; i++) {
                var obj = res.sensors[i];
                if (obj.type == 'ZHATemperature') {
                    _Temp = Math.floor( obj.state.temperature / 100 );
                }               
            }
            var string = '';
            if (_Temp != 'undefined') {
                string = _Temp;
            } 
            if (string != '') {
                $('.id-cardholder[data-groupname="' + target + '"] .sensors').text(string+' °C');
                //$('.id-cardholder[data-groupname="' + target + '"]').find('.card-body').text(string+' °C');                
            } else {
                
            }
        }
    });
}

function getTempProgress(value) {
    return  Math.floor(100 / 35 * value);    
}

/*
 * Wetter Vars
 */
var $W_Labels = [];
var $W_DataTemp = [];
var $W_DataRain = [];
var $W_DataFull = [];
function resetVars() {
    $W_Labels = [];
    $W_DataTemp = [];
    $W_DataRain = [];
    $W_DataFull = [];
}

function _init_weather() {
    var _time = new Date().getTime();
    $.ajax({
        url: '/api/get/weather?_=' + _time
    }).done(function (res) {

        if ($('div[data-weather="1"]').length == 1) {
// Update
            $('div[data-weather="1"] div.card-body p.temp')
                    .html(res.current.temp + ' °C <span class="weatherdescription text-muted text-sm"></span>');
            $('div[data-weather="1"] div.card-body p.temp span.weatherdescription')
                    .html(res.current.weather[0].description);
            $('div[data-weather="1"] div.card-body img.wicon')
                    .attr('src', '/image/weather/icons/' + res.current.weather[0].icon + '.png');
            $('div[data-weather="1"] div.card-footer.text-muted')
                    .html(res.current.dt);
            // ALERTS
            if ($WeatherWarning) {
                if (res.alerts == false) {
// REMOVE BOX
                    $('#unweatherbutton').hide();
                    if ($('div[data-alert]').length > 0) {
                        $('div[data-alert]').remove();
                        if (inCfg('disable_alert') == false) {
                            // Disable Alerts CSS
                            // $('link[href="' + $AlertCSS + '"]').attr('href', $NormalCSS);
                        }
                    }
                } else {
                    if ($('div[data-alert]').length > 0) {
                        $('div[data-alert]').remove();
                    }

                    for (i = 0; i < res.alerts.length; i++) {

                        alert = res.alerts[i];
                        $alertdiv = $('<div class="col col-md-6" data-alert="1">');
                        $alertcardWrapper = $('<div class="card bg-danger shadow position-relative">')
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
                        $tags = [];
                        for (x = 0; x < alert.tags.length; x++) {
                            if (typeof $tags[alert.tags[x]] == 'undefined') {
                                $('<span class="badge bg-info"> ')
                                        .html(alert.tags[x])
                                        .appendTo($alertcardHeader);
                                $tags[alert.tags[x]] = true;
                            }
                        }

                        $alertdiv.appendTo('#unweatherholder');
                    }

                    if (inCfg('disable_alert') == false) {
                        $('link[href="' + $NormalCSS + '"]').attr('href', $AlertCSS);
                    }
                    $('#unweatherbutton').show();
                }
            }
        } else {
// New
            $div = $('<div class="col col-md-3">');
            $cardWrapper = $('<div class="card shadow position-relative" data-weather="1">')
                    .appendTo($div);
            $cardHeader = $('<div class="card-header">')
                    .html('Aktuell')
                    .appendTo($cardWrapper);
            $cardBody = $('<div class="card-body" style="height: 300px">')
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
            $('<div class="card-footer text-muted">')
                    .html(res.current.dt)
                    .appendTo($cardWrapper);
            $div2 = $('<div class="col col-md-9">');
            $cardWrapper2 = $('<div class="card shadow position-relative" >')
                    .appendTo($div2);
            $cardHeader2 = $('<div class="card-header">')
                    .html('Vorschau')
                    .appendTo($cardWrapper2);
            $cardBody2 = $('<div class="card-body" style="height: 300px">')
                    .appendTo($cardWrapper2);
            $('<div class="card-footer text-muted">')
                    .html('&nbsp;')
                    .appendTo($cardWrapper2);
            $Canvas = $('<canvas id="weather">')
                    .appendTo($cardBody2);
            $div.appendTo('#weatherholder');
            $div2.appendTo('#weatherholder');
            
            if ($WeatherWarning) {
                if (res.alerts == false) {
// REMOVE BOX
                    $('#unweatherbutton').hide();
                    if (inCfg('disable_alert') == false) {
                        $('link[href="' + $AlertCSS + '"]').attr('href', $NormalCSS);
                    }
                } else {

                    for (i = 0; i < res.alerts.length; i++) {
                        alert = res.alerts[i];
                        $alertdiv = $('<div class="col col-md-6" data-alert="1">');
                        $alertcardWrapper = $('<div class="card bg-danger shadow position-relative">')
                                .appendTo($alertdiv);
                        $alertcardHeader = $('<div class="card-header">')
                                .html(alert.sender_name)
                                .appendTo($alertcardWrapper);
                        $alertcardBody = $('<div class="card-body" style="height:200px">')
                                .appendTo($alertcardWrapper);
                        $('<p class="alerttime">')
                                .html(alert.start + ' - ' + alert.end)
                                .appendTo($alertcardBody);
                        $('<p class="alerttopic">')
                                .html('<strong>' + alert.sender_name + '</strong><br /><i>' + alert.description + '</i>')
                                .appendTo($alertcardBody);
                        $alertFooter = $('<div class="card-footer text-muted">')
                                .html('&nbsp;')

                        $tags = [];
                        for (x = 0; x < alert.tags.length; x++) {
                            if (typeof $tags[alert.tags[x]] == 'undefined') {
                                $('<span class="badge  bg-info">')
                                        .html(alert.tags[x])
                                        .appendTo($alertFooter);
                                $tags[alert.tags[x]] = true;
                            }
                        }

                        $alertFooter.appendTo($alertcardWrapper);
                        $alertdiv.appendTo('#unweatherholder');
                        $('#unweatherbutton').show();
                    }

                    if (inCfg('disable_alert') == false) {
                        $('link[href="' + $NormalCSS + '"]').attr('href', $AlertCSS);
                    }
                }
            }
        }
        resetVars();
        for (i = 0; i < 24; i++) {
            $W_DataTemp.push(parseInt(res.hourly[i].temp, 10));
            $W_Labels.push(res.hourly[i].dt);
            $W_DataFull.push(res.hourly[i]);
        }
        _init_weather_chart();
    });
}

function _init_weather_chart() {
    
    if ($('table[data-weathertable="1"]').length == 1) {
        $Holder = $('table[data-weathertable="1"]').parent();
        $Holder.html('');
        $table = $('<table data-weathertable="1" class="table table-fluid">');
        $table.appendTo($Holder);
    } else {
        // Locate Canvas
        $Holder = $('canvas#weather').parent();
        $Holder.html('');
        $table = $('<table data-weathertable="1" class="table table-fluid">');
        $table.appendTo($Holder);
    }
    // prepare data 
    var $W_TempRow = [];

    for (i = 0; i < $W_DataFull.length; i++) {

        var $T = [];
        var $D = $W_DataFull[i];
        $T[0] = $D.dt;
        $T[1] = parseInt($D.temp, 10);
        $T[2] = $D.weather[0].icon;

        $W_TempRow[i] = $T;

    }

    $Row1 = $('<tr>');
    $Row2 = $('<tr>');
    $Row3 = $('<tr>');
    var max = 12;
    var Offset = 1;
    for (i = Offset; i < $W_TempRow.length; i++) {
        var _max = Offset + max;
        if (i < _max) {
            //if (i % 2) {
            var $D = $W_TempRow[i];
            $Td1 = $('<th>').html($D[0]).appendTo($Row1);
            $Td2 = $('<td class="text-center">').html($D[1] + ' °C').appendTo($Row3);
            $Td3 = $('<td>').html('').appendTo($Row2);
            $img = $('<img class="wicon">')
                    .attr('src', '/image/weather/icons/' + $D[2] + '.png')
                    .appendTo($Td3);
            //}
        }
    }
    $thead = $('<thead>').appendTo($table);
    $Row1.appendTo($thead);
    $Row2.appendTo($table);
    $Row3.appendTo($table);
    
    var d = new Date();
    var n = d.toLocaleTimeString();
    $Holder.parent().children('.card-header').html('Vorschau <span class="text-right">'+n+'</span>');
}

function reload() {
    location.reload(true);
}

//var groupsettings = document.getElementById('groupsettings');
//groupsettings.addEventListener('show.bs.modal', function (event) {
    
function loadRoom(groupid, bridgeid) {
    // Button that triggered the modal
    //var button = event.relatedTarget
    // Extract info from data-bs-* attributes
    //var groupid = button.getAttribute('data-settinggroup');
    //var bridgeid = button.getAttribute('data-bridge');
    var _time = new Date().getTime();
    // If necessary, you could initiate an AJAX request here
    // and then do the updating in a callback.
    //
    // Update the modal's content.
    $('#room').html('<div style="height:20%; width:20%; margin:30px auto;color:white;font-weight:700;text-align:center;">Loading</div>');
    $.ajax({
        url: '/js/room?_=' + _time,
        async: false
    }).done(function(res) {
        var Progress = $(res);
        $.ajax({
            url: '/api/get/group/' + bridgeid + '/' + groupid + '?_=' + _time
        }).done(function (res) {            
            // Lights
            for (i = 0; i < res.lights.length; i++) {
                
                $btn = $('<button>')
                        .addClass('btn');
                if (res.lights[i].reachable == false) {
                    $btn
                        .attr('disabled', 'disabled')
                        .addClass('btn-disabled')
                        .html('<i class="fa fa-toggle-off"></i>');
                } else {
                    if (res.lights[i].stateon == true) {
                        $btn
                            .addClass('btn-success')
                            .html('<i class="fa fa-toggle-on"></i>');
                    } else {
                        $btn
                            .addClass('btn-danger')
                            .html('<i class="fa fa-toggle-off"></i>');
                    }
                    
                    $btn
                        .attr({
                            'data-light': res.lights[i].id,
                            'data-bridge': bridgeid,
                            'data-lightswitch': 1
                        });
                }
                
                $tr = $('<tr>');
                
                $td = $('<td>').html('<strong>' + res.lights[i].name +'</strong>').appendTo($tr);
                $td = $('<td>').append($btn).appendTo($tr);
                Progress.find('#device-body').append($tr);
            }
            
            // Scenes
            
            for (i = 0; i < res.scenes.length; i++) {
                $btn = $('<button class="btn btn-lg btn-outline-dark" data-changescene="1" type="button" data-bridge="' + bridgeid + '" data-scene="' + res.scenes[i].id + '" data-group="' + groupid + '">')
                        .html(res.scenes[i].name);
                
                Progress.find('#group-scenes').append($btn);
            }
            
            var _time = new Date().getTime();
            $.ajax({
                url: '/api/get/sensors/' + res.name + '?_=' + _time
            }).done(function (res) {
                if (res.sensors != false) {
                    for (var i=0; i < res.sensors.length; i++) {
                        var obj = res.sensors[i];
                        if (obj.type == 'ZHATemperature') {
                            $li = $('<tr><td><strong>Temperatur</strong></td><td>'+ Math.floor( obj.state.temperature / 100 ) + ' °C</td></tr>');
                            $('#gsetting-sensors').append($li);
                        }
                        else if (obj.type == 'ZHAHumidity') {
                            $li = $('<tr><td><strong>Luftfeuchtigkeit</strong></td><td>' + obj.state.humidity / 100 + ' %</td></tr>');
                            Progress.find('#gsetting-sensors').append($li);
                        }
                        else if (obj.type == 'ZHAPressure') {
                            $li = $('<tr><td><strong>Luftdruck</strong></td><td>' + obj.state.pressure + ' hPa</td></tr>');
                            Progress.find('#gsetting-sensors').append($li);
                        }
                        else if (obj.type == 'ZHAPresence') {
                            var licht = (obj.state.dark)?'Ja':'Nein';
                            var bewe = (obj.state.presence)?'Ja':'Nein';
                            $li = $('<tr><td><strong>Dunkel</strong></td><td>' + licht + '</td></tr>');
                            Progress.find('#gsetting-sensors').append($li);
                            $li = $('<tr><td><strong>Bewegung</strong></td><td>' + bewe + '</td></tr>');
                            Progress.find('#gsetting-sensors').append($li);
                        }
                        else if (obj.type == 'ZHAOpenClose') {
                            var state = (obj.state.open)?'Offen':'Geschlossen';
                            $li = $('<tr><td><strong>' +obj.name +'</strong></td><td> ' + state + '</td></tr>');                 
                            Progress.find('#gsetting-sensors').append($li);
                        }
                        else {

                        }
                    }
                }
                });
                $('#room').html(Progress);
                return true;
            });    
        });
        
        
}

function setLightState(lightId, bridgeId, state) {
    var Obj = $('button[data-light="'+lightId+'"][data-bridge="'+bridgeId+'"]');
    if (Obj.length == 1) {
        Obj
            .removeClass('btn-danger')
            .removeClass('btn-success')
            .html('');
        if (state == 'on') {
            Obj
                .addClass('btn-success')
                .html('<i class="fa fa-toggle-on"></i>');
        } else {
            Obj
                .addClass('btn-danger')
                .html('<i class="fa fa-toggle-off"></i>');
        }
    }
}

// OLD
/**
 * $.ajax({
        url: '/api/get/group/' + bridgeid + '/' + groupid + '?_=' + _time
    }).done(function (res) {
        $('#gsetting-sensors').html('');
        $th = $('<thead>');
        $th2 = $('<tr><th>Sensor</th><th>Wert</th></tr>').appendTo($th);
        $th.appendTo('#gsetting-sensors');
        var _time = new Date().getTime();
        $.ajax({
            url: '/api/get/sensors/' + res.name + '?_=' + _time
        }).done(function (res) {
            if (res.sensors != false) {
                for (var i=0; i < res.sensors.length; i++) {
                    var obj = res.sensors[i];
                    if (obj.type == 'ZHATemperature') {
                        $li = $('<tr><td><strong>Temperatur</strong></td><td>'+ Math.floor( obj.state.temperature / 100 ) + ' °C</td></tr>');
                        $('#gsetting-sensors').append($li);
                    }
                    else if (obj.type == 'ZHAHumidity') {
                        $li = $('<tr><td><strong>Luftfeuchtigkeit</strong></td><td>' + obj.state.humidity / 100 + ' %</td></tr>');
                        $('#gsetting-sensors').append($li);
                    }
                    else if (obj.type == 'ZHAPressure') {
                        $li = $('<tr><td><strong>Luftdruck</strong></td><td>' + obj.state.pressure + ' hPa</td></tr>');
                        $('#gsetting-sensors').append($li);
                    }
                    else if (obj.type == 'ZHAPresence') {
                        var licht = (obj.state.dark)?'Ja':'Nein';
                        var bewe = (obj.state.presence)?'Ja':'Nein';
                        $li = $('<tr><td><strong>Dunkel</strong></td><td>' + licht + '</td></tr>');
                        $('#gsetting-sensors').append($li);
                        $li = $('<tr><td><strong>Bewegung</strong></td><td>' + bewe + '</td></tr>');
                        $('#gsetting-sensors').append($li);
                    }
                    else if (obj.type == 'ZHAOpenClose') {
                        var state = (obj.state.open)?'Offen':'Geschlossen';
                        $li = $('<tr><td><strong>' +obj.name +'</strong></td><td> ' + state + '</td></tr>');                 
                        $('#gsetting-sensors').append($li);
                    }
                    else {
                        
                    }
                }
            }
        });

        $('#gsetting-bridgename').html(res.bridgename);
        $('#gsetting-gname').html(res.name);
        $('#gsetting-devicelist').html('');
        for (i = 0; i < res.lights.length; i++) {
            var $class = (res.lights[i].reachable)?'bg-success':'bg-warning'; 
            $dev = $('<tr><td><strong>' + res.lights[i].name +'</strong></td></tr>').addClass($class);
            $('#gsetting-devicelist').append($dev);
        }
        
        $('#gsetting-scenes').html('');
        for (i = 0; i < res.scenes.length; i++) {
            $btn = $('<button class="btn btn-lg btn-outline-primary" data-changescene="1" type="button" data-bridge="' + bridgeid + '" data-scene="' + res.scenes[i].id + '" data-group="' + groupid + '">')
                    .html(res.scenes[i].name)
                    .appendTo('#gsetting-scenes');
        }
        $('#gsetting-automatics').html('');
        $table = $('<table class="table">');
        $thead = $('<thead>');
        $tr = $('<tr>').appendTo($thead);
        $('<th>').html('Sensor').appendTo($tr);
        $('<th>').html('Modus').appendTo($tr);
        $('<th>').html('Wert').appendTo($tr);
        $('<th>').html('Zeitraum').appendTo($tr);
        $('<th>').html('Status').appendTo($tr);

        $thead.appendTo($table);
        $tbody = $('<tbody>').appendTo($table);
        $table.appendTo('#gsetting-automatics');
        for (i = 0; i < res.automatic.length; i++) {
            var $class;
            if (res.automatic[i].active) {
                $class = 'btn-outline-success';
                $status = '<i class="fa fa-toggle-on"></i>';
            } else {
                $class = 'btn-outline-danger';
                $status = '<i class="fa fa-toggle-off"></i>';
            }
            $tr = $('<tr>');


            $btn = $('<button class="btn ' + $class + '" data-automaticswitch="' + res.automatic[i].id + '" type="button">')
                    .html($status)
                    ;

            if (res.automatic[i].sensor_type == 'ZHATemperature') {
                $value = res.automatic[i].value / 100 + ' °C';
            } else {
                $value = res.automatic[i].value;
            }
            $('<td>').html(res.automatic[i].sensor_name + ' ' + res.automatic[i].sensor_type).appendTo($tr);
            $('<td>').html(res.automatic[i].operation).appendTo($tr);
            $('<td>').html($value).appendTo($tr);
            $('<td>').html(res.automatic[i].start + ' - ' + res.automatic[i].end).appendTo($tr);
            $('<td>').append($btn).appendTo($tr);

            $tr.appendTo($tbody);

        }
    });
 */

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
    maxValue = Math.max(r, g, b);
    r /= maxValue;
    g /= maxValue;
    b /= maxValue;
    r = r * 255;
    if (r < 0) {
        r = 255
    }
    ;
    g = g * 255;
    if (g < 0) {
        g = 255
    }
    ;
    b = b * 255;
    if (b < 0) {
        b = 255
    }
    ;
    r = Math.round(r).toString(16);
    g = Math.round(g).toString(16);
    b = Math.round(b).toString(16);
    if (r.length < 2)
        r = "0" + r;
    if (g.length < 2)
        g = "0" + g;
    if (b.length < 2)
        b = "0" + r;
    rgb = "#" + r + g + b;
    return rgb;
}