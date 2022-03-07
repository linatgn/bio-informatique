<html>
    <head>
    	<!-- Importation du css -->
        <link rel="stylesheet" href="style.css" type="text/css" />
    </head>
    
    <header>
    	<h1>Analyse Informatique de Données Biologiques</h1>
    </header>
    
    
<div class="resultats">
    <?php

        echo '<h2>Résultats de la recherche</h2>';
      	//Declaration des variables qui contiennent
      	//les entrees des formulaires  
    	$ng = $_REQUEST['nomGene'];
    	$np = $_REQUEST['nomProt'];
    	$com = $_REQUEST['commentaires'];
    	
    	$connexion = oci_connect('c##hcelayi_a', 'hcelayi_a', 'dbinfo');

    	//Requete
    	$txtReq = "select distinct e.accession "
    	. "from entries e, gene_names gn, entry_2_gene_name e2gn, protein_names pn, proteins p, prot_name_2_prot pn2p, comments c "
    	. "where e.accession = e2gn.accession "
    	//Nom du gene
    	. "and e2gn.gene_name_id = gn.gene_name_id "
    	. "and gn.gene_name like '%$ng%' "
    	//Noms de la proteine
    	. "and e.accession = p.accession "
    	. "and p.accession = pn2p.accession "
    	. "and pn2p.prot_name_id = pn.prot_name_id "
    	. "and pn.prot_name like '%$np%' "
    	//Commentaires
    	. "and e.accession = c.accession "
    	. "and c.txt_c like '%$com%' ";

    	//Creation de l'ordre
    	$ordre1 = oci_parse($connexion, $txtReq);

    	//Exectution de l'ordre
    	oci_execute($ordre1);
        $i = 0;
    	while(($row = oci_fetch_array($ordre1, OCI_BOTH)) != false) {
    		//Affiche des lignes composee de 3 elements
            if(($i == 3)) {
            	//Chaque element est un formulaire de type submit et qui donc est comme un boutton 
                echo '<form method="post" action="question2.php">  <input type="submit" name="accession" value="' . $row[0]. '"> </input><br><br>    ';
                $i = 0;
            } else {
                echo '<form method="post" action="question2.php">  <input type="submit" name="accession" value="' . $row[0]. '"> </input>    ';
                $i++;
            }
    	}

    	//libération de la mémoire et deconnexion
    	oci_free_statement($ordre1);
    	oci_close($connexion);
        
    ?>
</div>
</html>
