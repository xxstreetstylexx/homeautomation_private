<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Service\WeatherService;

class GetWeatherController extends AbstractController
{
    private $Weather;
    
    public function __construct(WeatherService $Weather)
    {
        $this->Weather = $Weather;
    }
    /**
     * @Route("/api/get/weather", name="get_weather")
     */
    public function index(): Response
    {
        $raw_data = $this->Weather->getWeather();
        
        if (!isset($raw_data['current']))  
            return $this->json([]);
        
        $raw_data['current']['dt'] = \DateTime::createFromFormat( 'U', $raw_data['current']['dt'])->setTimeZone(new \DateTimeZone($raw_data['timezone']))->format('d.m.y H:i');            
        $raw_data['current']['sunrise'] = \DateTime::createFromFormat( 'U', $raw_data['current']['sunrise'])->setTimeZone(new \DateTimeZone($raw_data['timezone']))->format('H:i');            
        $raw_data['current']['sunset'] = \DateTime::createFromFormat( 'U', $raw_data['current']['sunset'])->setTimeZone(new \DateTimeZone($raw_data['timezone']))->format('H:i');            
        
        foreach ($raw_data['hourly'] as $k => $d) {
            $raw_data['hourly'][$k]['dt'] = \DateTime::createFromFormat( 'U', $raw_data['hourly'][$k]['dt'])->setTimeZone(new \DateTimeZone($raw_data['timezone']))->format('H:i'); 
        }
        foreach ($raw_data['daily'] as $k => $d) {
            $raw_data['daily'][$k]['dt'] = \DateTime::createFromFormat( 'U', $raw_data['daily'][$k]['dt'])->setTimeZone(new \DateTimeZone($raw_data['timezone']))->format('d.m.y');            
            $raw_data['daily'][$k]['sunrise'] = \DateTime::createFromFormat( 'U', $raw_data['daily'][$k]['sunrise'])->setTimeZone(new \DateTimeZone($raw_data['timezone']))->format('H:i');     
            $raw_data['daily'][$k]['sunset'] = \DateTime::createFromFormat( 'U', $raw_data['daily'][$k]['sunset'])->setTimeZone(new \DateTimeZone($raw_data['timezone']))->format('H:i');            
        }
        
        if (!isset($raw_data['alerts'])){
            
            $raw_data['alerts'] = false;
            
        } else {
            
            foreach ($raw_data['alerts'] as $k => $d) {
                $raw_data['alerts'][$k]['start'] = \DateTime::createFromFormat( 'U', $raw_data['alerts'][$k]['start'])->setTimeZone(new \DateTimeZone($raw_data['timezone']))->format('d.m.y H:i'); 
                $raw_data['alerts'][$k]['end'] = \DateTime::createFromFormat( 'U', $raw_data['alerts'][$k]['end'])->setTimeZone(new \DateTimeZone($raw_data['timezone']))->format('d.m.y H:i'); 
            }
        }
        
        
        return $this->json($raw_data);
    }
}
