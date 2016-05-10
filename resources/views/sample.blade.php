@extends('layouts/layout')

@section('content2')
    <?php $web_server = ''; // env('WEB_HOST', "http://localhost"); 
        use EID\Models\User as User;
    ?>

    <link rel="stylesheet" href="{{$web_server}}/css/pikaday.css">

    <!-- Select2 -->
    <link   href="{{$web_server}}/css/select2.min.css" rel="stylesheet" />

    <script src="{{$web_server}}/js/select2.min.js"></script>
    <script src="{{$web_server}}/js/plugins/notify.min.js"></script>
    <script src="{{$web_server}}/js/plugins/jquery.validate.min.js"></script>
    
    <style type="text/css">
        /*.container{
            width: 98%;
        }*/
        .dbs_header{
            background-color: #6F6A8F;
            border: 1px solid #6F6A8F;
            font-family: 'Segoe UI';
            font-size: 12px;
            font-weight: 600;
        }
        .datepicker{
            font-size: 12px;
            width: 8em;
            text-align: center;
        }
        select { 
            font-family: 'Segoe UI', arial; 
            font-size: 12px;
        }
        #dbs_samples  input {
        /* should be exactly the same as select, below */
            font-family: 'Segoe UI', arial;  
            font-size: 1.1em;
            text-align: center;
        }

        #dbs_samples  select {
        /* should be exactly the same as input, above */
            font-family: 'Segoe UI', arial; 
            font-size: 1.1em;
            text-align: center;
        }


        #dbs_status{
            background-color:yellow; 
            text-align: center;
            font-size: 1.5em;
            color: red;
        }

        td.narrow_column{
            /* make it as small as possible. (bigger elements will stretch)  */

            width: 1em;
            height: 2.5em;
            text-align: center;
        }

        .xl {
            border: none;
            border-bottom: 1px dotted gray;            
        }


        #envelope_number {
            font-size:2em; 
            width:225px; 
            text-align:center; 
            color:#A9BCF5; 
            font-family: 'Lucida Typewriter', 'Courier New', Courier, monospace; 
            font-weight:bold;
        }

        #batch_number {
            font-size:2em; 
            width:175px;
            color:#F5BCA9; 
            text-align:center; 
            font-family: 'Lucida Typewriter', 'Courier New', Courier, monospace; 
            font-weight:bold;
        }

        .submit_samples {

            float:right;
            width:11em; 
            height: 2em; 
            color: #fff;
            background-color:LightBlue; 
            border:2px solid #ccc;
            margin-right: 0em;
            margin-top: 0.2em;
            margin-bottom: 0.2em;

        }
       

        .smpls {
            font-size:11px;
            font-family: 'Segoe UI', arial;
            width: 91%;
            margin-left: 5%;
        }

        .checkboxes label {
            font-size: 11px;
            color: #656;
            border-radius: 3px;
            background: #f0f0f0;
            padding: 2px 5px;
            text-align: left;
        }

        input[type=checkbox]:checked + label {
            color: white;
            background: #86b3c1;
        }

        /* same as checked+label above, but easier to operate via jQuery */
        .highlight_label {
            color: white;
            background: #86b3c1;
        }


        .highlight_row {
            background: #F296C6;
            color: red
        }

        .error{
            color: red
        }

        .data_error{
            background: yellow;
            color: red
        }
    </style>

  </head>

