<?php
require_once('DeliciousMethods.class.php');

class DeliciousData {
  var $DeliciousMethods;
  var $DomOptions;
  function DeliciousData($user,$pass)
  {
    // FIXED: Had a syntax error here. Thanks for the heads up
    // goes out to Bowen Dwelle - http://www.dwelle.org
    $this->DeliciousMethods = new DeliciousMethods($user,$pass);
    $this->DomOptions = DOMXML_LOAD_PARSING
      + DOMXML_LOAD_COMPLETE_ATTRS
      + DOMXML_LOAD_SUBSTITUTE_ENTITIES
      + DOMXML_LOAD_DONT_KEEP_BLANKS;
    
  }
  
  /**
   * Gets total number of posts per date.
   * Returns in an associative array
   * Accepts (optional) argument $tag for filtering per tag-type.
   */
  function dateTotal($tag=false)
  {
    $dateTotal = array();
    $xml_string = domxml_open_mem($this->DeliciousMethods->dates($tag),$this->DomOptions);
    $root_node = $xml_string->document_element();
    foreach($root_node->child_nodes() as $child_node) {
      $dateTotal[] = array('date' => $child_node->get_attribute('date'), 'count' => $child_node->get_attribute('count'));
    }
    return $dateTotal;
  }
  
  /**
   * Gets all of the tags used by the user and their linkcounts.
   * Returns in an associative array
   */
  function allTags()
  {
    $allTags = array();
    $xml_string = domxml_open_mem($this->DeliciousMethods->getTags(),$this->DomOptions);
    $root_node = $xml_string->document_element();
    foreach($root_node->child_nodes() as $child_node) {
      $allTags[] = array('tag' => $child_node->get_attribute('tag'), 
			 'count' => $child_node->get_attribute('count'));
    }
    return $allTags;
    
  }
  
  /**
   * Gets a list of posts by a certain date, filterable by tag. If no date
   * is specified, most recent date is used.
   * Returns an associative array. print_r is your friend.
   */
  function postsByDate($tag=false,$dt=false)
  {
    $posts = array();
    $xml_string = domxml_open_mem($this->DeliciousMethods->getPosts(
								    (($tag) ? $tag : null),
								    (($dt) ? $dt : null)),
				  $this->DomOptions);
    $root_node = $xml_string->document_element();
    foreach($root_node->child_nodes() as $child_node) {
      $posts[] = array('tags' => explode(" ",trim($child_node->get_attribute('tag'))),
		       'time' => $child_node->get_attribute('time'),
		       'href' => $child_node->get_attribute('href'),
		       'description' => $child_node->get_attribute('description'));
    }
    return $posts;
  }
  
  /**
   * Add post. As of now $url and $description are the only required args,
   * though, technicallly, DeliciousMethods will take a null $description
   * arg - it's better to always provide the desc. Also you can backdate the
   * post and all that.
   */
  function addPost($url,$description,$dt=false,$extended=false,$tags=false)
  {
    $return = $this->DeliciousMethods->addPost($url,$dt,$description,$extended,$tag);
    return $return;
  }
  
  /**
   * Return the last $count posts, filterable by tag.
   * Returns in an associative array.
   */
  function postsByCount($tag=false,$count=false)
  {
    $posts = array();
    $xml_string = domxml_open_mem($this->DeliciousMethods->recentPosts(
								       (($tag) ? $tag : null),
								       (($count) ? $count : null)),
				  $this->DomOptions);
    $root_node = $xml_string->document_element();
    foreach($root_node->child_nodes() as $child_node) {
      $posts[] = array('tags' => explode(" ",trim($child_node->get_attribute('tag'))),
		       'time' => $child_node->get_attribute('time'),
		       'href' => $child_node->get_attribute('href'),
		       'description' => $child_node->get_attribute('description'));
    }
    return $posts;
  }
  /**
   * Rename $old tag to new $tag.
   * Returns true if successful, false if not.
   */
  function renameTag($old,$new)
  {
    $xml_string = domxml_open_mem($this->DeliciousMethods->renameTag($old,$new),$this->DomOptions);
    $root_node = $xml_string->document_element();
    if ($root_node->get_content() == "done") {
      return true;
    } else {
      return false;
    }
  }
  
  /**
   * Returns a list of recent inbox posts.
   * Returns an associative array.
   */
  function inbox($dt=false)
  {
    $inbox = array();
    $xml_string = domxml_open_mem($this->DeliciousMethods->getInbox(($dt) ? $dt : null),$this->DomOptions);
    $root_node = $xml_string->document_element();
    foreach($root_node->child_nodes() as $child_node) {
      $inbox[] = array('tags' => explode(" ",trim($child_node->get_attribute('tag'))),
		       'user' => $child_node->get_attribute('user'),
		       'time' => $child_node->get_attribute('time'),
		       'href' => $child_node->get_attribute('href'),
		       'description' => $child_node->get_attribute('description'));
    }
    return $inbox;
  }
  
  /**
   * Get a list of users you are subscribed to.
   * Returns an array of usernames.
   */
  function subs()
  {
    $users = array();
    $xml_string = domxml_open_mem($this->DeliciousMethods->inboxSubs(),$this->DomOptions);
    $root_node = $xml_string->document_element();
    foreach($root_node->child_nodes() as $child_node) {
      $users[] = $child_node->get_attribute('user');
    }
    return $users;
  }
  
  /**
   * Add subscription to $user, possible to drill down by $tag.
   * Returns the string xml that del.icio.us sends back, as of 
   * right now, it's <ok /> regardless of the $user value.
   */
  function addSub($user,$tag=false)
  {
    $ok = $this->DeliciousMethods->addSub($user, (($tag) ? "$tag" : null));
    return $ok;
  }
  
  function unSub($user,$tag=false)
  {
    $ok = $this->DeliciousMethods->unSub($user, (($tag) ? "$tag" : null));
    return $ok;
  }
}
?>
