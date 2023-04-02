<?php

declare(strict_types=1);

namespace App\Hospital\Application\Telegram\Tools;

class Tools
{
    public static function getDateRange(string $date)
    {
        $startDate = new DateTime($date . '09:00:00');
        $endDate = new DateTime($date . '17:30:00');
        $interval = new DateInterval('PT30M');

        $dateRange = [];
        while ($startDate <= $endDate) {
            $dateRange[] = $startDate->format('Y-m-d H:i:s');
            $startDate->add($interval);
        }

        return $dateRange;
    }
}