<?php 



    function testRequested($test_type, $sample)
    {
        $test = $test_type."_test_requested";

        if(empty($sample[$test])) 
            return false;/* no data */

        if($sample[$test] === "YES") 
            return true;

        return false;
    }


    function isset_get($array, $key, $default = null) {
        return isset($array[$key]) ? $array[$key] : $default;
    }


  function create_PMTCT_Option($row){


        $option = new StdClass;

        if( $row === null ){

        // make a blank <option>
            $option->value = "";
            $option->visibleText = "";

        }else{

            $option->value = $row->id;
            $option->visibleText = $row->code. ": " . $row->appendix;
        }

        return $option;
    }

    function print_options($pmtct_options, $option_used){

        $options = "";
        $nOptions = count($pmtct_options); 


    // first option is always blank
        $options .= "\n\t";
        $options .= "<option></option>";


        for($i=0; $i < $nOptions; $i++){

            $this_option = $pmtct_options[ $i ];
            $selected = ($option_used === $this_option->value) ? ' selected="YES" ' : "";

            $options .= "\n\t";
            $options .= "<option value='" . $this_option->value . "' " . $selected . ">";
            $options .= $this_option->visibleText;
            $options .= "</option>";

        }

        return $options;
    }

    function mk_PMTCT_Select($categoryID, $r = 0, $t = 0, $pmtct_used) {


    // step 1: initialize required variables
        static $need_to_fetch_data = true;

        // PMTCT <option>s for each stage of pregnancy:
        static $ante_natal_care = [];// create_PMTCT_Option(null);
        static $delivery_care = [];//create_PMTCT_Option(null);
        static $post_natal_care = [];//create_PMTCT_Option(null);
        static $infant_care = [];//create_PMTCT_Option(null);
            

        $ep = array(
            
            "id"    =>  $categoryID . "_" . $r,
            "css"   =>  "border:none; border-bottom:1px dotted gray; width: 7em;",
            "sql"   =>  "SELECT * FROM  appendices ORDER BY id ASC"
        );
        $tabIndex = ($t === 0)? '"' :  '" tabindex="' . $t . '" '  ;

    // step 2:  fetch data, if necessary. This is done only once. 
    //          DB results are saved into static variables, above, for future use.
        if( $need_to_fetch_data ){

            
            $results = DB::select( $ep["sql"] );
            $need_to_fetch_data = false;


            // types of PMTCT care:
            $MOTHER_PROPHYLAXIS = ''; 
            $INFANT_PROPHYLAXIS = 4;

            // stages of pregnancy: 
            $ANTE_NATAL = 1;
            $DELIVERY = 2;
            $POST_NATAL = 3;


            foreach ($results as $row) {

                //$ptype = $row->ptype;// prophylaxis type
                $pregnancy_stage = $row->categoryID;


                if($pregnancy_stage == $ANTE_NATAL){

                    $ante_natal_care[] = create_PMTCT_Option($row);

                }
                elseif($pregnancy_stage == $DELIVERY)
                    $delivery_care[] = create_PMTCT_Option($row); 
                
                elseif($pregnancy_stage == $POST_NATAL)
                    $post_natal_care[] = create_PMTCT_Option($row); 
                
                elseif($pregnancy_stage == $INFANT_PROPHYLAXIS)
                    $infant_care[] = create_PMTCT_Option($row);
            }
        } 


    // step 3: create all the drop-down lists

        $select = "";
        $params = "";

        $params .= ' r="' . $r . '"';
        $params .= ' id="' . $ep["id"] . '"';
        $params .= ' appendix="' . $ep["id"] . $tabIndex;
        $params .= ' style="' . $ep["css"] . '"';
        $params .= ' class="xl pcr_' . $r . '"' ;
        $params .= ' disabled="YES" ';
        
        $option_used = $pmtct_used;

        

    // step 4: select and return the required drop-down list (just one)

        switch ($categoryID) {
            case 'mother_antenatal_prophylaxis': 
                    $ante_natal_options =   "<select " . $params . ">" .
                                                print_options($ante_natal_care, $option_used) . "\n" .
                                            "</select>";
                    return $ante_natal_options;

            case 'mother_delivery_prophylaxis': 
                    $delivery_options = "<select " . $params . ">" . 
                                            print_options($delivery_care, $option_used) . "\n" . 
                                        "</select>";
                    return $delivery_options;

            case 'mother_postnatal_prophylaxis': 
                    $post_natal_options =   "<select " . $params . ">" . 
                                                print_options($post_natal_care, $option_used) . "\n".
                                            "</select>";
                    return $post_natal_options;

            case 'infant_prophylaxis': 
                    $infant_options =   "<select " . $params . ">" . 
                                            print_options($infant_care, $option_used) . "\n" . 
                                        "</select>";
                    return $infant_options;
            
            default:  return null;
        }
    }


    function mk_EntryPoint_Select($r = 0, $t = 0, $entryPoint_id) {

        $ep = array(
            
            "id"    =>  "infant_entryPoint_" . $r,
            "css"   =>  "border:none; border-bottom:1px dotted gray; width: 7em",
            "sql"   =>  "SELECT ID as id, name FROM entry_points ORDER BY name ASC"
        );

        $rowNum = ($r === 0) ? "" : $r;
        $tabIndex = ($t === 0)? "\"" :  '" tabindex="' . $t . '" '  ;

        $id = isset_get($ep, "id", "--nada--");
        $css = isset_get($ep, "css", "--nada--");
        $sql = isset_get($ep, "sql", "--nada--");
        
        $select = "";
        $params = "";

        $params = $params . 'class="xl"';
        $params = $params . ' r="' . $r . '"';
        $params = $params . 'id="' . $id . '" name="' . $id . $tabIndex;
        $params = $params . " style=\"" . $css . "\"";

        $results = DB::select( $ep['sql'] );

        $options = "<option></option>";

        foreach ($results as $row) {
            $selected = "";
            $selected = ($entryPoint_id === $row->id) ? ' selected="YES" ' : "";
            $options .= "\n\t<option value='" . $row->id . "' " . $selected . ">" . $row->name . "</option>";
        }
        $select = "<select " . $params . ">" . $options . "\n</select>";

        return "\n\n" . $select . "\n\n";
    }

    function getUserFullName($userID)
    {

        $usr = User::find($userID);

        if( $usr == null ) 
            return "UNKNOWN";
        else 
            return $usr->family_name . " " . $usr->other_name;
    }

    $batch = empty($batch)? [] : $batch;
    $entered_by = empty($batch)? Session::get('username') : getUserFullName($batch->entered_by);
