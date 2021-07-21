<?php


namespace App\Utils;


use App\Entity\Outing;
use App\Repository\OutingRepository;
use App\Repository\StateRepository;

class StateService
{
    protected $outingRepository;
    protected $stateRepository;

    public function __construct(StateRepository $stateRepository, OutingRepository $outingRepository)
    {
        $this->outingRepository = $outingRepository;
        $this->stateRepository = $stateRepository;
    }

    /**
     * @throws \Exception
     */
    public function checkState($open)
    {
        $now = new \DateTime();
        $nowDay = new \DateTime($now->format('Y-M-d'). "00:00:00");
        $nowHour = new \DateTime($now->format('Y-M-d H:i:s'));

        $closeState = $this->stateRepository->find(['label' => 'Cloturée']);
        $currentState = $this->stateRepository->find(['label' => 'Activité en cours']);
        $pastState = $this->stateRepository->find(['label' => 'Passée']);
        $activeState = $this->stateRepository->find(['label' => 'Ouverte']);


        if($open instanceof Outing)
        {
            $buffer = $open;
            $open = [] ;
            $open = $buffer;
        }

        foreach ($open as $outings)
        {
            if($outings->getLimitDateInscription()<=  $nowDay)
            {
                $outings->setEtat($closeState);
            }

            if($outings->getDateTimeStart() <=  $nowHour)
            {
                $outings->setEtat($currentState);
            }

            $activeDuration = $outings->getDuration();
            $starDateTimeActive = clone $outings->getDateTimeStart();
            $endDateTimeActive = $starDateTimeActive->add(new \DateInterval('P0Y0M0DT0H'. $activeDuration . 'M0S'));

            if($endDateTimeActive <=  $nowHour)
            {
                $outings->setState($pastState);
            }

            $archive = $endDateTimeActive->add(new \DateInterval('P0Y1M0DT0H0M0S'));

            if($archive <=  $nowDay)
            {
                $outings->setState($archive);
            }
        }
        return $open;
    }
}