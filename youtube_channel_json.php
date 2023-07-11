<?php
    $host = 'localhost';
    $dbName = 'youtube_db';
    $user = 'root';
    $pass = '';

    try{
        $conn = new PDO("mysql:host=$host;dbname=$dbName", $user, $pass);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e){
        echo "Connection failed: " . $e->getMessage();
    }

    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $limit = 20; 

    $offset = ($page - 1) * $limit;

    $count_videos = "SELECT COUNT(*) AS total FROM youtube_channel_videos";
    $count_vid_result = $conn->prepare($count_videos);
    $count_vid_result->execute();
    $total_videos = $count_vid_result->fetch(PDO::FETCH_ASSOC)['total'];
    
    $total_pages = ceil($total_videos / $limit);

    $sql_channel_query = "SELECT * FROM youtube_channels limit 1";
	$stmt_channel = $conn->prepare($sql_channel_query);

    header('Content-Type: application/json');

	$stmt_channel->execute();

    $sql_video_query = "SELECT * FROM youtube_channel_videos LIMIT $limit OFFSET $offset";
    $stmt_video = $conn->prepare($sql_video_query);
    $stmt_video->execute();

    $channel_fetch = $stmt_channel->fetch(PDO::FETCH_ASSOC);

    $video_fetch = $stmt_video->fetchAll(PDO::FETCH_ASSOC);
   
    $response = array (
        'channel' => $channel_fetch,
        'videos' => $video_fetch,
        'totalPages' => $total_pages  
    );

    echo json_encode($response);

    
?>