?>

<div id="s1" class="smpls mm">
    <?php echo Form::model($batch, array('url' => '/batch', 'id'=>'xf') ) ?>
    <table width="860px" align="center" border="0" id="batch_data">
        <tr>
            <th colspan="4" style="text-align: center; color:white; font-size:1.2em">
                PCR DRIED BLOOD SPOT Dispatch Form 
            </th>
        </tr>
        <tr>
            <td>
                <img src="{{$web_server}}/images/coat-of-arms.jpg" 
                     style="width:100px; height:auto;float:right; margin-right:12px;">
            </td>
            <td>
                <div style="width: 225px; text-align:center;">{!! Form::label('envelope_number', 'Envelope Number:') !!}</div>

                {!! Form::text('envelope_number', null, 
                                array("id"=>"envelope_number", "required"=>"YES", "tabindex"=>"1") ) !!}
            </td>
            <td>
                <div style="width: 175px; text-align:center;">
                    Batch Number:
                </div>

                {!! Form::text('batch_number', null, 
                                array("id"=>"batch_number", "required"=>"YES", "tabindex"=>"2") ) !!}

                {!! Form::hidden('batch_checksum', null, 
                                array("id"=>"batch_checksum", "required"=>"YES") ) !!}

                <? /* stores DB row id */ ?>
                {!! Form::hidden('id', null, 
                                array("id"=>"id", "required"=>"YES") ) !!}

            </td>
            <td align="right" valign="top">Entered by: &nbsp;{{ $entered_by }}
            </td>
        </tr>
        <tr>
            <td><div style="width:100px; display:block; float:right; margin-right:10px;">Health Unit:</div></td>
            <td> 
<?php 
      
                $SQL = "SELECT  facilities.id AS facility_id, 
                                facilities.facility AS facility_name, 
                                districts.name AS district, districtcode 

                            FROM    facilities, districts 

                            WHERE   facilities.districtID  = districts.id 

                            ORDER BY    district, facility_name";

                $results = DB::select( $SQL );
                $nFacilities = count($results);
                $district = "_NONE_";
