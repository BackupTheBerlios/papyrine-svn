<?php

	$methods = array (
		"blogger.newPost"       => array ("function" => "blogger_new_post"),
		"blogger.editPost"      => array ("function" => "blogger_edit_post"),
		"blogger.getUsersBlogs" => array ("function" => "blogger_get_blogs"),
		"blogger.getUserInfo"   => array ("function" => "blogger_get_info"),
	);

	$server = new XML_RPC_Server ($methods);

	function blogger_new_post ($params) 
	{
		$user = new PapyrineUser ($params[2]);

		if (!$user->ValidatePassword ($params[3]))
			return new XML_RPC_Response (0, $xmlrpcerruser+1, "There's a problem, Captain");

		//FIXME
		$title = "date";
		$summary = "open text summarizer?";

		$result = $papyrine->CreateEntry ($title, $summary, $params[4], 
		                                  $user->id, $params[5]);

    	if ($result)
    	    return new XML_RPC_Response(0, $xmlrpcerruser+1, "There's a problem, Captain");
    	else
    	    return new XML_RPC_Response (new XML_RPC_Value("All's fine!", "string"));
	}
?>
