<?php
if (!defined('included')) {
    exit();
}
$sql = "SELECT a.link_name, a.pagelink, a.main_content, a.updatedon FROM $ai_pagemaster a where a.linktype= 1 and a.status = 1 order by a.updatedon desc";
$result = $dbf->query($sql);
$rsCount = count($result);
if($rsCount > 0){
?> 
<rss version="2.0">
    <channel>
<?php  
		echo '<title>'.$host.' :: Latest Feeds</title>
		<link>https://'.$host.'</link>
		<description>Visit To Bengal</description>
		<language>en</language>'."\n";
	
		foreach($result as $rs1){
                    
                    
			
                    $description = strip_tags($rs1['main_content']);
				
				if(strlen($description) > 300 && @strpos($description, ' ', 300) > 0){					
                    $description = substr($description, 0, strpos($description, ' ', 300));
                }
		
		echo '<item>
			<title>'.htmlentities($rs1['link_name']).'</title>
			
			<link>'.$gen->make_link($rs1['pagelink'], '').'</link>
			
			<description>'.@htmlentities($description).'</description>			
			<pubDate>';			  
			
			  $datetime = $rs1['updatedon'];
			  echo date("D, d M Y H:i:s", strtotime($datetime)) . " +0530" ;

	  	echo '</pubDate>
			<author>'.$contactemail.'</author>
		</item>'."\n";

		}
                ?>

    </channel>
            
</rss>
<?php
}

?>