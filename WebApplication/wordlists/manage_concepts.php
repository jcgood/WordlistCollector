<?php
include_once 'util.php';
include_once 'menu.php';
if (!isset($_SESSION)){
    my_session_start();
}
// check that the user is logged in - if not, redirect to login.
if (!isset($_SESSION["user_email_address"])) {
    $location = "login.php";
    echo("<script>location.href='$location'</script>");
    exit;
}

// DB connection
require_once 'connect.php';

$conn = getDbConnection();
$query_string = "Select id, ordering_id, concept, group_id, concept_group FROM `concept_list_new` order by id";
$statement = $conn->prepare($query_string);
$statement->setFetchMode(PDO::FETCH_ASSOC);
$statement->execute();
$query_result = $statement->fetchAll();

if(!$query_result){
    $error = true;
    $result['status'] = 'error';
    $result['message'] = 'No concepts found. Please contact Administrator!!';
}
else{
    foreach ($query_result as $key => $value) {
        $concept_group_array[$value['ordering_id']] = $value['concept'];
    }
    foreach ($query_result as $key => $value) {
        if (is_null($query_result[$key]['group_id'])){
            $query_result[$key]['group'] = '-';
        }
        else{
            $query_result[$key]['group'] = $concept_group_array[$value['group_id']];
        }
    }
    $result['user_type'] = $_SESSION['user_type'];
    $result['status'] = 'success';
    $result['query_result'] = $query_result;
}


require_once 'h2o-php/h2o.php';
$template = new H2o('templates/manage_concepts.html', array(
    'cache_dir' => dirname(__FILE__)
));
echo $template->render(compact('result'));
?>