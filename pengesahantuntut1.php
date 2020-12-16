<?php
error_reporting(E_ALL ^ E_NOTICE);
session_start();

include"conn/conn.php";
include "calendar.jsp";

$Ic = $_SESSION['Ic'];
$NoGaji = $_SESSION['NoGaji'];
$Nama = $_SESSION['Nama'];
$StatusMakPeg = $_SESSION['StatusMakPeg'];
$KodCawanganPegawai = $_SESSION['KodCawanganPegawai'];
$CawanganPegawai = $_SESSION['cawanganpegawai'];
$Level = $_SESSION['Level']; //level OT
//$idseq = $_SESSION['id'];

$result2 = odbc_exec($gaSqlPHP['link'],"select * from smp2usrpriv where smp01noic='$Ic'");
$recordset2 = odbc_fetch_array($result2);
$passwordquery=$recordset2['smp01password'];
$status = $recordset2['smp01ecuti'];
$_SESSION['ecuti'] = $status;
	
date_default_timezone_set('Asia/Singapore');
$time = date('d/m/Y');

$Tahun = $_GET[Tahun];
$Bulan = $_GET[Bulan];
$Idseq = $_GET['id'];

$masasemak = $_POST[masasemak];
$masasemak2 = $_POST[masasemak2];

$Ic6 = substr($Ic,0,6);
$Ic2 = substr($Ic,6,2);
$Ic4 = substr($Ic,8,4);


			//================================
			// SELECT TABLE smp2makpenempatan
			//================================
			
			$kirapenempatan=0;
			$table3 = "smp2makpenempatan";
			$all3="*";
			$field3 = "smp32noic";
		
			$query3 ="select $all3 from $table3 where $field3 ='$Ic' and smp32nogaji = '$NoGaji'";
			 $result3 = odbc_exec($gaSqlPHP['link'], $query3);
			 while($recordset3= odbc_fetch_array($result3))
			 {
			   $smp32jabatan = $recordset3['smp32jabatan'];
			 }

			//================================
			// SELECT TABLE smp2makperkhidmatan
			//================================
			$table2 = "smp2makperkhidmatan";
			$all2="*";
			$field2 = "smp02noic";
			$field3 = "smp02nogaji";
		 				
			 $query8 ="select $all2 from $table2 where $field2 ='$Ic' and $field3 ='$NoGaji'";
			 $result5 = odbc_exec($gaSqlPHP['link'], $query8);
			 while($recordset2= odbc_fetch_array($result5))
			 {			 
			   $smp02namajwtn = $recordset2['smp02namajwtn'];
			 }

			$query = "select * from tpbulan where tp02status = 'BUKA'";
			$result = odbc_exec($gaSqlPHP['link'],$query);
			while($recordset = odbc_fetch_array($result))
			{
				$Id = $recordset['tp02id'];
				$Bulan = $recordset['tp02desc'];
				$Status = $recordset['tp02status'];
			}

			$queryT = "select tp03desc from tahuntuntutan where tp03status = 'BUKA'";
			$resultT = odbc_exec($gaSqlPHP['link'],$queryT);
			while($recordsetT = odbc_fetch_array($resultT))
			{
				$Tahun = $recordsetT['tp03desc'];
			}
			
			$result1 = odbc_exec($gaSqlPHP['link'],"select * from smp2makperkhidmatan where smp02noic='$Ic' and smp02stperkhidmatan='AKTIF'");
			$recordset1 = odbc_fetch_array($result1);
			$namajawatan=$recordset1['smp02namajwtn'];
			$kumpulan=$recordset1['smp02kumpjwtn'];
			$gredjawatan=substr($recordset1['smp02gredjwtn'],1,2);
			$StatusJawatan= $recordset1['smp02statusjwtn'];
			$nogaji=$recordset1['smp02nogaji'];
			
			$result2 = odbc_exec($gaSqlPHP['link'],"select * from smp2makpenempatan where smp32noic='$Ic' and smp32nogaji='$nogaji'");
			$recordset2 = odbc_fetch_array($result2);
			$sektor=$recordset2['smp32sektor'];
			$bahagian=$recordset2['smp32bahagian'];
			$jabatan=$recordset2['smp32jabatan'];
			$zon=$recordset2['smp32zon'];
			$negeri=$recordset2['smp32negeri'];
			
			
?>

<style>
.hm-fieldset {
	margin-top: 12px;
	width: 100%;
	border: 1px solid #ced4da !important;
	padding: 0 10px 10px 10px !important;
}

.hm-legend {
	font-size: 18px;
	color: #555;
	border-bottom: none;
	padding: 0 10px;
	width: auto;
}
</style>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>SISTEM TUNTUTAN ELAUN PERJALANAN</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Charisma, a fully featured, responsive, HTML5, Bootstrap admin template.">

    <!-- The styles -->
    <link id="bs-css" href="css/bootstrap-cerulean.min.css" rel="stylesheet">

    <link href="css/charisma-app.css" rel="stylesheet">
    <link href='bower_components/fullcalendar/dist/fullcalendar.css' rel='stylesheet'>
    <link href='bower_components/fullcalendar/dist/fullcalendar.print.css' rel='stylesheet' media='print'>
    <link href='bower_components/chosen/chosen.min.css' rel='stylesheet'>
    <link href='bower_components/colorbox/example3/colorbox.css' rel='stylesheet'>
    <link href='bower_components/responsive-tables/responsive-tables.css' rel='stylesheet'>
    <link href='bower_components/bootstrap-tour/build/css/bootstrap-tour.min.css' rel='stylesheet'>
    <link href='css/jquery.noty.css' rel='stylesheet'>
    <link href='css/noty_theme_default.css' rel='stylesheet'>
    <link href='css/elfinder.min.css' rel='stylesheet'>
    <link href='css/elfinder.theme.css' rel='stylesheet'>
    <link href='css/jquery.iphone.toggle.css' rel='stylesheet'>
    <link href='css/uploadify.css' rel='stylesheet'>
    <link href='css/animate.min.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="css/jscal2.css" />
    <link rel="stylesheet" type="text/css" href="css/border-radius.css" />
    <link type="text/css" href="css/bootstrap-timepicker.min.css" rel='stylesheet'/>

    <!-- jQuery -->
    <script src="bower_components/jquery/jquery.min.js"></script>
    <script src="js/jscal2.js"></script>
    <script src="js/lang/en.js"></script>
    <script language="javascript" src="includes/general.js"></script>
    <link rel="shortcut icon" href="img/favicon.ico">
