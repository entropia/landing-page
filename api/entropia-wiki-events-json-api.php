<?php

class EntropiaWikiEventsJsonApi
{
    const WIKI_EVENTS_LIST_URL = 'https://entropia.de/Termine';
    const DEFAULT_MAX_ENTRIES = 10;

    /**
     *
     */
    private static function getRequestParameters()
    {
        return array_merge($_REQUEST, $_GET, $_POST);
    }

    /**
     * @param ?string $url the public url of the requested website
     * @return string the html content of the website
     */
    private static function queryHtmlContent(?string $url = null)
    {
        return file_get_contents($url ?? self::WIKI_EVENTS_LIST_URL);
    }

    /**
     * @param string $entire_html_content the full page's html content
     */
    private static function parseTableHtmlContent(string $entire_html_content)
    {
        $dom_document = new DOMDocument();
        $dom_document->loadHTML($entire_html_content);

        $table_rows = $dom_document->getElementsByTagName('tr');

        $table_array = [];

        foreach ($table_rows as $table_row) {
            $row_content = explode("\n", $table_row->nodeValue);

            $table_array[] = [
                'title' => $row_content[3],
                'date' => $row_content[0],
                'time' => $row_content[1],
                'location' => $row_content[2],
            ];
        }

        array_shift($table_array);

        return $table_array;
    }

    /**
     * @param ?int $max_entries the desired maximum number of events in the API response JSON
     * @return string|false
     */
    private static function requestNextEventsAsJson(int $max_entries = null)
    {
        $entire_html_content = self::queryHtmlContent();
        $events_table_content = self::parseTableHtmlContent($entire_html_content);

        if (is_int($max_entries) && count($events_table_content) > $max_entries) {
            $events_table_content = array_slice($events_table_content, 0, $max_entries);
        }

        return json_encode(
            [
                'events' => $events_table_content
            ],
            JSON_UNESCAPED_UNICODE
        );
    }

    /**
     * @return void
     */
    public static function main()
    {
        $request_parameters = self::getRequestParameters();
        $max_entries = $request_parameters['max-entries'] ?? self::DEFAULT_MAX_ENTRIES;

        header('Content-type: application/json; charset=utf-8');
        echo self::requestNextEventsAsJson($max_entries);
    }
}

EntropiaWikiEventsJsonApi::main();
