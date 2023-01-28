<?php

interface openingHours 
{

 public function isOpen(DateTime $dateTime): bool;

 public function nextOpening(DateTime $dateTime): array;

}

class StoreOpeningHoursTest2 implements openingHours {
    private $opening_hours = [
        'mon' => ['open' => '09:00', 'close' => '18:00'],
        'tue' => ['open' => '09:00', 'close' => '18:00'],
        // wednesday removed
        'thu' => ['open' => '09:00', 'close' => '21:00'],
        'fri' => ['open' => '09:00', 'close' => '21:00'],
        'sat' => ['open' => '09:00', 'close' => '15:00'],
        'sun' => ['open' => '13:00', 'close' => '16:00']
    ];

    public function isOpen(DateTime $dateTime)
    {
        $day = strtolower($dateTime->format('D'));
        $time = $dateTime->format('H:i');

        if (!array_key_exists($day, $this->opening_hours)) {
            return false;
        }

        return ($time >= $this->opening_hours[$day]['open'] && $time < $this->opening_hours[$day]['close']);
    }

    public function nextOpening(DateTime $dateTime)
    {
        $day = strtolower($dateTime->format('D'));
        $time = $dateTime->format('H:i');

        // If the store is already open, return the current day and open time
        if ($this->isOpen($dateTime)) {
            return [
                'day' => $day,
                'time' => $this->opening_hours[$day]['open']
            ];
        }

        // Otherwise, find the next open day
        $nextDay = $day;
        do {
            $dateTime->modify('+1 day');
            $nextDay = strtolower($dateTime->format('D'));
        } while (!array_key_exists($nextDay, $this->opening_hours) || $nextDay == 'wed');

        return [
            'day' => $nextDay,
            'time' => $this->opening_hours[$nextDay]['open']
        ];
    }
}
