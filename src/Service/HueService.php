<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Service;

use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

use App\Service\ApiService;
use App\Service\UrlBuilderService;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Lights;
use App\Entity\LightBridges;
use App\Entity\LightGroups;
use App\Entity\LightLog;
use App\Entity\Scenes;

/**
 * Description of HueService
 *
 * @author Carsten
 */
class HueService {

    private $Keys = [];
    private $Api;
    private $UrlBuilder;
    private $entity;
    private $Hub;    
    private $mercure;
    public function __construct(ApiService $Api, UrlBuilderService $UrlBuilder, EntityManagerInterface $entityManager, HubInterface $hub) {
        $this->entity = $entityManager;
        $this->UrlBuilder = $UrlBuilder;
        $this->Api = $Api;
        $this->Hub = $hub;
        $this->mercure = false;

        /**
         * later dynamic
         */
        #$this->Keys['192.168.2.132'] = 't5ztrXJNeNZziUIcsmpG9NaTJHQ4HHu-FobVc0DO';
    }

    public function setKey($Ip, $key) {

        if (isset($this->Keys[$Ip])) {

            return true;
        }

        $this->Keys[$Ip] = $key;

        return true;
    }

    public function getAllLights($bridgeIp) {

        if (!isset($this->Keys[$bridgeIp])) {

            return ['no valid bridge'];
        }

        $url = [
            'api',
            $this->Keys[$bridgeIp],
            'lights'
        ];

        $domain = $bridgeIp;

        $fullUrl = $this->UrlBuilder->make($domain, $url);

        $data = $this->Api->request('GET', $fullUrl);

        return $data;
    }

    public function getAllGroups($bridgeIp) {

        if (!isset($this->Keys[$bridgeIp])) {

            return ['no valid bridge'];
        }

        $url = [
            'api',
            $this->Keys[$bridgeIp],
            'groups'
        ];

        $domain = $bridgeIp;

        $fullUrl = $this->UrlBuilder->make($domain, $url);

        $data = $this->Api->request('GET', $fullUrl);

        return $data;
    }

    public function getScene($bridgeIp, $groupId, $sceneId) {

        if (!isset($this->Keys[$bridgeIp])) {

            return ['no valid bridge'];
        }

        $url = [
            'api',
            $this->Keys[$bridgeIp],
            'groups',
            $groupId,
            'scenes',
            $sceneId
        ];

        $domain = $bridgeIp;

        $fullUrl = $this->UrlBuilder->make($domain, $url);

        $data = $this->Api->request('GET', $fullUrl);

        return $data;
    }
    
    public function recallScene($bridgeIp, $groupId, $sceneId) {

        if (!isset($this->Keys[$bridgeIp])) {

            return ['no valid bridge'];
        }

        $url = [
            'api',
            $this->Keys[$bridgeIp],
            'groups',
            $groupId,
            'scenes',
            $sceneId,
            'recall'
        ];
        
        $domain = $bridgeIp;

        $fullUrl = $this->UrlBuilder->make($domain, $url);
        
        $data = $this->Api->request('PUT', $fullUrl);        
        
        $this->updateGroups();
        $this->updateLights();

        return $data;
    }

    public function SwitchLight($bridgeIp, $lightId) {

        if (!isset($this->Keys[$bridgeIp])) {

            return ['no valid bridge'];
        }

        $url = [
            'api',
            $this->Keys[$bridgeIp],
            'lights',
            $lightId
        ];

        $domain = $bridgeIp;

        $fullUrl = $this->UrlBuilder->make($domain, $url);

        $data = $this->Api->request('GET', $fullUrl);

        switch ($data['state']['on']) {
            case true:
                return $this->switchOffLight($bridgeIp, $lightId);
                break;
            case false;
                return $this->switchOnLight($bridgeIp, $lightId);
                break;
        }
    }

    public function switchOnAllLight($bridgeIp) {

        if (!isset($this->Keys[$bridgeIp])) {

            return ['no valid bridge'];
        }

        $url = [
            'api',
            $this->Keys[$bridgeIp],
            'lights'
        ];

        $domain = $bridgeIp;

        $fullUrl = $this->UrlBuilder->make($domain, $url);

        $data = $this->Api->request('GET', $fullUrl);

        foreach ($data as $lightId => $data) {
            $this->switchOnLight($bridgeIp, $lightId);
        }

        return ['success', 'msg' => 'allon'];
    }

