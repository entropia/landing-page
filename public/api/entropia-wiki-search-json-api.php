<?php

class EntropiaWikiSearchJsonApi
{
    const WIKI_OPENSEARCH_JSON_API_URL_BASE = 'https://entropia.de/rest.php/v1/search/title';

    private static function getRequestParameters(): array
    {
        return array_merge($_REQUEST, $_GET, $_POST);
    }

    private static function queryOpensearchJsonApi(string $search_keyword): string|false
    {
        if (empty($search_keyword)) {
            return json_encode([ 'pages' => [] ]);
        }

        $url_base = self::WIKI_OPENSEARCH_JSON_API_URL_BASE;
        $url_parameters = http_build_query([ 'q' => $search_keyword ]);

        return file_get_contents("{$url_base}?{$url_parameters}");
    }

    public static function main(): void
    {
        $request_parameters = self::getRequestParameters();
        $search_keyword = $request_parameters['search-keyword'] ?? '';

        header('Content-type: application/json; charset=utf-8');
        echo self::queryOpensearchJsonApi($search_keyword);
    }
}

EntropiaWikiSearchJsonApi::main();