</head>
<script>
	function show()
	{
		var size = document.getElementById("bilangan").value;
		statuscheck = false;
		for (i=1;i<size;i++)
		{
			if(document.getElementById("checkbox1"+i).checked == true)
			{
				statuscheck = true;
			}
		}
    form = document.hantar;
	if(statuscheck == false)
	{
		alert("SILA TANDA SEMAKAN DI CHECKBOX");
		return;
	}
	else
	{
		var reg = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
		
			var r=confirm("Adakah anda pasti.");
			if (r==true)
			{
				form.submit();
			}
		}
	}
	
</script>
<script>
	var toggle = true;

function toggleBoxes() {
    var objList = document.getElementsByName('checkbox[]')
    
    for(i = 0; i < objList.length; i++)
        objList[i].checked = toggle;
    
    toggle = !toggle;
}

var checkflag = "false";
function check(field) {
  if (checkflag == "false") {
    for (i = 0; i < field.length; i++) {
      field[i].checked = true;
    }
    checkflag = "true";
    return "Uncheck All";
  } else {
    for (i = 0; i < field.length; i++) {
      field[i].checked = false;
    }
    checkflag = "false";
    return "Check All";
  }
}

function selectState(val) {
$("#search-box").val(val);
$("#suggesstion-box").hide();
}
</script>
<body>
    <!-- topbar starts -->
    <div class="navbar navbar-default" role="navigation">

        <div class="navbar-inner">
            <button type="button" class="navbar-toggle pull-left animated flip">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <!--<a class="navbar-brand" href="#">-->&nbsp;<?php /*?><img alt="" src="" class="hidden-xs" width="90"/><?php */?><!--</a>-->

            <!-- user dropdown starts -->
            <div class="btn-group pull-right">
                <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    <i class="glyphicon glyphicon-user"></i><span class="hidden-sm hidden-xs">&nbsp;<?php echo $Nama;?></span>
              <!--                    <span class="caret"></span>
--></button>
                <ul class="dropdown-menu">
                    <!--<li><a href="#">Profile</a></li>
                    <li class="divider"></li>-->
<!--                    <li><a href="logout.php">Logout</a></li>
-->                </ul>
            </div>
        </div>
    </div>
    
    <!-- topbar ends -->
<div class="ch-container">
	<div class="row">
        <!-- left menu starts -->
        <div class="col-sm-2 col-lg-2">
            <div class="sidebar-nav">
                <div class="nav-canvas">
                    <div class="nav-sm nav nav-stacked"></div>
                    <?php include "header.php"; ?>
                </div>
            </div>
            <br>
            <div class="panel-heading" style="background-color:#FFF">
            <div id="collapse2" class="panel-collapse collapse">
            <div class="panel-body"></div>
    	</div>
	</div>
