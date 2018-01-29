<?php
    if (!isset($_GET['s']) || $_GET['s'] == '') {
        echo "error";
        exit();
    }
    $searchRaw = $_GET['s'];
    $searchNoSpace = str_replace(' ', '+', $searchRaw);
    $search = str_replace('%20', '+', $searchNoSpace);
    // echo $search;
    // exit();
    $website = "http://search.chiasenhac.vn/search.php?s=$search"; 
    $csnApi = getlink($website);

    // $array = array(array('rank' => '1', 'name' => 'my love'), array('rank' => '2', 'name' => 'evergreen'));
    echo json_encode($csnApi, JSON_UNESCAPED_UNICODE);

    function getlink($website){
        $content = @file_get_contents($website);
        $csnApi = array();
        $startLoop = 0;

        // for ($i = 0; $i < 25; $i++) {
        //     $startContent = strpos($content, "<tr title", $startLoop);
        //     if ($startContent == FALSE) {
        //         break;
        //     }
        //     $onePartApi = findAllObjectInAPart($content, $startContent, $startLoop);
        //     $csnApi[] = $onePartApi;
        // }

        while($startContent = strpos($content, "class=\"tenbh\"", $startLoop)) {
            $onePartApi = findAllObjectInAPart($content, $startContent, $startLoop);
            if ($onePartApi != FALSE) {
                $csnApi[] = $onePartApi;
            }
        }
        return $csnApi;
    }

    function findAllObjectInAPart($content, $startContent, &$startLoop){
        $startLink = strpos($content, "a href=", $startContent);
        $endLink = strpos($content, "\"", $startLink + 10);
        $link = substr($content, $startLink + 8, $endLink - $startLink - 8);
        if ( (!strpos($link, "/mp3/")) || (strpos($link, "/beat-playback/") != FALSE) ) {
            $startLoop = $endLink;
            return FALSE;
        }
        $linkDownload = getLinkDownload($link);

        $startName = strpos($content, ">", $endLink);
        $endName = strpos($content, "<", $startName);
        $name = substr($content, $startName + 1, $endName - $startName - 1);

        $startArtist = strpos($content, "<p>", $endName);
        $endArtist = strpos($content, "</p>", $startArtist);
        $artist = substr($content, $startArtist + 3, $endArtist - $startArtist - 3);

        $startDuration = strpos($content, "class=\"gen\">", $endArtist);
        $endDuration = strpos($content, "<", $startDuration);
        $duration = substr($content, $startDuration + 12, $endDuration - $startDuration - 12);

        $startQualityPart = strpos($content, "span", $endDuration);
        $startQuality = strpos($content, ">", $startQualityPart);
        $endQuality = strpos($content, "<", $startQuality);
        $quality = substr($content, $startQuality + 1, $endQuality - $startQuality - 1);

        $startGenrePart = strpos($content, "-&gt;", $endQuality);
        $startGenre = strpos($content, ">", $startGenrePart);
        $endGenre = strpos($content, "<", $startGenre);
        $genre = substr($content, $startGenre + 1, $endGenre - $startGenre - 1);

        $startAlbum = strpos($content, "<p>", $endGenre);
        $endAlbum = strpos($content, "<", $startAlbum + 2);
        $album = substr($content, $startAlbum + 3, $endAlbum - $startAlbum - 3);

        $startView = strpos($content, "<p>", $endAlbum);
        $endView = strpos($content, "<", $startView + 1);
        $view = substr($content, $startView + 3, $endView - $startView - 3);

        $onePartApi = array('link' => "$linkDownload", 'name' => "$name", 'artist' => "$artist", 'view' => "$view", 'quality' => "$quality", 'duration' => "$duration", 'genre' => "$genre", 'album' => "$album");

        $startLoop = $endView;

        return $onePartApi;

        // echo "rank : $rank<br>";
        // echo "avatar : $avatar<br>";
        // echo "link : $link<br>";
        // echo "name : $name<br>";
        // echo "artist : $artist<br>";
        // echo "view : $view<br>";
        // echo "quality : $quality<br>";
        // echo "index : $endQuality<br>";
    }

    function getLinkDownload($link){
        $content = @file_get_contents($link);
        $startFuntion = strpos($content, "decode_download_url");
        
        $startFirstPart = strpos($content, "\"", $startFuntion);
        $endFirstPart = strpos($content, "\"", $startFirstPart + 1);
        $firstPart = substr($content, $startFirstPart + 1, $endFirstPart - $startFirstPart - 1);

        $startMiddlePart = strpos($content, "\"", $endFirstPart + 1);
        $endMiddlePart = strpos($content, "\"", $startMiddlePart + 1);
        $middlePart = substr($content, $startMiddlePart + 1, $endMiddlePart - $startMiddlePart - 1);
        $middlePartDecode = decodeString($middlePart);

        $startLastPart = strpos($content, "\"", $endMiddlePart + 1);
        $endLastPart = strpos($content, "\"", $startLastPart + 1);
        $lastPart = substr($content, $startLastPart + 1, $endLastPart - $startLastPart - 1);

        $linkDownload = $firstPart.$middlePartDecode.$lastPart;
        return $linkDownload;
    }

    function decodeString($stringEncode){
        $encodeArray = array('U', 'W', 'J', 'H', 'D', 'G', 'M', 'A', 'Y', 'I', 'X', 'N', 'R', 'L', 'B', 'P', 'K');
        $decodeArray = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'c', 'u', 'f', 'r', '1', '1', '2');
        $count = count($encodeArray);

        for($i = 0; $i < $count; $i++){
            $charEncode = $encodeArray[$i];
            $charDecode = $decodeArray[$i];
            $stringEncode = str_replace($charEncode, $charDecode, $stringEncode);
        }
        return $stringEncode;
    }
?>

