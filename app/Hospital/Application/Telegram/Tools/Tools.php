<?php

declare(strict_types=1);

namespace App\Hospital\Application\Telegram\Tools;

use DateTime;
use DateInterval;

class Tools
{
    public static function getTimeRange(): array
    {
        $startDate = new DateTime('09:00');
        $endDate = new DateTime('17:30');
        $interval = new DateInterval('PT30M');

        $dateRange = [];
        while ($startDate <= $endDate) {
            $dateRange[] = $startDate->format('H:i');
            $startDate->add($interval);
        }

        return $dateRange;
    }

    public static function formatDate(string $date): string
    {
        $formattedDate = DateTime::createFromFormat('d.m.Y', $date);
        return $formattedDate->format('Y-m-d');
    }
}
