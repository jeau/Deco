<?php
// get user-configured background image or pattern
function deco_body_bg(){
	if(get_theme_option('bg_img')!='none'){
		//There's not currently a good reason to separate CSS and IMG options, but...
	
		$img=array('paperlight','greenlit','fabric_grey','graph','grungewood','whitewood','wood');
		$css=array('tartan','madras','blueprint');
		
		if(array_search(get_theme_option('bg_img'),$img)!==false){
			$bgimg=get_theme_option('bg_img');
			return $bgimg;
			
		}elseif(array_search(get_theme_option('bg_img'),$css)!==false){
			$bgclass=get_theme_option('bg_img');
			return $bgclass;
		}		
		
	}
}

// custom display docs viewer
function deco_docs_viewer($item,$files=null){
		if (
			(get_theme_option('Docs Viewer Placement')=='yes')
			&&( 
				(plugin_is_active('DocsViewer','2.0'))
				&&(get_option('docsviewer_embed_public')==0) 
			)
			) {
		foreach (loop('files', $item->Files) as $file){
			$files[] = $file;	
		}	
		$view=get_view();
		echo $view->docsViewer($files);
		}			
}


// custom display images
function deco_image($file,$mime,$img,$index){

		$caption = (metadata($file,array('Dublin Core', 'Title'))) ? metadata($file, array('Dublin Core', 'Title')) : metadata('Item',array('Dublin Core', 'Title'));
		
		// images: the first one uses fullsize
		if (($file->hasThumbnail()&&($index == 0)))
		echo file_markup($file, array('linkToFile'=>true,'imageSize'=>'fullsize','linkAttributes'=>array('rel'=>'fancy_group', 'class'=>'fancyitem','title' => $caption)),array('class' => 'fullsize', 'id' => 'item-image'));
		// images: the rest use the thumbnails
		elseif (($file->hasThumbnail()&&($index !== 0)))
		echo file_markup($file, array('imageSize'=>'square_thumbnail', 'linkToFile'=>true,'linkAttributes'=>array('rel'=>'fancy_group', 'class'=>'fancyitem','title' => $caption)),array('class' => 'square_thumbnail'));
			
		$index++;	
}

// custom display video
function deco_videojs($file,$videoIndex,$mime,$videoJS_h264,$videoJS_webM,$videoJS_ogg){
		echo '<div class="video-js-box">';
		
		echo '<video id="htmlvideo-'.$videoIndex.'" class="video-js" width="100%" controls preload="none" poster="'.img('vid-poster.jpg').'" data-setup="{}">';
			echo '<source src="'.file_display_url($file,'original').'" ';
			
			// getting the video MIME Types
			if (array_search($mime,$videoJS_h264) !== false) echo 'type=\'video/mp4; codecs="avc1.42E01E, mp4a.40.2"\' />';
			elseif (array_search($mime,$videoJS_webM) !== false) echo 'type=\'video/webm; codecs="vp8, vorbis"\' />';
			elseif (array_search($mime,$videoJS_ogg) !== false) echo 'type=\'video/ogg; codecs="theora, vorbis"\' />';
			
			// the Flash fallback
			echo '<object id="flashvideo_'.$videoIndex.'" class="vjs-flash-fallback" scaling="fit" width="500" height="282" type="application/x-shockwave-flash" data="'.url('').'themes/deco/common/flowplayer/flowplayer-3.2.7.swf">';
				echo '<param name="movie" value="'.url('').'themes/deco/common/flowplayer/flowplayer-3.2.7.swf" />';
				echo '<param name="allowfullscreen" value="true" />';
				echo '<param name="flashvars" value=\'config={"playlist":["'.img('vid-poster.jpg').'", {"url": "'.file_display_url($file,'original').'","autoPlay":false,"autoBuffering":true}]}\' />';
				// the static image fallback as last resort
				echo '<img src="'.img('vid-poster.jpg').'" scale="tofit" width="600" height="338" alt="Poster Image" title="No video playback capabilities." />';
			echo '</object>';
		echo '</video>';
		echo deco_video_ResponsifyVideoScript($videoIndex);
		echo '</div></br><br/>';
		$videoIndex++;	
}

// get user-configured fonts for header call to Google Web Fonts API
function deco_get_fonts(){
	$primary=get_theme_option('decofont');
	$secondary=get_theme_option('headfont');
	
	$fonts='';
	
	$fonts .= ($primary != 'none') ? $primary : false;
	$fonts .= ($secondary != 'none') ? ( ($primary!=='none')?'|':'' ).$secondary : false;
	
	return (strlen($fonts)>0) ? $fonts : false;
}
// format the font names for use in CSS styles
function deco_fonts_for_css($which){
	$primary=(get_theme_option('decofont')!=='none') ? 'font-family: '.get_theme_option('decofont') : '';
	$secondary=(get_theme_option('headfont')!=='none') ? 'font-family: '.get_theme_option('headfont') : '';	
	
	$primary = str_replace("+"," ", $primary);
	$secondary = str_replace("+"," ", $secondary);
	
	switch ($which) {
	    case 'primary':
	        return $primary;
	        break;
	    case 'secondary':
	        return $secondary;
	        break;
	    default:
	       return "";
	}		

}

