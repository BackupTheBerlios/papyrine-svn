<?php

	function smarty_function_wishlist_getitems ($params, &$smarty) 
	{
		if (empty ($params['assign']))
			$params['assign'] = 'items';

		$wishlist = AmazonWishlistPlugin;
		$smarty->assign ($params['assign'], $wishlist->GetItems ());
	}
?>
