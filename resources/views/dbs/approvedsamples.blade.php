@extends('layouts/layout')

@section('content')

    <link rel="stylesheet" href="/css/pikaday.css">

    <!-- Select2 -->
    <script src="/js/jquery11.min.js"></script>
    <link   href="/css/select2.min.css" rel="stylesheet" />

    <script src="/js/select2.min.js"></script>



    <style type="text/css">
        body, td{
            font-family: 'Segoe UI', arial;  font-size:11px;
        }

        .dbs_header{
            background-color: #ddd;
            border: 1px solid #ddd;
            font-family: 'Segoe UI';
            font-size: 12px;
            font-weight: 600;
        }
        .readOnly_datepicker{
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
            font-size: 12px;
            text-align: center;
        }

        #dbs_samples  select {
        /* should be exactly the same as input, above */
            font-family: 'Segoe UI', arial; 
            font-size: 12px;
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
            border: none;
        }

        #batch_number {
            font-size:2em; 
            width:175px;
            color:#F5BCA9; 
            text-align:center; 
            font-family: 'Lucida Typewriter', 'Courier New', Courier, monospace; 
            font-weight:bold;
            border: none;
        }
        
        .hide_me{
            display: none;
        }

        .parent_row:hover, input:hover {
            background-color: #ffff99;
        }

        #row_2{
            background-color: LightBlue;   
        }


    </style>



<?php 



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
    function mk_EntryPoint_Select($r = 0, $t = 0, $entryPoint_id, $read_only=true) {

        $ep = array(
            
            "id"    =>  "infant_entryPoint_" . $r,
            "css"   =>  "border:none; border-bottom:1px dotted gray",
            "sql"   =>  "SELECT ID as id, name FROM entry_points ORDER BY name ASC"
        );

        $rowNum = ($r === 0) ? "" : $r;
        $tabIndex = ($t === 0)? "\"" :  '" tabindex="' . $t . '" '  ;

        $id = isset_get($ep, "id", "--nada--");
        $css = isset_get($ep, "css", "--nada--");
        $sql = isset_get($ep, "sql", "--nada--");
        
        $select = "";
        $params = "";
        $params = 'id="' . $id . '" name="' . $id . $tabIndex;
        $params = $params . " style=\"" . $css . "\"";

        $results = DB::select( $ep['sql'] );

        $options = "<option></option>";

        foreach ($results as $row) {

            if($read_only){

                if($entryPoint_id === $row->id){
                    return '<div class="xl" style="padding-bottom: 4px;">' . $row->name . '</div>';
                }

            }else{

                $selected = "";
                $selected = ($entryPoint_id === $row->id) ? ' selected="YES" ' : "";
                $options .= "\n\t<option value='" . $row->id . "' " . $selected . ">" . $row->name . "</option>";                
            }
        }
        $select = "<select " . $params . ">" . $options . "\n</select>";

        return "\n\n" . $select . "\n\n";
    }


$batch = empty($batch)? [] : $batch;


?>
    <!-- <form id="xf" > -->
<section id='s1' class='mm'></section>
@foreach($batches AS $bth)
    <?php echo Form::model($batch, array('url' => '/batch', 'id'=>'xf') ) ?>
    <table width="860px" align="center" border="0" id="batch_data" style="margin-top: 0.4em">
        <tr>
            <th colspan="4" style="text-align: center; color:DarkBlue; font-size:1.2em">
                PCR DRIED BLOOD SPOT Dispatch Form 
            </th>
        </tr>
        <tr>
            <td><img src="/images/coat-of-arms.jpg" style="width:100px; height:auto;float:right; margin-right:12px;"></td>
            <td>
                <div style="width: 225px; text-align:center;">Envelope Number:</div>
                
                {{ $bth->envelope_number }}
            </td>
            <td>
                <div style="width: 175px; text-align:center;">
                    Batch Number:
                </div>
                 {{ $bth->batch_number }}
                </td>
            <td align="right" >&nbsp;
            </td>
        </tr>
        <tr>
            <td><div style="width:100px; display:block; float:right; margin-right:10px;">Health Unit:</div></td>
            <td> 
                <?php 
                    
                    $SQL = "SELECT facilitys.id, facilitys.name AS facility_name , " .
                                    "districts.name AS district, districtcode " .
                                "FROM facilitys, districts " .
                                    "WHERE facilitys.district = districts.id AND facilitys.id={{ $bth->facility_id }}" .
                                    "ORDER BY district, facility_name";

                    $results = DB::select( $SQL );
                    
                ?>
                
                      
                        <div class="xl" style="width:225px;">{{ $results->facility_name }}</div>

            
                
            </td>
            <td rowspan="4">
                <div style="width: 175px; text-align:center;">
                <b>Site Return Address</b><br>
                (Clinic / Department)<br>
                </div>