function bp_simple_search($html='',$buttonText = "Search", $formProperties=array('class'=>'simple-search'), $uri = null)
{
    // Always post the 'items/browse' page by default (though can be overridden).
    if (!$uri) {
        $uri = url('items/browse');
    }
    
    $searchQuery = array_key_exists('search', $_GET) ? $_GET['search'] : '';
    $formProperties['action'] = $uri;
    $formProperties['method'] = 'get';
    $html = '<form ' . tag_attributes($formProperties) . '>' . "\n";
    $html .= '<fieldset>' . "\n\n";
    $html .= get_view()->formText('search', $searchQuery, array('name'=>'search','class'=>'textinput','placeholder'=>'Search items'));
    $html .= get_view()->formSubmit('submit_search', $buttonText);
    $html .= '</fieldset>' . "\n\n";
    
    // add hidden fields for the get parameters passed in uri
    $parsedUri = parse_url($uri);
    if (array_key_exists('query', $parsedUri)) {
        parse_str($parsedUri['query'], $getParams);
        foreach($getParams as $getParamName => $getParamValue) {
            $html .= get_view()->formHidden($getParamName, $getParamValue);
        }
    }
    
    $html .= '</form>';
    return $html;
}


function deco_exhibit_builder_nested_nav($exhibitPage = null){
	if (!$exhibitPage) {
	    $exhibitPage = get_current_record('exhibit_page', false);
	}

	$exhibit = $exhibitPage->getExhibit();
	$topPages = $exhibit->getTopPages();
	$currentPage = $exhibitPage->id;
	$addClass=' class="current" ';
	    
	$html = '<ul>';
	foreach($topPages as $page){
	
		$html .= '<li'.__(($page->id == $currentPage) ? $addClass : "").'><a href="'.exhibit_builder_exhibit_uri($exhibit, $page).'">'.$page->title.'</a>';
		
		$children=exhibit_builder_child_pages($page);
		if($children){
			$html .= '<ul>';
			foreach($children as $child){
				$html .= '<li'.__(($child->id == $currentPage) ? $addClass : "").'><a href="'.exhibit_builder_exhibit_uri($exhibit, $child).'">'.$child->title.'</a></li>';
				$grandchildren=exhibit_builder_child_pages($child);
				if($grandchildren){
					$html .= '<ul>';
				foreach($grandchildren as $grandchild){
					$html .= '<li'.__(($grandchild->id == $currentPage) ? $addClass : "").'><a href="'.exhibit_builder_exhibit_uri($exhibit, $grandchild).'">'.$grandchild->title.'</a></li>';
				}
					$html .= '</ul>';
				}
			}
			$html .= '</ul>';
		}
		$html .='</li>';
	}
	$html .= '</ul>';
	
	return $html;
}



function deco_exhibit_builder_pagination_nav($exhibitPage = null,$summaryPage=false){
	if (!$exhibitPage) {
	    $exhibitPage = get_current_record('exhibit_page', false);
	}

	$exhibit = $exhibitPage->getExhibit();
	$topPages = $exhibit->getTopPages();
	$currentPage = $exhibitPage->id;
	$addClass=' class="current" ';
	$index=0;
	    
	$html = '<nav class="pagination"><ul>';
	
	$html .= ($summaryPage!==true) ? '<li>'.link_to_exhibit('Summary').'</li>' : '';
	$html .= ( ($summaryPage!==true) && ($prevLink = exhibit_builder_link_to_previous_page('Previous')) ) ? '<li>'.$prevLink.'</li>' : '' ;
	
	foreach($topPages as $page){
		$index++;
		$html .= '<li'.__(($page->id == $currentPage) ? $addClass : "").'><a title="'.$page->title.'" href="'.exhibit_builder_exhibit_uri($exhibit, $page).'">'.$index.'</a>';
		
		$children=exhibit_builder_child_pages($page);
		if($children){
			foreach($children as $child){
				$index++;
				$html .= '<li'.__(($child->id == $currentPage) ? $addClass : "").'><a title="'.$child->title.'" href="'.exhibit_builder_exhibit_uri($exhibit, $child).'">'.$index.'</a></li>';
				$grandchildren=exhibit_builder_child_pages($child);
				if($grandchildren){
				foreach($grandchildren as $grandchild){
					$index++;
					$html .= '<li'.__(($grandchild->id == $currentPage) ? $addClass : "").'><a title="'.$grandchild->title.'"href="'.exhibit_builder_exhibit_uri($exhibit, $grandchild).'">'.$index.'</a></li>';
					}
				}
			}
		}
		$html .='</li>';
	}
	$html .= ( ($summaryPage!==true) && ($nextLink = exhibit_builder_link_to_next_page('Next')) ) ? '<li>'.$nextLink.'</li>' : '' ;
	$html .= '</ul></nav>';
	
	return $html;
}




