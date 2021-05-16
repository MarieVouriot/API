<?php
    include "cnx.php";
    $request_method = $_SERVER["REQUEST_METHOD"];

    function  getLesActivites()
    {
        global $cnx;
		$reponse = array();
		$requete = $cnx->prepare("select AC_NUM, AC_DATE, AC_LIEU, AC_THEME from activite_compl");
		$requete->execute();
		$lesActivites = $requete->fetchAll(PDO::FETCH_NUM);
		foreach($lesActivites as $row)
		{
			$uneActivite = [
				'Num' => $row[0],
                'Date' => $row[1],
                'Lieu' => $row[2],
                'Theme' => $row[3],
			];
			$reponse[] = $uneActivite;
		}
		echo json_encode($reponse);
    }
	
	function  getLesPraticiens()
    {
        global $cnx;
		$reponse = array();
		$requete = $cnx->prepare("select PRA_NUM, PRA_NOM from praticien");
		$requete->execute();
		$lesPraticiens = $requete->fetchAll(PDO::FETCH_NUM);
		foreach($lesPraticiens as $row)
		{
			$lePraticien = [
				'Num' => $row[0],
                'Nom' => $row[1],
			];
			$reponse[] = $lePraticien;
		}
		echo json_encode($reponse);
    }

	function  getLesVisiteurs()
    {
        global $cnx;
		$reponse = array();
		$requete = $cnx->prepare("select VIS_MATRICULE, VIS_NOM from visiteur");
		$requete->execute();
		$lesVisiteurs = $requete->fetchAll(PDO::FETCH_NUM);
		foreach($lesVisiteurs as $row)
		{
			$leVisiteur = [
				'Matricule' => $row[0],
                'Nom' => $row[1],
			];
			$reponse[] = $leVisiteur;
		}
		echo json_encode($reponse);
    }

	function  getLesRapports()
    {
        global $cnx;
		$reponse = array();
		$requete = $cnx->prepare("select RAP_NUM, RAP_DATE, RAP_MOTIF, RAP_BILAN from rapport_visite");
		$requete->execute();
		$lesRapports = $requete->fetchAll(PDO::FETCH_NUM);
		foreach($lesRapports as $row)
		{
			$leRapport = [
				'Num' => $row[0],
                'Date' => $row[1],
				'Motif' => $row[2],
                'Bilan' => $row[3],
			];
			$reponse[] = $leRapport;
		}
		echo json_encode($reponse);
    }

	function AddRapport()
	{
		global $cnx;
		$json_str = file_get_contents('php://input');
		$leRapport = json_decode($json_str);
		$requete = $cnx->prepare("insert into rapport_visite (RAP_DATE, RAP_MOTIF, RAP_BILAN, PRA_NUM, VIS_MATRICULE) values(:date, :motif, :bilan, :praNum, :visMatricule)");
		$requete->bindValue('date',$leRapport->Date);
		$requete->bindValue('motif',$leRapport->Motif);
		$requete->bindValue('bilan',$leRapport->Bilan);
		$requete->bindValue('praNum',$leRapport->PraNum);
		$requete->bindValue('visMatricule',$leRapport->VisMatricule);
		$requete->execute();
	}

    function UpdateActivite()
	{
		global $cnx;
		$json_str = file_get_contents('php://input');
		$lActivite = json_decode($json_str);
		$sql = $cnx->prepare("update activite_compl set AC_THEME = :theme, AC_LIEU = :lieu where AC_NUM = :num");
		$sql->bindValue("theme",$lActivite->Theme, PDO::PARAM_STR);
		$sql->bindValue("lieu",$lActivite->Lieu, PDO::PARAM_STR);
	 	$sql->bindValue("num",$lActivite->Num, PDO::PARAM_INT);
		$sql->execute();
	}

    switch($request_method)
    {
        case 'GET':
			if(!empty($_GET["id"]))
			{
				if($_GET["id"] == 1)
				{
					getLesPraticiens($_GET["id"]);
				}
				else if($_GET["id"] == 2)
				{
					getLesVisiteurs($_GET["id"]);
				}
				else if($_GET["id"] == 3)
				{
					getLesRapports($_GET["id"]);
				}
			}
			else
			{
				getLesActivites();
			}
            break;
		case 'POST':
			AddRapport();
			break;
        case 'PUT':
            UpdateActivite();
            break;
    }
?>