<!--                 
    <textarea   style="height: 5em; width:175px; text-align:left; border: 1px solid #eee;"  
                                id="results_return_address" name="results_return_address" 
                                    tabindex="7"></textarea>
 -->
                {!! Form::textarea('results_return_address', null, 
                                array("id"=>"results_return_address","readonly"=>"true", 
                                "style"=>"height: 5em; width:175px; border: 1px solid #eee;",
                                "required"=>"YES", "tabindex"=>"7") ) !!}

            </td>
            <td rowspan="4" align="right">
                <div style="width: 175px; text-align:center; font-weight: bold">
                    &nbsp;<br>
                    Comments/Issues:
                </div>
<!--                 <textarea   style="height: 5em; width:175px; border: 1px solid #eee;"  
                                tabindex="8" id="senders_comments" 
                                    name="senders_comments"></textarea>
 -->
                {!! Form::textarea('senders_comments', null, 
                                array("id"=>"senders_comments","readonly"=>"true", 
                                "style"=>"height: 5em; width:175px; border: 1px solid #eee;",
                                "required"=>"YES", "tabindex"=>"8") ) !!}


            </td>
        </tr>
        <tr>
            <td><div style="width:100px; display:block; float:right; margin-right:10px;">District:</div> </td>
            <td> 

                <!-- <input type="hidden" id="facility_id" name="facility_id" class="xl" />  -->
                {!! Form::hidden('facility_id', null, 
                                array("id"=>"facility_id", "required"=>"YES" ) ) !!}


                <!-- <input type="hidden" id="facility_name" name="facility_name" class="xl" />  -->
                {!! Form::hidden('facility_name', null, 
                                array("id"=>"facility_name", "required"=>"YES") ) !!}
<!-- 
                <input type="text"   id="facility_district" name="facility_district" class="xl"  
                                                    style="width:225px;" tabindex="4" readonly="YES" />  -->
                {!! Form::text('facility_district', null, 
                        array("id"=>"facility_district", "required"=>"YES", "tabindex"=>"4",
                                    "readonly"=>"true", "style"=>"width:225px;", "class"=>"xl" 

                        ) ) !!}

                
            </td>
        </tr>
        <tr>
            <td><div style="width:100px; display:block; float:right; margin-right:10px;">Name of sender:</div> </td>
            <td> 
                <!-- <input type="text" id="senders_name" name="senders_name" style="width:225px; " class="xl"  tabindex="5" required="YES" />  -->
                {!! Form::text('senders_name', null, 
                                array("id"=>"senders_name", "required"=>"YES", "readonly"=>"true",
                                        "style"=>"width:225px;", "class"=>"xl", "tabindex"=>"5") ) !!}

                </td>
        </tr>
        <tr>
            <td><div style="width:100px; display:block; float:right; margin-right:10px;">Telephone No:</div> </td>
            <td> 
                <!-- <input type="text"   id="senders_telephone" name="senders_telephone"  
                    style="width:225px; " class="xl phone"  tabindex="6"  required="YES"/>  -->
                {!! Form::text('senders_telephone', null, 
                        array("id"=>"senders_telephone", "readonly"=>"true",
                                "style"=>"width:225px;", "class"=>"xl phone",       
                                    "required"=>"YES", "tabindex"=>"6") ) !!}

                    </td>
        </tr>
        <tr>
            <td colspan="2" style="padding-bottom: 0px; padding-top: 25px;">

                <div style="margin-bottom:5px;" >
                    Date Samples Dispatched from health unit: 
