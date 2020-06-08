
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
        .main_btn{
            line-height: 35px !important;
            width: 130px !important;
            padding: 0px 0px !important;
        }

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

/*        .table {
            width: 100%;
            border-spacing: 0px;        }

        .table td {
            padding: 10px;
            border-bottom: 1px solid #d9d9d9;
        }

        .table th {
            padding: 10px;
            border-bottom: 1px solid #d9d9d9;
            text-align: left;
        }*/
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
                            <!-- <input type="submit" name="execute_sp1" value="Get Biya Citation" id="execute_sp1" class="main_btn" /> -->
                            <!-- <input type="submit" name="execute_sp2" value="Execute SP2" id="execute_sp2" class="main_btn" /> -->
                        </div>
                        <div style="margin-top: 20px;" id="div_citation_list">                            
                            
                        </div>
                        <div style="margin-top: 20px;" id="div_speakers_list">                            
                            
                        </div>
                        <div id="response" style="margin-top: 30px;"></div>
                        <div id="error_div" class="mrt15 f12 tx-red error_span text-danger pr" style="margin-top: 30px; width: fit-content; margin:auto; padding: 10px;">
                            <div id="error_response"></div>
                        </div>
                        <input type="hidden" id="query_string"/>
                        <input type="hidden" id="query_type"/>
                    </div>
                </div>
            </div>
        </section>
    </body>
</html>

<script src="JavaScripts/jquery-3.3.1.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>

<script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/fixedheader/3.1.6/js/dataTables.fixedHeader.min.js"></script>

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

<link rel="stylesheet" href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.1.6/css/fixedHeader.dataTables.min.css">

