<?php

namespace Bundles\ElasticsearchBundle\Trait;

use Bundles\ElasticsearchBundle\Class\Client;
use Elastic\Elasticsearch\ClientBuilder;
use Symfony\Component\Yaml\Yaml;

/**
 * 
 */
trait Indexable
{
    /**
     * function to pick the attributes and make an array in the format ["attributes.attribute"=>"value"]
     * @param $query
     * @return array
     */
    private function makeQuery($query){
        $query_array = [];

        foreach ($query as $key => $value) {
            
            $key == "id" ? $query_array["_".$key] = $value : $query_array["".$key] = $value;
        }

        return $query_array;
    }

    /**
     * function to pick the attributes and make an array in the format ["match"=>["attribute"=>"value"]
     * @param $query
     * @return array
     */

    private function makeMatch($query,$match_name="match"){

        $match = [];

        foreach ($query as $key => $value) {
            $match[] = [
                $match_name => [
                    $key => $value
                ]
            ];
        }



        return $match;

    }

    private function getDefaultIndex(){
        
        $config = Yaml::parseFile("../config/ecommerce/elastic-search.yaml");

        return $config["pimcore_ecommerce_framework"]["index_service"]["tenants"]["ElasticSearch"]["config_options"]["client_config"]["indexName"];
    }

    /**
     * function to search in Elasticsearch by id
     * @param $id
     * @return array
     */

    public function getByQueryById($id){

        $result = $this->getByQuery(["id"=>$id]);

        return $result != null ? [false, $result[0]] : [true,$this->getById($id)];
    }

    public function getByQuery($query,$from=0,$size=10)
    {
        try{
            
    
            $container = \Pimcore::getContainer();

            $index = $container->getParameter('bundles_elasticsearch.index');
            $host = $container->getParameter('bundles_elasticsearch.host');
            $user = $container->getParameter('bundles_elasticsearch.username');
            $password = $container->getParameter('bundles_elasticsearch.password');


            $client = Client::getClient($host,$user,$password);
            
            $query = $this->makeQuery($query);
            $match = $this->makeMatch($query);
            
            $params = [
                'from' => $from,
                'size' => $size,
                'index' => $index,
                'body' => [
                    'query' => [
                        "bool" =>[
                            "must" => [
                                [
                                    "match"=>[
                                        "classId" => $this->classId
                                    ]
                                ],...$match
                            ]
                        ]
                    ]
                        
                ]
            ];

            // dd($params);
            
            // Make the search in Elasticsearch
            $response = $client->search($params);
            
            // Check if it returned any results
            if ($response['hits']['total']['value'] > 0) {
                // return the results
                return $response['hits']['hits'];
            }
        }catch(\Exception $e){
            
        }
        
        //if there are no results in Elasticsearch, return null to search in the database
        return null;
    }

    public function getAll(){
        return $this->getByQuery([]);

    }
}