?>
                <select class="js-example-basic-single" style="width:225px; " 
                                id="facility_selector" name="facility_selector" tabindex="3">
                        <option></option>
                    @for($i=0; $i < $nFacilities; $i++)
                        <?php $facility = $results[ $i ] ?>

                            @if( $district !== $facility->district )
                                
                                @if( $district !== "_NONE_" )
                                    </optgroup>
                                @endif

                                {{ $district =  $facility->district }}
                                <optgroup label="{{ $district }}">
                            @endif
                        <?php   

                            $v = json_encode($facility);
                            $this_facility_id = empty($batch)? "" : $batch->getFacilityID();
                            $selected = "";

                            if($facility->facility_id === $this_facility_id){
                                $selected = ' selected="YES" ';
                            } 
                        ?>
                        <option value="{{ $v }}" {{ $selected }}>{{ $facility->facility_name }}</option>
                    @endfor



                </select>
            
                
            </td>
            <td rowspan="4">
                <div style="width: 175px; text-align:center;">
                <b>Site Return Address</b><br>
                (Clinic / Department)<br>
                </div>
                {!! Form::textarea('results_return_address', null, 
                array("id"=>"results_return_address", "style"=>"height: 5em; width:175px;", "tabindex"=>"7") ) !!}


            </td>
            <td rowspan="4" align="right">
                <div style="width: 175px; text-align:center;">
                    &nbsp;<br>
                    Comments/Issues:
                </div>
                {!! Form::textarea('senders_comments', null, 
                array("id"=>"senders_comments", "style"=>"height: 5em; width:175px;", "tabindex"=>"8") ) !!}
    
            </td>
        </tr>
        <tr>
            <td><div style="width:100px; display:block; float:right; margin-right:10px;">District:</div> </td>
            <td> 
                {!! Form::hidden('facility_id', null, 
                                array("id"=>"facility_id", "required"=>"YES" ) ) !!}

                {!! Form::hidden('facility_name', null, 
                                array("id"=>"facility_name", "required"=>"YES") ) !!}

                {!! Form::text('facility_district', null, 
                        array("id"=>"facility_district", "required"=>"YES", "tabindex"=>"4",
                                    "readonly"=>"true", "style"=>"width:225px; text-transform: uppercase", "class"=>"xl" ) ) !!}
            </td>
        </tr>
        <tr>
            <td><div style="width:100px; display:block; float:right; margin-right:10px;">Name of sender:</div> </td>
            <td> 
                {!! Form::text('senders_name', null, 
                                array("id"=>"senders_name",  
                                        "style"=>"width:225px;", "class"=>"xl", "tabindex"=>"5") ) !!}

            </td>
        </tr>
        <tr>
            <td><div style="width:100px; display:block; float:right; margin-right:10px;">Telephone No:</div> </td>
            <td> 
                {!! Form::text('senders_telephone', null, 
                        array("id"=>"senders_telephone", 
                                "style"=>"width:225px;", "class"=>"xl phone", "tabindex"=>"6") ) !!}
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding-bottom: 0px; padding-top: 25px;">

                <div style="margin-bottom:5px;" >
                    Date Samples Dispatched from health unit: 

                    {!! Form::text('date_dispatched_from_facility', null, 
                            array("id"=>"date_dispatched_from_facility", 
                                "class"=>" xl", 
                                "required"=>"YES", "tabindex"=>"9") ) !!}

                </div>
                How will samples be transported back:
                <select style=" width:11em; " class="xl"  tabindex="10"  disabled="yes" id="results_transport_method" name="results_transport_method" >
                    <option value="POSTA_UGANDA">1.POSTA UGANDA</option>
                    <option value="COLLECTED_FROM_LAB">2.PICK FROM LAB DIRECTLY</option>
                </select>
            </td>
            <td colspan="2"  style="padding-bottom: 0px; padding-top: 25px;">
                <div style="margin-bottom:5px;float:left; " >
                    <div>
                        <div style="float:left">Name of Testing Lab:&nbsp;</div>
                        <select  style="FLOAT:LEFT" class="xl"  tabindex="11" id="lab" name="lab" disabled="yes">
                            <option>CPHL</option>
                        </select>
                    </div>
                    <div style="clear:both">
                        
                    <div style="float:left">Date samples received at lab:&nbsp;</div>
                        {!! Form::text('date_rcvd_by_cphl', null, 
                                        array("id"=>"date_rcvd_by_cphl", "required"=>"YES", 
                                                "tabindex"=>"12", "class"=>"xl" ) ) !!}

                    </div>
                </div>
                <input type="submit" style="float:right;width:11em; height: 3em;"  
                        tabindex="13" id="submit_button" name="submit_button" value="SAVE BATCH DATA"/>
            </td>
        </tr>
    </table>
    <?php echo Form::close() ?>

