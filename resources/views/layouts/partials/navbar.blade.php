@if (Auth::guest())
@else
<div class="navbar-custom navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <link rel="shortcut icon" href="/favicon.ico?<?php echo time() ?>" />

            <a class="navbar-brand" href="/"> <span class='glyphicon glyphicon-home'></span> EID LIMS</a>
        </div>
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <?php
                /*$links=
                [
                ['perm'=>'1','url'=>'samples','lbl'=>"Samples"],
                ['perm'=>'1','url'=>'batchQ','lbl'=>"Approve DBS"],
                ['perm'=>'2','url'=>'wlist','lbl'=>"Worksheets"],
                ['perm'=>'3','url'=>'dispatch','lbl'=>"Results Dispatch"],
                ['perm'=>'4','url'=>'follow','lbl'=>"Follow up"],
                ['perm'=>'5','url'=>'commodities/home','lbl'=>"Commodities Manag't"],
                ['perm'=>'6','url'=>'customer_care/complaints/index','lbl'=>"Customer Care"],
                ['perm'=>'7','url'=>'admin','lbl'=>"System Admin"]
                ];

                foreach ($links as $link) {
                    if(in_array($link['perm'],session('permission_parents')) || session('is_admin')==1){
                        echo "<li><a href='/".$link['url']."'>$link[lbl]</a></li>";
                    }                 
                }*/
                ?>
               
                    <!-- 
                    <li><a href="/samples">Samples</a></li>
                    <li><a href="/batchQ">Approve DBS</a></li>
                    <li><a href="/wlist">Worksheets</a></li>
                   
                    <li><a href="/commodities/home">Commodities Manag't</a></li>
                    <li><a href="/customer_care/complaints/index">Customer Care</a></li>
                    <li><a href="/admin">System Admin</a>
                    </li> -->

                    <!-- <li>{!! link_to('/approve', 'New Layout for Sample Verification') !!}</li> -->
                    <?php
                    $add_batch_link=MyHTML::anchor("/samples","Add Batch",11);
                    $list_batches_link=MyHTML::anchor("/batches","List of Batches",12);
                    ?>
                    @if(!empty($add_batch_link) || !empty($list_batches_link))
                    <li id='l1' class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"> Batches <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li>{!! $add_batch_link !!}</li>
                            <li>{!! $list_batches_link !!}</li>
                        </ul>
                    </li>
                    @endif
                    <li id='l2'>{!! MyHTML::anchor("/batchQ","Approvals",14) !!}</li>
                    <li id='l3'>{!! MyHTML::anchor("/wlist","Worksheets",2) !!}</li>
                    <!-- 
                        <li id='l4'>{!! MyHTML::anchor("/dispatch","Dispatch",3) !!}</li>
                     -->
                    
                    <li id='l4' class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" 
                            role="button" aria-expanded="false"> Dispatch 
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="/dispatch">EID Results</a></li>
                            <li><a href="/dispatch_scd">Sickle Cell Results</a></li>

                            <li class="divider"></li>
                            <li><a href="/rejects">Rejected Results</a></li>
                        </ul>
                    </li>


                    <li id='l5'>{!! MyHTML::anchor("/follow","Follow-Up",4) !!}</li>
                    
                    <li id='l4' class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" 
                            role="button" aria-expanded="false"> More
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li id='l6'>{!! MyHTML::anchor("/customer_care/complaints/index","Customer Care",6) !!}</li>
                            <li id='l7'>{!! MyHTML::anchor("/commodities/home","Commodities",5) !!}</li> 
                              
                        </ul>
                    </li>                                 
                    
                    @if(session('is_admin')==1) <li id='l8'><a href="/admin">Sys Admin</a> @endif
                    <li id='l9'>{!! MyHTML::anchor("/labss","Search",5) !!}</li>


                   <!--  <li><a href="/rlogin">Admin</a></li>
                    <li><a href="/logout">Logout</a></li> -->
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"> {{ Auth::user()->username }} <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="/user_pwd_change">Change Password</a></li>
                        <li><a href="/logout">Logout</a></li>
                    </ul>
                </li>
            </ul>

                
        </div><!--/.nav-collapse -->
    </div>
</div>
<script type="text/javascript">
$(document).ready(function () {
    
    for (var i = 1; i <= 8; i++) {
        var sect=$("#s"+i);
        if(sect.hasClass("mm")){
            var lnk=$("#l"+i);
            if (!lnk.hasClass('active')) {
                lnk.addClass('active');
            }
        }
    }
});
</script>
@endif