<?php

    namespace App\Command;

    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputArgument;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use App\Services\Igdb;

    class GetDataFromApiCommand extends Command {

        private $igdb;

        public function __construct(igdb $igdb)
        {
            $this->igdb = $igdb;
            parent::__construct();
        }

        protected function configure () {
            // On set le nom de la commande
            $this->setName('app:getdata');

            // On set la description
            $this->setDescription("Récupère les donnés de l'api IGDB");

            // On set l'aide
            $this->setHelp("Cette commande permet de récuperer les donnés de l'api IGDB");

        }

        public function execute (InputInterface $input, OutputInterface $output) {
            ini_set('memory_limit', '1024M');
            $this->igdb->initCron();
            $output->write('DONE !');
            return Command::SUCCESS;
        }
    }
