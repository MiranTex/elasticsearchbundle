#ElasticSearch Bundle

Add this to config.yaml file:

    bundles_elasticsearch:
        host: your elasticsearch host
        index: your_index_name
        username: your_user_name
        password: elasticsearch_password

To use this bundle, after instal it in your project
    - The dataObject you want to index must implements two interfaces (Arrayable and getClassId)
    - Then use the trait Indexable "namespace Bundles\ElasticsearchBundle\Trait\Indexable" 

There is a single command for the terminal to index, erase all data and reindex documents ind the elastic-server
    "php bin/console indexaction:sync <class> <mode>"
    - class: the Class of the dataObject you want to index
    - mode: 
        * index: to index registers in the server
        * delete: to erase all data from the server of this specifc index
        * reindex: maily will erase all data and rewrite the documents on the elastisearch server 
