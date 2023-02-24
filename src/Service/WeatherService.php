<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Description of WeatherService
 *
 * @author Carsten
 */
class WeatherService {
    
    private $lat;
    private $lon;
    private $token;
    private $units;
    private $lang;
    private $exclude;
    private $url;
    
    public function __construct(ContainerInterface $container)
    {
        
        $Weather = $container->getParameter('weather');
        
        if ($Weather['enabled'] === true) {
            $this->lat = $Weather['lat'];
            $this->lon = $Weather['lon'];
            $this->token = $Weather['token'];
            $this->units = $Weather['units'];
            $this->exclude = $Weather['exclude'];
            $this->lang = $Weather['lang'];
            $this->url = $Weather['url'];
        }
    }
    
    public function getWeather() {
        
        $dir = '/var/www/hue/_tmp/weather/unprogressed/';
        $file = '_current.json';
        
        return json_decode(file_get_contents($dir.$file), true);
        
    }
    
    public function updateWeather() {
        
        
        $curl = curl_init();
        $data = [
            'lat' => $this->lat,
            'lon' => $this->lon,
            'exclude' => $this->exclude,
            'appid' => $this->token,
            'units' => $this->units,
            'lang' => $this->lang                
        ];
        
        $url = sprintf("%s?%s", $this->url, http_build_query($data));
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        curl_close($curl);
        
        return $result;
        
    }
}
