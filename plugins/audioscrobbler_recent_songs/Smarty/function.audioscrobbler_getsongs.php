<?php

	function smarty_function_audioscrobbler_getsongs ($params, &$smarty) 
	{
		if (empty ($params['assign']))
			$params['assign'] = 'songs';

		$scrobbler = AudioScrobblerPlugin;
		$smarty->assign ($params['assign'], $scrobbler->GetSongs ());
	}
?>