<!--the pcr form details         -->


    <table id="dbs_samples" align="center" style="border:3px solid #6F6A8F"  >
            
        <tr style="border:1px solid #6F6A8F">

            <th rowspan="2" class="dbs_header">No.</th>
            <th rowspan="2" class="dbs_header"><center>Date of<br>Collection</center></th>
            <th rowspan="2" class="dbs_header">Infant Name</th>
            <th rowspan="2" class="dbs_header">EXP <br />Number</th>
            <th rowspan="2" class="dbs_header">Sex <br/>(M/F)</th>
            <th rowspan="2" class="dbs_header"><center>Age <br/>(Mths)</center></th>
            <th rowspan="2" class="dbs_header">Caregiver's<br/>Phone No.</th>
            <th rowspan="2" class="dbs_header">Entrypoint<br/>Clinic</th>
            <th rowspan="2" class="dbs_header" 
                title="PCR = for HIV and SCD = for Sickle Cell" >Type of Test</th>
            <th rowspan="2" class="dbs_header">1<sup>st</sup> or 2<sup>nd</sup> PCR?</th>
            <th rowspan="2" class="dbs_header">Non <br>Routine<br> PCR <br>R1/R2</th>
            <th rowspan="2" class="dbs_header">Breast Feeding? <br/>(Y/N)</th>
            <th     colspan="3" class="dbs_header">Mother PMTCT ARVs <br/>(use codes)</th>
            <th rowspan="2" class="dbs_header">Infant's PMTCT ARVs <br/>(use codes)</th>            

      </tr>
      <tr>
            <th class="dbs_header"><div align="center">Ante-Natal</div></th>
            <th class="dbs_header"><div align="center">Delivery</div></th>
            <th class="dbs_header"><div align="center">Post-Natal</div></th>
                
                
            
    </tr>
    <tr>
        <td colspan="15">
            <div id="dbs_status" ></div>
        </td>
    </tr>


<?php 
    
    if( empty($batch) ){
        $samples = [];
    }else{

        $samples =  $batch->samples;// empty($samples) ? [] : $samples;     
    }

?>

