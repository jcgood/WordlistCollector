<?php

include_once 'util.php';
if (!isset($_SESSION)){
    my_session_start();
}
// DB connection
require_once 'connect.php';
$conn = getDbConnection();

if (isset($_POST["request"])) {
	if ($_POST["request"] == "getConceptsGroups"){
	    $query_string = "Select id, ordering_id, concept, group_id, concept_group FROM `concept_list_new` order by id";
		// $query_result = mysqli_query($conn, $query_string);
		$statement = $conn->prepare($query_string);
	    $statement->setFetchMode(PDO::FETCH_ASSOC);
	    $statement->execute([$user_email_address]);
	    $query_result = $statement->fetchAll();

		if(!$query_result){
			$error = true;
			$result['status'] = 'error';
			$result['message'] = 'No concepts found. Please contact Administrator!!';
			echo json_encode($result);
			exit;
		}
		foreach ($query_result as $key => $value) {
			$concept_group_array[$value['ordering_id']] = $value['concept'];
		}
		foreach ($query_result as $key => $value) {
			if (is_null($query_result[$key]['group_id'])){
				$query_result[$key]['group'] = '-';
			}
			else{
				$query_result[$key]['group'] = $concept_group_array[$value['ordering_id']];
			}
		}
		echo json_encode($query_result);
		exit;
	}
	if ($_POST["request"] == "update"){
		$old_data_query = "Select group_id from concept_list_new where id = ? ;";

		// get query result
		$statement = $conn->prepare($old_data_query);
		$statement->setFetchMode(PDO::FETCH_ASSOC);
		$args = array();
		array_push($args, $_POST['id']);
		$statement->execute($args);
		$old_data_result = $statement->fetchAll();

		$group_id_old = $old_data_result[0]['group_id'];
		if($group_id_old == $_POST['group_id']){
			$result['status'] = 'error';
	        $result['message'] = "Error in updating Concept Group. Selected Group is same as Existing Group.";
		}
		else{
		    $query_string = "UPDATE concept_list_new set group_id = ?, updated_on = ?, updated_by = ? where id = ?;";
			try {
				$statement = $conn->prepare($query_string);
	            $statement->execute([$_POST['group_id'], date("d M Y h:m:i"), $_SESSION['user_id'], $_POST['id']]);
	            $count = $statement->rowCount();

	            // Insert log
		        $log_data_query = "INSERT INTO logs (module, module_id, field, old_value, new_value, updated_on, updated_by) Values (?, ?, ?, ?, ?, ?, ?);";
				$statement = $conn->prepare($log_data_query);
				$statement->setFetchMode(PDO::FETCH_ASSOC);
				$args = array();
				array_push($args, 'concept_list_new');
				array_push($args, $_POST['id']);
				array_push($args, 'group_id');
				array_push($args, $group_id_old);
				array_push($args, $_POST['group_id']);
				array_push($args, date("d M Y h:m:i"));
				array_push($args, $_SESSION['user_id']);
				$statement->execute($args);
				$last_id = $conn->lastInsertId();

		        $result['status'] = 'success';
		        $result['message'] = "Concept updated Successfully.";
		    } catch (Exception $e) {
		        $result['status'] = 'error';
		        $result['message'] = "Error in updating Concept. Please contact Administrator.";
		    }
		}
		echo json_encode($result);
		exit;
	}
}
?>