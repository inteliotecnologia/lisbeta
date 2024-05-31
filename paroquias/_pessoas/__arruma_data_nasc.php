<?
if ($_SESSION["id_usuario_sessao"]!="") {
	$result= mysql_query("select * from pessoas where DATE_FORMAT(data_nasc, '%Y') > '2008'");
	
	while ($rs= mysql_fetch_object($result)) {
		
		$data_nasc= explode('-', $rs->data_nasc);
		
		$ano= $data_nasc[0]-100;
		$mes= $data_nasc[1];
		$dia= $data_nasc[2];
		
		$nova= $ano.'-'.$mes.'-'.$dia;
		
		$result2= mysql_query("update pessoas set data_nasc = '". $nova ."' where id_pessoa = '". $rs->id_pessoa ."' ");
		echo $rs->data_nasc .' -> '. $nova ."<br />";
		
	}
	
}
?>