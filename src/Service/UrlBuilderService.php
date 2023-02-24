<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Service;

/**
 * Description of UrlBuilderService
 *
 * @author Carsten
 */
class UrlBuilderService {
    //put your code here
    
    public function make($domain, array $querystring = []) {
        
        return 'http://'.$domain.'/'.implode('/', $querystring);
        
    }
}