<!--                     <input type="text" class="readOnly_datepicker xl"  required="YES" 
                            id="" name="date_dispatched_from_facility"  tabindex="9" />
 -->                {!! Form::text('date_dispatched_from_facility', null, 
                            array("id"=>"date_dispatched_from_facility", "readonly"=>"true",
                                "class"=>"readOnly_datepicker xl", 
                                "required"=>"YES", "tabindex"=>"9") ) !!}

                </div>
                How will samples be transported back:
                <select disabled="true" style=" width:11em; " class="xl"  tabindex="10"  id="results_transport_method" name="results_transport_method" >
                    <option value="POSTA_UGANDA">1.POSTA UGANDA</option>
                    <option value="COLLECTED_FROM_LAB">2.PICK FROM LAB DIRECTLY</option>
                </select>
            </td>
            <td colspan="2"  style="padding-bottom: 0px; padding-top: 25px;">
                <div style="margin-bottom:5px;float:left; " >
                    <div>
                        <div style="float:left">Name of Testing Lab:&nbsp;</div>
                        <select  disabled="true" style="FLOAT:LEFT" class="xl"  tabindex="11" id="lab" name="lab">
                            <option>CPHL</option>
                        </select>
                    </div>
                    <div style="clear:both">
                        
                    <div style="float:left">Date samples received at lab:&nbsp;</div>
                        <!-- <input type="text" class="readOnly_datepicker xl"  tabindex="12"  required="YES"  id="date_rcvd_by_cphl" name="date_rcvd_by_cphl" > -->
                        {!! Form::text('date_rcvd_by_cphl', null, 
                                        array("id"=>"date_rcvd_by_cphl", "readonly"=>"true", "required"=>"YES", "tabindex"=>"12", "class"=>"readOnly_datepicker xl" ) ) !!}

                    </div>
                </div>
                <input type="submit" style="float:right;width:11em; height: 3em;" class="hide_me"  
                        tabindex="13" id="submit_button" name="submit_button" value="SAVE BATCH DATA"/>

            </td>
        </tr>
    </table>
    <!-- /form -->
    <?php echo Form::close() ?>

<!--the pcr form details         -->

            
    <table id="dbs_samples" align="center" style="margin-top:50px;border:1px solid #ddd"  >
            
        <tr style="border:1px solid #ddd">

            <th rowspan="2" class="dbs_header">No.</th>
            <th rowspan="2" class="dbs_header">Date of<br>Collection</th>
            <th rowspan="2" class="dbs_header">Infant Name</th>
            <th rowspan="2" class="dbs_header">EXP <br />Number</th>
            <th rowspan="2" class="dbs_header">Sex <br/>(M/F)</th>
            <th rowspan="2" class="dbs_header">Age <br/>(Mths)</th>
            <th rowspan="2" class="dbs_header">Caregiver's<br/>Phone No.</th>
            <th rowspan="2" class="dbs_header">Entrypoint<br/>Clinic</th>
            <th rowspan="2" class="dbs_header hide_me">1<sup>st</sup> or 2<sup>nd</sup> PCR?</th>
            <th rowspan="2" class="dbs_header hide_me">Breast Feeding? <br/>(Y/N)</th>
            <th     colspan="3" class="dbs_header hide_me">Mother PMTCT ARVs <br/>(use codes)</th>
            <th rowspan="2" class="dbs_header">Infant's PMTCT ARVs <br/>(use codes)</th>            
            <th  class="dbs_header"> Sample<br>Verification Status</th>

        </tr>
        <tr>
            <th class="dbs_header hide_me"><div align="center">Ante-Natal</div></th>
            <th class="dbs_header hide_me"><div align="center">Delivery</div></th>
            <th class="dbs_header hide_me"><div align="center">Post-Natal</div></th>
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
    
    $nSamples = count($samples);    

?>

@for($col=14, $i=1, $j=0; $i <= $nSamples ; $i++, $j++)
<!-- $col=14 because... 14 = tabIndex of 1st input in the loop (i.e. 1st input after header) -->


    <?php   $this_sample = empty( $samples[ $j ] ) ? [] : $samples[ $j ];   ?>

    <?php echo Form::model($this_sample, array('url'=>'/dbs')); ?>
    <tr class="parent_row" id="row_{{ $i }}" >
        <td align="center" class="narrow_column" >{{ $i }}.</td>
        <td class="narrow_column">
<!--             <input  type="text" readonly="yes" 
                    name="date_dbs_taken_{{ $i }}" id="date_dbs_taken_{{ $i }}" 
                    tabindex="{{ $col++ }}"  class="readOnly_datepicker xl"/>
 -->
            {!! Form::text("date_dbs_taken", null, 
                    array( "required"=>"YES", 
                                "tabindex"=>$col++, "class"=>"readOnly_datepicker xl",
                                    "readonly"=>"true" ) ) !!}


            {!! Form::hidden("checksum", null, 
                    array("id"=>"checksum_".$i, "required"=>"YES", 
                                "tabindex"=>$col++ ) ) !!}


            {!! Form::hidden("sample", null, 
                    array("id"=>"sample_".$i, "required"=>"YES", 
                                "tabindex"=>$col++ ) ) !!}

