<?php

use Sabre\VObject;

require '../../vendor/autoload.php';

class EntropiaCloudEventsJsonApi
{
    const TIMEZONE = new DateTimeZone('Europe/Berlin');
    const DEFAULT_ICS_URL = 'https://cloud.entropia.de/remote.php/dav/public-calendars/3Ag5YobrwKpWYKsZ?export';
    const DEFAULT_MAX_ENTRIES = 10;

    /**
     * @param ?string $url the url of the ics file
     * @return string of the response body
     */
    private static function queryIcsContent(?string $url = null)
    {
        return file_get_contents($url ?? self::DEFAULT_ICS_URL);
    }

    private static function getUpcomingEvents(int $maxEntries = null)
    {
        $vcalendar = VObject\Reader::read(self::queryIcsContent());

        $events = [];
        $i = 0;

        $now = new DateTime('now', self::TIMEZONE);

        foreach ($vcalendar->VEVENT as $event) {
            // ignore past events
            if ($event->DTEND->getDateTime(self::TIMEZONE) < $now) {
                continue;
            }

            $events[] = [
                'title' => (string) $event->SUMMARY,
                'datetime' => $event->DTSTART->getDateTime(self::TIMEZONE)->format(DateTimeInterface::ATOM),
                'location' => (string) $event->LOCATION,
            ];

            // respect max-entries parameter
            if (++$i == $maxEntries) {
                break;
            }
        }

        return $events;
    }

    public static function main()
    {
        $maxEntries = $_GET['max-entries'] ?? self::DEFAULT_MAX_ENTRIES;

        header('Content-type: application/json; charset=utf-8');
        try {
            $events = self::getUpcomingEvents($maxEntries);

            echo \json_encode([
                'events' => $events
            ]);
        } catch (\Throwable $e) {
            http_response_code(500);
            echo '{}';
        }
    }
}

EntropiaCloudEventsJsonApi::main();
