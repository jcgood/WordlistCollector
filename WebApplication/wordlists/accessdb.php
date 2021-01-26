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
?>
<html lang="en">
    <head>
        <title>Access Database</title>
    </head>
    <body>
        <section>  
            <br><br><br><br><br><br>
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
                text += "<input type='submit' name='get_language_citation' value='Get Wordlist' id='get_language_citation' class='main_btn' onclick=getLanguageCitation() />";
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
    query_sp_1 = "Select id, wordlist_id, concept_id, concept, speaker_name, word, noun_class from `master_word_list` where wordlist = '"+language_value+"' order by concept";
    $("#query_search_box").html(query_sp_1);
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
    var speaker_query = "Select *, group_concat(language_name) as LanguageNames from speaker where speaker_id = '"+speaker_value+"'";
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
            .replace('E', 'ɛ')
            .replace('A', 'æ')
            .replace('Y', 'ϋ')
            .replace('O', 'ø')
            .replace('<', 'ɨ')
            .replace('@', 'ə')
            .replace('>', 'ʉ')
            .replace('W', 'ɯ')
            .replace('&', 'ɑ')
            .replace('$', 'ʊ')
            .replace('%', 'ɔ')
            .replace('#', 'ɒ')
            .replace('V', 'ʊ̈')
            .replace('U', 'ʉ̈')
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
        console.log('exact_match checked');
        console.log(result);
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
    form_text += '           <div class="col-md-3" id = "exact_match_div">';
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
    form_text += '           <div class="col-md-3" id = "easy_match_div">';
    form_text += '                   <label for="easy_match" class="main_btn">Easy match</label>';
    form_text += '                   <a href="" class="tooltip_easy_match"><img src="img/help.png" class="h20p w20p cp mrb10"/>';
    form_text += '                       <span>In order to make it easy to search for characters that are not on the regular keyboard, we can program the search boxes so that a character that is easy to type can be used for another character. <br> Type the easy character on the <b>Column 1</b> of the table. The result would contain string matching on the <b>Column 2</b> of the table.<br>';
    form_text += '                          <table id="easy_match_table" class = "table table-striped table-dark table-bordered table-hover table-sm  table-fixed" style="height:20px !important; overflow-y: auto !important; background-color: #212529 !important">';
    form_text += '                              <tr>';
    form_text += '                                  <th><b>Column 1</b></th>';
    form_text += '                                  <th><b>Column 2</b></th>';
    form_text += '                              </tr>';
    form_text += '                              <tr>';
    form_text += '                                  <th>!</th>';
    form_text += '                                  <th>ɪ</th>';
    form_text += '                              </tr>';
    form_text += '                              <tr>';
    form_text += '                                  <th>E</th>';
    form_text += '                                  <th>ɛ</th>';
    form_text += '                              </tr>';
    form_text += '                              <tr>';
    form_text += '                                  <th>A</th>';
    form_text += '                                  <th>æ</th>';
    form_text += '                              </tr>';
    form_text += '                              <tr>';
    form_text += '                                  <th>Y</th>';
    form_text += '                                  <th>ϋ</th>';
    form_text += '                              </tr>';
    form_text += '                              <tr>';
    form_text += '                                  <th>O</th>';
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
    form_text += '                                  <th>W</th>';
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
    form_text += '                              <tr>';
    form_text += '                                  <th>V</th>';
    form_text += '                                  <th>ʊ̈</th>';
    form_text += '                              </tr>';
    form_text += '                              <tr>';
    form_text += '                                  <th>U</th>';
    form_text += '                                  <th>ʉ̈</th>';
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
                if(data_keys[j] == 'language_name'){
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
            if((data_keys[j] == 'concept') || (data_keys[j] == 'sr_no')){
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
                        column_val
                        .search(updateStringAccents( current_val ),true,false,true)
                        .draw();
                    }
                });
            });
        },
    });
    $("div.dataTables_filter input").keyup( function (e) {
        language_citation_table_datatable
        .search(updateStringAccents( $(this).val() ),true,false,true)
        .draw();
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
    else{
        $("#exact_match_div").hide();
        $("#easy_match_div").hide();
    }
}

function hideRemoveResult(){
    $("#response").html('');
    $("#error_response").html('');
    $(".error_span").hide();
    $("#error").empty();
}
</script>