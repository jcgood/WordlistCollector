<?php
$local_db = false;
$local_db = false;
if($local_db){
	$server = 'localhost';
	$username = 'root';
	$password = '';
	$db_name = 'test';
}
else{
	$server = 'dspathwaysorg.ipagemysql.com';
	$username = 'jeff';
	$password = 'PathwaysDS20!7';
	$db_name = 'access_database';
}
$error = false;
error_reporting(E_ALL);
$data = $_POST;
if($data['query'] == ''){
	$error = true;
	$result['status'] = 'error';
	$result['message'] = 'Please enter the query !!';
	echo json_encode($result);
	exit;
}

// pagination
if (! (isset($data['pageNumber']))) {
    $pageNumber = 1;
} else {
    $pageNumber = $data['pageNumber'];
}
$perPageCount = 100;


$link = mysqli_connect($server, $username, $password, $db_name);
$link ->set_charset("utf8");
$query_string = $data['query'];

try{
	$query_result = mysqli_query($link, $query_string);
}
catch(Exception $e){
	$error = true;
	$result['status'] = 'error';
	$result['message'] = 'Query is invalid. Please check !!';
	echo json_encode($result);
	exit;
}
if(!$query_result){
	$error = true;
	$result['status'] = 'error';
	$result['message'] = 'Query returned zero results. Please check !!';
	echo json_encode($result);
	exit;
}

// pagination
$rowCount = mysqli_num_rows($query_result);
mysqli_free_result($query_result);
$pagesCount = ceil($rowCount / $perPageCount);
$lowerLimit = ($pageNumber - 1) * $perPageCount;
$sqlQuery = $query_string." limit " . ($lowerLimit) . " ,  " . ($perPageCount) . " ";
$query_result = mysqli_query($link, $sqlQuery);
if(!$query_result){
	$error = true;
	$result['status'] = 'error';
	$result['message'] = 'Query returned zero results. Please check !!';
	echo json_encode($result);
	exit;
}
$result = fetchQueryResult($query_result);

// Language Citation
if($data['query_type'] == 'language_citation'){
	// SR no for concepts (words)
	$total_concepts_list = mysqli_query($link, "Select distinct Concept as concept_name FROM `ConceptList` order by Concept");
	if(!$total_concepts_list){
		$error = true;
		$error_message = 'No Words found. Please contact Administrator!!';
	}
	else{
		$concept_sr_no = getConceptSrList($total_concepts_list);
		$concepts_list = array_unique(array_column($result, 'concept_name'));

		// Speakers list
		$speakers_list = mysqli_query($link, "select distinct UserName from User_Citation where LANGUAGE = '".$data['language']."'");
		if(!$speakers_list){
			$error = true;
			$error_message = 'No Speakers found. Please contact Administrator!!';
		}
		else{
			$speakers_list = array_unique(array_column(fetchQueryResult($speakers_list),'UserName'));
			$speakers_concepts_query = "select concept_name as Concept, Citation, UserName as Speaker from User_Citation where concept_name in ('".implode('\',\'', $concepts_list)."') and UserName in ('".implode('\',\'', $speakers_list)."')";
			$speakers_concepts_list = fetchQueryResult(mysqli_query($link, $speakers_concepts_query));

			$concept_speaker_combo = array();
			foreach ($speakers_concepts_list as $key => $value) {
				$concept_speaker_combo[$value['Concept']][$value['Speaker']] = $value['Citation'];
			}

			$result = updateLanguageCitationResult($result, $speakers_list, $concept_sr_no, $concept_speaker_combo);
		}
	}
}
elseif($data['query_type'] == 'speaker_query'){
	$result[0]['LanguageName'] = $result[0]['LanguageNames'];
	unset($result[0]['LanguageNames']);
	unset($result[0]['LanguageIDs']);
}
if(!$error){
	$final_result['query_string'] = $query_string;
	$final_result['message'] = $result;
	$final_result['page_no'] = $pageNumber;
	$final_result['total_pages'] = $pagesCount;
	$final_result['status'] = 'success';
	$result = $final_result;
}
else{
	$result['status'] = 'error';
	$result['message'] = $error_message;
}
echo json_encode($result);
exit;


function fetchQueryResult($query_result){
	$count = 0;
	$result = array();
	// while ($row = mysqli_fetch_array($query_result,MYSQLI_NUM))
	while ($row = mysqli_fetch_array($query_result,MYSQLI_ASSOC))  
	{
	    foreach ($row as $key => $value) {
	        $result[$count][$key] = $value;
	    }
	    $count++;
	}
	return $result;
}

function getConceptSrList($total_concepts_list){
	$total_concepts = fetchQueryResult($total_concepts_list);
	$total_concepts_list = array_unique(array_column($total_concepts,'concept_name'));
	// print_r($total_concepts_list);
	$result = array();
	foreach ($total_concepts_list as $key => $value) {
		$result[$value]['sr_no'] = $key+1;
	}
	return $result;
}

function updateLanguageCitationResult($result, $speakers_list, $concept_sr_no, $concept_speaker_combo){
	$result_m = array();
	foreach ($result as $total_concepts_key => $total_concepts_value) {
		foreach ($speakers_list as $speaker_key => $speaker_value) {
			$result_m[$total_concepts_key]['sr_no'] = $concept_sr_no[$total_concepts_value['concept_name']]['sr_no'];
			$result_m[$total_concepts_key]['concept_name'] = $total_concepts_value['concept_name'];
			if(isset($concept_speaker_combo[$total_concepts_value['concept_name']][$speaker_value])){
				$result_m[$total_concepts_key][$speaker_value] = $concept_speaker_combo[$total_concepts_value['concept_name']][$speaker_value];
			}
			else{
				$result_m[$total_concepts_key][$speaker_value] = '';
			}
		}
	}
	return $result_m;
}
?>