function deco_homepage_gallery_items(){

			$items = get_random_featured_items(10);
			if ($items!=null) 
			{	
			$html = '';	
			foreach ($items as $item): 
				if (metadata($item, 'has thumbnail')){
					set_current_record('item',$item);
					$file=item_image('fullsize',$item);
					$src = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $file , $matches);
					$first_img = $matches[1][0];
					$title=metadata($item,array('Dublin Core', 'Title'));
	
			    	       $html .= '<div class="main">';
			    	       	   $html .= '<img src="'.$first_img.'">';
				    	       $html .=  '<div class="caption">';
				    	       $html .=  '<h3>'.link_to($item,'show',$title).'</h3>';
				    	       $html .=  '<p>'.metadata($item,array('Dublin Core', 'Description'),array('snippet'=>190));
				    	       $html .=  link_to($item,'show',' ...more').'</p>';
				    	       $html .= '</div>'; 
			    	       $html .='</div>';
	
				}
			endforeach; 
			return $html;
			}
    	
}

function deco_homepage_gallery(){
	if (get_theme_option('Featured Image Gallery') == 'yes'){
?>			
        	<style type="text/css">
				.swipe {
				  overflow: hidden;
				  visibility: hidden;
				  position: relative;
				}
				.swipe-wrap {
				  overflow: hidden;
				  position: relative;
				}
				.swipe-wrap > div {
				  float:left;
				  width:100%;
				  position: relative;
				}        	
        	</style>
			<h2 class="gallery">Featured Items</h2>
			<div id='slider' class='swipe'>
			  <div class='swipe-wrap'>
			    <?php echo deco_homepage_gallery_items();?>
			  </div>
			</div>
			
			<div id="slide-nav">
				<button class="prev icon-angle-left" onclick="mySwipe.prev()"><span>prev</span></button>
				<button class="next icon-angle-right" onclick="mySwipe.next()"><span>next</span></button>
			</div>			
<?php
}
}

/*
** Script to resize the video based on desired aspect ratio and browser viewport
** This basically iterates a separate action for each video (see mh_video_files() loop above),
** which is not very efficient, but having more than one video per record is probably not very common
*/
function deco_video_ResponsifyVideoScript($videoIndex, $aspectRatio='360/640'){
?>
	<script>
	var vidCount=<?php echo $videoIndex; ?>;
	var aspectRatio=<?php echo $aspectRatio; ?>;
	for (var i=1;i<=vidCount;i++){
		var vidID = "#htmlvideo-"+i+"";
		_V_(vidID).ready(function(i){
			var myVid = this;
			function resizeVideoJS(i){
				var width = document.getElementById(myVid.id).parentElement.offsetWidth;
				myVid.width(width).height( width * aspectRatio );
			}
			resizeVideoJS(i);
			var $window = jQuery(window);
			jQuery($window).resize(resizeVideoJS);
		});
	}
	
	</script>
<?php
}

//extends featured exhibit function to include snippet from description and read more link
function deco_exhibit_builder_display_random_featured_exhibit($num=1){

  if (function_exists('exhibit_builder_link_to_exhibit')){
		$featuredExhibit=get_records('Exhibit', array('featured'=>true),$num);
		$html ='';
		if ($featuredExhibit){
			
			foreach( $featuredExhibit as $exhibit){
				$html .= '<div class="featured-exhibit">';
				$html .= '<h2>Featured Exhibit</h2>';
				
				$html .= '<h3>' . exhibit_builder_link_to_exhibit($exhibit) . '</h3>';
				
				$snippetlink = '&nbsp;'.exhibit_builder_link_to_exhibit($exhibit,'... Continue Reading.').'';
				$html .= '<p>' . snippet($exhibit->description, 0, 600,$snippetlink) . '</p>';
				
				$html .= '</div>';
			}
		}else{
			$html .= '<p>You have no featured exhibits.</p>';
		}
		return $html;
  } 



} 


/**
 * This function returns the tagline for the theme.  
 *
 **/

