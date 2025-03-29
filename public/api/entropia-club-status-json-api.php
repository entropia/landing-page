<?php

class EntropiaClubStatusJsonApi
{
    const WIKI_SPACE_API_URL = 'https://club.entropia.de/spaceapi';

    /**
     * @return string|false
     */
    private static function requestClubStatus()
    {
        $space_api_content = file_get_contents(self::WIKI_SPACE_API_URL);
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
     * @return void
     */
    public static function main()
    {
        header('Content-type: application/json; charset=utf-8');
        echo self::requestClubStatus();
    }
}

EntropiaClubStatusJsonApi::main();
