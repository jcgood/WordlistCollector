
<!doctype html>
<html lang="en">
    <style type="text/css">
        .error_span{
            height: auto;
            background: #f2dede;
            font-size: 19px !important;
            padding-left: 10px;
            border: 1px solid #F3565D!important;
        }
        .fa-close:hover{
            cursor: pointer;
        }
        .pr{position: relative !important;}
        .mrt15{margin-top:15px;}
        .f12{font-size:12px;}
        .tx-red{color:#ff0000;}
        .pb{position: absolute !important;}
        .t10{top: 10px;}
        .r10{right: 10px;}

        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
        }

        body {
            border: 0;
            font: 12px;
            font-family: Arial;
            color: #000000;
            background: #ffffff;
        }

        #inner-container {
            width: 1000px;
            max-width: 100%;
            padding: 20px 0 70px 0;
            height: 100%;
        }

        #container {
            min-height: 100%;
            padding: 20px;
            border-radius: 2px;
        }

        .pages {
            padding: 10px 14px;
            color: #000000;
            border-radius: 50%;
            background: #CCC;
            text-decoration: none;
            margin: 0px 6px;
            font-size: 0.9em;
        }

        .pages:hover {
            color: #ffffff;
            background: #666;
        }

        .current {
            padding: 10px 14px;
            color: #ffffff;
            background: #73AD21;
            text-decoration: none;
            border-radius: 50%;
            margin: 0px 6px;
        }

        .table {
            width: 100%;
            border-spacing: 0px;
            /*color: #6d6c6c;*/
        }

        .table td {
            padding: 10px;
            border-bottom: 1px solid #d9d9d9;
        }

        .table th {
            padding: 10px;
            border-bottom: 1px solid #d9d9d9;
            /*background: #d9d9d9;*/
            text-align: left;
        }
    </style>
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <link rel="icon" href="img/favicon.png" />
        <title>Access Database</title>
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="css/bootstrap.css">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/responsive.css">
        <link href="css/StyleSheet1.css" rel="stylesheet" />
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
            </div>
        </header>
        <section>         
            <div class="container">
                <div class="banner_content text-center">
                    <div>
                        <div>
                            <textarea name="query_search_box" rows="2" cols="20" id="query_search_box" placeholder="Click to Enter Query.." class="textBox"></textarea>
                        </div>
                        <div>
                            <input type="submit" name="execute_query" value="Execute Query" id="execute_query" class="main_btn" />
                            <input type="submit" name="execute_sp1" value="Execute SP1" id="execute_sp1" class="main_btn" />
                            <input type="submit" name="execute_sp2" value="Execute SP2" id="execute_sp2" class="main_btn" />
                        </div>
                        <div id="response" style="margin-top: 30px;"></div>
                        <div id="error_div" class="mrt15 f12 tx-red error_span text-danger pr" style="margin-top: 30px; width: fit-content; margin:auto; padding: 10px;">
                            <div id="error_response"></div>
                        </div>
                        <input type="hidden" id="query_string"/>
                    </div>
                </div>
            </div>
        </section>
    </body>
</html>

<script src="JavaScripts/jquery-3.3.1.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $("#result").hide();
        $("#error_response").html('');
        $(".error_span").hide();
        $("#error").empty();
    });

    

    // Execute on click of query
    $("#execute_query").on("click",function(){
        getResult($("#query_search_box").val());
    });

   
    // Execute SP 1
    $("#execute_sp1").on("click",function(){

        query_sp_1 = "select A.Concept_Name As Concept, A.Citation As Citation_ICN_Biya_2, B.Citation As Citation_ENB_Biya_1 from (select * from User_Citation where UserName='ICN-Biya-2') A inner join (select * from User_Citation where UserName='ENB-Biya-1') B on A.Concept_Name =B.Concept_Name where A.UserName='ICN-Biya-2' Or A.UserName='ENB-Biya-1'";

        $("#query_search_box").html(query_sp_1);
        getResult(query_sp_1);
    });

    // Execute SP 2
    $("#execute_sp2").on("click",function(){
        // query_sp_2 = "SELECT id, payment_amount FROM purchase_request_payment";
        // $("#query_search_box").html(query_sp_2);
        // getResult(query_sp_2);
    });

    function page_query(pageNumber){
        getResult('false_query', pageNumber, true);
    }

    // get result for a given query
    function getResult(query_text, pageNumber = 1, check_query_div = false){
        if(check_query_div){
            query_text = $("#query_string").val();
        }
        $("#response").html('');
        $("#error_response").html('');
        $(".error_span").hide();
        $("#error").empty();

        var jsonData = {};
        jsonData['query'] = query_text;
        jsonData['pageNumber'] = pageNumber;

        $.ajax(
        {
            type: "POST",
            url : "execute_query.php",
            data : jsonData,
            dataType: "json",
            async: true,
            success:function(data) 
            {
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
                    var page_no = data['page_no'];
                    var total_pages = data['total_pages'];
                    var query_string = data['query_string'];
                    $("#query_string").val(query_string);

                    // Pagination text
                    var pagination_text = '';
                    pagination_text += '<div style="height: 30px;"></div>';
                    pagination_text += '<ul class="pagination pagination-circular" role="navigation" aria-label="Pagination">';
                    if(total_pages > 10){ 
                        if(page_no>=10){
                            pagination_text += '<li><a href="javascript:void(0);" class="pages" onclick = page_query('+1+')> First </a></li>';
                        }
                    }
                    i = Math.floor(page_no/10)*10;
                    if(i == 0){
                        i = 1;
                    }
                    if(i>=20){
                        pagination_text += '<li><a href="javascript:void(0);" class="pages" onclick = page_query('+(i-10)+')> '+(i-10)+' </a></li>';
                    }
                    for (page_counter = 0; page_counter <= 10; page_counter++){
                        if(i == page_no){
                            pagination_text += '<li><a href="javascript:void(0);" class="current" onclick = page_query('+i+')> '+i+' </a></li>';
                        }
                        else{
                            pagination_text += '<li><a href="javascript:void(0);" class="pages" onclick = page_query('+i+')> '+i+' </a></li>';
                        }
                        if(i >= total_pages){
                            break;
                        }
                        i += 1;
                    }
                    if(i < total_pages){
                        pagination_text += '<li><a href="javascript:void(0);" class="pages" onclick = page_query('+total_pages+')> Last </a></li>';
                    }
                    pagination_text += '</ul>';

                    // List text
                    data = data['message'];
                    text += pagination_text;
                    text += '<table class = "table table-striped table-bordered table-hover">';
                    
                    var data_keys = Object.getOwnPropertyNames(data[0]);
                    for (i = 0, len = data.length; i < len; i++) {
                        console.log(data[i]);
                        if(i == 0){
                            text += '<thead>';
                            text += '<tr>';

                            // add columns
                            for (j = 0; j < data_keys.length; j++){
                                text += '<th>'+data_keys[j]+'</th>';
                            }
                            text += '</tr>';
                            text += '</thead>';
                            text += '<tbody>';
                        }
                        // add rows
                        text += '<tr>';
                        for (j = 0; j < data_keys.length; j++){
                            text += '<th>'+data[i][data_keys[j]]+'</th>';
                        }
                        text += '</tr>';
                    }
                    text += '</tbody>';
                    text += '</table>';
                    text += pagination_text;

                    $("#response").append(text);
                }
            }
        });
    }
</script>