    public function switchOffAllLight($bridgeIp) {

        if (!isset($this->Keys[$bridgeIp])) {

            return ['no valid bridge'];
        }

        $url = [
            'api',
            $this->Keys[$bridgeIp],
            'lights'
        ];

        $domain = $bridgeIp;

        $fullUrl = $this->UrlBuilder->make($domain, $url);

        $data = $this->Api->request('GET', $fullUrl);

        foreach ($data as $lightId => $data) {
            $this->switchOffLight($bridgeIp, $lightId);
        }

        return ['success', 'msg' => 'alloff'];
    }

    public function switchAllLight($bridgeIp) {

        if (!isset($this->Keys[$bridgeIp])) {

            return ['no valid bridge'];
        }

        $url = [
            'api',
            $this->Keys[$bridgeIp],
            'lights'
        ];

        $domain = $bridgeIp;

        $fullUrl = $this->UrlBuilder->make($domain, $url);

        $data = $this->Api->request('GET', $fullUrl);

        foreach ($data as $lightId => $data) {
            switch ($data['state']['on']) {
                case true:
                    $this->switchOffLight($bridgeIp, $lightId);
                    break;
                case false;
                    $this->switchOnLight($bridgeIp, $lightId);
                    break;
            }
        }
        return ['success'];
    }

    public function switchOnLight($bridgeIp, $lightId) {

        if (!isset($this->Keys[$bridgeIp])) {

            return ['no valid bridge'];
        }

        $url = [
            'api',
            $this->Keys[$bridgeIp],
            'lights',
            $lightId,
            'state'
        ];

        $domain = $bridgeIp;

        $fullUrl = $this->UrlBuilder->make($domain, $url);

        $body = ['on' => true];

        $data = $this->Api->request('PUT', $fullUrl, $body);
        $this->updateGroups();
        $this->updateLights();
        
        if (isset($data[0]['success']))
            $data['state'] = 'on';
        else 
            $data['state'] = 'unknown';
        
        return $data;
    }

    public function switchOffLight($bridgeIp, $lightId) {

        if (!isset($this->Keys[$bridgeIp])) {

            return ['no valid bridge'];
        }

        $url = [
            'api',
            $this->Keys[$bridgeIp],
            'lights',
            $lightId,
            'state'
        ];

        $domain = $bridgeIp;

        $fullUrl = $this->UrlBuilder->make($domain, $url);

        $body = ['on' => false];

        $data = $this->Api->request('PUT', $fullUrl, $body);

        $this->updateGroups();
        $this->updateLights();
        
        if (isset($data[0]['success']))
            $data['state'] = 'off';
        else 
            $data['state'] = 'unknown';
        
        return $data;
    }

    public function switchOnGroup($bridgeIp, $groupId) {

        if (!isset($this->Keys[$bridgeIp])) {

            return ['no valid bridge'];
        }

        $url = [
            'api',
            $this->Keys[$bridgeIp],
            'groups',
            $groupId,
            'action'
        ];

        $domain = $bridgeIp;

        $fullUrl = $this->UrlBuilder->make($domain, $url);

        $body = ['on' => true];

        $data = $this->Api->request('PUT', $fullUrl, $body);
        
        $data = ['data' => $data, 'url' => $fullUrl, 'body' => $body];
        
        $this->updateGroups();
        $this->updateLights();

        return $data;
    }

    public function switchOffGroup($bridgeIp, $groupId) {

        if (!isset($this->Keys[$bridgeIp])) {

            return ['no valid bridge'];
        }

        $url = [
            'api',
            $this->Keys[$bridgeIp],
            'groups',
            $groupId,
            'action'
        ];

        $domain = $bridgeIp;

        $fullUrl = $this->UrlBuilder->make($domain, $url);

        $body = ['on' => false];

        $data = $this->Api->request('PUT', $fullUrl, $body);

        $this->updateGroups();
        $this->updateLights();

        return $data;
    }

    public function SwitchGroup($bridgeIp, $groupId) {

        if (!isset($this->Keys[$bridgeIp])) {

            return ['no valid bridge'];
        }

        $url = [
            'api',
            $this->Keys[$bridgeIp],
            'groups',
            $groupId
        ];

        $domain = $bridgeIp;

        $fullUrl = $this->UrlBuilder->make($domain, $url);

        $data = $this->Api->request('GET', $fullUrl);

        switch ($data['state']['all_on']) {
            case true:
                return $this->switchOffGroup($bridgeIp, $groupId);
                break;
            case false;
                return $this->switchOnGroup($bridgeIp, $groupId);
                break;
        }
    }

