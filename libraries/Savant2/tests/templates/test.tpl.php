<?php
/**
* 
* Template for testing token assignment.
* 
* @version $Id: test.tpl.php,v 1.2 2004/07/22 02:20:25 pmjones Exp $
*
*/
?>
<p><?php echo $this->variable1 ?></p>
<p><?php echo $this->variable2 ?></p>
<p><?php echo $this->variable3 ?></p>
<p><?php echo $this->key0 ?></p>
<p><?php echo $this->key1 ?></p>
<p><?php echo $this->key2 ?></p>
<p><?php echo $this->reference1 ?></p>
<p><?php echo $this->reference2 ?></p>
<p><?php echo $this->reference3 ?></p>
<ul>
<?php foreach ($this->set as $key => $val) echo "<li>$key = $val</li>\n" ?>
</ul>
