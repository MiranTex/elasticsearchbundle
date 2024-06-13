<?php

namespace Bundles\ElasticsearchBundle\Controller;

use Bundles\ElasticsearchBundle\Class\ConfigProvider;
use Elastic\Elasticsearch\ClientBuilder;
use Pimcore\Controller\FrontendController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Bundles\ElasticsearchBundle\Class\Client;
use Pimcore\Model\DataObject\Student;

class DefaultController extends FrontendController
{
    
    /**
     * @Route("/elasticsearch", name="bundles_elasticsearch")
     */
    public function indexAction(Request $request)
    {
       
        dd((new Student)->getAll([]));
    }


   
}
