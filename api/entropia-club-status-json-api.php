<?php

class EntropiaClubStatusJsonApi
{
    const WIKI_SPACE_API_URL = 'https://club.entropia.de/spaceapi';

    /**
     * @param string|null $url the public url of the requested website
     * @return string the html content of the website
     */
    private static function queryJsonContent(string $url = null)
    {
        return file_get_contents($url ?? self::WIKI_SPACE_API_URL);
    }

    /**
     * @return string|false
     */
    private static function requestClubStatus()
    {
        $space_api_content = self::queryJsonContent();
        $space_api_json = json_decode($space_api_content, true);

        return json_encode(
            [
                'isOpen' => $space_api_json['state']['open'],
                'lastChange' => date('c', $space_api_json['state']['lastchange']),
            ],
            JSON_UNESCAPED_UNICODE
        );
    }

    /**
     *
     */
    public static function main()
    {
        header('Content-type: application/json; charset=utf-8');
        echo self::requestClubStatus();
    }
}

EntropiaClubStatusJsonApi::main();
