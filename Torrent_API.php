<?php


class Torrent_API
{
    var $RES = False, $INFO = False, $ERROR = False,
        $FILTER = array();

    private $last_request = 0;

    const category_list = array(
        'XXX' => "4",
        'MOVIES_XVID' => "14",
        'MOVIES_X264' => "17",
        'TV_EPISODES' => "18",
        'MUSIC_MP3' => "23",
        'MUSIC_FLAC' => "25",
        'GAMES_PC_ISO' => "27",
        'GAMES_PC_RIP' => "28",
        'GAMES_XBOX360' => "32",
        'SOFTWARE_PC_ISO' => "33",
        'EBOOKS' => "35",
        'GAMES_PS3' => "40",
        'TV_HD_EPISODES' => "41",
        'MOVIES_FULL_BD' => "42",
        'MOVIES_X264_1080' => "44",
        'MOVIES_X264_720' => "45",
        'MOVIES_BD_REMUX' => "46",
        'MOVIES_X264_3D' => "47",
        'MOVIES_XVID_720' => "48",
        'MOVIES' => "movies",
        'TV' => "tv",
        'TV_SHOWS' => "1;18;41",
        'GAMES' => "1;27;28;29;30;31;32;40",
        'MUSIC' => "1;23;24;25;26",
        'SOFTWARE' => "1;33;34;43",
        'NON_XXX' => "1;14;15;16;17;21;22;42;18;19;41;27;28;29;30;31;32;40;23;24;25;26;33;34;43;44;45;46;47;48",
        'ALL' => "1;4;14;15;16;17;21;22;42;18;19;41;27;28;29;30;31;32;40;23;24;25;26;33;34;43;44;45;46;47;48"
    );

    const sort_list = array(
        'SEEDERS' => "seeders",
        'LEECHERS' => "leechers",
        'LAST' => "last"
    );

    const BASE_URL = "https://torrentapi.org/pubapi_v2.php";

    /**
     * Rarbg_API constructor.
     * @param string $app_id
     */
    function __construct(string $app_id = 'generic')
    {
        $req = self::curl_get(self::BASE_URL, [
            'app_id' => $app_id,
            'get_token' => 'get_token'
        ]);
        $TOKEN = json_decode($req);
        if ($TOKEN) {
            $this->FILTER ['token'] = $TOKEN->token;
            $this->FILTER ['app_id'] = $app_id;
        }
    }

    /**
     * @return null
     */
    public function List()
    {
        $this->FILTER ['mode'] = 'list';
        return null;
    }

    /**
     * @param string $id
     * @return null
     */
    public function SearchTheMovieDB(string $id)
    {
        $this->FILTER ['mode'] = 'search';
        $this->FILTER ['search_themoviedb'] = $id;
        return null;
    }

    /**
     * @param string $id
     * @return null
     */
    public function SearchIMDB(string $id)
    {
        $this->FILTER ['mode'] = 'search';
        $this->FILTER ['search_imdb'] = $id;
        return null;
    }

    /**
     * @param string $id
     * @return null
     */
    public function SearchTVDB(string $id)
    {
        $this->FILTER ['mode'] = 'search';
        $this->FILTER ['search_tvdb'] = $id;
        return null;
    }

    /**
     * @param string $string
     * @return null
     */
    public function Search(string $string)
    {
        $this->FILTER ['mode'] = 'search';
        $this->FILTER ['search_string'] = $string;
        return null;
    }

    /**
     * @param string $category
     * @return mixed
     */
    public function Category(string $category = "ALL")
    {
        return $this->FILTER ['category'] = self::category_list[$category];
    }

    /**
     * @param int $limit
     * @return int
     */
    public function Limit(int $limit = 0)
    {
        return $this->FILTER ['limit'] = $limit;
    }

    /**
     * @param string $sort
     * @return mixed
     */
    public function Sort(string $sort = "LAST")
    {
        return $this->FILTER ['sort'] = self::sort_list[$sort];
    }

    /**
     * @param int $integer
     * @return int
     */
    public function MinSeeders(int $integer = 0)
    {
        return $this->FILTER ['min_seeders'] = $integer;
    }

    /**
     * @param int $integer
     * @return int
     */
    public function MinLeechers(int $integer = 0)
    {
        return $this->FILTER ['min_leechers'] = $integer;
    }

    /**
     * @param bool $boolean
     * @return string
     */
    public function Extended(bool $boolean = False)
    {

        if ($boolean) return $this->FILTER ['format'] = "json_extended";
        return $this->FILTER ['format'] = "json";
    }

    /**
     * @param bool $boolean
     * @return string
     */
    public function Ranked(bool $boolean = True)
    {
        if ($boolean) return $this->FILTER ['ranked'] = "1";
        return $this->FILTER ['ranked'] = "0";
    }


    /**
     * @return mixed
     */
    public function Execute()
    {
        if (!isset($this->FILTER ['mode'])) $this->List();
        $res = json_decode(self::curl_get(self::BASE_URL, $this->FILTER));
        return $this->RES = $res->torrent_results;

    }

    /**
     * @return array
     */
    static public function get_category()
    {
        return self::category_list;
    }

    /**
     * @return array
     */
    static public function get_sort()
    {
        return self::sort_list;
    }

    /**
     * Send a GET requst using cURL
     * @param string $url to request
     * @param array $get values to send
     * @param array $options for cURL
     * @return string
     */
    private function curl_get(string $url, array $get = array(), array $options = array())
    {
        $defaults = array(
            CURLOPT_URL => $url . (strpos($url, '?') === FALSE ? '?' : '') . http_build_query($get),
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_TIMEOUT => 4,
            CURLOPT_USERAGENT => "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.108 Safari/537.36"
        );

        while (microtime(true) - $this->last_request <= 2)
            usleep(500);

        $ch = curl_init();
        curl_setopt_array($ch, ($options + $defaults));
        if (!$result = curl_exec($ch))
            $this->ERROR = curl_error($ch);
        $info = curl_getinfo($ch);
        if ($info['http_code'] != 200)
            $this->INFO = $info;
        curl_close($ch);
        $this->last_request = microtime(true);
        return $result;
    }
}

?>
