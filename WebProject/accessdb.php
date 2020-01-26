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
    </style>
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
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
        // query_sp_1 = "SELECT * FROM purchase_request_payment";
        // $("#query_search_box").html(query_sp_1);
        // getResult(query_sp_1);
    });

    // Execute SP 2
    $("#execute_sp2").on("click",function(){
        // query_sp_2 = "SELECT id, payment_amount FROM purchase_request_payment";
        // $("#query_search_box").html(query_sp_2);
        // getResult(query_sp_2);
    });

    // get result for a given query
    function getResult(query_text){
        $("#response").html('');
        $("#error_response").html('');
        $(".error_span").hide();
        $("#error").empty();

        var jsonData = {};
        jsonData['query'] = query_text;

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
                    data = data['message'];
                    text += '<table class = "table table-striped table-bordered table-hover">';
                    
                    var data_keys = Object.getOwnPropertyNames(data[0]);
                    for (i = 0, len = data.length; i < len; i++) {
                        console.log(data[i]);
                        if(i == 0){
                            text += '<thead>';
                            text += '<tr>';

                            // add columns
                            console.log(data_keys);
                            console.log(data_keys.length);
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
                    $("#response").append(text);
                    console.log(text);
                }
            }
        });
    }
</script>