</div>
        
    <div id="content" class="col-lg-10 col-sm-10">
        <!--<div>
        <ul class="breadcrumb">
            <li>
                <ul class="nav nav-pills nav-stacked">
                    <a href="#KunciMasukTP.php">Main</a>
                </ul>
            </li>
        <li>
            <a href="#">Kunci Masuk Tuntutan Perjalanan</a>
        </li>
        </ul>
    </div>-->

    <div class="row">
        <div class="box col-md-12">
            <div class="box-inner">
                <div class="box-header well" data-original-title="">
                    <h2>KEMASUKAN TUNTUTAN PERJALANAN DALAM NEGERI BAGI BULAN 
                    <font color="#000000"><?php echo $Bulan; ?></font> TAHUN <font color="#000000"><?php echo $Tahun; ?></font> </h2>

                    <div class="box-icon">
                        <!--<a href="#" class="btn btn-setting btn-round btn-default"><i
                                class="glyphicon glyphicon-cog"></i></a>-->
                        <!--<a href="#" class="btn btn-minimize btn-round btn-default"><i
                                class="glyphicon glyphicon-chevron-up"></i></a>-->
                        <!--<a href="#" class="btn btn-close btn-round btn-default"><i
                                class="glyphicon glyphicon-remove"></i></a>-->
                    </div>
                </div>
          </div>
          <br>
            <table width="90%" border="0" align="center" cellpadding="0">
            <tr>
                <td rowspan=5><div align="center">
                <img style= height="120" src="img/tabung.png" width="150" align="center" border="0" />
                </div></td>
                <td><div align="center"><font size="2">
                PERBADANAN TABUNG PENDIDIKAN TINGGI NASIONAL<br>
                <b>KENYATAAN TUNTUTAN PERJALANAN DALAM NEGERI</b><br>
                BAGI BULAN <b><?php echo $Bulan;?> <?php echo $Tahun; ?></b>
                </font></div></td>	
                <td rowspan=5><div align="center">
                <font size="1">
                PTPTN-K-03/13-2014 Pin.1<br>
                <font size="4">!<?php echo $NoGaji; ?>!</font>
                </font></div></td>
            </tr>
            </table>
       
            <?php
			if($masasemak=="" && $masasemak2=="")
				{
					?>
            <form name="hantar" method="post" action="pengesahantuntut1.php">
              <table width="80%" border="1" cellpadding="0" bordercolor="#000000" align="center">
                <tr bgcolor="#21807D">
                  <th colspan="2" style="text-align:center">&nbsp;<font color="#FFFFFF">SENARAI TUNTUTAN BAYARAN TERUS </font> <a href="pengesahantuntut_cetak.php?tarikhmula=<?php echo $masasemak; ?>&tarikhakhir=<?php echo $masasemak2; ?>" target="_blank"><!--<img src="img/print.JPG" width="15" height="15" />--></a>
                    </h3>
                    <br></th>
                </tr>
                <tr bordercolor="#000000" align="left">
                  <td><center>
                    <label>TARIKH MULA </label>
                    <input id="masasemak" name="masasemak" type="text" readonly="enable"/>
                    <span id="f_btn1"> <i class="glyphicon glyphicon-calendar"></i> </span>
                    <label>TARIKH AKHIR </label>
                    <input id="masasemak2" name="masasemak2" type="text" readonly="enable"/>
                    <span id="f_btn2"> <i class="glyphicon glyphicon-calendar"></i> </span>
                    <input name="cari" type="button" id="cari" value="CARI" onClick="carisenarai();">
                  </center></td>
                </tr>
              </table>
            </form>
          <br>
			<?php
			}
            if($masasemak2!="")
            { ?>
      <form id="form1" name="form1" action="pengesahantuntut_view.php" method="post" >
      <table align="center" width="100%">
                <tr>
                    <td colspan="6" align="center">
                    <span class="style15">
                        TARIKH CARIAN : <?php echo $masasemak;  ?> <span class="style11">sehingga</span> <?php echo $masasemak2; ?>
                    </span>
                    </td>
                </tr>
                <tr>
                    <td colspan="4"><br></td>
                </tr>
                <tr>
                	<td width="19%" align="right"><span class="style15"><b>NO. BAUCER : </b></span></td>
                    <td width="21%">
                        <span class="style15">
                            <input type="text" name="NoBaucer" id="NoBaucer"/>
                        </span>
                    </td>
                    <td width="17%" align="right"><span class="style15"><b>TARIKH BAUCER : </b></span></td>
                    <td width="23%">
                        	 <span class="style15">
                             </span>
                        	<input id="Trkbaucer" name="Trkbaucer" type="text" readonly="enable"/>
                            <span id="f_btn3">
                                <i class="glyphicon glyphicon-calendar"></i>
                            </span>
                           
                    </td>
                    <td width="20%" align="right"><span class="style15">
                    <input name="bilangan" type="hidden" id="bilangan" size="50" maxlength="50" value="<?php echo $i; ?>" > 
                    <input name="submit" type="button" id="kemaskinimaklumat" value="Hantar" class="butang3" onClick="show()">
                    </span></td>
                   
                </tr>   
                </table>  
<br>
  <table width="98%" height="203" border="1" cellpadding="1" bordercolor="#000000" style="border-collapse:collapse">
                                <tr bordercolor="#FF0000" bgcolor="#5CCAC6">
                                <td width="69" align="center" bordercolor="#000000" bgcolor="#5CCAC6"><div align="center"><strong><font size="1">BIL</font></strong></div></td>
                                <td width="154" align="center" bordercolor="#000000" bgcolor="#5CCAC6"><div align="center"><strong><font size="1">NAMA</font></strong></div></td>
                                <td width="73" align="center" bordercolor="#000000" bgcolor="#5CCAC6"><div align="center"><strong><font size="1">No.KP</font></strong></div></td>
                                <td width="93" align="center" bordercolor="#000000" bgcolor="#5CCAC6"><div align="center"><strong><font size="1">NO AKAUN BANK</font></strong></div></td>
                                <td align="center" bordercolor="#000000" bgcolor="#5CCAC6" colspan="3"><div align="center"><strong><font size="1">P21101</font></strong></div></td>
                                <td align="center" bordercolor="#000000" bgcolor="#5CCAC6" colspan="3"><div align="center"><strong><font size="1">P21102</font></strong></div></td>
                                <td align="center" bordercolor="#000000" bgcolor="#5CCAC6" colspan="3"><div align="center"><strong><font size="1">P21103</font></strong></div></td>
                                <td align="center" bordercolor="#000000" bgcolor="#5CCAC6" colspan="3"><div align="center"><strong><font size="1">P21104</font></strong></div></td>
                                <td align="center" bordercolor="#000000" bgcolor="#5CCAC6" colspan="3"><div align="center"><strong><font size="1">P21199</font></strong></div></td>
                                <td align="center" bordercolor="#000000" bgcolor="#5CCAC6" colspan="3"><div align="center"><strong><font size="1">P22201</font></strong></div></td>
                                <td align="center" bordercolor="#000000" bgcolor="#5CCAC6" colspan="3"><div align="center"><strong><font size="1">P26101</font></strong></div></td>
                                <td align="center" bordercolor="#000000" bgcolor="#5CCAC6" colspan="3"><div align="center"><strong><font size="1">P29399</font></strong></div></td>
                                <td align="center" bordercolor="#000000" bgcolor="#5CCAC6" colspan="3"><div align="center"><strong><font size="1">P29103</font></strong></div></td>
                                <td align="center" bordercolor="#000000" bgcolor="#5CCAC6" colspan="3"><div align="center"><strong><font size="1">P29108</font></strong></div></td>
                                <td align="center" bordercolor="#000000" bgcolor="#5CCAC6" colspan="3"><div align="center"><strong><font size="1">P24199</font></strong></div></td>
                                <td align="center" bordercolor="#000000" bgcolor="#5CCAC6" colspan="3"><div align="center"><strong><font size="1">P29106</font></strong></div></td>
                                <td align="center" bordercolor="#000000" bgcolor="#5CCAC6" colspan="3"><div align="center"><strong><font size="1">P29107</font></strong></div></td>
                                <td align="center" bordercolor="#000000" bgcolor="#5CCAC6" colspan="3"><div align="center"><strong><font size="1">P29302</font></strong></div></td>
                                <td align="center" bordercolor="#000000" bgcolor="#5CCAC6" colspan="3"><div align="center"><font size="1"> <strong>TUNTUTAN (RM) / JARAK</strong></font></div></td>
                                <td width="237" align="center" bordercolor="#000000" bgcolor="#5CCAC6">
                    <div align="center">
                        <font size="1">
                            <strong>
                            	TINDAKAN<br>
									<input name="mybutton" type="button" onClick="toggleBoxes()" value="Check All" class="btn btn-primary btn-xs">        </strong>
                                    
                        </font>
                    </div>
					</td>
                  </tr>
				  <?php
                   
                  $idcheckbox=0;
                  $sqltuntut ="select tp01ic,tp01idtuntut,tp02idtuntut,tp02bulan,tp02tahun,tp02status,smp08nama,smp08noic,smp03namabank,smp03noakaunbank
				  from tuntutankdetails,tuntutanperjalanan,smp2makpeg,smp2makakaun,smp2makperkhidmatan,smp2makpenempatan
                  where tp01idtuntut = tp02idtuntut
				  and tp02tahun='$Tahun'
				  and smp08noic = tp01ic
				  and smp08noic = smp03noic
				  and smp02nogaji = smp32nogaji 
                  and smp02nogaji = smp03nogaji
				  and tp02status = 'DISEMAK2'
				  group by 1,2,3,4,5,6,7,8,9,10";
                  $resulttuntut = odbc_exec($gaSqlPHP['link'], $sqltuntut);
                  while($data = odbc_fetch_array($resulttuntut))
                  {
					  $lineindex++;
					  $idcheckbox=0;
					  $i=1;
	
					  $Id = $data['tp02id'];
					  $Idtuntut = $data['tp02idtuntut'];
					  $idtuntut = $data['tp01idtuntut'];
					  $JenisTuntutan = $data['tp02jenistuntutan'];
					  $Jarak = $data['tp02jarak'];
					  $Tuntutan = $data['tp02tuntutan'];
					  $statuspadam = $data['tp02status'];
					  $NoKPPegawai= $data['tp01ic'];
					  $NoGaji=$data['tp01nogaji'];
					  $Bulan = $data['tp02bulan'];
					  $Tahun = $data['tp02tahun'];

					  $count1 += $Tuntutan;
				  
					  /*$sqlmakakaun="select smp03nogaji, smp03namabank, smp03noic, smp03noakaunbank  from smp2makakaun where smp03noic = '$NoKPPegawai'  group by 1,2,3,4";
					  $querymakakaun = odbc_exec($gaSqlPHP['link'],$sqlmakakaun);
					  $recordmakakaun = odbc_fetch_array($querymakakaun);*/
					  
					  $namabankquery2 = $data["smp03namabank"];
					  $noakaunbankquery = $data["smp03noakaunbank"];
					  
					  $sqlmakakaun2="select * from tpkodbank where tp20kodbank = '$namabankquery2'";
					  $querymakakaun2 = odbc_exec($gaSqlPHP['link'],$sqlmakakaun2);
					  $recordmakakaun2 = odbc_fetch_array($querymakakaun2);
					  $namabankquery = $recordmakakaun2["tp20kodbanknegara"];
					  
					  /*$sqlsum = "SELECT SUM(tp02tuntutan) as sum FROM tuntutanperjalanan
					  WHERE tp02idtuntut='$idtuntut'
					  and tp02status='BARU'
					  GROUP BY tp02idtuntut
					  ORDER BY tp02idtuntut";
					  $sums = odbc_exec($gaSqlPHP['link'],$sqlsum);
					  $resultsum=odbc_fetch_array($sums);
					  $jumlahtuntutan=$resultsum['sum'];*/
					  
					  /*$query2 = odbc_exec($gaSqlPHP['link'],"select smp08nama from smp2makpeg where smp08noic='$NoKPPegawai'");
					  $recordset2 = odbc_fetch_array($query2);*/
					  $nama = $data['smp08nama'];
									
					  $sqlcount = "SELECT tp02kodtuntutan[1,1],tp02kodtuntutan[1,3] as tp02kodtuntutanfull FROM tuntutanperjalanan,
								  tuntutankdetails WHERE tuntutanperjalanan.tp02idtuntut = tuntutankdetails.tp01idtuntut
								  AND tp02idtuntut = '$Idtuntut'
								  AND tp02bulan = '$Bulan'
								  GROUP BY 1,2";
					  $resultcount = odbc_exec($gaSqlPHP['link'], $sqlcount);
					  $Kod = "";
					  $Kodfull = "";
					  $Amount_A = 0;
					  $Amount_BC = 0;
					  $Amount_E = 0;
					  $Amount_L = 0;
					  $Amount_K = 0;
					  $Amount_D = 0;
					  $Amount_F = 0;
					  $Amount_GH = 0;
					  $Amount_I = 0;
					  $Amount_M = 0;
					  
					  while($datacount = odbc_fetch_array($resultcount))
					  {
						  $Kod = $datacount['tp02kodtuntutan']; //dari field tp02kodtuntutan
						  $Kodfull = $datacount["tp02kodtuntutanfull"];
						  
						  if($Kod == "A")     //A1 elaun mkn semenanjung
											  //A2 elaun mkn sabah/sarawak
						  {
							  $sqlA =  "SELECT sum(tp02tuntutan) as total FROM tuntutanperjalanan,
										tuntutankdetails WHERE tuntutanperjalanan.tp02idtuntut = tuntutankdetails.tp01idtuntut
										AND tp02idtuntut = '$Idtuntut'
										AND tp02bulan = '$Bulan'
										AND tp02status = 'DISEMAK2'
										AND tp02kodtuntutan[1,1] IN ('A')";
							  $resultA = odbc_exec($gaSqlPHP['link'], $sqlA);
							  $dataA = odbc_fetch_array($resultA);
							  $Amount_A = $dataA['total'];
							  $jumlaHamountA=$Amount_A;
						  }
						  else if($Kod == "B" || $Kod == "C")
						  {
							  $sqlBC = "SELECT sum(tp02tuntutan) as total FROM tuntutanperjalanan,
									tuntutankdetails WHERE tuntutanperjalanan.tp02idtuntut = tuntutankdetails.tp01idtuntut
									AND tp02idtuntut = '$Idtuntut'
									AND tp02bulan = '$Bulan'
									AND tp02status = 'DISEMAK2'
									AND tp02kodtuntutan[1,2] IN ('B1','B2','B3','B4','C1','C2','C3') ";
							  $resultBC = odbc_exec($gaSqlPHP['link'], $sqlBC);
							  $dataBC = odbc_fetch_array($resultBC);
							  $Amount_BC = $dataBC['total'];
						  }
						  else if($Kod == "D")//{$Amount;}
						  {
							  $sqlD = "SELECT sum(tp02tuntutan) as total FROM tuntutanperjalanan,
								  tuntutankdetails WHERE tuntutanperjalanan.tp02idtuntut = tuntutankdetails.tp01idtuntut
								  AND tp02idtuntut = '$Idtuntut'
								  AND tp02bulan = '$Bulan'
								  AND tp02kodtuntutan[1,2] IN ('D','D1','D2','D3','D4')
								  ";
							  $resultD = odbc_exec($gaSqlPHP['link'], $sqlD);
							  $dataD = odbc_fetch_array($resultD);
							  $Amount_D = $dataD['total'];
						  }
						  else if($Kod == "F")//{$Amount;}
						  {
							  $sqlF = "SELECT sum(tp02tuntutan) as total FROM tuntutanperjalanan,
								   tuntutankdetails WHERE tuntutanperjalanan.tp02idtuntut = tuntutankdetails.tp01idtuntut
								   AND tp02idtuntut = '$Idtuntut'
								   AND tp02bulan = '$Bulan'
								   AND tp02kodtuntutan[1,2] IN ('F')
								   ";
							  $resultF = odbc_exec($gaSqlPHP['link'], $sqlF);
							  $dataF = odbc_fetch_array($resultF);
							  $Amount_F = $dataF['total'];
						  }
						  else if($Kod == "G" || $Kod == "H")//{$Amount;}
						  {
							  $sqlGH = "SELECT sum(tp02tuntutan) as total FROM tuntutanperjalanan,
									tuntutankdetails WHERE tuntutanperjalanan.tp02idtuntut = tuntutankdetails.tp01idtuntut
									AND tp02idtuntut = '$Idtuntut'
									AND tp02status = 'DISEMAK2'
									AND tp02bulan = '$Bulan'
									AND tp02kodtuntutan[1,2] IN ('G','G1','H','H1')
									";
							  $resultGH = odbc_exec($gaSqlPHP['link'], $sqlGH);
							  $dataGH = odbc_fetch_array($resultGH);
							  $Amount_GH = $dataGH['total'];
						  }
						  else if($Kod == "I")//{$Amount;}
						  {
							  $sqlI = "SELECT sum(tp02tuntutan) as total FROM tuntutanperjalanan,
								   tuntutankdetails WHERE tuntutanperjalanan.tp02idtuntut = tuntutankdetails.tp01idtuntut
								   AND tp02idtuntut = '$Idtuntut'
								   AND tp02bulan = '$Bulan'
								   AND tp02kodtuntutan[1,2] IN ('I')
								   ";
							  $resultI = odbc_exec($gaSqlPHP['link'], $sqlI);
							  $dataI = odbc_fetch_array($resultI);
							  $Amount_I = $dataI['total'];
						  }
						  else if($Kod == "M")//{$Amount;}
						  {
							  $sqlM = "SELECT sum(tp02tuntutan) as total FROM tuntutanperjalanan,
									   tuntutankdetails WHERE tuntutanperjalanan.tp02idtuntut = tuntutankdetails.tp01idtuntut
									   AND tp02idtuntut = '$Idtuntut'
									   AND tp02bulan = '$Bulan'
									   AND tp02kodtuntutan[1,2] IN ('M')
									   ";
							  $resultM = odbc_exec($gaSqlPHP['link'], $sqlM);
							  $dataM = odbc_fetch_array($resultM);
							  $Amount_M = $dataM['total'];
						  }
						  /*else if($Kod == "J")//{$Amount;}
						  {
							  $sqlJ = "SELECT sum(tp02tuntutan) as total FROM tuntutanperjalanan,
								   tuntutankdetails WHERE tuntutanperjalanan.tp02idtuntut = tuntutankdetails.tp01idtuntut
								   AND tp02idtuntut = '$Idtuntut'
								   AND tp02bulan = '$Bulan'
								   AND tp02kodtuntutan[1,2] IN ('J')
								   ";
							  $resultJ = odbc_exec($gaSqlPHP['link'], $sqlJ);
							  $dataJ = odbc_fetch_array($resultJ);
							  $Amount_J = $dataJ['total'];
						  }
						  
						  
						  
						  else if($Kod == "N")//{$Amount;}
						  {
							  $sqlN = "SELECT sum(tp02tuntutan) as total FROM tuntutanperjalanan,
									   tuntutankdetails WHERE tuntutanperjalanan.tp02idtuntut = tuntutankdetails.tp01idtuntut
									   AND tp02idtuntut = '$Idtuntut'
									   AND tp02bulan = '$Bulan'
									   AND tp02kodtuntutan[1,2] IN ('N')
									   ";
							  $resultN = odbc_exec($gaSqlPHP['link'], $sqlN);
							  $dataN = odbc_fetch_array($resultN);
							  $Amount_N = $dataN['total'];
						  }
						  else if($Kod == "O")//{$Amount;}
						  {
							  $sqlO = "SELECT sum(tp02tuntutan) as total FROM tuntutanperjalanan,
									   tuntutankdetails WHERE tuntutanperjalanan.tp02idtuntut = tuntutankdetails.tp01idtuntut
									   AND tp02idtuntut = '$Idtuntut'
									   AND tp02bulan = '$Bulan'
									   AND tp02kodtuntutan[1,2] IN ('O')
									   ";
							  $resultO = odbc_exec($gaSqlPHP['link'], $sqlO);
							  $dataO = odbc_fetch_array($resultO);
							  $Amount_O = $dataO['total'];
						  }*/
						  else if($Kod == "K")//{$Amount;}
						  {
							  $sqlK = "SELECT sum(tp02tuntutan) as total FROM tuntutanperjalanan,
								   tuntutankdetails WHERE tuntutanperjalanan.tp02idtuntut = tuntutankdetails.tp01idtuntut
								   AND tp02idtuntut = '$Idtuntut'
								   AND tp02bulan = '$Bulan'
								   AND tp02kodtuntutan[1,2] IN ('K')
								   ";
							  $resultK = odbc_exec($gaSqlPHP['link'], $sqlK);
							  $dataK = odbc_fetch_array($resultK);
							  $Amount_K = $dataK['total'];
						  }
						  else if($Kod == "L")//{$Amount;}
						  {
							 $sqlL = "SELECT sum(tp02tuntutan) as total FROM tuntutanperjalanan,
								   tuntutankdetails WHERE tuntutanperjalanan.tp02idtuntut = tuntutankdetails.tp01idtuntut
								   AND tp02idtuntut = '$Idtuntut'
								   AND tp02status = 'DISEMAK2'
								   AND tp02bulan = '$Bulan'
								   AND tp02kodtuntutan[1,2] IN ('L')
								   ";
							  $resultL = odbc_exec($gaSqlPHP['link'], $sqlL);
							  $dataL = odbc_fetch_array($resultL);
							  $Amount_L = $dataL['total'];
						  }
						  else if($Kod == "E")//{$Amount;}
						  {
							  if($Kodfull == "E1")
							  {
								  $sqlJarakE1 = "SELECT sum(cast(tp02jarak as int)) as totaljarak FROM tuntutanperjalanan,
										  tuntutankdetails WHERE tuntutanperjalanan.tp02idtuntut = tuntutankdetails.tp01idtuntut
										  AND tp02idtuntut = '$Idtuntut'
										  AND tp02bulan = '$Bulan'
										  AND tp02status = 'DISEMAK2'
										  AND tp02kodtuntutan[1,2] IN ('E1')
										  ";
								  $resultJarakE1 = odbc_exec($gaSqlPHP['link'], $sqlJarakE1);
								  $dataJarakE1 = odbc_fetch_array($resultJarakE1);
								  $Amount_Jarak = $dataJarakE1['totaljarak'];
								  
								  $A = $Amount_Jarak;
								  $B=500;
								  
								  if($A <= 500)
								  {
									  $balancesum = $A * 0.85;
									  echo $Amount_E += $balancesum;
								  }
								  else if($A > 500)
								  {
									  $balance = $A - 500;
									  $total = 500 * 0.85;
									  $balancesum = $balance * 0.75;
									  $sumall = $total + $balancesum;
									  $Amount_E += $sumall;
									  $jumlaHamountE=$jumlaHamountE+$Amount_E;

								  }
							  }
							  else if($Kodfull == "E2")
							  {
								  $sqlJarakE2 = "SELECT sum(cast(tp02jarak as int)) as totaljarak FROM tuntutanperjalanan,
										  tuntutankdetails WHERE tuntutanperjalanan.tp02idtuntut = tuntutankdetails.tp01idtuntut
										  AND tp02idtuntut = '$Idtuntut'
										  AND tp02bulan = '$Bulan'
										  AND tp02status = 'DISEMAK2'
										  AND tp02kodtuntutan[1,2] IN ('E2')
										  ";
								  $resultJarakE2 = odbc_exec($gaSqlPHP['link'], $sqlJarakE2);
								  $dataJarakE2 = odbc_fetch_array($resultJarakE2);
								  $Amount_Jarak = $dataJarakE2['totaljarak'];
								  
								  $A = $Amount_Jarak;
								  $B=500;
								  
								  if($A <= 500)
								  {
									  $balancesum = $A * 0.55;
									  $Amount_E += $balancesum;
								  }
								  else if($A > 500)
								  {
									  $balance = $A - 500;
									  $total = 500 * 0.55;
									  $balancesum = $balance * 0.45;
									  $sumall = $total + $balancesum;
									  $Amount_E += $sumall;
								  }
	
								  //$Amount_KL = $Amount_E1 + $Amount_E2 + $Amount_D;
								  //$Amount_DE = sprintf('%0.2f', $Amount_KL); //boleh guna number format untuk titik perpuluhan
							  }
						  }
					  }
									
                
					  $JumlahKeseluruhan = $Amount_A + $Amount_BC + $Amount_DE + $Amount_F + $Amount_GH + $Amount_I + $Amount_J + $Amount_K + $Amount_L + $Amount_M + $Amount_N + $Amount_O;
					  
					  $JumlahKeseluruhan = sprintf('%0.2f', $JumlahKeseluruhan);
					  $money = $JumlahKeseluruhan;
					  $sum = ((string)$money);
					  $JumlahAwal = $sum[strlen($sum)-1];
					
					  if($JumlahAwal =='1')
					  {
						  $JumlahSedang = $money - 0.01;
					  }
					  else if($JumlahAwal =='2')
					  {
						  $JumlahSedang = $money - 0.02;
					  }
					  else if($JumlahAwal =='3')
					  {
						  $JumlahSedang = $money + 0.02;
					  }
					  else if($JumlahAwal =='4')
					  {
						  echo $JumlahSedang = $money + 0.01;
					  }
					  else if($JumlahAwal =='6')
					  {
						  $JumlahSedang = $money - 0.01;
					  }
					  else if($JumlahAwal =='7')
					  {
						  $JumlahSedang = $money - 0.02;
					  }
					  else if($JumlahAwal =='8')
					  {
						  $JumlahSedang = $money + 0.02;
					  }
					  else if($JumlahAwal =='9')
					  {
						  $JumlahSedang = $money + 0.01;
					  }
					  $JumlahBundar = sprintf('%0.2f', $JumlahSedang);
					  
					  $wang_bersih = $JumlahBundar - $wang_pendah;
					  $wang_bersih = sprintf('%0.2f', $wang_bersih);
					  
					  $money = 100;
					  $sum = ((string)$money);
					  $sum[strlen($sum)-1];
					  ?>
										
                  <tr bordercolor="#FFFFFF">
                    <td bordercolor="#000000" bgcolor="#C4F5E6"><div align="center"><font size="1"><?php echo $lineindex; ?></font></div></td>
                    <td bordercolor="#000000" bgcolor="#C4F5E6"><div align="center"><font size="1"><?php echo $nama; ?><br></font></div></td>
                    <td bordercolor="#000000" bgcolor="#C4F5E6"><div align="center"><font size="1"><?php echo $NoKPPegawai; ?></font></div></td>
                    <td bordercolor="#000000" bgcolor="#C4F5E6"><div align="center"><font size="1"><?php echo $noakaunbankquery; ?><br><?php echo $namabankquery; ?></font></div></td>
                    <td bordercolor="#000000" bgcolor="#C4F5E6" colspan="3"><div align="center"><font size="1"><?php if($Amount_A != 0){echo $Amount_A;}?></font></div></td>
                    <td bordercolor="#000000" bgcolor="#C4F5E6" colspan="3"><div align="center"><font size="1"><?php if($Amount_BC != 0){echo $Amount_BC;}?></font></div></td>
                    <td bordercolor="#000000" bgcolor="#C4F5E6" colspan="3"><div align="center"><font size="1"><?php if($Amount_E != 0){echo $Amount_E;}?></div></td>
                    <td bordercolor="#000000" bgcolor="#C4F5E6" colspan="3"><div align="center"><font size="1"><?php if($Amount_D != 0){echo $Amount_D;}?></font></div></td>
                    <td bordercolor="#000000" bgcolor="#C4F5E6" colspan="3"><div align="center"><font size="1"><?php if($Amount_GH != 0){echo $Amount_GH;}?></div></td>
                    <td bordercolor="#000000" bgcolor="#C4F5E6" colspan="3"><div align="center"><font size="1"><?php if($Amount_L != 0){echo $Amount_L;}?></div></td>
                    <td bordercolor="#000000" bgcolor="#C4F5E6" colspan="3"><div align="center"><font size="1"><?php if($Amount_F != 0){echo $Amount_F;}?></font></div></td>
                    <td bordercolor="#000000" bgcolor="#C4F5E6" colspan="3"><div align="center"><font size="1"> </font></div></td>
                    <td bordercolor="#000000" bgcolor="#C4F5E6" colspan="3"><div align="center"><font size="1"><?php if($Amount_K != 0){echo $Amount_K;}?></font></div></td>
                    <td bordercolor="#000000" bgcolor="#C4F5E6" colspan="3"><div align="center"><font size="1"> </font></div></td>
                    <td bordercolor="#000000" bgcolor="#C4F5E6" colspan="3"><div align="center"><font size="1"> </font></div></td>
                    <td bordercolor="#000000" bgcolor="#C4F5E6" colspan="3"><div align="center"><font size="1"><?php if($Amount_M != 0){echo $Amount_M;}?></font></div></td>
                    <td bordercolor="#000000" bgcolor="#C4F5E6" colspan="3"><div align="center"><font size="1"> </font></div></td>
                    <td bordercolor="#000000" bgcolor="#C4F5E6" colspan="3"><div align="center"><font size="1"> </font></div></td>
                    <td bordercolor="#000000" bgcolor="#C4F5E6" colspan="3">
                    <div align="center">
                    <font size="1"><?php echo $JumlahKeseluruhan = $Amount_A + $Amount_BC + $Amount_E + $Amount_D + $Amount_GH + $Amount_L + $Amount_F + $Amount_K + $Amount_L + $Amount_M ;
?></font>
                    </div>
                    </td>
        			<td bordercolor="#000000" bgcolor="#C4F5E6" align="center">
                    <center>
                    <input name="checkbox[]" type="checkbox" id="checkbox1<?php echo $i;?>"  value="<?php echo $i; ?>"/>
                    </center>
                    <input type="hidden" id="noid<?php echo $lineindex;?>" name="noid<?php echo $lineindex;?>" value="<?php echo $Id;?>">
                    <input type="hidden" id="NoKPPegawai<?php echo $lineindex;?>" name="NoKPPegawai<?php echo $lineindex;?>" value="<?php echo $NoKPPegawai;?>">
                    <input type="hidden" id="Nama<?php echo $lineindex;?>" name="Nama<?php echo $lineindex;?>" value="<?php echo $nama;?>">
                    <input type="hidden" id="nobank<?php echo $lineindex;?>" name="nobank<?php echo $lineindex;?>" value="<?php echo $noakaunbankquery;?>">
                    <input type="hidden" id="namabank<?php echo $lineindex;?>" name="namabank<?php echo $lineindex;?>" value="<?php echo $namabankquery;?>">
                    <input type="hidden" id="tuntutan<?php echo $lineindex;?>" name="tuntutan<?php echo $lineindex;?>" value="<?php echo $Tuntutan;?>">
                    <input type="hidden" id="jenistuntutan<?php echo $lineindex;?>" name="jenistuntutan<?php echo $lineindex;?>" value="<?php echo $JenisTuntutan;?>">                    
            		</td>
                  </tr>
				  <?php
				  $i++; 
				  }
				  ?>
                  <tr>
                    <td height="39" colspan="4"  align="right"><b>Jumlah :</b></td>
                    <td bordercolor="#000000"  colspan="3"><div align="center"><font size="1"></font><?php echo $jumlaHamountA?></div></td>
                    <td bordercolor="#000000"  colspan="3"><div align="center"><font size="1"> </font></div></td>
                    <td bordercolor="#000000"  colspan="3"><div align="center"><font size="1"> </font><?php echo $Amount_E?></div></td>
                    <td bordercolor="#000000"  colspan="3"><div align="center"><font size="1"> </font></div></td>
                    <td bordercolor="#000000"  colspan="3"><div align="center"><font size="1"> </font></div></td>
                    <td bordercolor="#000000"  colspan="3"><div align="center"><font size="1"> </font></div></td>
                    <td bordercolor="#000000"  colspan="3"><div align="center"><font size="1"> </font></div></td>
                    <td bordercolor="#000000"  colspan="3"><div align="center"><font size="1"> </font></div></td>
                    <td bordercolor="#000000"  colspan="3"><div align="center"><font size="1"> </font></div></td>
                    <td bordercolor="#000000"  colspan="3"><div align="center"><font size="1"> </font></div></td>
                    <td bordercolor="#000000"  colspan="3"><div align="center"><font size="1"> </font></div></td>
                    <td bordercolor="#000000"  colspan="3"><div align="center"><font size="1"> </font></div></td>
                    <td bordercolor="#000000"  colspan="3"><div align="center"><font size="1"> </font></div></td>
                    <td bordercolor="#000000"  colspan="3"><div align="center"><font size="1"> </font></div></td>

                      <td width="78" align="center"><b><?php echo number_format($count1,2); ?></b></td>
                  </tr>
                </table>
                
                <?php 
						$size = $lineindex;
						?>
				<input type="hidden" id="saiz" name="saiz" value="<?php echo $size;?>">
                

						<?php 
						/*$checkbox	= $_POST['checkbox'];
						 $max		= sizeof($checkbox);
						
						for ($idcheckbox=0; $idcheckbox<$max; $idcheckbox++)
						{
							 $noid=$_POST['noid'.$checkbox[$idcheckbox]];
							 $jumlahtuntut=$_POST['jumlahtuntut'.$checkbox[$idcheckbox]];
							 $JumlahT=$JumlahT + $jumlahtuntut;
						}*/
			}
					 ?>
          </form>
    	</div>
    </div>
    </div><!--/#content.col-md-0-->
