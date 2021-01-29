<?php

    namespace App\Command;

    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputArgument;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use App\Services\Igdb;

    class GetDataFromApiCommand extends Command {

        private $igdb;

        protected function configure () {
            // On set le nom de la commande
            $this->setName('app:getdata');

            // On set la description
            $this->setDescription("Récupère les donnés de l'api IGDB");

            // On set l'aide
            $this->setHelp("Cette commande permet de récuperer les donnés de l'api IGDB");

        }

        public function getIgdb(Igdb $igdb){
            $this->$igdb = $igdb;
        }

        public function execute (InputInterface $input, OutputInterface $output) {

            $output->write('DONE !');
        }
    }