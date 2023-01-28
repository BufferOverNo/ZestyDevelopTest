<?php

interface openingHours 
{

 public function isOpen(DateTime $dateTime): bool;

 public function nextOpening(DateTime $dateTime): array;

}

class StoreOpeningHoursTest1 implements openingHours {
    private $opening_hours = [
        "mon" => ["open" => "09:00", "close" => "18:00"],
        "tue" => ["open" => "09:00", "close" => "18:00"],
        "wed" => ["open" => "09:00", "close" => "20:00"],
        "thu" => ["open" => "09:00", "close" => "21:00"],
        "fri" => ["open" => "09:00", "close" => "21:00"],
        "sat" => ["open" => "09:00", "close" => "15:00"],
        "sun" => ["open" => "13:00", "close" => "16:00"],
    ];

    public function isOpen($datetime)
    {
        $day = strtolower(date("D", $datetime->getTimestamp()));
        $time = date("H:i", $datetime->getTimestamp());

        $open_time = $this->opening_hours[$day]['open'];
        $close_time = $this->opening_hours[$day]['close'];

        $isOpen = ($time >= $open_time && $time <= $close_time);

        return $isOpen;
    }

    public function nextOpening($datetime)
    {
        $day = strtolower(date("D", $datetime->getTimestamp()));
        $time = date("H:i", $datetime->getTimestamp());

        $open_time = $this->opening_hours[$day]['open'];
        $close_time = $this->opening_hours[$day]['close'];

        if ($time >= $open_time && $time <= $close_time) {
            return $datetime;
        }

        // Get the next open day
        do {
            $datetime->modify('+1 day');
            $day = strtolower(date("D", $datetime->getTimestamp()));
            $open_time = $this->opening_hours[$day]['open'];
        } while (!$open_time);

        // Set the next open time
        $datetime->setTime($open_time);
        
        //Convert to the local time of the customer
        $datetime->setTimezone(new DateTimeZone("Europe/Paris"));
        $datetime->setTimezone(new DateTimeZone("America/Chicago"));
        return $datetime;
    }
}