</div><!--/fluid-row-->

    <hr>

    <footer class="row">
        <p class="col-md-12 col-sm-12 col-xs-12 powered-by"><font size="1">&copy; <a href="#" target="_blank">BPPA</a> 2019</font></p>
    </footer>

</div><!--/.fluid-container-->

	<!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog modal-md">
            <!-- Modal content-->
        </div>
    </div>
<!-- external javascript -->



<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/js/bootstrap-timepicker.min.js"></script>
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

<!-- library for cookie management -->
<script src="js/jquery.cookie.js"></script>
<!-- calender plugin -->
<script src='bower_components/moment/min/moment.min.js'></script>
<script src='bower_components/fullcalendar/dist/fullcalendar.min.js'></script>
<!-- data table plugin -->
<script src='js/jquery.dataTables.min.js'></script>

<!-- select or dropdown enhancer -->
<script src="bower_components/chosen/chosen.jquery.min.js"></script>
<!-- plugin for gallery image view -->
<script src="bower_components/colorbox/jquery.colorbox-min.js"></script>
<!-- notification plugin -->
<script src="js/jquery.noty.js"></script>
<!-- library for making tables responsive -->
<script src="bower_components/responsive-tables/responsive-tables.js"></script>
<!-- tour plugin -->
<script src="bower_components/bootstrap-tour/build/js/bootstrap-tour.min.js"></script>
<!-- star rating plugin -->
<script src="js/jquery.raty.min.js"></script>
<!-- for iOS style toggle switch -->
<script src="js/jquery.iphone.toggle.js"></script>
<!-- autogrowing textarea plugin -->
<script src="js/jquery.autogrow-textarea.js"></script>
<!-- multiple file upload plugin -->
<script src="js/jquery.uploadify-3.1.min.js"></script>
<!-- history.js for cross-browser state change on ajax -->
<script src="js/jquery.history.js"></script>
<!-- application script for Charisma demo -->
<script src="js/charisma.js"></script>
<script>
	$('#MasaBertolak').timepicker();
	$('#MasaSampai').timepicker();
	
	$(document).ready(function(){  
		$('.update_tuntutan').click(function(){  
			$.ajax({
			url:"KemaskiniTp.php",
			cache:false,
			success:function(data){
			$(".modal-md").html(data);
			}});
		});
	});
