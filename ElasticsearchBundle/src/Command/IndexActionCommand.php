<?php

namespace Bundles\ElasticsearchBundle\Command;

use Bundles\ElasticsearchBundle\Helpers\IndexactionHelper;
use Pimcore\Console\AbstractCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Validator\Constraints\Length;

class IndexActionCommand extends AbstractCommand {

    private IndexactionHelper $indexactionHelper;


    /**
     * Constructor
     *
     * @param IndexactionHelper $indexactionHelper
     */

    public function __construct(IndexactionHelper $indexactionHelper) {
        parent::__construct();
        $this->indexactionHelper = $indexactionHelper;
    }

    /**
     * Command config
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('indexaction:sync')
            ->setDescription('Sync vacancies between HRM system and Javra PIMCore website data objects.')
            ->addArgument('class',InputArgument::REQUIRED,"Class name")
            ->addArgument('action',InputArgument::REQUIRED,"Action (delete/index/reindex))");
    }

    /**
     * Command execute
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // mouting the class with the complete namespace till the listing class of the data object targetted
        $listing = "\\Pimcore\\Model\\DataObject\\".$input->getArgument('class')."\\Listing";

        // check if this class exists or not
        if(class_exists($listing) == false) {
            $output->writeln("Invalid class!");
            return Command::FAILURE;
        }

        // create an instance of the listing class
        $listingInstance = new $listing();

        // load the data from the listing class
        $results = $listingInstance->load();

        // get the action from the input
        $action = $input->getArgument('action');

        // check if the action is valid or not
        if($action != 'delete' && $action != 'reindex' && $action != 'index') {
            $output->writeln("Invalid action!");
            return Command::FAILURE;
        }

        // dd($this->indexactionHelper->test());

        // loop through the results and perform the action
        foreach ($results as $key => $row) {
            
            $id = $row->getId();
            
            // check what action to perform depending on the input
            switch($action) {
                case 'delete':
                    $this->indexactionHelper->delete($id);
                    break;
                case 'index':
                    $this->indexactionHelper->index($id,[...$row->toArray(),...["classId"=>$row->getClassId()]]);
                    break;
                case 'reindex':
                    $this->reindex($id,[...$row->toArray(),...["classId"=>$row->getClassId()]]);
                    break;
            }

        }
        
        // print the final message
        $output->writeln("Operation Done!");

        return Command::SUCCESS;
    }


    /**
     * Reindex
     *
     * @param [type] $id
     * @param [type] $data
     * @return void
     * to delete al register and index again
     */
    public function reindex($id,$data) {
        $this->indexactionHelper->delete($id);
        $this->indexactionHelper->index($id,$data);
    }

}