    public function alertLight($bridgeIp, $lightId) {

        if (!isset($this->Keys[$bridgeIp])) {

            return ['no valid bridge'];
        }

        $url = [
            'api',
            $this->Keys[$bridgeIp],
            'lights',
            $lightId,
            'state'
        ];

        $domain = $bridgeIp;

        $fullUrl = $this->UrlBuilder->make($domain, $url);

        $body = ['alert' => 'lselect'];

        $data = $this->Api->request('PUT', $fullUrl, $body);

        $this->updateLights();

        return $data;
    }

    public function updateLights() {

        $Bridges = $this->entity->getRepository(LightBridges::class);
        $Lights = $this->entity->getRepository(Lights::class);

        $AllBridges = $Bridges->findAll();
        $BridgeRows = [];
        foreach ($AllBridges as $BridgeData) {

            $BridgeRows[] = [$BridgeData->getId(), $BridgeData->getName()];
        }

        foreach ($AllBridges as $BridgeData) {

            $this->setKey($BridgeData->getIp(), $BridgeData->getAccount());

            $data = $this->getAllLights($BridgeData->getIp());

            // Update Data

            foreach ($data as $InternalId => $LightData) {
                if (isset($LightData['modelid']) && $LightData['modelid'] == 'ConBee II') {
                    continue;
                }

                if (isset($LightData['type']) && $LightData['type'] == 'On/Off plug-in unit') {
                    $LightData['state'] = [
                        'bri' => 1,
                        'hue' => 1,
                        'sat' => 1,
                        'xy' => [1, 1],
                        'on' => $LightData['state']['on'],
                        'reachable' => $LightData['state']['reachable'],
                    ];
                }
                $Light = $Lights->findOneBy(['internalId' => $InternalId, 'uniqueid' => $LightData['uniqueid']]);
                if ($Light === null) {
                    // Create Light Entry

                    $Light = new Lights();
                    $Light->setBridge($BridgeData);
                    $Light->setChecktime(new \DateTime());

                    $Light->setFactory((isset($LightData['productname'])) ? $LightData['productname'] : 'Unkown');
                    $Light->setInternalId($InternalId);
                    $Light->setReachable($LightData['state']['reachable']);
                    $Light->setStateBri($LightData['state']['bri']);
                    $Light->setStateHue($LightData['state']['hue']);
                    $Light->setStateOn($LightData['state']['on']);
                    $Light->setStateSat($LightData['state']['sat']);
                    $Light->setStateXY($LightData['state']['xy']);
                    $Light->setUniqueid($LightData['uniqueid']);
                    $this->entity->persist($Light);
                }

                $Light->setType($LightData['type']);
                $Light->setHascolor((isset($LightData['hascolor']) ? $LightData['hascolor'] : true));
                $Light->setModel((isset($LightData['modelid'])) ? $LightData['modelid'] : 'Unkown');
                $Light->setChecktime(new \DateTime());
                $Light->setReachable($LightData['state']['reachable']);
                $Light->setStateBri($LightData['state']['bri']);
                $Light->setStateHue($LightData['state']['hue']);
                
                $UpdatePost = ($Light->getStateOn() !== $LightData['state']['on']);
                
                $Light->setStateOn($LightData['state']['on']);
                $Light->setStateSat($LightData['state']['sat']);
                $Light->setStateXY($LightData['state']['xy']);
                $Light->setName($LightData['name']);

                $this->entity->persist($Light);
                
                if ($UpdatePost && $this->mercure) {
                    $update = new Update(
                        'http://user.sm.local/lights',
                        json_encode(
                                [
                                    'target' => 'lights',
                                    'status' => ($LightData['state']['on']) ? 'on' : 'off', 
                                    'bridge' => $BridgeData->getId(), 
                                    'light' => $Light->getId()
                                ]
                        )
                    );

                    $this->Hub->publish($update);
                }


                // Log entry for this entity

                /* $Log = new LightLog();
                $Log->setDatetime(new \DateTime());
                $Log->setLight($Light);
                $Log->setReachable($LightData['state']['reachable']);
                $Log->setStateBri($LightData['state']['bri']);
                $Log->setStateHue($LightData['state']['hue']);
                $Log->setStateOn($LightData['state']['on']);
                $Log->setStateSat($LightData['state']['sat']);
                $Log->setStateXY($LightData['state']['xy']);

                $this->entity->persist($Log);
                */
            }

            $this->entity->flush();
        }
    }