</script>


<script>
$(document).ready(function() {
 
 	$('#KodTuntutan').change(function() {
  	if(( $(this).val() == 'A11') || ( $(this).val() == 'A12')) {
       		$('#ElaunMakan').attr( "disabled", false );
    } else {       
      $('#ElaunMakan').attr( "disabled", true );
    }
  });
 
});

function carisenarai()
{
	form = document.hantar;
	if	(form.masasemak.value == "")
	{
		alert("Sila pilih Tarikh Mula.");
		form.masasemak.focus();
		return;
	}
	else if	(form.masasemak2.value == "")
	{
		alert("Sila pilih Tarikh Akhir.");
		form.masasemak2.focus();
		return;
	}
	else
	{
		form.submit();
	}	
}
</script>

<script src="js/tuntutan.js"></script>
<script>
	var currentTime = new Date();
	var month = currentTime.getMonth() + 1;
	var day = currentTime.getDate();
	var year = currentTime.getFullYear();
	Calendar.setup({
	inputField : "masasemak",
	trigger    : "f_btn1",
	onSelect   : function() { this.hide() },
	dateFormat : "%d/%m/%Y"   
	});
</script>
<script>
	var currentTime = new Date();
	var month = currentTime.getMonth() + 1;
	var day = currentTime.getDate();
	var year = currentTime.getFullYear();
	Calendar.setup({
	inputField : "masasemak2",
	trigger    : "f_btn2",
	onSelect   : function() { this.hide() },
	dateFormat : "%d/%m/%Y"   
	});
</script>

<script>
	var currentTime = new Date();
	var month = currentTime.getMonth() + 1;
	var day = currentTime.getDate();
	var year = currentTime.getFullYear();
	Calendar.setup({
	inputField : "Trkbaucer",
	trigger    : "f_btn3",
	onSelect   : function() { this.hide() },
	dateFormat : "%d/%m/%Y"   
	});
</script>

</body>
</html>
