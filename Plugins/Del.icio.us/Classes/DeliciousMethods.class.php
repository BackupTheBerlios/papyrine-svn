<?php
class DeliciousMethods {
  var $baseURL;
  var $ch;
  var $methodResult;
  
  function DeliciousMethods($user,$pass)
  {
    $this->user = $user;
    $this->pass = $pass;
    $this->baseURL = 'http://del.icio.us';
  }
  
  function execute($apiMethod)
  {
    //print $apiMethod; exit;
    $this->apiMethodHistory[] = $apiMethod;
    $this->ch = curl_init( "{$this->baseURL}/api/{$apiMethod}");
    curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($this->ch, CURLOPT_USERPWD, "{$this->user}:{$this->pass}");
    curl_setopt($this->ch, CURLOPT_USERAGENT, "DeliciousPHP/0.100 (" . PHP_OS . "/PHP-" . phpversion() . ")");
    $this->methodResult = curl_exec($this->ch);
    $this->methodResultInfo = curl_getinfo($this->ch);
  }
  
  /**
   *	Returns a list of dates with the number of posts at each date. 
   *	[tag] adds a filter
   */
  function dates($tag=false)
  {
    if ($tag) $this->execute("posts/dates?tag=$tag");
    else $this->execute("posts/dates");
    return $this->methodResult;
  }
  
  /**
   * returns a list of tags the user has used.
   */
  function getTags()
  {
    $this->execute("tags/get");
    return $this->methodResult;
  }
  
  /**
   *returns a list of posts on a given date, filtered by tag. if no date is supplied, most recent date will be used
   * &tag= filter by this tag - optional
   * &dt= filter by this date
   */
  function getPosts($tag=false,$dt=false)
  {
    $this->execute( "posts/get" 
		    . (($dt or $tag) ? "?" : '')
		    . (($dt) ? "dt={$dt}" : '')
		    . (($dt and $tag) ? "&" : '')
		    . (($tag) ? "tag={$tag}" : ''));
    return $this->methodResult;
  }
  
  /**
   * returns a list of most recent posts, possibly filtered by tag.
   *
   * &tag= filter by this tag - optional
   * &count= number of items to retrieve - optional (defaults to 15)
   */
  function recentPosts($tag=null,$count=null)
  {
    $this->execute("posts/recent" 
		   . (($tag or $count) ? "?" : '')
		   . (($tag) ? "tag=$tag" : '')
		   . (($tag and $count) ? '&' : '')
		   . (($count) ? "count=$count" : ''));
    return $this->methodResult;
  }
 
  /**
   * makes a post to delicious
   *
   * &url= url for post
   * &description= description for post
   * &extended= extended for post
   * &tags= space-delimited list of tags
   * &dt= datestamp for post, format "CCYY-MM-DDThh:mm:ssZ"
   */
  function addPost($url,$dt=false,$description=false,$extended=false,$tags=false)
  {
    $this->execute("posts/add?url=" . urlencode($url)
		   . "&dt=" . (($dt) ? urlencode($dt) : urlencode(strftime("%Y-%m-%dT%TZ")))
		   . "&description=" . (($description) ? urlencode($description) : '%20')
		   . "&extended=" . (($extended) ? urlencode($extended) : '%20')
		   . "&tags=" . (($tags) ? urlencode($tags) : '%20'));
    return $this->methodResult;
  }
  
  /**
   * renames tags across all posts
   *
   * &old= old tag
   * &new= new tag
   */
  function renameTag($old,$new)
  {
    $this->execute("tags/rename?old=$old&new=$new");
    return $this->methodResult;
  }
  
  /**
   * returns a list of inbox entries
   *
   * &dt= filter by this date
   */
  function getInbox($dt=false)
  {
    if ($dt) $this->execute("inbox/get?dt=$dt");
    else $this->execute("inbox/get");
    return $this->methodResult;
  }
  
  /**
   * returns a list of dates containing inbox entries
   */
  function inboxDates()
  {
    $this->execute("inbox/dates");
    return $this->methodResult;
  }
  
  /**
   * returns a list of your subscriptions
   */
  function inboxSubs()
  {
    $this->execute("inbox/subs");
    return $this->methodResult;
  }
  
  /**
   * adds a subscription
   *
   * &user= username
   * &tag = tag - optional, leave blank for all posts
   */
  function addSub($user,$tag=false)
  {
    if ($tag) $this->execute("inbox/sub?user=$user&tag=$tag");
    else $this->execute("inbox/sub?user=$user");
    return $this->methodResult;
  }
  
  /**
   * removes a subscription
   *
   * &tag = tag - optional, leave blank for all posts
   * $user= username
   */
  function unSub($user,$tag=false)
  {
    if ($tag) $this->execute("inbox/unsub?user=$user&tag=$tag");
    else $this->execute("inbox/unsub?user=$user");
    return $this->methodResult;
  }
}
?>