    public function updateGroups() {
        $Bridges = $this->entity->getRepository(LightBridges::class);
        $Lights = $this->entity->getRepository(Lights::class);
        $Groups = $this->entity->getRepository(LightGroups::class);
        $Scenes = $this->entity->getRepository(Scenes::class);

        $AllBridges = $Bridges->findAll();
        $BridgeRows = [];

        foreach ($AllBridges as $BridgeData) {

            $BridgeRows[] = [$BridgeData->getId(), $BridgeData->getName()];
        }

        foreach ($AllBridges as $BridgeData) {

            $this->setKey($BridgeData->getIp(), $BridgeData->getAccount());

            $data = $this->getAllGroups($BridgeData->getIp());

            foreach ($data as $InternalId => $GroupData) {

                $Group = $Groups->findOneBy(['internalId' => $InternalId, 'bridge' => $BridgeData]);

                #dump([$InternalId => $GroupData]);

                if ($Group === null) {

                    $Group = new LightGroups();
                    $Group->setInternalId($InternalId);
                    $Group->setBridge($BridgeData);
                }

                # Clean Lights 
                foreach ($Group->getLights() as $Light) {
                    $Group->removeLight($Light);
                }

                # Update 
                foreach ($GroupData['lights'] as $LightId) {
                    $Light = $Lights->findOneBy(['internalId' => $LightId, 'bridge' => $BridgeData]);
                    $Group->addLight($Light);
                }

                if ($Group->getLights() === null) {
                    dump($Group->getId() . ' has no lights');
                }


                $Group->setClass((!isset($GroupData['class'])) ? 'Unknown' : $GroupData['class']);
                $Group->setName($GroupData['name']);
                
                /**
                 * 
                 */
                $UpdatePost = false;
                
                $UpdatePost = ($Group->getStateAll() !== $GroupData['state']['all_on']) ? true : false;
                
                $Group->setStateAll($GroupData['state']['all_on']);
                
                if (!$UpdatePost) {
                    $UpdatePost = ($Group->getStateAny() !== $GroupData['state']['any_on']) ? true : false;
                }
                
                $Group->setStateAny($GroupData['state']['any_on']);
                $Group->setType($GroupData['type']);
                
                
                if ($UpdatePost && $this->mercure) {
                    
                    $groupName = $Group->getInternalId() . '--' . $BridgeData->getId();
                    $update = new Update(
                        'http://user.sm.local/groups',
                        json_encode(
                                [
                                    'target' => 'groups',
                                    'groupname' => $groupName,
                                    'status' => [
                                        'all' => ($GroupData['state']['all_on']) ? 'on' : 'off',
                                        'any' => ($GroupData['state']['any_on']) ? 'on' : 'off', 
                                    ]
                                ]
                        )
                    );

                    $this->Hub->publish($update);
                }

                $this->entity->persist($Group);

                $this->entity->flush();
                
                /*
                 * ScenesInDB create
                 */
                $ScenesInDB = [];
                if (count($Group->getScenes()) > 0) {
                    foreach ($Group->getScenes() as $Scene) {
                        /*
                         * ScenesInDB fill with Scene data from DB
                         */
                        $ScenesInDB[$Scene->getSceneId()] = $Scene;
                    }
                }
                

                if (isset($GroupData['scenes'])) {
                    foreach ($GroupData['scenes'] as $SceneData) {
                        if (isset($ScenesInDB[$SceneData['id']])) {
                            /*
                             * Delete Active Scenes from ScenesInDB
                             */
                            unset($ScenesInDB[$SceneData['id']]);
                        }
                        $data = $this->getScene($BridgeData->getIp(), $InternalId, $SceneData['id']);
                                
                        $Scene = $Scenes->findOneBy(['SceneId' => $SceneData['id'], 'GroupId' => $Group, 'Bridge' => $BridgeData]);
                        
                        if ($Scene === null) {
                            $Scene = new Scenes();
                        }
                        $Scene->setBridge($BridgeData);
                        $Scene->setGroupId($Group);
                        $Scene->setName($data['name']);
                        $Scene->setSceneId($SceneData['id']);
                        $this->entity->persist($Scene);

                        $this->entity->flush();
                    }
                }
                
                /*
                 * CleanUp not longer active Scenes from DB
                 */
                if (count($Group->getScenes()) > 0) {
                    foreach ($Group->getScenes() as $Scene) {
                        if (isset($ScenesInDB[$Scene->getSceneId()])) {
                            $Group->removeScene($Scene);
                        }
                    }
                }
                
                /*
                 * Save it and go!
                 */
                $this->entity->flush();
            }
        }
    }

}
