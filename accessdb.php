
<?php

include 'util.php';
my_session_start();
?>
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
        .w115p{width: 115px}
        .cl333{color: #333}
        .bd1ps{border: 1px solid #e5e5e5}
        .fs14p{font-size: 14px}
        .fwn{font-weight: normal}
        .w13p{width: 13px}
        .w20p{width: 20px}
        .w35p{width: 35px}
        .h13p{height: 13px}
        .h20p{height: 20px}
        .h35p{height: 35px}
        .h34p{height: 34px}
        .h30p{height: 30px}
        .ti10p{text-indent: 10px}
        .pr{position: relative !important;}
        .cp{cursor: pointer;}
        .tsn{text-shadow: none;}
        .cl5b9bd1{color: #5b9bd1;}
        .pd10{padding: 10px;}
        .wdfc{width: fit-content;}
        .tal{text-align: left;}
        .mrga{margin:auto;}
        .mrb10{margin-bottom:10px;}
        .mrt15{margin-top:15px;}
        .mrt20{margin-top:20px;}
        .mrr20{margin-right:20px;}
        .mrt30{margin-top:30px;}
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

        .bcfff{background-color: #fff}

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

        span[title]:hover{height: 100px;color: #000000; background: #ffffff;}

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


        a.tooltip {
            border-bottom: 1px dashed;
            text-decoration: none;
            display: contents;
        }
        a.tooltip:hover {
            cursor: help;
            position: relative;
        }
        a.tooltip span {
            display: none;
        }
        a.tooltip:hover span {
            border: #000 2px solid;
            padding: 5px 20px 5px 5px;
            display: block;
            z-index: 100;
            background: #e3e3e3;
            left: 0px;
            margin: 15px;
            width: 500px;
            top: 30px;
            position: absolute;
            text-decoration: none;
        }
        a.tooltip_easy_match {
            border-bottom: 1px dashed;
            text-decoration: none;
            display: contents;
        }
        a.tooltip_easy_match:hover {
            cursor: help;
            position: relative;
        }
        a.tooltip_easy_match span {
            display: none;
        }
        a.tooltip_easy_match:hover span {
            border: #000 2px solid;
            padding: 10px 10px 10px 10px;
            display: block;
            z-index: 100;
            background: #e3e3e3;
            /*background: lightyellow;*/
            left: -250px;
            /*margin: 15px;*/
            width: 500px;
            top: 30px;
            position: absolute;
            text-decoration: none;
        }

         .table-fixed{
          width: 100%;
          background-color: #f3f3f3;
          tbody{
            height:200px;
            overflow-y:auto;
            width: 100%;
            }
         thead,tbody,tr,td,th{
            display:block;
          }
         tbody{
            td{
              float:left;
            }
          }
         thead {
            tr{
              th{
                float:left;
               background-color: #f39c12;
               border-color:#e67e22;
              }
            }
          }
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
            </div>
        </header>
        <section>         
            <div class="container">
                <div class="banner_content text-center">
                    <div>
                        <div>
                            <textarea name="query_search_box" rows="2" cols="20" id="query_search_box" placeholder="Click to Enter Query.." class="textBox"></textarea>
                        </div>
                        <div class="mrt20">
                            <input type="submit" name="execute_query" value="Execute Query" id="execute_query" class="main_btn" />
                        </div>
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

(function(){
    function removeAccents ( data ) {
        typeof data === 'string' ?

            data = data
                .replace(/[áÁàÀâÂäÄãÃåÅæÆ]/g, 'a')
                .replace(/[çÇ]/g, 'c')
                .replace(/[éÉèÈêÊëË]/g, 'e')
                .replace(/[íÍìÌîÎïÏîĩĨĬĭ]/g, 'i')
                .replace(/[ñÑ]/g, 'n')
                .replace(/[óÓòÒôÔöÖœŒ]/g, 'o')
                .replace(/[ß]/g, 's')
                .replace(/[úÚùÙûÛüÜ]/g, 'u')
                .replace(/[ýÝŷŶŸÿ]/g, 'n') :
            data;
        return data;
    }
     
    var searchType = jQuery.fn.DataTable.ext.type.search;
     
    searchType.string = function ( data ) {
        // console.log('string');
        return ! data ?
            '' :
            typeof data === 'string' ?
                removeAccents( data ) :
                data;
    };
     
    searchType.html = function ( data ) {
        // console.log('html');
        return ! data ?
            '' :
            typeof data === 'string' ?
                removeAccents( data.replace( /<.*?>/g, '' ) ) :
                data;
    };
}());

var concept_list = {};
var language_citation_table_datatable = '';
var exact_match = false;

$(document).ready(function(){
    hideRemoveResult();
    getSpeakersList();
    getLanguageList();
    getConceptList();
});

// Get speakers list
function getSpeakersList(){
    $.ajax(
    {
        type: "POST",
        url : "speakers_list.php",
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
                text += "<select id='select_speaker' class='bcfff mrr20 w115p cl333 bd1ps fs14p fwn h34p ti10p'>";
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

// Get languages list
function getLanguageList(){
    $.ajax(
    {
        type: "POST",
        url : "language_list.php",
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
                text += "<select id='select_language' class='bcfff mrr20 w115p cl333 bd1ps fs14p fwn h34p ti10p'>";
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

// Get concepts list
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

// Get Language Citation
function getLanguageCitation(language_value = false){
    if(!language_value){
        language_value = $('#select_language').val();
    }
    else{
        $("#select_language").val(language_value);
    }
    query_sp_1 = "Select distinct Concept as concept_name FROM `ConceptList` order by Concept";
    getResult(query_sp_1, false, 'language_citation');
}

// Get data for a given speaker
function getSpeakerInfo(speaker_value = false){
    if(!speaker_value){
        speaker_value = $('#select_speaker').val();
    }
    else{
        $("#select_speaker").val(speaker_value);
    }
    var speaker_query = "Select *, group_concat(LanguageName) as LanguageNames, group_concat(LanguageID) as LanguageIDs from SpeakerMetaData where SpeakerID = '"+speaker_value+"'";
    $("#query_search_box").html(speaker_query);
    getResult(speaker_query, true, 'speaker_query');
}

// Execute on click of query
$("#execute_query").on("click",function(){
    getResult($("#query_search_box").val(), false, 'execute_query');
});

// Download excel - set query
function exportExcel(){
    console.log('export excel');
    $("#excel_query").val($("#query_string").val());
}

// get result for a given query
function getResult(query_text, transpose_result = false, query_type = 'execute_query'){
    hideRemoveResult();

    var jsonData = {};
    jsonData['query'] = query_text;
    jsonData['query_type'] = query_type;
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
            // Display errors
            if(data['status'] == 'error'){
                text += data['message'];
                $("#error_response").append(text);
                $(".error_span").show();
                $('#display_errors').append("<strong>"+data['message']+"</strong> <br>");
            }
            // Display result
            else{
                displayResultQueryAjax(data, query_type, transpose_result);
            }
        }
    });
}

function replaceStringCharacters(data){
    data = data
            // .replace(/[!]/g, 'ɪ')
            .replace('!', 'ɪ')
            .replace('F1', 'ɛ')
            .replace('F2', 'æ')
            .replace('F3', 'ϋ')
            .replace('F4', 'ø')
            .replace('<', 'ɨ')
            .replace('@', 'ə')
            .replace('>', 'ʉ')
            .replace('+', 'ɯ')
            .replace('&', 'ɑ')
            .replace('$', 'ʊ')
            .replace('%', 'ɔ')
            .replace('#', 'ɒ')
            ;
    console.log(data)
    return data;
}

// Get possible words in search for datatable
function updateStringAccents(data){
    if(data == null){
        return '';
    }
    var result = Array();

    data = replaceStringCharacters(data);
    result.push(data);

    if($("#exact_match").prop("checked")){
        return result;
    }

    // characters possible for given alphabet
    var characters_list = {};
    characters_list.a = Array('à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ά', 'α');
    characters_list.c = Array('ç', 'Ç');
    characters_list.e = Array('έ', 'ε', 'é', 'ê', 'ə́', 'è', 'e');
    characters_list.i = Array('ί', 'ϊ', 'ΐ', 'ι', 'í', 'î', 'ì');
    characters_list.n = Array('ή', 'η');
    characters_list.o = Array('ό', 'õ');
    characters_list.u = Array('ύ', 'ϋ', 'ΰ');

    // Iterate for each character in string
    for (var position = 0; position < data.length; position++) {
        if(characters_list.hasOwnProperty(data[position])){
            for (var char_list_pos = 0; char_list_pos < characters_list[data[position]].length; char_list_pos++) {
                let tmp_string = data.substring(0, position) + characters_list[data[position]][char_list_pos] + data.substring(position + 1);
                result.push(tmp_string);
            }
        }
    }
    console.log(result);
    return result.join('|');
}

// display the terms and conditions popup
function exactMatchChange(){
    console.log($("#exact_match").val());
    console.log($("#exact_match").prop("checked"));
    // $("#language_citation_table").DataTable().reload();
    if($("#exact_match").prop("checked")){
        exact_match = true;
    }
    else{
        exact_match = false;
    }
    getLanguageCitation();
}

function getResultFormText(query_type, transpose_result){
    form_text = '';
    form_text += '<div class="h30p">';
    form_text += '   <form action="export_excel.php" method = "post" id="excel_form" class="tal">';
    form_text += '       <div class="row">';
    form_text += '           <div class="col-md-3">';
    form_text += '               <input type="submit" name="excel_submit" value="Download Results" id="excel_submit" class="main_btn" onclick=exportExcel() />';
    form_text += '           </div>';
    form_text += '           <div class="col-md-3">';
    if(exact_match == true){
        form_text += '               <input type="checkbox" id="exact_match" name="exact_match" class="exact_match h20p w20p" value="Exact match" onchange= "exactMatchChange()" checked>';
    }
    else{
        form_text += '               <input type="checkbox" id="exact_match" name="exact_match" class="exact_match h20p w20p" value="Exact match" onchange= "exactMatchChange()">';
    }
    form_text += '                   <label for="exact_match" class="main_btn">Exact match</label>';
    form_text += '                   <a href="" class="tooltip"><img src="img/help.png" class="h20p w20p cp mrb10"/>';
    form_text += '                       <span>If this box is checked, the search will match for the exact string entered. This means, for instance, that a character without an accent diacritic will not match a character with one in the transcribed data.</span>';
    form_text += '                   </a>';
    form_text += '           </div>';
    form_text += '           <div class="col-md-3">';
    form_text += '           </div>';
    form_text += '           <div class="col-md-3">';
    form_text += '                   <label for="easy_match" class="main_btn">Easy match</label>';
    form_text += '                   <a href="" class="tooltip_easy_match"><img src="img/help.png" class="h20p w20p cp mrb10"/>';
    form_text += '                       <span>In order to make it easy to search for characters that are not on the regular keyboard, we can program the search boxes so that a character that is easy to type can be used for another character. <br> Type the easy character on the <b>Column 1</b> of the table. The result would contain string matching on the <b>Column 2</b> of the table.<br>';
    form_text += '                          <table id="easy_match_table" class = "table table-striped table-dark table-bordered table-hover table-sm  table-fixed" style="height:20px !important; overflow-y: auto !important;">';
    form_text += '                              <tr>';
    form_text += '                                  <th><b>Column 1</b></th>';
    form_text += '                                  <th><b>Column 2</b></th>';
    form_text += '                              </tr>';
    form_text += '                              <tr>';
    form_text += '                                  <th>!</th>';
    form_text += '                                  <th>ɪ</th>';
    form_text += '                              </tr>';
    form_text += '                              <tr>';
    form_text += '                                  <th>F1</th>';
    form_text += '                                  <th>ɛ</th>';
    form_text += '                              </tr>';
    form_text += '                              <tr>';
    form_text += '                                  <th>F2</th>';
    form_text += '                                  <th>æ</th>';
    form_text += '                              </tr>';
    form_text += '                              <tr>';
    form_text += '                                  <th>F3</th>';
    form_text += '                                  <th>ϋ</th>';
    form_text += '                              </tr>';
    form_text += '                              <tr>';
    form_text += '                                  <th>F4</th>';
    form_text += '                                  <th>ø</th>';
    form_text += '                              </tr>';
    form_text += '                              <tr>';
    form_text += '                                  <th><</th>';
    form_text += '                                  <th>ɨ</th>';
    form_text += '                              </tr>';
    form_text += '                              <tr>';
    form_text += '                                  <th>@</th>';
    form_text += '                                  <th>ə</th>';
    form_text += '                              </tr>';
    form_text += '                              <tr>';
    form_text += '                                  <th>></th>';
    form_text += '                                  <th>ʉ</th>';
    form_text += '                              </tr>';
    form_text += '                              <tr>';
    form_text += '                                  <th>+</th>';
    form_text += '                                  <th>ɯ</th>';
    form_text += '                              </tr>';
    form_text += '                              <tr>';
    form_text += '                                  <th>&</th>';
    form_text += '                                  <th>ɑ</th>';
    form_text += '                              </tr>';
    form_text += '                              <tr>';
    form_text += '                                  <th>$</th>';
    form_text += '                                  <th>ʊ</th>';
    form_text += '                              </tr>';
    form_text += '                              <tr>';
    form_text += '                                  <th>%</th>';
    form_text += '                                  <th>ɔ</th>';
    form_text += '                              </tr>';
    form_text += '                              <tr>';
    form_text += '                                  <th>#</th>';
    form_text += '                                  <th>ɒ</th>';
    form_text += '                              </tr>';
    form_text += '                          </table>';
    form_text += '                      </span>';
    form_text += '                   </a>';
    form_text += '           </div>';
    form_text += '       </div>';
    form_text += '<input type="hidden" name="excel_query" id="excel_query" value="'+$("#query_string").val()+'"/>';
    form_text += '<input type="hidden" name="excel_query_type" id="excel_query_type" value="'+query_type+'"/>';
    form_text += '<input type="hidden" name="transpose_result" id="transpose_result" value="'+transpose_result+'" />';
    form_text += '<input type="hidden" name="language" id="language" value="'+$("#select_language").val()+'" /></form>';
    return form_text;
}

function getTransposeResultText(data, query_type){
    var data_keys = Object.getOwnPropertyNames(data[0]);
    transposed_text = '';
    transposed_text += '<thead>';
    for (i = 0, len = data.length; i < len; i++) {
        // add rows
        for (j = 0; j < data_keys.length; j++){
            transposed_text += '<tr>';
            transposed_text += '<th>'+[data_keys[j]]+'</th>';
            if(query_type == 'speaker_query'){
                if(data_keys[j] == 'LanguageName'){
                    language_list = data[i][data_keys[j]].split(',');
                    transposed_text += "<th>";
                    for (language_list_count = 0; language_list_count < language_list.length; language_list_count++){
                        transposed_text += "<p onclick = getLanguageCitation('"+language_list[language_list_count]+"')><h class='cp tsn cl5b9bd1'>"+language_list[language_list_count]+"</h>";
                        if(language_list_count == language_list.length - 1){
                            transposed_text += " </p>";
                        }
                        else{
                            transposed_text += ", </p>";
                        }
                    }
                }
                else{
                    transposed_text += '<th>'+data[i][data_keys[j]]+'</th>';
                }
            }
            else{
                transposed_text += '<th>'+data[i][data_keys[j]]+'</th>';
            }
            transposed_text += '</tr>';
        }
    }
    transposed_text += '</thead>';
    transposed_text += '</tbody>';
    return transposed_text;
}

function getUnTransposeResultText(data, query_type){
    var data_keys = Object.getOwnPropertyNames(data[0]);
    untransposed_text = '';
    untransposed_text += '<thead>';
    untransposed_text += '<tr>';
    for (j = 0; j < data_keys.length; j++){
        // untransposed_text += '<th>'+data_keys[j]+'</th>';

        if(query_type == 'language_citation'){
            if((data_keys[j] == 'concept_name') || (data_keys[j] == 'sr_no')){
                untransposed_text += '<th>'+data_keys[j]+'</th>';
            }
            else{
                speaker_name = data_keys[j].split('-')[0];
                untransposed_text += "<th onclick = getSpeakerInfo('"+speaker_name+"')><h class='cp tsn cl5b9bd1'>"+data_keys[j]+"</h></th>";
            }
        }
        else{
            untransposed_text += '<th>'+data_keys[j]+'</th>';
        }
    }
    untransposed_text += '</tr>';
    untransposed_text += '</thead>';
    untransposed_text += '<tbody>';
    for (i = 0, len = data.length; i < len; i++) {
        // add rows
        untransposed_text += '<tr>';
        for (j = 0; j < data_keys.length; j++){
            untransposed_text += '<td>'+data[i][data_keys[j]]+'</td>';
        }
        untransposed_text += '</tr>';
    }
    untransposed_text += '</tbody>';

    if(query_type == 'language_citation'){
        // add footer for filter
        untransposed_text += '<tfoot>';
        untransposed_text += '<tr>';
        for (j = 0; j < data_keys.length; j++){
            untransposed_text += '<th>'+data_keys[j]+'</th>';
        }
        untransposed_text += '</tr>';
        untransposed_text += '</tfoot>';
    }
    return untransposed_text;
}

function displayDataTableLanguageCitation(){
    // Datatable upgraded
    $('#language_citation_table tfoot th').each( function () {
        var title = $(this).text();
        $(this).html( "<input type='text' placeholder='Search "+title+"' />" );
    } );


    // DataTable
    language_citation_table_datatable = $('#language_citation_table').DataTable({
        // "searching": false,
        "search": {regex: true},
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
                var column_val = this;
                $( 'input', this.footer() ).on( 'keyup change clear', function () {
                    var current_val = this.value;
                    if ( column_val.search() !== current_val ) {
                        
                            // column_val
                            // .search(updateStringAccents( current_val ),true,false,true)
                            // .search( $.fn.DataTable.ext.type.search.string( current_val ) )
                            // .draw();

                            if($("#exact_match").prop("checked")){
                                column_val
                                .search( '^.*'+updateStringAccents(current_val)+ '[a-zA-Z 0-9!@#$&()\\-.+\',/\"]*$',true,false,true)
                                .draw();
                            }
                            else{
                                column_val
                                .search(updateStringAccents( current_val ),true,false,true)
                                .draw();
                            }

                    }
                } );
            });

            // if($("#exact_match").prop("checked")){
            // $(this)
            //     .search( '^.*'+updateStringAccents(current_val)+ '[a-zA-Z 0-9!@#$&()\\-.+\',/\"]*$',true,false,true)
            //     .draw();
            // }
            // else{
            // $(this)
            //     .search(updateStringAccents( current_val ),true,false,true)
            //     .draw();
            // }
        },
    });
    // $('#div.dataTables_filter input').keyup(function(){
    //     // oTable.search($(this).val()).draw() ;
    //     console.log(this)
    // })
    $("div.dataTables_filter input").keyup( function (e) {
        // language_citation_table_datatable.search($(this).val()).draw() ;
        if($("#exact_match").prop("checked") && ($(this).val() != "")){
            language_citation_table_datatable
            .search( '^.*'+updateStringAccents($(this).val())+ '[a-zA-Z 0-9!@#$&()\\-.+\',/\"]*$',true,false,true)
            .draw();
        }
        else{
            language_citation_table_datatable
            .search(updateStringAccents( $(this).val() ),true,false,true)
            .draw();
        }
    });
}

function displayResultQueryAjax(data, query_type, transpose_result){
    var query_string = data['query_string'];
    $("#query_string").val(query_string);

    form_text = getResultFormText(query_type, transpose_result);
    var text = "";
    text += form_text;

    // List text
    data = data['message'];

    // Create table
    if(query_type == 'speaker_query'){
        text += '<table id="speaker_table" class = "table table-striped table-bordered table-hover">';
    }
    else if(query_type == 'language_citation'){
        text += '<table id="language_citation_table" class = "table table-striped table-bordered table-hover">';
    }
    else{
        text += '<table class = "table table-striped table-bordered table-hover">';
    }
    
    // Get Transposed/Untransposed Result
    if(transpose_result){
        transposed_text = getTransposeResultText(data, query_type);
        text += transposed_text;
    }
    else{
        untransposed_text = getUnTransposeResultText(data, query_type);
        text += untransposed_text;
    }
    // Close table
    text += '</table>';
    $("#response").append(text);

    if(query_type == 'language_citation'){
        displayDataTableLanguageCitation();
    }
}

function hideRemoveResult(){
    $("#response").html('');
    $("#error_response").html('');
    $(".error_span").hide();
    $("#error").empty();
}
</script>