<?php

class EntropiaClubStatusJsonApi
{
    const WIKI_SPACE_API_URL = 'https://club.entropia.de/spaceapi';

    public static function main()
    {
        header('Content-type: application/json; charset=utf-8');
        echo file_get_contents(self::WIKI_SPACE_API_URL);
    }
}

EntropiaClubStatusJsonApi::main();
