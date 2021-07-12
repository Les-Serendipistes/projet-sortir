<?php


namespace App\Data;


use App\Entity\Campus;
use App\Entity\State;
use App\Entity\User;
use DateTime;

class SearchData
{

    /**
     * @var string
     */
    public $q = '';

    /**
     * @var Campus[]
     */
    public $campus;

    /**
     * @var User
     */
    public $organizer;

    /**
     * @var User
     */
    public $registered;

    /**
     * @var boolean
     */
    public $unregistered = false;

    /**
     * @var boolean
     */
    public $olds = false;

    /**
     * @var DateTime
     */
    public $dateStart;

    /**
     * @var DateTime
     */
    public $dateEnd;

}