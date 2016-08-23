<?php
/*
 * Functions for OVH Cloud Public Storage website
 */

use \Ovh\Api;

function connectionToOVH () {
    /**
     * Instanciate an OVH Client.
     * You can generate new credentials with full access to your account on
     * the token creation page
     */
    $ovh = new Api( APPLICATION_KEY,  // Application Key
                    APPLICATION_SECRET,  // Application Secret
                    END_POINT,      // Endpoint of API OVH Europe (List of available endpoints)
                    CONSUMER_KEY); // Consumer Key

    return $ovh;
}

function getDownloadUrl ($ovhConnection) {
    return $ovhConnection->post("/cloud/project/".SERVICE_NAME."/storage/access");
}

function getFilesList ($ovhConnection) {
    try {
//        echo "Welcome " . $ovhConnection->get('/me')['firstname'];
        $result = $ovhConnection->get(PROJECT_URL);
//        $staticUrl = getDownloadUrl($ovhConnection);
        $region = $result["region"];
        $name = $result["name"];

        $staticUrl = "https://storage.".$region.".cloud.ovh.net/v1/AUTH_".SERVICE_NAME."/".$name;

        $filesList = array();

//        echo "My objects :)";

        $directory = "";

        $i = 0;
        foreach ($result["objects"] as $result) {
            if (strcmp($result["contentType"], "application/directory") == 0) {
                $directory = $result["name"];
            } elseif (strcmp($result["contentType"], "application/x-iso9660-image") == 0) {
                $filesList[$i]["folder"]=$directory;
                $filesList[$i]["size"]=FileSizeConvert($result["size"]);
                list($filesList[$i]["name"]) = sscanf( $result["name"], $directory."/%s");
                $filesList[$i]["lastModified"] = date_format(new DateTime($result["lastModified"]), "d-m-Y H:i");
                $filesList[$i]["url"] = $staticUrl."/".$directory."/".$filesList[$i]["name"];
            }

            $i++;
        }
        return $filesList;

    } catch (GuzzleHttp\Exception\ClientException $e) {
        $response = $e->getResponse();
        $responseBodyAsString = $response->getBody()->getContents();
        echo $responseBodyAsString;
    }

    return "";
}

/**
 * Converts bytes into human readable file size.
 *
 * @param string $bytes
 * @return string human readable file size (2,87 Мб)
 * @author Mogilev Arseny
 */
function FileSizeConvert($bytes)
{
    $bytes = floatval($bytes);
    $arBytes = array(
        0 => array(
            "UNIT" => "TB",
            "VALUE" => pow(1024, 4)
        ),
        1 => array(
            "UNIT" => "GB",
            "VALUE" => pow(1024, 3)
        ),
        2 => array(
            "UNIT" => "MB",
            "VALUE" => pow(1024, 2)
        ),
        3 => array(
            "UNIT" => "KB",
            "VALUE" => 1024
        ),
        4 => array(
            "UNIT" => "B",
            "VALUE" => 1
        ),
    );

    foreach($arBytes as $arItem)
    {
        if($bytes >= $arItem["VALUE"])
        {
            $result = $bytes / $arItem["VALUE"];
            $result = str_replace(".", "," , strval(round($result, 2)))." ".$arItem["UNIT"];
            break;
        }
    }
    return $result;
}


?>