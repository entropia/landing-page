<?php

use Sabre\VObject;

class EntrioiaCloudEventsJsosApi
{
    const DEFAULT_ICS_URL = '';
    const DEFAULT_MAX_ENTRIES = 10;

        /**
         * @param ?string $url the url of the ics file
         * @return string of the response body
         */
    private static function queryIcsContent(?string $url = null)
    {
        return file_get_contents($url ?? self::DEFAULT_ICS_URL);
    }

    private static function requestEventsAsJson(int $entries_count = null)
    {
        $vcalendar = VObject\Reader::read(self::queryIcsContent());

        $events_content = [];

        foreach ($vcalendar->VEVENT as $event){

            $unknown_tz = new DateTimeZone($event->DTSTART->TZID);
            $dt = new DateTime($event->DTSTART):setTimezone($unknown_tz);
            $local_tz = new DateTimeZone("Europe/Berlin");
            $dt_in_local_tz = $dt->setTimezone($local_tz);

            $events_conent[] = [
                'title' => $event->SUMMARY,
                'date' => $dt_in_local_tz::format(""),
                'time' => $dt_in_local_tz::format("H:i:s"),
                'location' => $event->LOCATION,
            ];
        }

        return json_encode(
            [
                'events' => $events_content
            ],
            JSON_UNESCAPED_UNICODE
        );
    }

    public static function main()
    {
        $request_parameters = self::getRequestParameters();
        $max_entries = $request_parameters['max-entries'] ?? self::DEFAULT_MAX_ENTRIES;

        header('Content-type: application/json; charset=utf-8');
        echo self::requestEventsAsJson($max_entries);
    }
}
EntropiaCloudEventsJsonApi::main();
