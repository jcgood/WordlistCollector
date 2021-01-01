<?php

include 'util.php';
my_session_start();
?>
<html lang="en">    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <link rel="icon" href="img/favicon.png" />
        <title>Access Database</title>
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="css/bootstrap.css">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="css/responsive.css">
        <link href="css/StyleSheet1.css" rel="stylesheet" />
        <link rel="stylesheet" href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
    </head>
    <body>
        <!--================Header Menu Area =================-->
        <header class="header_area">
            <div class="main_menu">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <div class="container">
                        <p class="navbar-brand logo_h"><img src="img/favicon.png" alt="">  Access To Database</p> 
                    </div>
                </nav>
                <div class="container">
                    <br>
                    <div class="">
                        <input type="button" name="database_dashboard" value="Database Page" id="database_dashboard" class="main_btn mrr35" onclick="location.href = 'accessdb.php'"  />
                        <input type="button" name="manage_concepts" value="Concepts Page" id="manage_concepts" class="main_btn" onclick="location.href = 'manage_concepts.php'"  />
                    </div>
                    <br>
                </div>
            </div>
        </header>
        <section>         
            <div class="container">
                <br><br>
                <div class="banner_content text-center">
                    <div>
                        <div class="mrt20" id="div_citation_list">
                        </div>
                        <div class="mrt20" id="div_speakers_list">
                        </div>
                        <div id="response" class="mrt30">
                        </div>
                        <div id="error_div" class="mrt15 f12 tx-red error_span text-danger pr" class="mrt30 mrga">
                            <div id="error_response"></div>
                        </div>
                        <input type="hidden" id="query_string"/>
                    </div>
                </div>
            </div>
        </section>
    </body>


</html>

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

<script type="text/javascript">
    $(document).ready(function(){
        getConceptsGroups();
    });

    // Get speakers list
    function getConceptsGroups(){
    $.ajax(
    {
        type: "POST",
        url : "concept_group_controller.php",
        dataType: "json",
        data:{"request":"getConceptsGroups"},
        success:function(data) 
        {
            console.log(data);
            var text = "";
            // Display errors
            if(data['status'] == 'error'){
                text += data['message'];
                $("#error_response").append(text);
                $(".error_span").show();
                $('#display_errors').append("<strong>"+data['message']+"</strong> <br>");
            }
            // Display result
            else{
                text += '<table id="concept_group_table" class = "table table-striped table-bordered table-hover">';
                text += '<thead>';
                text += '<tr>';
                text += '<th>ID</th>';
                text += '<th>Ordering ID</th>';
                text += '<th>Concept</th>';
                text += '<th>Concept Group</th>';
                text += '<th>Group</th>';
                text += '<th>Edit Group</th>';
                text += '</tr>';
                text += '</thead>';
                text += '<tbody>';
                // text += "<select id='select_speaker' class='bcfff mrr20 w115p cl333 bd1ps fs14p fwn h34p ti10p'>";
                select_concept_text = '<option value=""> -- Select Group -- </option>';
                for (j = 0, len = data.length; j < len; j++) {
                    select_concept_text += "<option value='"+data[j]['ordering_id']+"'>"+data[j]['concept']+"</option>";
                }
                for (i = 0, len = data.length; i < len; i++) {
                    text += "<tr>";
                    text += "<td>"+data[i]['id']+"</td>";
                    text += "<td>"+data[i]['ordering_id']+"</td>";
                    text += "<td>"+data[i]['concept']+"</td>";
                    text += "<td>"+data[i]['concept_group']+"</td>";
                    text += "<td>"+data[i]['group']+"</td>";
                    text += "<td>";
                    text += "<select id='select_speaker' class='bcfff mrr20 w115p cl333 bd1ps fs14p fwn h34p ti10p' style='width:160px;'>";
                    text += select_concept_text;
                    text += "</select>";
                    text += "</td>";
                    text += "</tr>";
                }
                // text += "</select>";
                // text += "<input type='submit' name='get_speaker_data' value='Get Speaker Data' id='get_speaker_data' class='main_btn' onclick=getSpeakerInfo() />";
                // text += '</tr>';
                text += '</tbody>';
                text += '</table>';
                $("#div_speakers_list").append(text);
                $('#concept_group_table').DataTable();
            }
        }
    });
    

}
</script>