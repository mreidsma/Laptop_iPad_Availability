<?php
error_reporting(E_ALL);
include ('../libs/simple_html_dom.php');

function availability($record) {

	// Get the OPAC laptop page for this campus
	// Zumberege http://library.catalog.gvsu.edu/search~S17?/.b1449654/.b1449654/1,1,1,B/holdings~1449654&FF=&1,0,
	// Steelcase http://library.catalog.gvsu.edu/search~S17?/.b1661763/.b1661763/1,1,1,B/holdings~1661763&FF=&1,0,
	// Frey
	$html = file_get_html('http://library.catalog.gvsu.edu/search~S17?/.b' . $record . '/.b' . $record . '/1,1,1,B/holdings~' . $record . '&FF=&1,0,');

	$no = 0;

	// How many laptops are available?
	foreach($html->find('table#bib_items tr td[width=24%]') as $element) {
		if((trim($element) == '<td width="24%" ><!-- field % -->&nbsp;AVAILABLE </td>') || (trim($element) == '<td width="24%" ><!-- field % -->&nbsp;Being Processed - Ask at Service Desk </td>')) {
			$no = $no + 1;
		}
	}
	echo $no;
}

function iPadAvailability($campus) {

	// Get the OPAC iPad page
	$html = file_get_html('http://library.catalog.gvsu.edu/search~S17?/.b3397959/.b3397959/1,1,1,B/holdings~3397959&FF=&1,0,');

	if($campus == "JHZ") {
		$campus_match = '&nbsp;ZUMBERGE Course Reserves';
	} else {
		if($campus == "STL") {
				$campus_match = '&nbsp;STEELCASE Course Reserves';
				
		} else {
			if($campus == "CHS") {
					$campus_match = '&nbsp;FREY Course Reserves (CHS 290)';
	}}}
	
	$no = 0;

	// How many laptops are available?
	foreach($html->find('table#bib_items tr.bibItemsEntry') as $element) {
		
		//print_r($element);
		
		$location = $element->find('td[width=38%]',0)->plaintext;
		//print_r(trim($location));
		if(trim($location) == $campus_match) {
			$ipads = $element->find('td[width=24%]',0)->plaintext;
			if((trim($ipads) == '&nbsp;AVAILABLE') || (trim($ipads) == '&nbsp;Being Processed - Ask at Service Desk')) {
				$no = $no + 1;
			}
		}
	}
	echo $no;
}

?>

<style>
.lib-table td,
.lib-table th { width: 32%; }
/* TABLES STYLES */
table { border-collapse: collapse;}
.lib-table table {width:100%; font-family: Arial, Helvetica, sans-serif; }
.lib-table table th {text-align: left;background-color: #005088;color: white;font-size:1em;padding:.333em;}
.lib-table table tr.lib-row-headings th {background-color: #004875;color: #fff;font-size: inherit;font-weight:bold;border-bottom: 1px solid #bbb;}
.lib-table tr:nth-of-type(odd) td {background: #eee;}
.lib-table tr td { border-bottom:1px solid #bbb;}
.lib-table th,
.lib-table td {padding:.5em;}
.lib-table tr td span { display: none; }
@media screen and (min-width:30em) {
.lib-table tr td span { display: inline; }
}
</style>

<div class="lib-table">
	<table class="lib-table">
		<tr class="lib-row-headings">
			<th>Library</th>
			<th>Laptops</th>
			<th>iPads</th>
		</tr>
	
		<tr>
			<!-- Commented out scripts since IT is now doing lending at Mary I, but won't be up and running until Fall -->
			<td><strong>Mary Idema Pew</strong></td>
			<td><?php //availability('1449654'); ?>0<span> Available</span></td>
			<td><?php //iPadAvailability('JHZ'); ?>0<span> Available</span></td>
		</tr>
	
		<tr>
			<td><strong>Steelcase</strong></td>
			<td><?php availability('1661763'); ?><span> Available</span></td>
			<td><?php iPadAvailability('STL'); ?><span> Available</span></td>
		</tr>
	
		<tr>
			<td><strong>Frey @ <abbr title="Cook-DeVos Center for Health Sciences">CHS</abbr></strong></td>
			<td><?php availability('1782453'); ?><span> Available</span></td>
			<td><?php iPadAvailability('CHS'); ?><span> Available</span></td>
		</tr>	
	</table>
</div>
	