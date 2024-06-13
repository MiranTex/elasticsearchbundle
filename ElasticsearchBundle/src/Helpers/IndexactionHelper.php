<?php


namespace Bundles\ElasticsearchBundle\Helpers;

use Bundles\ElasticsearchBundle\Class\Client;
use Elastic\Elasticsearch\Client as ElasticsearchClient;
use Pimcore\Model\DataObject\Student;
use Symfony\Component\DependencyInjection\ContainerInterface;

class IndexactionHelper
{
    private ElasticsearchClient $client;
    private ContainerInterface $containerBuilder;
    private $index;

    public function __construct(Client $client, ContainerInterface $containerBuilder) {

        $this->containerBuilder = $containerBuilder;
        $this->client = $client->getClient(
            $this->containerBuilder->getParameter('bundles_elasticsearch.host'),
            $this->containerBuilder->getParameter('bundles_elasticsearch.username'),
            $this->containerBuilder->getParameter('bundles_elasticsearch.password')
        );
        $this->index = $this->containerBuilder->getParameter('bundles_elasticsearch.index');
    }

    public function exists($id){

        return $this->client->exists([
            'index' => $this->index,
            "id"=> $id
        ])->getStatusCode() == 200;
    }

    public function get($id){

        if(!$this->exists($id))
            return null;
        
        return $this->client->get([
            'index' => $this->index,
            "id"=> $id
        ])->toArray();

    }


    public function index($id,$body){

        $response = $this->client->index([
            'index' => $this->index,
            'id'    => $id,
            'body'  => $body
        ]);
    
        return $response;
    }

    public function delete($id){
        
        $extis = $this->exists($id);


        if($extis){
            $response = $this->client->delete([
                'index' => $this->index,
                'id'    => $id
            ]);
        

            return $response;
        }
    }

    public function test(){
        return (new Student())->getByQuery([
            "name" => "ari"
        ]);
    }

}