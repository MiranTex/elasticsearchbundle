<?php

namespace Bundles\ElasticsearchBundle\Class;

use Elastic\Elasticsearch\ClientBuilder;

class Client{

    private static $client = null;

    public static function getClient($host,$user,$password){
        if(!self::$client){
            self::$client = ClientBuilder::create()
            ->setHosts([$host]) // Configure acording to your Elasticsearch configuration
            ->setBasicAuthentication($user, $password)
            ->build();
        }


        return self::$client;
    }
}