<?php

	function smarty_function_delicious_writeconf ($params, &$smarty) 
	{
		if (!isset ($_POST ["user"]) || !isset ($_POST ["pass"]))
			exit;

		if ($file = fopen ("delicious.conf", "w"))
		{
			fputs ($file, 
				"<?php \r\n" .
				"\t$user = " . $_POST ["user"] .";\r\n" .
				"\t$pass = " . $_POST ["pass"] .";\r\n" .
				"?>\r\n"
			);

			fclose ($file);
		}
	}
?>
