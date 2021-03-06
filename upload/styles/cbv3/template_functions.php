<?php

/**
 * widget callback function to display user box..
 */

function displayUserBox($widget)
{
    return Fetch('widgets/user-box.html');     
}

/**
 * Fetch user0box for admin area
 * @param type $widget
 * @return type 
 */
function displayUserBoxAdmin($widget)
{
  
    Fetch('/layout/widgets/user-box-admin.html',FRONT_TEMPLATEDIR);
}

/**
 * outputs related videos widget....
 * 
 */
function displayRelatedVideos($widget)
{
    return Fetch('widgets/related-videos.html');  
}

/**
 * Get CBv3 Rating
 * @param type $video
 * @param type $type
 * @return string 
 */


function cbv3_rating($video,$type='perc')
{
    if($type=='perc')
    {
        $rating = $video['rating'];
        
        if($rating>5)
        {
            $rating_output = '<span class="rating-text rating-green">';
        }elseif($rating<5 && $rating)
        {
            $rating_output = '<span class="rating-text rating-red">';
        }else
        {
            $rating_output = '<span class="rating-text">';
        }
        
         $rating_output .= round($rating*10+0.49,0);
         $rating_output .= '%</span>';
         
         return $rating_output;
    }
    
    if($type=='video-bar')
    {
        assign('video',$video);
        
        if($video['rated_by']>0)
        {
            $rated_by = $video['rated_by'];
            $rating = $video['rating'];
            $rating_full = $rating*10;
            $likes = $rating_full*$rated_by/100;
            $likes = round($likes+0.49,0);
            
            $dislikes = $rated_by-$likes;
            
            assign('rating',array('rating'=>$rating,
                'dislikes'=>$dislikes,
                'likes'=>$likes,
                'rated_by'=>$rated_by,
                'rating_perc'=>$rating_full
                ));
        }
        
        
        return Fetch('blocks/rating.html');
    }
}


/**
 * Show-rating function for cbv3 template
 *  
 */
function cbv3_show_rating($rating)
{
    
    $array = array();
    if(error())
    {
        $array['err'] = error();
    }
    
    $array['rating'] = $rating;
    
    $rated_by = $rating['ratings'];
    $rating = $rating['rating'];
    $rating_full = $rating*10;
    $likes = $rating_full*$rated_by/100;
    $likes = round($likes+0.49,0);

    $dislikes = $rated_by-$likes;

    assign('rating',array('rating'=>$rating,
        'dislikes'=>$dislikes,
        'likes'=>$likes,
        'rated_by'=>$rated_by,
        'rating_perc'=>$rating_full
        ));
    
    $template = Fetch('blocks/rating.html');
    
    $array['template'] = $template;
    
    echo json_encode($array);
    
}

function cbv3_photo_tagger_options( $options ) {
    $options['labelWrapper'] = 'photo-tags';
    $options['buttonWrapper'] = 'photo-tagger-button';
    $options['addIcon'] = false;
    $options['autoComplete'] = true;

    return $options;
}

register_filter( 'tagger_configurations', 'cbv3_photo_tagger_options' );

function show_template_preview( $template ) {
//    global $cbtpl;
//    $name = $template['name'];
//    $preview = $cbtpl->get_preview_thumb( $template['dir'] );
//    
//    if ( $preview ) {
//        $name = "<span class='template-name'>$name</span>";
//        $preview = "<img src='$preview' class='template-thumb' />";
//        $author = "<span class='template-author'>".$template['author']."</span>";
//        
//        $template['name'] = $preview.$name.$author;
//    }
//    
//    return $template;
        $params['file'] = 'blocks/template_changer/item.html';
        $params['template'] = $template;
        
        return fetch_template_file( $params );
}

register_filter( 'template_selection_item', 'show_template_preview' );

function show_total_videos ( $name ) {
    global $usercontent;
    $user = $usercontent->get_current_user();
    $total_videos = number_format( $user['total_videos'] );
    return $name." <span>$total_videos</span>";
}

function show_total_photos ( $name ) {
    global $usercontent;
    $user = $usercontent->get_current_user();
    $total_videos = number_format( $user['total_photos'] );
    return $name." <span>$total_videos</span>";
}

function show_total_collections ( $name ) {
    global $usercontent;
    $user = $usercontent->get_current_user();
    $total_collections = number_format( $user['total_collections'] );
    return $name." <span>$total_collections</span>";
}

function show_total_subscribers( $name ) {
    global $usercontent;
    $user = $usercontent->get_current_user();
    return $name." <span>".number_format( $user['subscribers'] )."</span>";
}

function show_total_subscriptions ( $name ) {
     global $usercontent;
    $user = $usercontent->get_current_user();
    return $name." <span>".number_format( $user['total_subscriptions'] )."</span>";   
}

function show_total_friends( $name ) {
    global $usercontent, $userquery;
    $user = $usercontent->get_current_user();
    
    $total_contacts = $userquery->get_contacts( $user['userid'], 0, 'yes', true );
    return $name." <span>".number_format( $total_contacts )."</span>";  
}

register_filter( 'videos_name_filter', 'show_total_videos' );
register_filter( 'photos_name_filter', 'show_total_photos' );
register_filter( 'collections_name_filter', 'show_total_collections' );

register_filter('subscribers_name_filter', 'show_total_subscribers');
register_filter('subscriptions_name_filter', 'show_total_subscriptions');
register_filter('friends_name_filter', 'show_total_friends');
?>