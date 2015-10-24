<?php

class fix_gawker_embeds extends Plugin {
	private $host;
	private $dbh;

	function about(){
		return array(1.0,
			'This plugin fixes the problem with embeded content in Gawker\'s various feeds.',
			'nowotny');
	}

	function init($host){
		$this->host = $host;
		$this->dbh = Db::get();

		$host->add_hook($host::HOOK_ARTICLE_FILTER, $this);
		$host->add_hook($host::HOOK_PREFS_TAB, $this);
	}
	
	function hook_prefs_tab($args){
		
		if( $args != 'prefPrefs' ) return;

		echo '<div dojoType="dijit.layout.AccordionPane" title="'. __('fix_gawker_embeds') .'">
					<p>'. __('Clicking the button below will apply the fixes to already downloaded and yet unread articles. Note that this can take a long time depending on the number of unread articles. This means that the script may timeout before it finishes applying the fixes. Right now, the only solution in this situation is to increase the time limit in <a href="https://www.google.com/search?q=php+set+time+limit">PHP configuration file</a> or in <a href="https://www.google.pl/search?q=.htaccess+php_value+set_time_limit">.htaccess file</a>.') .'</p>
					<button type="button" onClick="return fix_gawker_embeds_apply(this)">'. __('Apply fixes to already downloaded articles') .'</button><img src="images/indicator_white.gif" id="fix_gawker_embeds_indicator" style="vertical-align: middle; visibility: hidden;"/>
				</div>';
				
		return;
	}
	
	function get_prefs_js() {
		return file_get_contents(__DIR__ . '/init.js');
	}
	
	function fix_gawker_embeds_apply(){
		
		$result = $this->dbh->query('SELECT `id`, `link`, `content` FROM `ttrss_entries`, `ttrss_user_entries` WHERE `ref_id` = `id` AND `unread` = 1 AND `owner_uid` = ' . $_SESSION['uid']);
		if( $this->dbh->num_rows($result) > 0 ){
			while( $article = $this->dbh->fetch_assoc($result) ){
				
				$article = $this->make_fixes($article);
				
				$r = $this->dbh->query('UPDATE `ttrss_entries` SET `content` = "'. $this->dbh->escape_string($article['content'], false) .'" WHERE `id` = '.$article['id']);
						
			}
		}
		
	}
	
	function make_fixes($article){
				
		if ( strpos($article['content'], 'src="/ajax/inset/iframe?id=') !== false ){
			
			# Replace the YouTube embed with the original
			
			$article['content'] = preg_replace('#src="/ajax/inset/iframe\?id=youtube-video-([^&]+)[^"]+"#i', 'src="https://www.youtube.com/embed/$1"', $article['content']);
			
		}
		
		
		return $article;
	}
	
	
	function hook_article_filter($article){

		$article = $this->make_fixes($article);
		
		return $article;
		
	}

	function api_version(){
		return 2;
	}

}
?>
