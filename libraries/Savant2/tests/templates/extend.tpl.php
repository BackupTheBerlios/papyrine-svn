<?php
/**
* 
* Template for testing token assignment.
* 
* @version $Id: extend.tpl.php,v 1.2 2004/07/22 02:20:25 pmjones Exp $
*
*/
?>
<p><?php $result = $this->plugin('example'); var_dump($result); ?></p>
<p><?php $result = $this->plugin('example_extend'); var_dump($result); ?></p>