<!-- 
            <input  type="" name="checksum_{{ $i }}" id="checksum_{{ $i }}" />
            <input  type="hidden" name="sample_{{ $i }}" id="sample_{{ $i }}" value="" /> -->
        </td>
        <td class="narrow_column">
<!--             <input  type="text" tabindex="{{ $col++ }}" 
                    name="infant_name_{{ $i }}" id="infant_name_{{  $i }}"
                     class="xl"/>
 -->
            {!! Form::text("infant_name", null, 
                    array("id"=>"infant_name_".$i, "required"=>"YES", 
                                "class"=>"xl", "readonly"=>"true", "tabindex"=>$col++ ) ) !!}

        </td>
        <td class="narrow_column">
<!--             <input  type="text"  tabindex="{{ $col++ }}" 
                    name="infant_exp_id_{{ $i }}" id="infant_exp_id_{{ $i }}" 
                    style="width:5em;" class="xl"/></td>
 -->
            {!! Form::text("infant_exp_id", null, 
                    array("id"=>"infant_exp_id_".$i, "required"=>"YES", "readonly"=>"true",
                                "class"=>"xl", "tabindex"=>$col++ ) ) !!}

        <td class="narrow_column">
<!--             <select tabindex="{{ $col++ }}" 
                        name="infant_gender_{{ $i }}" id="infant_gender_{{ $i }}" 
                        style="width:60px; " class="xl">
                    <option value=""></option>
                    <option value="MALE">M</option>
                    <option value="FEMALE">F</option>
                    <option value="NOT_RECORDED">Blank</option>
            </select>
 -->
            {!! 

            Form::text("infant_gender", null,
                        array("id"=>"infant_gender_".$i, "required"=>"YES", "readonly"=>"true",
                                    "class"=>"xl", "tabindex"=>$col++ ) ) !!}


<!--                     array("id"=>"infant_exp_id_".$i, "required"=>"YES", 
                                "class"=>"xl", "tabindex"=>$col++ ),
 -->
        </td>


        <td class="narrow_column">
<!--             <input type="text"  tabindex="{{ $col++ }}" 
                    name="infant_age_{{ $i }}" id="infant_age_{{ $i }}" 
                    style="width:10em; " class="xl ageFmt" />  
 -->
            {!! Form::text("infant_age", null, 
                    array("id"=>"infant_age_".$i, "required"=>"YES", "readonly"=>"true",
                            "class"=>"xl ageFmt", "tabindex"=>$col++ ) ) !!}

<!-- 
            <input type="hidden" class="text" 
                    name="infant_dob_{{ $i }}" id="infant_dob_{{ $i }}" 
                    style="width:10em; " />   -->

            {!! Form::hidden("infant_dob", null, 
                    array("id"=>"infant_dob_".$i, "required"=>"YES", 
                                "tabindex"=>$col++ ) ) !!}

        </td>
        <td class="narrow_column">
<!--             <input  type="text"  tabindex="{{ $col++ }}" 
                    name="infant_contact_phone_{{ $i }}"  id="infant_contact_phone_{{ $i }}" 
                    style="width:10em; " class="xl phone" />
 -->
            {!! Form::text("infant_contact_phone", null, 
                    array("id"=>"infant_contact_phone_".$i, "required"=>"YES", "readonly"=>"true",
                                "class"=>"xl phone", "tabindex"=>$col++ ) ) !!}

        </td>
        <td class="narrow_column"> 

            <?php $entry_point = ( empty($this_sample) ) ? "" : $this_sample->getEntryPoint()  ?>

            {!! mk_EntryPoint_Select( $i, $col++, $entry_point) !!}
    
        </td>
        <td class="narrow_column hide_me">
