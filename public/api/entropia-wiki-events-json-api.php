<?php

class EntropiaWikiEventsJsonApi
{
    const WIKI_EVENTS_LIST_URL = 'https://entropia.de/Termine';
    const DEFAULT_MAX_ENTRIES = 10;

    const COLUMN_DATE_INDEX = 0;
    const COLUMN_TIME_INDEX = 1;
    const COLUMN_LOCATION_INDEX = 2;
    const COLUMN_TITLE_INDEX = 3;

    /**
     * @return array all relevant HTTP request parameters combined
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
     * @return DOMDocument
     */
    private static function convertHtmlToDomElement(string $entire_html_content)
    {
        $dom_document = new DOMDocument();
        $dom_document->loadHTML($entire_html_content);

        return $dom_document;
    }

    /**
     * @param DOMElement $table_row_column the DOM element of a <td> items inside the events wiki table
     * @return string|null
     */
    private static function getTableColumnText(DOMElement $table_row_column)
    {
        if ($table_row_column->nodeValue) {
            return trim($table_row_column->nodeValue);
        }

        return null;
    }

    /**
     * @param DOMElement $table_row_column the DOM element of a <td> items inside the events wiki table
     * @return string|null
     */
    private static function getTableColumnLink(DOMElement $table_row_column)
    {
        $table_row_column_a_tags = $table_row_column->getElementsByTagName('a');

        if (!(empty($table_row_column_a_tags))) {
            $table_row_column_a_tag = $table_row_column_a_tags->item(0);

            if ($table_row_column_a_tag instanceof DOMElement) {
                if (!empty($table_row_column_a_tag->getAttribute('href'))) {
                    return trim($table_row_column_a_tag->getAttribute('href'));
                }
            }
        }

        return null;
    }

    /**
     * @param DOMNodeList $table_row_columns including DOM elements of all <td> items inside an events wiki table row
     * @return array text and links of all columns inside an events wiki table row
     */
    private static function createEventsTableRowArray(DOMNodeList $table_row_columns)
    {
        $columns = [];

        foreach ($table_row_columns as $table_row_column) {
            if (!($table_row_column instanceof DOMElement)) {
                continue;
            }

            $text = self::getTableColumnText($table_row_column);
            $link = self::getTableColumnLink($table_row_column);

            $column = [
                'text' => $text,
                'link' => $link,
            ];

            $columns[] = $column;
        }

        return $columns;
    }

    /**
     * @param DOMNodeList $table_rows including DOM elements of all <tr> items inside the events wiki table
     * @return array
     */
    private static function createEventsTableArray(DOMNodeList $table_rows)
    {
        $table_array = [];

        foreach ($table_rows as $table_row) {
            /**
             * @var DOMNodeList $table_row_columns
             */
            $table_row_columns = $table_row->getElementsByTagName('td');

            if ($table_row_columns->length < 4) {
                continue;
            }

            $columns = self::createEventsTableRowArray($table_row_columns);

            $table_array[] = [
                'title'    => $columns[self::COLUMN_TITLE_INDEX],
                'date'     => $columns[self::COLUMN_DATE_INDEX],
                'time'     => $columns[self::COLUMN_TIME_INDEX],
                'location' => $columns[self::COLUMN_LOCATION_INDEX],
            ];
        }

        return $table_array;
    }

    /**
     * @param string $entire_html_content the full page's html content
     * @return array
     */
    private static function parseTableHtmlContent(string $entire_html_content)
    {
        $dom_document = self::convertHtmlToDomElement($entire_html_content);
        $table_rows = $dom_document->getElementsByTagName('tr');

        return self::createEventsTableArray($table_rows);
    }

    /**
     * @param ?int $max_entries the desired maximum number of events in the API response JSON
     * @return string|false
     */
    private static function requestNextEventsAsJson(?int $max_entries = null)
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
