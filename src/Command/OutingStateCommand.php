<?php


namespace App\Command;


use App\Repository\OutingRepository;
use App\Repository\StateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class OutingStateCommand extends Command
{

    protected static $defaultName = 'app:update-outing-states';

    protected  $entityManager;
    protected  $outingRepository;
    protected  $stateRepository;

    public function __construct(EntityManagerInterface $entityManager, OutingRepository $outingRepository, StateRepository $stateRepository, string $name = null)
    {
        $this->entityManager = $entityManager;
        $this->stateRepository =$stateRepository;
        $this->outingRepository = $outingRepository;
        parent::__construct($name);
    }

    protected function configure()
    {
        $this->setDescription('Update outing\'s states with cron job');
    }

    /**
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $now = new \DateTime();
        $nowFormat = new \DateTime($now->format('Y-M-d'). "00:00:00");

        $outingInProgress = $this->outingRepository->findBy(['state' => 'Activitée en cours']);
        $closedState = $this->stateRepository->find(['label' => 'Cloturée']);

        foreach ($outingInProgress as $outing)
        {
            if($outing->getLimitDateInscription() <= $nowFormat){
                $outing->setState($closedState);
                $this->entityManager->persist($outing);
            }
        }
        $this->entityManager->flush();

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }

}