<!--             <select  tabindex="{{ $col++ }}" 
                        name="pcr_{{ $i }}" id="pcr_{{ $i }}"  
                         class="xl">
                            <option value=""></option>
                            <option value="FIRST">1st</option>
                            <option value="SECOND">2nd</option>
                            <option value="UNKNOWN">Blank</option>
                            <option value="NON_ROUTINE">Other</option>
            </select>
 -->
            {!! 

            Form::select("pcr", 
                    array(""=>"", "FIRST"=>"1st", "SECOND"=>"2nd","UNKNOWN"=>"Blank", "NON_ROUTINE"=>"Other"), null,
                        array("id"=>"pcr_".$i, "required"=>"YES",
                                    "class"=>"xl", "tabindex"=>$col++ ) ) !!}


        </td>
        <td class="narrow_column hide_me">  
<!--             <select  tabindex="{{ $col++ }}" 
                        style="width:90px; " class="xl"
                        name='infant_is_breast_feeding_{{ $i }}' id='infant_is_breast_feeding_{{ $i }}'>

                            <option value=''></option>
                            <option value='NO'>No</option>
                            <option value='YES'>Yes</option>
                            <option value='UNKNOWN'>Blank</option>
            </select> -->

            {!! 

            Form::select("infant_is_breast_feeding", 
                    array(""=>"", "NO"=>"No", "YES"=>"Yes", "UNKNOWN"=>"Blank"), null,
                        array("id"=>"infant_is_breast_feeding_".$i, "required"=>"YES",
                                    "class"=>"xl", "tabindex"=>$col++ ) ) !!}

        </td>
        <td class="narrow_column hide_me">
            <?php $ante_natal = ( empty($this_sample) ) ? "" : $this_sample->getAnteNatalPMTCT()  ?>
            <?php echo mk_PMTCT_Select("mother_antenatal_prophylaxis", $i, $col++, $ante_natal ) ?>
        </td>
        <td class="narrow_column hide_me">
            <?php $delivery = ( empty($this_sample) ) ? "" : $this_sample->getDeliveryPMTCT()  ?>
            <?php echo mk_PMTCT_Select("mother_delivery_prophylaxis" , $i, $col++, $delivery ) ?>
        </td>
        <td class="narrow_column hide_me">
            <?php $post_natal = ( empty($this_sample) ) ? "" : $this_sample->getPostNatalPMTCT()  ?>
            <?php echo mk_PMTCT_Select("mother_postnatal_prophylaxis", $i, $col++, $post_natal ) ?>
        </td>
        <td>
            <?php $infant = ( empty($this_sample) ) ? "" : $this_sample->getInfantPMTCT()  ?>
            <?php echo mk_PMTCT_Select("infant_prophylaxis", $i, $col++, $infant ) ?>
        </td>
        <td align="center">

            <?php   $sample_accepted = $this_sample->wasAccepted();  ?>
        
            @if($sample_accepted === null)
                {!!  link_to("/approve/" . $this_sample->id, "Not Yet Checked: ") !!}
            @elseif($sample_accepted === true)
                {!!  link_to("/approve/" . $this_sample->id, "Accepted") !!}
            @elseif($sample_accepted === false)
                {!!  link_to("/approve/" . $this_sample->id, "Rejected") !!}
            @else
                {!! "Unknown & Unexpected" !!}
            @endif
            
        </td>
    </tr>
    <?php echo Form::close() ?>
@endfor

@endforeach

</table>


    <script src="/js/moment.js"></script>
    <script src="/js/md5.js"></script>

    <!-- First load pikaday.js and then its jQuery plugin -->
    <script src="/js/pikaday.js"></script>
    <script src="/js/plugins/pikaday.jquery.js"></script>

    <!-- JavaScript to handle validation + display of infant age -->
    <script src="/js/ageFmt.js"></script>

    <!-- JavaScript that is specific to this page -->

    <script src="/js/sample.blade.js"></script>
  



    <script type="text/javascript">

        $(function(){

            


            $(document.body).on("change","#facility_selector",function(){

                var this_facility = JSON.parse( this.value );


                $("#facility_id").val(this_facility.facility_id);
                $("#facility_name").val(this_facility.facility_name);
                $("#facility_district").val(this_facility.district.toUpperCase());
                
            });


            function format_dates(){

                var formatted_date;
                var dateFields = $(".readOnly_datepicker");
                var nDateFields = dateFields.length;
                var current_dateField;

                for(var i=0; i<nDateFields; i++){
                    current_dateField = dateFields[i];

                    if(current_dateField.defaultValue === "") continue;
                    formatted_date = moment(current_dateField.defaultValue).format("Do MMM YYYY");
                    current_dateField.value = formatted_date;
                }                
            }

            $(".js-example-basic-single").select2();
            format_dates();

        }); 

    </script>

