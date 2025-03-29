<?php

class EntropiaClubStatusJsonApi
{
    const WIKI_SPACE_API_URL = 'https://club.entropia.de/spaceapi';

    /**
     * @return void
     */
    public static function main()
    {
        $space_api_content = file_get_contents(self::WIKI_SPACE_API_URL);

        header('Content-type: application/json; charset=utf-8');
        echo $space_api_content;
    }
}

EntropiaClubStatusJsonApi::main();