@for($col=14, $i=1, $j=0; $i <= 7; $i++, $j++)
<!-- $col=14 because... 14 = tabIndex of 1st input in the loop (i.e. 1st input after header) -->


    <?php   $this_sample = empty( $samples[ $j ] ) ? [] : $samples[ $j ]; ?>
    <?php   $sample_id = empty($this_sample->id) ? "" : $this_sample->id; ?>  
    <?php   $sample_verified = empty($this_sample->sample_rejected) ? "NO" : (($this_sample->sample_rejected === "NOT_YET_CHECKED") ? "NO" : "YES") ; ?>  


    <?php echo Form::model($this_sample, array('url'=>'/dbs')); ?>
    <tr class="parent_row" id="row_{{ $i }}" row="{{ $i }}">
        <td align="center" class="narrow_column" >{{ $i }}.</td>
        <td class="narrow_column">

        <?php $rw = empty($this_sample->date_dbs_taken) ? "readonly" : "readwrite"; ?>

            {!! Form::text("date_dbs_taken", null, 
                    array("id"=>"date_dbs_taken_".$i, "required"=>"YES", 
                                "tabindex"=>$col++, "class"=>"datepicker xl dbs_date",
                                    "r"=>"$i", "$rw"=>"yes" ) ) !!}

            {!! Form::hidden("checksum", null, 
                    array("id"=>"checksum_".$i, "required"=>"YES", "r"=>"$i", 
                                "tabindex"=>$col++ ) ) !!}

            {!! Form::hidden("sample", $sample_id, 
                    array("id"=>"sample_".$i, "r"=>"$i", 
                            "required"=>"YES", "tabindex"=>$col++ ) 
                                                    ) !!}


            {!! Form::hidden("sample_verified", $sample_verified, 
                    array("id"=>"sample_verified_".$i, "r"=>"$i", 
                            "required"=>"YES", "tabindex"=>$col++ ) 
                                                    ) !!}


        </td>
        <td class="narrow_column">

            {!! Form::text("infant_name", null, 
                    array("id"=>"infant_name_".$i, "required"=>"YES", "r"=>"$i", 
                                "class"=>"xl capitalized", "tabindex"=>$col++ ) ) !!}
        </td>
        <td class="narrow_column">
            {!! Form::text("infant_exp_id", null, 
                    array("id"=>"infant_exp_id_".$i, "required"=>"YES", "r"=>"$i", 
                                "class"=>"xl", "style"=>"width:5em;", "tabindex"=>$col++ ) ) !!}

        <td class="narrow_column">
            {!! Form::select("infant_gender", 
                    array(""=>"", "MALE"=>"M", "FEMALE"=>"F", "NOT_RECORDED"=>"Blank"), null,
                        array("id"=>"infant_gender_".$i, "required"=>"YES","r"=>"$i", 
                                    "class"=>"xl", "tabindex"=>$col++ ) ) !!}
        </td>
        <td class="narrow_column">
            {!! Form::text("infant_age", null, 
                    array("id"=>"infant_age_".$i, "style"=>"width:100px;", "r"=>"$i", 
                            "class"=>"xl ageFmt", "tabindex"=>$col++ ) ) !!}

            {!! Form::hidden("infant_dob", null, 
                    array("id"=>"infant_dob_".$i, "required"=>"YES", "r"=>"$i", 
                                "tabindex"=>$col++ ) ) !!}
        </td>
        <td class="narrow_column">
            {!! Form::text("infant_contact_phone", null, 
                    array("id"=>"infant_contact_phone_".$i, "required"=>"YES", "style"=>"width:125px;", "r"=>"$i", 
                                "class"=>"xl phone", "tabindex"=>$col++ ) ) !!}
        </td>
        <td class="narrow_column"> 

            <?php $entry_point = ( empty($this_sample) ) ? "" : $this_sample->getEntryPoint()  ?>

            <?php echo Form::select("infant_entryPoint_".$i,
                               [""=>""] + EID\Models\Appendix::appendicesArr2(8,false), $entry_point,
                               ['id'=>'infant_entryPoint_'.$i,'class'=>'xl','r'=>$i, 'tabindex'=>$col++]) ?>
    
        </td>
        <td nowrap="yes" class="narrow_column">
            <div class="checkboxes" style="width:7em;">

                <?php $PCR_selected = testRequested("PCR", $this_sample); ?>
                <?php $SCD_selected = testRequested("SCD", $this_sample); ?>


                {!! Form::checkbox("PCR_test_requested_$i", "YES", $PCR_selected,
                        array("id"=>"PCR_test_requested_".$i,  "style"=>"position:absolute;left: -5000px;", 
                             "type_of_test"=>"PCR", "do_pcr"=>"$i", "r"=>"$i", "class"=>"xl highlight", "tabindex"=>$col++ ) ) !!}
                <label for="PCR_test_requested_{{$i}}" class="xl highlight">PCR</label>
                

                {!! Form::checkbox("SCD_test_requested_$i", "YES", $SCD_selected,
                        array("id"=>"SCD_test_requested_".$i,  "style"=>"position:absolute;left: -5000px;", 
                                "type_of_test"=>"SCD", "do_scd"=>"$i", "r"=>"$i", "class"=>"xl highlight", "tabindex"=>$col++ ) ) !!}
                <label id="PCR_label_{{$i}}" for="SCD_test_requested_{{$i}}" class="xl highlight">SCD</label>


            </div>

        </td>
        <td class="narrow_column">
            {!! Form::select("pcr", 
                    array(""=>"", "FIRST"=>"1st", "SECOND"=>"2nd","UNKNOWN"=>"Blank", "NON_ROUTINE"=>"Other"), null,
                        array("id"=>"pcr_".$i, "disabled"=>"YES", "r"=>"$i" ,
                                    "class"=>"xl pcr_".$i, "tabindex"=>$col++ ) ) !!}
        </td>
        <td class="narrow_column">
            {!! Form::select("non_routine", 
                    array(""=>"Blank", "R1"=>"R1", "R2"=>"R2"), null,
                        array("id"=>"non_routine_".$i,  "disabled"=>"YES", "r"=>"$i" ,
                                "class"=>"xl pcr_".$i, "tabindex"=>$col++ ) ) !!}
        </td>
        <td class="narrow_column">  
            {!! Form::select("infant_is_breast_feeding", 
                    array(""=>"", "YES"=>"Yes", "NO"=>"No", "UNKNOWN"=>"Blank"), null, 
                        array("id"=>"infant_is_breast_feeding_".$i,  "disabled"=>"YES","r"=>"$i" ,
                                    "class"=>"xl pcr_".$i, "tabindex"=>$col++ ) ) !!}

        </td>
        <td class="narrow_column">
            <?php $ante_natal = ( empty($this_sample) ) ? "" : $this_sample->getAnteNatalPMTCT(); ?>
            <?php echo mk_PMTCT_Select("mother_antenatal_prophylaxis", $i, $col++, $ante_natal ); ?>
        </td>
        <td class="narrow_column">
            <?php $delivery = ( empty($this_sample) ) ? "" : $this_sample->getDeliveryPMTCT()  ?>
            <?php echo mk_PMTCT_Select("mother_delivery_prophylaxis" , $i, $col++, $delivery ) ?>
        </td>
        <td class="narrow_column">
            <?php $post_natal = ( empty($this_sample) ) ? "" : $this_sample->getPostNatalPMTCT()  ?>
            <?php echo mk_PMTCT_Select("mother_postnatal_prophylaxis", $i, $col++, $post_natal ) ?>
        </td>
        <td class="narrow_column">
            <?php $infant = ( empty($this_sample) ) ? "" : $this_sample->getInfantPMTCT()  ?>
            <?php echo mk_PMTCT_Select("infant_prophylaxis", $i, $col++, $infant ) ?>
        </td>
    </tr>
    <?php echo Form::close() ?>
