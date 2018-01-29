<?php
    $website = 'http://chiasenhac.vn'; 
    $csnApi = getlink($website);

    // $array = array(array('rank' => '1', 'name' => 'my love'), array('rank' => '2', 'name' => 'evergreen'));
    echo json_encode($csnApi, JSON_UNESCAPED_UNICODE);

    function getlink($website){
        $content = @file_get_contents($website);
        $csnApi = array();
        $startLoop = 0;

        for ($i = 0; $i < 10; $i++) {
            $startContent = strpos($content, "class=\"li-3\"", $startLoop);
            $onePartApi = findAllObjectInAPart($content, $startContent, $startLoop);
            $csnApi[] = $onePartApi;
        }

        // while($startContent = strpos($content, "list-r list-1", $startLoop)) {
        //     $onePartApi = findAllObjectInAPart($content, $startContent, $startLoop);
        //     $csnApi[] = $onePartApi;
        // }
        return $csnApi;
    }

    function findAllObjectInAPart($content, $startContent, &$startLoop){
        $startRank = strpos($content, "<p>", $startContent);
        $endRank = strpos($content, "<", $startRank + 1);
        $ranger = $endRank - $startRank;
        if ($ranger == 3) {
            $startRank = strpos($content, "topranks", $startContent);
            $endRank = strpos($content, "<", $startRank);
            $rank = substr($content, $startRank + 10, $endRank - $startRank - 10);
        }else {
            $rank = substr($content, $startRank + 3, $endRank - $startRank - 3);
        }
        
        $startAvatar = strpos($content, "img src=", $endRank);
        $endAvatar = strpos($content, "\"", $startAvatar + 12);
        $avatar = substr($content, $startAvatar + 9, $endAvatar - $startAvatar - 9);

        $startLink = strpos($content, "a href=", $endAvatar);
        $endLink = strpos($content, "\"", $startLink + 10);
        $link = substr($content, $startLink + 8, $endLink - $startLink - 8);
        $linkDownload = getLinkDownload($link);

        $startName = strpos($content, ">", $endLink);
        $endName = strpos($content, "<", $startName);
        $name = substr($content, $startName + 1, $endName - $startName - 1);

        $startArtist = strpos($content, ">", $endName + 8);
        $endArtist = strpos($content, "<", $startArtist);
        $artist = substr($content, $startArtist + 1, $endArtist - $startArtist - 1);

        $startView = strpos($content, "<p>", $endArtist);
        $endView = strpos($content, "<", $startView + 1);
        $view = substr($content, $startView + 3, $endView - $startView - 3);

        $startQualityPart = strpos($content, "<p>", $endView);
        $startQuality = strpos($content, ">", $startQualityPart + 5);
        $endQuality = strpos($content, "<", $startQuality);
        $quality = substr($content, $startQuality + 1, $endQuality - $startQuality - 1);

        $onePartApi = array('rank' => "$rank", 'avatar' => "$avatar", 'link' => "$linkDownload", 'name' => "$name", 'artist' => "$artist", 'view' => "$view", 'quality' => "$quality");

        $startLoop = $endQuality;

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

