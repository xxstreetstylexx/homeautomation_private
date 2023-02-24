<?php

/*
 *  Friendu_Frontend // ApiService.php
 *  
 *  (c) 2018 Carsten Zeidler
 */

namespace App\Service;

/**
 * Description of ApiService
 *
 * @author Carsten
 */
class ApiService {

    public function request(string $method, string $url, array $data = [], string $returnType = 'array') 
    {
        
        $curl = curl_init();

        if ($curl === false) {
            throw new Exception('failed to initialize');
        }

        switch ($method) {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);

                if (count($data) > 0)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
                
                if (count($data) > 0) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
                }
                break;
            case "GET":
                           
                break;
            case "DELETE":
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                if (count($data) > 0)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
                break;
            default:
                if (count($data) > 0)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }

        // Optional Authentication:
        #curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        #curl_setopt($curl, CURLOPT_USERPWD, "username:password");

        curl_setopt($curl, CURLOPT_URL, $url);

        if ($returnType == 'none') {
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 0);
        } else {
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        }

        /*
         * Ignore SSL Error on Local Connections
         */

        $result = curl_exec($curl);

        if ($result === false) {
            throw new \Exception(curl_error($curl), curl_errno($curl));
        }

        curl_close($curl);

        if ($returnType != 'none') {
            if ($returnType == 'array') {
                return json_decode($result, true);
            } elseif ($returnType == 'plain') {
                return $result;
            }
        }
    }

}
