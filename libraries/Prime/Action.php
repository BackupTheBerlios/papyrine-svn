<?php
abstract class Action {
	abstract public function execute( ActionMapping $map, ActionForm $form, Request $req );
}
?>