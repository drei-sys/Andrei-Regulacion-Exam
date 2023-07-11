<?php
    $host = 'localhost';
    $dbName = 'youtube_db';
    $user = 'root';
    $pass = '';

    try{
        $conn = new PDO("mysql:host=$host;dbname=$dbName", $user, $pass);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "Connected successfully";
    }
    catch(PDOException $e){
        echo "Connection failed: " . $e->getMessage();
    }
    

    $key = "AIzaSyDrSvN5AFY4D2IjMo2EjWqXb9LC9E-Z1c8";
    $base_url = "https://www.googleapis.com/youtube/v3/";
    $channelId = "UCWJ2lWNubArHWmf3FIHbfcQ";

    $maxResult = 50;

    $API_URL_CHANNEL = $base_url . "channels?&part=snippet&id=".$channelId."&key=".$key;
    $API_URL_VIDEOS = $base_url . "search?order=date&type=video&part=snippet&channelId=".$channelId."&maxResults=".$maxResult."&key=".$key;
    
    $channel = json_decode( file_get_contents ( $API_URL_CHANNEL ) );
    $videos = json_decode( file_get_contents ( $API_URL_VIDEOS ) );

    $sql_channel = "INSERT INTO `youtube_channels`(`id`, `profile_picture`, `name`, `description`, `date`)
    VALUES (NULL, :cprofile, :cname, :cdescription, :cdate)";

    $stmtchannel = $conn->prepare($sql_channel);
    $stmtchannel->bindParam(":cprofile", $channel->items[0]->snippet->thumbnails->default->url);
    $stmtchannel->bindParam(":cname", $channel->items[0]->snippet->title);
    $stmtchannel->bindParam(":cdescription", $channel->items[0]->snippet->description);
    $stmtchannel->bindParam(":cdate", $channel->items[0]->snippet->publishedAt);

    $stmtchannel->execute();

    $nextPageToken = $videos->nextPageToken;

    foreach($videos->items as $video){
    
    $sql_videos = "INSERT INTO `youtube_channel_videos`(`id`, `video_link`, `title`, `description`, `thumbnail`, `date`)
    VALUES (NULL, :vlink, :vtitle, :vdescription, :vthumbnail, :vdate)";

    $stmtvideo = $conn->prepare($sql_videos);
    $videoLink = "https://www.youtube.com/watch?v=".$video->id->videoId;
    $stmtvideo->bindParam(":vlink", $videoLink);
    $stmtvideo->bindParam(":vtitle", $video->snippet->title);
    $stmtvideo->bindParam(":vdescription", $video->snippet->description);
    $stmtvideo->bindParam(":vthumbnail", $video->snippet->thumbnails->high->url);
    $stmtvideo->bindParam(":vdate", $video->snippet->publishedAt);

    $stmtvideo->execute();
}

    $API_URL_NEXTPAGE = $base_url . "search?order=date&part=snippet&channelId=".$channelId."&maxResults=".$maxResult."&type=video&pageToken=".$nextPageToken."&key=".$key;
    $nextPageContents = json_decode( file_get_contents ( $API_URL_NEXTPAGE ) );

    foreach($nextPageContents->items as $video){
    $sql_videos = "INSERT INTO `youtube_channel_videos`(`id`, `video_link`, `title`, `description`, `thumbnail`, `date`)
    VALUES (NULL, :vlink, :vtitle, :vdescription, :vthumbnail, :vdate)";

    $stmtvideo = $conn->prepare($sql_videos);
    $videoLink = "https://www.youtube.com/watch?v=".$video->id->videoId;
    $stmtvideo->bindParam(":vlink", $videoLink);
    $stmtvideo->bindParam(":vtitle", $video->snippet->title);
    $stmtvideo->bindParam(":vdescription", $video->snippet->description);
    $stmtvideo->bindParam(":vthumbnail", $video->snippet->thumbnails->high->url);
    $stmtvideo->bindParam(":vdate", $video->snippet->publishedAt);

    $stmtvideo->execute();
}
    
    

?>