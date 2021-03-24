<?php

namespace App\Entity;

use DateTime;

class EvenementRecher{

    private $place;

    /**
     * @return mixed
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * @param mixed $place
     */
    public function setPlace(String $place): EvenementRecher
    {
        $this->place = $place;
        return $this;
    }

}