<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Command;

use Envms\FluentPDO\Query;
use League\Csv\Reader;
use Selmak\Proaxive2\Domain\Customer\Customer;
use Selmak\Proaxive2\Domain\Customer\Repository\CustomerRepository;
use Selmak\Proaxive2\Settings\SettingsInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportCustomerCommand extends Command
{
    private Query $query;
    private SettingsInterface $settings;

    public function __construct(Query $query, SettingsInterface $settings)
    {
        parent::__construct();
        $this->query = $query;
        $this->settings = $settings;
    }

    protected function configure(): void
    {
        parent::configure();

        $this->setName('csv:import:customers');
        $this->setDescription('For import customers');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title("Tentative d'importation des données Customers...");
        $reader = Reader::createFromPath(__DIR__ . '/../../storage/app/imports/ProaxiveLite_Export-Clients.csv', 'r');
        $reader->setHeaderOffset(0);
        $io->progressStart(iterator_count($reader));
        foreach($reader->getRecords() as $row) {
            $repo = new CustomerRepository($this->query);
            if(empty($row['mail']) OR $row['mail'] === 'NC'){
                $mail = 'mail@mail'.random_int(100, 999).'.fr';
            } else {
                $mail = $row['mail'];
            }
            $c = $repo->ifExist('mail', $row['mail']);
            if($c === 0){
                $customer = (new Customer())
                    ->setLastname($row['lastname'])
                    ->setFirstname($row['firstname'])
                    ->setFullname($row['firstname'] . ' ' . $row['lastname'])
                    ->setAddress($row['address'])
                    ->setPhone($row['phone'])
                    ->setMail($mail)
                    ->setZipcode($row['zipcode'])
                    ->setCity($row['city'])
                ;
                $repo->add($customer, true);
            }
            $io->progressAdvance();
        }
        $io->progressFinish();
        $io->success("La commande s'est terminée correctement !");
    }
}