function deco_get_tagline($tagline = null)
{    
    if (!$tagline) {
        
        $tagline = get_theme_option('Tagline') ? 
        get_theme_option('Tagline') : 
        'Add a tagline for your site in theme options';
    }
    
    return $tagline; 
    
}
/**
 * This function returns the homepage about text for the theme.  
 *
 **/

function deco_get_about($about = null)
{    
    if (!$about) {
        
        $about = get_theme_option('About') ? 
        get_theme_option('About') : 
        'Add some text about your site in theme options. You can use HTML!';
    }
    
    return '<div id="site-description"><h2>About</h2><h3>'.option('site_title').'</h3>'.$about.'</div>'; 
    
}
/**
 * This function returns the number of recent items to display on the homepage for the theme.  
 *
 **/
function deco_get_recent_number($recentItems = null)
{    
    if (!$recentItems) {
        
        $recentItems = get_theme_option('Recent Items') ? 
        get_theme_option('Recent Items') : 
        '5';
    }
    
    return $recentItems; 
    
}
/**
 * This function returns the theme credits settings, displayed in the footer for the theme.  
 *
 **/

function deco_display_theme_credit(){
		$theme_credit=get_theme_option('Theme Credit');
		$credit_text=' | <a href="https://github.com/ebellempire/Deco" title="Deco theme on Github">Deco</a> theme by <a href="http://erinbell.org" title="ErinBell.org">E. Bell</a>';
		if ($theme_credit == 'yes')return $credit_text;
}


/**
 * This function returns the FancyBox (lightbox) settings for the theme
 * if the user has not turned them off in theme settings
 *
 **/
 
function deco_fancybox(){
		$fancybox_setting=get_theme_option('Fancy Box');
		if ($fancybox_setting == 'yes') echo js('fancybox/fancybox-init-config');
		}


/**
 * This function returns the random featured collection settings for the theme.  
 *
 **/
//this is the function that toggles the Collection Thumbs
function deco_collection_thumbs_number($num=4){
		$collection_thumbs_setting=get_theme_option('Collection Thumbs');
		if ($collection_thumbs_setting == 'yes'){
			
			echo '<div id="index-collection-img">';
    	    $coll=get_current_record('Collections',false)->id;
    	    
    	    $items=get_records('item',array('hasImage'=>true,'collection'=>$coll),$num);
    	    if(count($items)>=$num){
	        set_loop_records('items', $items);
	        if (has_loop_records('items')){
		        foreach (loop('items') as $item){
		        	echo link_to_item(item_image('square_thumbnail'));
		        	}
	        }
	        }
			echo'</div>';
			
		} 
}
		
function deco_random_featured_collection(){
			$collection=get_db()->getTable('Collection')->findRandomFeatured();
	        if ($collection){
	        set_current_record('collection', $collection);
					echo '<h2>Featured Collection</h2>';
					echo '<h3>'.link_to_collection().'</h3>';
					echo '<p>'.metadata($collection,array("Dublin Core","Description"),array('snippet'=>750)).'</p>';
					echo deco_collection_thumbs_number();
			}else{
        		echo'<p><em>There are no featured collections right now. You should turn off "Display Featured Collections" in the theme settings until you have some.</em></p>';
    		}
}

//this is the function that is actually used on homepage...
function deco_display_random_featured_collection(){
		$random_featured_collection_setting=get_theme_option('Random Featured Collection') ? get_theme_option('Random Featured Collection') : 'yes';
		if ($random_featured_collection_setting == 'yes')return deco_random_featured_collection();
}

// this function uses Zend_Feed to fetch and display an RSS or Atom feed
// example usage, to display one post from omeka.org --> echo deco_display_rss('http://omeka.org/feed/',1) 
function deco_display_rss($feedUrl, $num = 3) {
    try {
        $feed = Zend_Feed_Reader::import($feedUrl);
    } catch (Zend_Feed_Exception $e) {
        echo '<p>Feed not available.</p>';
        return;
    }

    $posts = 0;
    foreach ($feed as $entry) {
        if (++$posts > $num) break;
        $title = $entry->getTitle();
        $link = $entry->getLink();
        $description = $entry->getDescription();
        echo "<p class='feed-title'><a href=\"$link\">$title</a></p>"
           . "<p class='feed-content'>$description <a href=\"$link\">...more</a></p>";
    }
}
function deco_nav(){
		$navArray = array(
		array('label'=>'All', 'uri'=>url('items/browse')),
		array('label'=>'Tags', 'uri'=>url('items/tags')),
		array('label'=>'Search', 'uri'=>url('items/search'))
		);
		if(plugin_is_active('geolocation')){
			array_push($navArray,array('label'=>'Map', 'uri'=>url('geolocation/map/browse')));
		}
		return nav($navArray);	
}