<script type="text/javascript">
    var concept_list = {};
    $(document).ready(function(){
        $("#result").hide();
        $("#error_response").html('');
        $(".error_span").hide();
        $("#error").empty();

        getSpeakersList();
        getLanguageList();
        getConceptList();
    });

    function getSpeakersList(){
        $.ajax(
        {
            type: "POST",
            url : "speakers_list.php",
            // url : "excel_export.php",
            // data : jsonData,
            dataType: "json",
            async: true,
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
                    text += "<select style='margin-right: 20px;width: 115px;color: #333;background-color: #fff;border: 1px solid #e5e5e5;font-size: 14px;font-weight: normal;height: 34px;text-indent: 10px;' id='select_speaker'>";
                    for (i = 0, len = data.length; i < len; i++) {
                        text += "<option value='"+data[i]['SpeakerID']+"'>"+data[i]['SpeakerID']+"</option>";
                    }
                    text += "</select>";
                    text += "<input type='submit' name='get_speaker_data' value='Get Speaker Data' id='get_speaker_data' class='main_btn' onclick=getSpeakerInfo() />";
                    $("#div_speakers_list").append(text);
                }
            }
        });
    }

    function getLanguageList(){
        $.ajax(
        {
            type: "POST",
            url : "language_list.php",
            // url : "excel_export.php",
            // data : jsonData,
            dataType: "json",
            async: true,
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
                    text += "<select style='margin-right: 20px;width: 115px;color: #333;background-color: #fff;border: 1px solid #e5e5e5;font-size: 14px;font-weight: normal;height: 34px;text-indent: 10px;' id='select_language'>";
                    for (i = 0, len = data.length; i < len; i++) {
                        text += "<option value='"+data[i]['Language']+"'>"+data[i]['Language']+"</option>";
                    }
                    text += "</select>";
                    text += "<input type='submit' name='get_language_citation' value='Language Citation' id='get_language_citation' class='main_btn' onclick=getLanguageCitation() />";
                    $("#div_citation_list").append(text);
                }
            }
        });
    }

    function getConceptList(){
        $.ajax(
        {
            type: "POST",
            url : "concept_list.php",
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
                    concept_list = data;
                }
            }
        });
    }

    function getLanguageCitation(language_value = false){
        // query_sp_1 = "select A.Concept_Name As Concept, A.Citation As Citation_ICN_Biya_2, B.Citation As Citation_ENB_Biya_1 from (select * from User_Citation where UserName='ICN-Biya-2') A inner join (select * from User_Citation where UserName='ENB-Biya-1') B on A.Concept_Name =B.Concept_Name where A.UserName='ICN-Biya-2' Or A.UserName='ENB-Biya-1'";
        // query_sp_1 = "select concept_name as Concept, citation as Citation, userName as Speaker from User_Citation where LANGUAGE = 'Biya'";
        if(!language_value){
            language_value = $('#select_language').val();
        }
        else{
            $("#select_language").val(language_value);
        }
        // query_sp_1 = "select distinct concept_name from User_Citation where LANGUAGE = '"+language_value+"'";
        query_sp_1 = "Select distinct Concept as concept_name FROM `ConceptList` order by Concept";

        // $("#query_search_box").html(query_sp_1);
        $("#query_type").val('language_citation');
        getResult(query_sp_1, 1, false, false, 'language_citation');
    }

    function getSpeakerInfo(speaker_value = false){
        if(!speaker_value){
            speaker_value = $('#select_speaker').val();
        }
        else{
            $("#select_speaker").val(speaker_value);
        }
        console.log(speaker_value);
        console.log('getSpeakerInfo');
        var speaker_query = "Select *, group_concat(LanguageName) as LanguageNames, group_concat(LanguageID) as LanguageIDs from SpeakerMetaData where SpeakerID = '"+speaker_value+"'";
        $("#query_search_box").html(speaker_query);
        $("#query_type").val('speaker_query');
        getResult(speaker_query, 1, false, true, 'speaker_query');
    }
    

    // Execute on click of query
    $("#execute_query").on("click",function(){
        $("#query_type").val('execute_query');
        getResult($("#query_search_box").val(), 1, false, false, 'execute_query');
    });

    // Execute SP 1
    $("#execute_sp1").on("click",function(){
        // // query_sp_1 = "select A.Concept_Name As Concept, A.Citation As Citation_ICN_Biya_2, B.Citation As Citation_ENB_Biya_1 from (select * from User_Citation where UserName='ICN-Biya-2') A inner join (select * from User_Citation where UserName='ENB-Biya-1') B on A.Concept_Name =B.Concept_Name where A.UserName='ICN-Biya-2' Or A.UserName='ENB-Biya-1'";
        // // query_sp_1 = "select concept_name as Concept, citation as Citation, userName as Speaker from User_Citation where LANGUAGE = 'Biya'";
        // query_sp_1 = "select distinct concept_name from User_Citation where LANGUAGE = 'Biya'";

        // // $("#query_search_box").html(query_sp_1);
        // $("#query_type").val('execute_sp1');
        // getResult(query_sp_1, 1, false, false, 'execute_sp1');
    });

    // Execute SP 2
    $("#execute_sp2").on("click",function(){
        // query_sp_2 = "SELECT id, payment_amount FROM purchase_request_payment";
        // $("#query_search_box").html(query_sp_2);
        // getResult(query_sp_2);
    });

    function page_query(pageNumber){
        getResult('false_query', pageNumber, true, false, $("#query_type").val());
    }

    function exportExcel(){
    // $("#excel_submit").on("click",function(){
        console.log('aaa');
        $("#excel_query").val($("#query_string").val());
    // });
    }

    // get result for a given query
    function getResult(query_text, pageNumber = 1, check_query_div = false, transpose_result = false, query_type = 'execute_query'){
        if(check_query_div){
            query_text = $("#query_string").val();
        }
        $("#response").html('');
        $("#error_response").html('');
        $(".error_span").hide();
        $("#error").empty();

        var jsonData = {};
        jsonData['query'] = query_text;
        jsonData['query_type'] = $("#query_type").val();

        // As no pagination
        // jsonData['pageNumber'] = pageNumber;

        // jsonData['concept_list'] = concept_list;
        jsonData['language'] = $("#select_language").val();
        console.log(jsonData);

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
                    // As no pagination
                    // var page_no = data['page_no'];
                    // var total_pages = data['total_pages'];

                    var query_string = data['query_string'];
                    $("#query_string").val(query_string);
                    console.log(query_text);

                    text += '<div style="height: 30px;"><form action="export_excel.php" method = "post" id="excel_form" style="text-align:left;">';
                    text += '<input type="submit" name="excel_submit" value="Export to Excel" id="excel_submit" class="main_btn" onclick=exportExcel() />';
                    text += '<input type="hidden" name="excel_query" id="excel_query" value="'+$("#query_string").val()+'"/>';
                    text += '<input type="hidden" name="excel_query_type" id="excel_query_type" value="'+$("#query_type").val()+'"/>';
                    text += '<input type="hidden" name="transpose_result" id="transpose_result" value="'+transpose_result+'" />';
                    text += '<input type="hidden" name="language" id="language" value="'+$("#select_language").val()+'" /></form>';
                    
                    // As no pagination
                    // *** Code for pagination ***
                    // 1 - Pagination
                    // // Pagination text
                    // var pagination_text = '';
                    // pagination_text += '<div style="height: 30px;"></div>';
                    // pagination_text += '<ul class="pagination pagination-circular" role="navigation" aria-label="Pagination">';
                    // if(total_pages > 10){ 
                    //     if(page_no>=10){
                    //         pagination_text += '<li><a href="javascript:void(0);" class="pages" onclick = page_query('+1+')> First </a></li>';
                    //     }
                    // }
                    // i = Math.floor(page_no/10)*10;
                    // if(i == 0){
                    //     i = 1;
                    // }
                    // if(i>=100){
                    //     pagination_text += '<li><a href="javascript:void(0);" class="pages" onclick = page_query('+(i-10)+')> '+(i-10)+' </a></li>';
                    // }
                    // for (page_counter = 0; page_counter <= 10; page_counter++){
                    //     if(i == page_no){
                    //         pagination_text += '<li><a href="javascript:void(0);" class="current" onclick = page_query('+i+')> '+i+' </a></li>';
                    //     }
                    //     else{
                    //         pagination_text += '<li><a href="javascript:void(0);" class="pages" onclick = page_query('+i+')> '+i+' </a></li>';
                    //     }
                    //     if(i >= total_pages){
                    //         break;
                    //     }
                    //     i += 1;
                    // }
                    // if(i < total_pages){
                    //     pagination_text += '<li><a href="javascript:void(0);" class="pages" onclick = page_query('+total_pages+')> Last </a></li>';
                    // }
                    // pagination_text += '</ul>';
                    // 1 - Pagination End


                    // As no pagination
                    // text += pagination_text;

                    // List text
                    data = data['message'];
                    if(query_type == 'speaker_query'){
                        text += '<table id="speaker_table" class = "table table-striped table-bordered table-hover">';
                    }
                    else if(query_type == 'language_citation'){
                        text += '<table id="language_citation_table" class = "table table-striped table-bordered table-hover">';
                    }
                    else{ 
                        text += '<table class = "table table-striped table-bordered table-hover">';
                    }
                    
                    if(transpose_result){
                        var data_keys = Object.getOwnPropertyNames(data[0]);
                        for (i = 0, len = data.length; i < len; i++) {
                            // add rows
                            for (j = 0; j < data_keys.length; j++){
                                text += '<tr>';
                                text += '<th>'+[data_keys[j]]+'</th>';
                                if(query_type == 'speaker_query'){
                                    if(data_keys[j] == 'LanguageName'){
                                        language_list = data[i][data_keys[j]].split(',');
                                        text += "<th>";
                                        for (language_list_count = 0; language_list_count < language_list.length; language_list_count++){
                                            text += "<p onclick = getLanguageCitation('"+language_list[language_list_count]+"')><h style='text-shadow: none;color: #5b9bd1;cursor: pointer;'>"+language_list[language_list_count]+"</h>";
                                            if(language_list_count == language_list.length - 1){
                                                text += " </p>";
                                            }
                                            else{
                                                text += ", </p>";
                                            }
                                        }
                                    }
                                    else{
                                        text += '<th>'+data[i][data_keys[j]]+'</th>';
                                    }
                                }
                                else{
                                    text += '<th>'+data[i][data_keys[j]]+'</th>';
                                }
                                text += '</tr>';
                            }
                        }
                        text += '</tbody>';
                        text += '</table>';
                    }
                    else{
                        var data_keys = Object.getOwnPropertyNames(data[0]);
                        text += '<thead>';
                        text += '<tr>';
                        for (j = 0; j < data_keys.length; j++){
                            // text += '<th>'+data_keys[j]+'</th>';

                            if(query_type == 'language_citation'){
                                if((data_keys[j] == 'concept_name') || (data_keys[j] == 'sr_no')){
                                    text += '<th>'+data_keys[j]+'</th>';
                                }
                                else{
                                    speaker_name = data_keys[j].split('-')[0];
                                    text += "<th onclick = getSpeakerInfo('"+speaker_name+"')><h style='text-shadow: none;color: #5b9bd1;cursor: pointer;'>"+data_keys[j]+"</h></th>";
                                }
                            }
                            else{
                                text += '<th>'+data_keys[j]+'</th>';
                            }
                        }
                        text += '</tr>';
                        text += '</thead>';
                        text += '<tbody>';
                        for (i = 0, len = data.length; i < len; i++) {
                            // add rows
                            text += '<tr>';
                            for (j = 0; j < data_keys.length; j++){
                                text += '<td>'+data[i][data_keys[j]]+'</td>';
                            }
                            text += '</tr>';
                        }
                        text += '</tbody>';

                        if(query_type == 'language_citation'){
                            // add footer for filter
                            text += '<tfoot>';
                            text += '<tr>';
                            for (j = 0; j < data_keys.length; j++){
                                text += '<th>'+data_keys[j]+'</th>';
                            }
                            text += '</tr>';
                            text += '</tfoot>';
                        }
                        text += '</table>';
                    }

                    // As no pagination
                    // text += pagination_text;

                    $("#response").append(text);

                    // 3 - Datatable upgraded
                    $('#language_citation_table tfoot th').each( function () {
                        var title = $(this).text();
                        $(this).html( "<input type='text' placeholder='Search "+title+"' />" );
                    } );

                    // DataTable
                    var table = $('#language_citation_table').DataTable({
                        initComplete: function () {
                            // Display search options - at top (default = bottom)
                            var r = $('#language_citation_table tfoot tr');
                            r.find('th').each(function(){
                                $(this).css('padding', 8);
                            });
                            $('#language_citation_table thead').append(r);
                            $('#search_0').css('text-align', 'center');

                            // Apply the search
                            this.api().columns().every( function () {
                                var that = this;
                                $( 'input', this.footer() ).on( 'keyup change clear', function () {
                                    if ( that.search() !== this.value ) {
                                        that
                                            .search( this.value )
                                            .draw();
                                    }
                                } );
                            } );
                        }
                    });
                    // end 3

                    // 1 - Simple datatable
                    // if(query_type == 'language_citation'){
                    //     $('#language_citation_table').DataTable({
                    //         "order": [
                    //             [2, "asc"]
                    //         ],
                    //         "language": {
                    //             "searchPlaceholder": "Search Anything"
                    //         },
                    //     });
                    // }
                    // end 1
                }
            }
        });
    }

    // // function exportExcel(){
    // $("#excel_submit").on("click",function(){
    //     console.log('aaa');
    //     $("#excel_query").val($("#query_string").val());
    // });
</script>