<?php
error_reporting(E_ALL);
include ('../libs/simple_html_dom.php');

function availability($record) {

	// Get the OPAC laptop page for this campus
	$html = file_get_html('http://library.catalog.gvsu.edu/record=' . $record);

	$no = 0;

	// How many laptops are available?
	foreach($html->find('table#bib_items tr td[width=24%]') as $element) {
		if(trim($element) == '<td width="24%" ><!-- field % -->&nbsp; AVAILABLE </td>') {
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
			if(trim($ipads) == '&nbsp; AVAILABLE') {
				$no = $no + 1;
			}
		}
	}
	echo $no;
}

?>

<h3>Technology Available from University Libraries<h3>

<table>
	<tr>
		<th>Library</th>
		<th>Laptops</th>
		<th>iPads</th>
	</tr>
	
	<tr>
		<td>Zumberge</td>
		<td><?php availability(b1449654); ?> Available</td>
		<td><?php iPadAvailability(JHZ); ?> Available</td>
	</tr>
	
	<tr>
		<td>Steelcase</td>
		<td><?php availability(b1661763); ?> Available</td>
		<td><?php iPadAvailability(STL); ?> Available</td>
	</tr>
	
	<tr>
		<td>Frey @ <abbr title="Cook-DeVos Center for Health Sciences">CHS</abbr></td>
		<td><?php availability(b1782453); ?> Available</td>
		<td><?php iPadAvailability(CHS); ?> Available</td>
	</tr>	
</table>
	