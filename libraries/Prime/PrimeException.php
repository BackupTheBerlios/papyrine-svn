<?php
class PrimeException extends Exception {
	function __construct( $msg = null ){
		parent::__construct( $msg );
		$trace = parent::getTrace();
		$trace = $trace[0];
		?>
		<style type="text/css">
		<!--
		.label {font-family: Arial; font-size: 12px; font-weight: bold;}
		.table {border: 1px solid #000000;}
		.text {font-size: 11px; font-family: Arial;}
		.title {font-family: Arial; font-size: 16px; font-weight: bold; color: #FF0000;}
		-->
		</style>

		<table width="400" border="0" align="center" cellpadding="5" cellspacing="0" class="table">
          <tr align="left">
            <td colspan="2" nowrap><img src="./libraries/Prime/logoPrime150x29.gif" border="0">              <span class="title">
            </span>              <hr size="1" color="#000000"></td>
          </tr>
          <tr>
            <td align="right" nowrap class="label">Exception</td>
            <td width="100%" class="text"><span class="title">
              <?=get_class($this);?>
            </span></td>
          </tr>
          <tr>
            <td align="right" nowrap class="label">Message</td>
            <td class="text"><?=parent::getMessage();?></td>
          </tr>
          <tr>
            <td colspan="2" class="label"><hr size="1" color="#000000"><pre><?=var_dump( parent::getTrace() );?></pre></td>
          </tr>
          <!--
		  <tr>
            <td align="right" nowrap class="label">File</td>
            <td width="100%" class="text"><?=$trace["file"];?></td>
          </tr>
          <tr>
            <td align="right" nowrap class="label">Line</td>
            <td width="100%" class="text"><?=$trace["line"];?></td>
          </tr>
          <tr>
            <td align="right" nowrap class="label">Class</td>
            <td width="100%" class="text"><?=$trace["class"];?></td>
          </tr>
          <tr>
            <td align="right" nowrap class="label"><?=(!empty($trace["class"]))?"Method":"Function";?></td>
            <td width="100%" class="text"><?=$trace["function"];?></td>
          </tr>
		  -->
        </table>
		<?
		exit;
	}
}
?>
