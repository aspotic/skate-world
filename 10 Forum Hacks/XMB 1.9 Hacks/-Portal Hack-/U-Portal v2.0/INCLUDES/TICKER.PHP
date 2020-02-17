<?php

/************************************************************************/
/* Xmb Ultimate Portal v2.0.0                                           */
/* ==================================================================== */
/*  Copyright (c) 2003 - 2004 by FREEWILL46 (freewill_46@hotmail.com)   */
/*  http://www.fw46.com/eforum                                          */
/*======================================================================*/
/* BASED ON PHP-NUKE: Advanced Content Management System                */
/* =====================================================================*/
/* Copyright (c) 2002 by Francisco Burzi                                */
/* http://phpnuke.org                                                   */
/************************************************************************/

loadtemplates('index_ticker');
global $tickerstatus, $bordercolor, $tickercontents, $tickerdelay, $borderwidth, $tablespace, $imgdir, $langfile, $catbgcode, $altbg2, $bbname, $altbg1, $cattext, $tablewidth;
if($tickerstatus == "on"){
	$news 		= explode("\r\n", $tickercontents);
	$contents	= '';
	for($i=0;$i<count($news);$i++){
		$news[$i]  = addslashes($news[$i]);
		$contents .= "tickercontents[$i]='$news[$i]'\n";
	}
	eval("\$ticker 	= \"".template("index_ticker")."\";");
    echo $ticker;
}
?>
