<?php
include_once("../../login/check.php");
if(!empty($_GET)){
	extract($_GET);
	include_once("../../csv/csv.php");
	include_once("../../class/alumno.php");
	include_once("../../class/cursomateriaexportar.php");
	include_once("../../class/registronotas.php");
	include_once("../../class/casilleros.php");
	include_once("../../class/notascualitativa.php");
	include_once("../../class/config.php");
	include_once("../../class/agenda.php");
	include_once("../../class/curso.php");
	$config=new config;
	$agenda=new agenda;
	$curso=new curso;
	$notascualitativa=new notascualitativa;
	$cur=$curso->mostrarCurso($CodCurso);
	$cur=array_shift($cur);
	
	if($cur['Bimestre']){
		$InicioBimestre1=$config->mostrarConfig("InicioBimestre1",1);
		$FinBimestre1=$config->mostrarConfig("FinBimestre1",1);
		$InicioBimestre2=$config->mostrarConfig("InicioBimestre2",1);
		$FinBimestre2=$config->mostrarConfig("FinBimestre2",1);
		$InicioBimestre3=$config->mostrarConfig("InicioBimestre3",1);
		$FinBimestre3=$config->mostrarConfig("FinBimestre3",1);
		$InicioBimestre4=$config->mostrarConfig("InicioBimestre4",1);
		$FinBimestre4=$config->mostrarConfig("FinBimestre4",1);
	}else{
		$InicioTrimestre1=($config->mostrarConfig("InicioTrimestre1",1));
		$FinTrimestre1=($config->mostrarConfig("FinTrimestre1",1));
		$InicioTrimestre2=($config->mostrarConfig("InicioTrimestre2",1));
		$FinTrimestre2=($config->mostrarConfig("FinTrimestre2",1));
		$InicioTrimestre3=($config->mostrarConfig("InicioTrimestre3",1));
		$FinTrimestre3=($config->mostrarConfig("FinTrimestre3",1));
	}
	
	$alumno=new alumno;
	$casilleros=new casilleros;
	$registronotas=new registronotas;
	$cursomateriaexportar=new cursomateriaexportar;
	
	$fila=array();
	if($Numeracion=="si"){
		if($Cabecera=="si")
		$fila[]="N";	
	}
	if($Cabecera=="si")
		$fila[]=$idioma["Apellidos"]." ".$idioma["Nombres"];
	
	if($Trimestre=="todo"){
		
		if($Cabecera=="si"){
			foreach($cursomateriaexportar->mostrarMaterias($CodCurso) as $CurMatExp){
			if($SeparadorMateria!=""){
				$fila[]=$SeparadorMateria;	
			}
			if($cur['Bimestre']){
				$fila[]="NP1";
				$fila[]="NotaCualitativa1";
				$fila[]="NP2";
				$fila[]="NotaCualitativa2";
				$fila[]="NP3";
				$fila[]="NotaCualitativa3";
				$fila[]="NP4";
				$fila[]="NotaCualitativa4";
			}else{
			$fila[]="NP1";
			$fila[]="NotaCualitativa1";
			$fila[]="NP2";
			$fila[]="NotaCualitativa2";
			$fila[]="NP3";
			$fila[]="NotaCualitativa3";
			}
			//$fila[]="Ref";
			}
			if($SeparadorEstadisticas!=""){
				$fila[]=$SeparadorEstadisticas;	
			}
			if($cur['Bimestre']){
				$fila[]="Dias Trab-1";
				$fila[]="Falta C/Lic-1";
				$fila[]="Falta S/Lic-1";
				$fila[]="Atrasos-1";
				$fila[]="Dias Trab-2";
				$fila[]="Falta C/Lic-2";
				$fila[]="Falta S/Lic-2";
				$fila[]="Atrasos-2";
				$fila[]="Dias Trab-3";
				$fila[]="Falta C/Lic-3";
				$fila[]="Falta S/Lic-3";
				$fila[]="Atrasos-3";
				$fila[]="Dias Trab-4";
				$fila[]="Falta C/Lic-4";
				$fila[]="Falta S/Lic-4";
				$fila[]="Atrasos-4";
			}else{
			$fila[]="Dias Trab-1";
			$fila[]="Falta C/Lic-1";
			$fila[]="Falta S/Lic-1";
			$fila[]="Atrasos-1";
			$fila[]="Dias Trab-2";
			$fila[]="Falta C/Lic-2";
			$fila[]="Falta S/Lic-2";
			$fila[]="Atrasos-2";
			$fila[]="Dias Trab-3";
			$fila[]="Falta C/Lic-3";
			$fila[]="Falta S/Lic-3";
			$fila[]="Atrasos-3";
			}
		}
	}else{
		$cas=array_shift($casilleros->mostrarMateriaCursoTrimestre($Materias,$CodCurso,$Trimestre));
		if($Cabecera=="si"){
			$fila[]="N".$Trimestre;
			$fila[]="Dps".$Trimestre;
			/*$fila[]="Dias Trabajados";
			$fila[]="Falta C/Lic";
			$fila[]="Falta S/Lic";
			$fila[]="Atrasos";*/
		}
	}
	$datos=array();
	if($Cabecera=="si"){
		array_push($datos,$fila);
	}
	//print_r($_GET);
	$i=0;
		foreach($alumno->mostrarDatosAlumnos($CodCurso) as $al){$i++;
		$sw=0;
		$fila=array();
			if($Numeracion=="si"){
				$fila[]=$i;	
			}
			
			$fila[]=ucwords($al['Paterno'])." ".ucwords($al['Materno'])." ".ucwords($al['Nombres']);
			if($Trimestre=="todo"){
				/**/
				if($cur['Bimestre']){
				$faltasConLic1=array_shift($agenda->mostrarCodCursoCodObservacionCodAlumnoRango($CodCurso,14,$al['CodAlumno'],$InicioBimestre1,$FinBimestre1));
				$faltasSinLic1=array_shift($agenda->mostrarCodCursoCodObservacionCodAlumnoRango($CodCurso,12,$al['CodAlumno'],$InicioBimestre1,$FinBimestre1));
				$Atrasos1=array_shift($agenda->mostrarCodCursoCodObservacionCodAlumnoRango($CodCurso,11,$al['CodAlumno'],$InicioBimestre1,$FinBimestre1));
				
				$faltasConLic2=array_shift($agenda->mostrarCodCursoCodObservacionCodAlumnoRango($CodCurso,14,$al['CodAlumno'],$InicioBimestre2,$FinBimestre2));
				$faltasSinLic2=array_shift($agenda->mostrarCodCursoCodObservacionCodAlumnoRango($CodCurso,12,$al['CodAlumno'],$InicioBimestre2,$FinBimestre2));
				$Atrasos2=array_shift($agenda->mostrarCodCursoCodObservacionCodAlumnoRango($CodCurso,11,$al['CodAlumno'],$InicioBimestre2,$FinBimestre2));
				
				$faltasConLic3=array_shift($agenda->mostrarCodCursoCodObservacionCodAlumnoRango($CodCurso,14,$al['CodAlumno'],$InicioBimestre3,$FinBimestre3));
				$faltasSinLic3=array_shift($agenda->mostrarCodCursoCodObservacionCodAlumnoRango($CodCurso,12,$al['CodAlumno'],$InicioBimestre3,$FinBimestre3));
				$Atrasos3=array_shift($agenda->mostrarCodCursoCodObservacionCodAlumnoRango($CodCurso,11,$al['CodAlumno'],$InicioBimestre3,$FinBimestre3));
				
				$faltasConLic4=array_shift($agenda->mostrarCodCursoCodObservacionCodAlumnoRango($CodCurso,14,$al['CodAlumno'],$InicioBimestre4,$FinBimestre4));
				$faltasSinLic4=array_shift($agenda->mostrarCodCursoCodObservacionCodAlumnoRango($CodCurso,12,$al['CodAlumno'],$InicioBimestre4,$FinBimestre4));
				$Atrasos4=array_shift($agenda->mostrarCodCursoCodObservacionCodAlumnoRango($CodCurso,11,$al['CodAlumno'],$InicioBimestre4,$FinBimestre4));
				/**/
				$r1=array_shift($registronotas->mostrarRegistroNotas($cas1['CodCasilleros'],$al['CodAlumno'],1));
				$r2=array_shift($registronotas->mostrarRegistroNotas($cas2['CodCasilleros'],$al['CodAlumno'],2));
				$r3=array_shift($registronotas->mostrarRegistroNotas($cas3['CodCasilleros'],$al['CodAlumno'],3));
				$r4=array_shift($registronotas->mostrarRegistroNotas($cas4['CodCasilleros'],$al['CodAlumno'],4));
				//$promedioAnual=number_format(($r1['NotaFinal']+$r2['NotaFinal']+$r3['NotaFinal'])/3,0);
				/*$fila[]="N1";
				$fila[]="N2";
				$fila[]="N3";
				$fila[]="N4";
				$fila[]="Dias Trab-1";
				$fila[]="Falta C/Lic-1";
				$fila[]="Falta S/Lic-1";
				$fila[]="Atrasos-1";
				$fila[]="Dias Trab-2";
				$fila[]="Falta C/Lic-2";
				$fila[]="Falta S/Lic-2";
				$fila[]="Atrasos-2";
				$fila[]="Dias Trab-3";
				$fila[]="Falta C/Lic-3";
				$fila[]="Falta S/Lic-3";
				$fila[]="Atrasos-3";
				$fila[]="Dias Trab-4";
				$fila[]="Falta C/Lic-4";
				$fila[]="Falta S/Lic-4";
				$fila[]="Atrasos-4";*/
				
				$fila[]=$r1['Resultado'];
				$fila[]=$r2['Resultado'];
				$fila[]=$r3['Resultado'];
				$fila[]=$r4['Resultado'];
				$fila[]="68";
				$fila[]=$faltasConLic1['Cantidad'];
				$fila[]=$faltasSinLic1['Cantidad'];
				$fila[]=$Atrasos1['Cantidad'];
				$fila[]="64";
				$fila[]=$faltasConLic2['Cantidad'];
				$fila[]=$faltasSinLic2['Cantidad'];
				$fila[]=$Atrasos2['Cantidad'];
				$fila[]="68";
				$fila[]=$faltasConLic3['Cantidad'];
				$fila[]=$faltasSinLic3['Cantidad'];
				$fila[]=$Atrasos3['Cantidad'];
				$fila[]="68";
				$fila[]=$faltasConLic4['Cantidad'];
				$fila[]=$faltasSinLic4['Cantidad'];
				$fila[]=$Atrasos4['Cantidad'];
				}else{
				$faltasConLic1=array_shift($agenda->mostrarCodCursoCodObservacionCodAlumnoRango($CodCurso,14,$al['CodAlumno'],$InicioTrimestre1,$FinTrimestre1));
				$faltasSinLic1=array_shift($agenda->mostrarCodCursoCodObservacionCodAlumnoRango($CodCurso,12,$al['CodAlumno'],$InicioTrimestre1,$FinTrimestre1));
				$Atrasos1=array_shift($agenda->mostrarCodCursoCodObservacionCodAlumnoRango($CodCurso,11,$al['CodAlumno'],$InicioTrimestre1,$FinTrimestre1));
				
				$faltasConLic2=array_shift($agenda->mostrarCodCursoCodObservacionCodAlumnoRango($CodCurso,14,$al['CodAlumno'],$InicioTrimestre2,$FinTrimestre2));
				$faltasSinLic2=array_shift($agenda->mostrarCodCursoCodObservacionCodAlumnoRango($CodCurso,12,$al['CodAlumno'],$InicioTrimestre2,$FinTrimestre2));
				$Atrasos2=array_shift($agenda->mostrarCodCursoCodObservacionCodAlumnoRango($CodCurso,11,$al['CodAlumno'],$InicioTrimestre2,$FinTrimestre2));
				
				$faltasConLic3=array_shift($agenda->mostrarCodCursoCodObservacionCodAlumnoRango($CodCurso,14,$al['CodAlumno'],$InicioTrimestre3,$FinTrimestre3));
				$faltasSinLic3=array_shift($agenda->mostrarCodCursoCodObservacionCodAlumnoRango($CodCurso,12,$al['CodAlumno'],$InicioTrimestre3,$FinTrimestre3));
				$Atrasos3=array_shift($agenda->mostrarCodCursoCodObservacionCodAlumnoRango($CodCurso,11,$al['CodAlumno'],$InicioTrimestre3,$FinTrimestre3));
				
				/**/
				$r1=array_shift($registronotas->mostrarRegistroNotas($cas1['CodCasilleros'],$al['CodAlumno'],1));
				$r2=array_shift($registronotas->mostrarRegistroNotas($cas2['CodCasilleros'],$al['CodAlumno'],2));
				$r3=array_shift($registronotas->mostrarRegistroNotas($cas3['CodCasilleros'],$al['CodAlumno'],3));
				$r4=array_shift($registronotas->mostrarRegistroNotas($cas4['CodCasilleros'],$al['CodAlumno'],4));
				//$promedioAnual=number_format(($r1['NotaFinal']+$r2['NotaFinal']+$r3['NotaFinal'])/3,0);
				$fila[]=$r1['Resultado'];
				$fila[]=$r1['Dps'];
				$fila[]=$r2['Resultado'];
				$fila[]=$r2['Dps'];
				$fila[]=$r3['Resultado'];
				$fila[]=$r3['Dps'];
				$fila[]=$r4['Nota2'];
				$fila[]="68";
				$fila[]=$faltasConLic1['Cantidad'];
				$fila[]=$faltasSinLic1['Cantidad'];
				$fila[]=$Atrasos1['Cantidad'];
				$fila[]="64";
				$fila[]=$faltasConLic2['Cantidad'];
				$fila[]=$faltasSinLic2['Cantidad'];
				$fila[]=$Atrasos2['Cantidad'];
				$fila[]="68";
				$fila[]=$faltasConLic3['Cantidad'];
				$fila[]=$faltasSinLic3['Cantidad'];
				$fila[]=$Atrasos3['Cantidad'];
				}
				
				/**/
				foreach($cursomateriaexportar->mostrarMaterias($CodCurso) as $CurMatExp){
					if($SeparadorMateria!=""){
						$fila[]=$SeparadorMateria;	
					}
					if($CurMatExp['CodMateria']==1000){
						for($na=1;$na<=3;$na++){
							$cas1=array_shift($casilleros->mostrarMateriaCursoTrimestre(17,$CodCurso,$na));
							$cas2=array_shift($casilleros->mostrarMateriaCursoTrimestre(18,$CodCurso,$na));
							$cas3=array_shift($casilleros->mostrarMateriaCursoTrimestre(19,$CodCurso,$na));
							
							/*$casref1=array_shift($casilleros->mostrarMateriaCursoTrimestre($CurMatExp['CodMateria'],$CodCurso,4));
							$casref1=array_shift($casilleros->mostrarMateriaCursoTrimestre($CurMatExp['CodMateria'],$CodCurso,4));
							$casref1=array_shift($casilleros->mostrarMateriaCursoTrimestre($CurMatExp['CodMateria'],$CodCurso,4));
							*/
							$mr1=array_shift($registronotas->mostrarRegistroNotas($cas1['CodCasilleros'],$al['CodAlumno'],$na));
							$mr2=array_shift($registronotas->mostrarRegistroNotas($cas2['CodCasilleros'],$al['CodAlumno'],$na));
							$mr3=array_shift($registronotas->mostrarRegistroNotas($cas3['CodCasilleros'],$al['CodAlumno'],$na));
							
							//$r4=array_shift($registronotas->mostrarRegistroNotas($cas4['CodCasilleros'],$al['CodAlumno'],$na));	
							
							$promedioresul=number_format(($mr1['Resultado']+$mr2['Resultado']+$mr3['Resultado'])/3,0);
							$dpsresul=number_format(($mr1['Dps']+$mr2['Dps']+$mr3['Dps'])/3,0);
							$fila[]=$promedioresul;
							$fila[]=$dpsresul;
						}
						
						//$fila[]=$r2['Resultado'];
						//$fila[]=$r2['Dps'];
						//$fila[]=$r3['Resultado'];
						//$fila[]=$r3['Dps'];
						//$fila[]=$r4['Nota2'];
						$fila[]=0;
						
					}else{
						
						$cas1=array_shift($casilleros->mostrarMateriaCursoSexoTrimestre($CurMatExp['CodMateria'],$CodCurso,$al['Sexo'],1));

						$cas2=array_shift($casilleros->mostrarMateriaCursoSexoTrimestre($CurMatExp['CodMateria'],$CodCurso,$al['Sexo'],2));
						$cas3=array_shift($casilleros->mostrarMateriaCursoSexoTrimestre($CurMatExp['CodMateria'],$CodCurso,$al['Sexo'],3));
						$cas4=array_shift($casilleros->mostrarMateriaCursoSexoTrimestre($CurMatExp['CodMateria'],$CodCurso,$al['Sexo'],4));
						$r1=array_shift($registronotas->mostrarRegistroNotas($cas1['CodCasilleros'],$al['CodAlumno'],1));
						$r2=array_shift($registronotas->mostrarRegistroNotas($cas2['CodCasilleros'],$al['CodAlumno'],2));
						$r3=array_shift($registronotas->mostrarRegistroNotas($cas3['CodCasilleros'],$al['CodAlumno'],3));
						$r4=array_shift($registronotas->mostrarRegistroNotas($cas4['CodCasilleros'],$al['CodAlumno'],4));
						//$promedioAnual=number_format(($r1['NotaFinal']+$r2['NotaFinal']+$r3['NotaFinal'])/3,0);
						
						
						$ncuali1=array_shift($notascualitativa->mostrarNota($cas1['CodDocenteMateriaCurso'],1));
						$ncuali2=array_shift($notascualitativa->mostrarNota($cas2['CodDocenteMateriaCurso'],2));
						$ncuali3=array_shift($notascualitativa->mostrarNota($cas3['CodDocenteMateriaCurso'],3));
						
						$fila[]=$r1['NotaFinal'];
						if($r1['NotaFinal']>=1 && $r1['NotaFinal']<=35){
							$fila[]=mb_strtoupper($ncuali1['PrimerRango'],"utf8");
							
						}elseif($r1['NotaFinal']>=36 && $r1['NotaFinal']<=49){
							$fila[]=mb_strtoupper($ncuali1['SegundoRango'],"utf8");
							
						}elseif($r1['NotaFinal']>=50 && $r1['NotaFinal']<=60){
							$fila[]=mb_strtoupper($ncuali1['TercerRango'],"utf8");
							
						}elseif($r1['NotaFinal']>=61 && $r1['NotaFinal']<=70){
							$fila[]=mb_strtoupper($ncuali1['CuartoRango'],"utf8");
						}

						$fila[]=$r2['NotaFinal'];
						if($r2['NotaFinal']>=1 && $r2['NotaFinal']<=35){
							$fila[]=mb_strtoupper($ncuali2['PrimerRango'],"utf8");
							
						}elseif($r2['NotaFinal']>=36 && $r2['NotaFinal']<=49){
							$fila[]=mb_strtoupper($ncuali2['SegundoRango'],"utf8");
							
						}elseif($r2['NotaFinal']>=50 && $r2['NotaFinal']<=60){
							$fila[]=mb_strtoupper($ncuali2['TercerRango'],"utf8");
							
						}elseif($r2['NotaFinal']>=61 && $r2['NotaFinal']<=70){
							$fila[]=mb_strtoupper($ncuali2['CuartoRango'],"utf8");
						}
						
						$fila[]=$r3['NotaFinal'];
						if($r3['NotaFinal']>=1 && $r3['NotaFinal']<=35){
							$fila[]=mb_strtoupper($ncuali3['PrimerRango'],"utf8");
							
						}elseif($r3['NotaFinal']>=36 && $r3['NotaFinal']<=49){
							$fila[]=mb_strtoupper($ncuali3['SegundoRango'],"utf8");
							
						}elseif($r3['NotaFinal']>=50 && $r3['NotaFinal']<=60){
							$fila[]=mb_strtoupper($ncuali3['TercerRango'],"utf8");
							
						}elseif($r3['NotaFinal']>=61 && $r3['NotaFinal']<=70){
							$fila[]=mb_strtoupper($ncuali3['CuartoRango'],"utf8");
						}
						
						
						$fila[]=$r4['Nota2'];
						if($r4['Nota2']!=0){
							$sw=1;	
						}
					}
				}		
				if($SeparadorEstadisticas!=""){
					$fila[]=$SeparadorEstadisticas;	
				}
				$total1=68-$faltasConLic1['Cantidad']-$faltasSinLic1['Cantidad'];
				$fila[]=$total1;
				$fila[]=$faltasConLic1['Cantidad'];
				$fila[]=$faltasSinLic1['Cantidad'];
				$fila[]=$Atrasos1['Cantidad'];
				
				$total2=64-$faltasConLic2['Cantidad']-$faltasSinLic2['Cantidad'];
				$fila[]=$total2;
				$fila[]=$faltasConLic2['Cantidad'];
				$fila[]=$faltasSinLic2['Cantidad'];
				$fila[]=$Atrasos2['Cantidad'];
				
				$total3=68-$faltasConLic3['Cantidad']-$faltasSinLic3['Cantidad'];
				$fila[]=$total3;
				$fila[]=$faltasConLic3['Cantidad'];
				$fila[]=$faltasSinLic3['Cantidad'];
				$fila[]=$Atrasos3['Cantidad'];
			}else{
				$r=array_shift($registronotas->mostrarRegistroNotas($cas['CodCasilleros'],$al['CodAlumno'],$Trimestre));
				$fila[]=$r['NotaFinal'];
				$fila[]=$r['Dps'];
			}
			if($sw==1){
				$fila[]=6;	
			}else{
				$fila[]=0;	
			}
			array_push($datos,$fila);
		}
	
	
	archivocsv("reportecualitativo-$CodCurso.csv",$datos,$Separador,stripslashes( $SeparadorFila));
}
?>