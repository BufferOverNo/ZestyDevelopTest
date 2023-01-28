<?php

interface openingHours 
{

 public function isOpen(DateTime $dateTime): bool;

 public function nextOpening(DateTime $dateTime): array;

}

class StoreOpeningHoursTest3 implements openingHours {
    private $storeTimezone = 'Europe/Paris';

    private $opening_hours = [
        "Mon" => ["open" => "09:00", "close" => "18:00"],
        "Tue" => ["open" => "09:00", "close" => "18:00"],
        "Wed" => ["open" => "09:00", "close" => "20:00"],
        "Thu" => ["open" => "09:00", "close" => "21:00"],
        "Fri" => ["open" => "09:00", "close" => "21:00"],
        "Sat" => ["open" => "09:00", "close" => "15:00"],
        "Sun" => ["open" => "13:00", "close" => "16:00"],
    ];

    private $timezone;

    public function __construct($timezone = 'UTC') {
        $this->timezone = new DateTimeZone($timezone);
    }

    public function isOpen(DateTime $dateTime, $timezone) {
        $storeDateTime = new DateTime($dateTime->format('Y-m-d H:i:s'), new DateTimeZone($timezone));
        $storeDateTime->setTimezone(new DateTimeZone($this->storeTimezone));
        $day = $storeDateTime->format('l');
        $time = $storeDateTime->format('H:i');

        if (!array_key_exists($day, $this->days)) {
            return "Invalid day";
        }
        if ($time >= $this->days[$day]['open'] && $time < $this->days[$day]['close']) {
            return "Open";
        } else {
            return "Closed";
        }
    }

    public function nextOpening(DateTime $dateTime, $timezone) {
        $storeDateTime = new DateTime($dateTime->format('Y-m-d H:i:s'), new DateTimeZone($timezone));
        $storeDateTime->setTimezone(new DateTimeZone($this->storeTimezone));
        $day = $storeDateTime->format('l');
        $time = $storeDateTime->format('H:i');

        while (true) {
            if (!array_key_exists($day, $this->days)) {
                $storeDateTime->modify('+1 day');
                $day = $storeDateTime->format('l');
                continue;
            }
            if ($time < $this->days[$day]['open']) {
                return $storeDateTime->format('l, H:i');
            }
            $storeDateTime->modify('+1 day');
            $day = $storeDateTime->format('l');
        }
    }
}
