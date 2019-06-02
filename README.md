# Torrent_API

> This php class need php version > 5.6* (Not tested) or 7 (Tested) / curl with SSL

*Note: PHP 5.6 support stops at the end of 2018
## How use this class

    $torrent = new Torrent_API();  
    $torrent->Search('STRING TO SEARCH');
    // or  $torrent->SearchTheMovieDB('ID TO SEARCH');
    // or  $torrent->SearchIMDB('ID TO SEARCH');
    // or  $torrent->SearchTVDB('ID TO SEARCH');
    // or  $torrent->List();
    $torrent->Execute(); // print this or $torrent->RES
### Limit
Results limit

    $torrent->Limit(10);

### Sort
Sort type

    $torrent->Sort('LAST');

**Option**
- SEEDERS
- LEECHERS  
- LAST
### Category
Category

    $torrent->Category('ALL');

**Options**
- XXX
- MOVIES_XVID
- MOVIES_X264
- TV_EPISODES
- MUSIC_MP3
- MUSIC_FLAC
- GAMES_PC_ISO
- GAMES_PC_RIP
- GAMES_XBOX360
- SOFTWARE_PC_ISO
- EBOOKS
- GAMES_PS3
- TV_HD_EPISODES
- MOVIES_FULL_BD
- MOVIES_X264_1080
- MOVIES_X264_720
- MOVIES_BD_REMUX
- MOVIES_X264_3D
- MOVIES_XVID_720
- MOVIES
- TV
- TV_SHOWS
- GAMES
- MUSIC
- SOFTWARE
- NON_XXX
- ALL

### MinSeeders
    $torrent->MinSeeders(1);
### MinLeechers
    $torrent->MinLeechers(1);
  
### Extended
Extended results

    $torrent->Extended(false);

### Ranked
Ranked results

    $torrent->Ranked(True);

### Execute

> Put this at the end for run the query

     $res = $torrent->Execute();
     var_dump($res);
     //or
     var_dump($torrent->RES);

### This class was created for informational and educational purposes only, other uses are prohibited.  The creator takes no responsibility for how it is used.
