<?php

include_once 'util.php';
if (!isset($_SESSION)){
    my_session_start();
}
// DB connection
require_once 'connect.php';
$conn = getDbConnection();

if (isset($_POST["request"])) {
	if ($_POST["request"] == "update"){
		$old_data_query = "Select user_type from user_profile where user_id = ? ;";

		// get query result
		$statement = $conn->prepare($old_data_query);
		$statement->setFetchMode(PDO::FETCH_ASSOC);
		$args = array();
		array_push($args, $_POST['user_id']);
		$statement->execute($args);
		$old_data_result = $statement->fetchAll();

		$user_role_old = $old_data_result[0]['user_type'];
		if ($user_role_old == $_POST['user_role_new']){
			$result['status'] = 'error';
	        $result['message'] = "Error in updating User Role. Selected Role is same as Existing Role.";
		}
		else{
		    $query_string = "UPDATE user_profile set user_type = ?, updated_on = ?, updated_by = ? where user_id = ?;";
			try {
				$statement = $conn->prepare($query_string);
				$statement->execute([$_POST['user_role_new'], date("d M Y h:m:i"), $_SESSION['user_id'], $_POST['user_id']]);
	            $count = $statement->rowCount();

	            // Insert log
		        $log_data_query = "INSERT INTO logs (module, module_id, field, old_value, new_value, updated_on, updated_by) Values (?, ?, ?, ?, ?, ?, ?);";
				$statement = $conn->prepare($log_data_query);
				$statement->setFetchMode(PDO::FETCH_ASSOC);
				$args = array();
				array_push($args, 'user_profile');
				array_push($args, $_POST['user_id']);
				array_push($args, 'user_type');
				array_push($args, $user_role_old);
				array_push($args, $_POST['user_role_new']);
				array_push($args, date("d M Y h:m:i"));
				array_push($args, $_SESSION['user_id']);
				$statement->execute($args);
				$last_id = $conn->lastInsertId();

		        $result['status'] = 'success';
		        $result['message'] = "User Role updated Successfully.";
		    } catch (Exception $e) {
		        $result['status'] = 'error';
		        $result['message'] = "Error in updating User Role. Please contact Administrator.";
		    }
		}
		echo json_encode($result);
		exit;
	}
}
?>