@endfor

</table>
    <div style="margin-top: 5px;">
        <button style="float:right; background: blue; color: white; margin-left:1em;" onclick="location.href='/samples';" 
                    id="next_form" type="submit">NEXT FORM</button>                    

        <button id="show_axn_numbers" style="float:right; background: brown; color: white">SHOW ACCESSION NUMBERS</button>

        <button style="float:right; background: blue; color: white; margin-right:1em;" 
                    id="submit_samples_bottom" 
                    type="submit">SAVE ALL</button>
                    <style type="text/css">
                        .capitalize {
                            text-transform: lowercase;
                            text-transform: capitalize;
                        }
                    </style>
    </div>
</div>
    <script src="{{$web_server}}/js/moment.js"></script>
    <script src="{{$web_server}}/js/md5.js"></script>


    <!-- First load pikaday.js and then its jQuery plugin -->
    <script src="{{$web_server}}/js/pikaday.js"></script>
    <script src="{{$web_server}}/js/plugins/pikaday.jquery.js"></script>

    <script src="/js/odiff.umd.js"></script>
    <script src="/js/edit_batches.js"></script>
    <script type="text/javascript">
        @if(empty($batch))
            DBS.IGNORE_EVENTS = false;/* new batch: record data entrant's speed */
        @else
            DBS.IGNORE_EVENTS = true;/* existing batch: ignored */
        @endif
    </script>
    

    <script type="text/javascript">
        jQuery.fn.ucwords = function() {
          return this.each(function(){
            var val = $(this).text(), newVal = '';
            val = val.split(' ');

            for(var c=0; c < val.length; c++) {
              newVal += val[c].substring(0,1).toUpperCase() + val[c].substring(1,val[c].length) + (c+1==val.length ? '' : ' ');
            }
            $(this).text(newVal);
          });
        }
    </script>

    <!-- JavaScript to handle validation + display of infant age -->
    <script src="{{$web_server}}/js/ageFmt.js"></script>

    <!-- JavaScript that is specific to this page -->

    <script src="{{$web_server}}/js/sample.blade.js"></script>

@stop