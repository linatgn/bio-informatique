<html>

    <head>
        <meta charset="utf-8" />
    <title>Analyse Informatique de Données Biologiques</title>
    	<link rel="stylesheet" href="style.css" type="text/css" />
    </head>
    
    <header>
    	<h1>Analyse Informatique de Données Biologiques</h1>
    </header>

    <div class="resultats">
        <?php

            //Récupérer dans des variables locales les paramètres du formulaire
            $ac = $_REQUEST['accession'];

            $connexion = oci_connect('c##hcelayi_a', 'hcelayi_a', 'dbinfo');
            
            //Informations sur la séquence
            $txtReq = " select  p.seq, p.seqLength, p.seqMass, e.specie "
            . "from proteins p, entries e "
            . "where e.accession = :acces "
            . "and p.accession = e.accession ";
            
            //Noms des protéines avec leurs types et sortes
            $protReq = " select  pn.prot_name_id, pn.prot_name, pn.name_kind, pn.name_type "
            . "from proteins p, protein_names pn, prot_name_2_prot p2 "
            . "where p.accession = :acces "
            . "and p.accession = p2.accession "
            . "and p2.prot_name_id = pn.prot_name_id ";
            
            //Noms des gènes et leurs types
            $geneReq = " select  gn.gene_name, gn.name_type "
            . "from gene_names gn, entry_2_gene_name g2, entries e "
            . "where e.accession = :acces "
            . "and e.accession = g2.accession "
            . "and g2.gene_name_id = gn.gene_name_id ";
            
            //Keywords
            $keyReq = " select  k.kw_label "
            . "from keywords k, entries_2_keywords k2, entries e "
            . "where e.accession = :acces "
            . "and e.accession = k2.accession "
            . "and k2.kw_id = k.kw_id ";
            
            //Commentaires
            $comReq = " select  c.type_c, c.txt_c "
            . "from comments c, entries e "
            . "where e.accession = :acces "
            . "and e.accession = c.accession ";

            //Informations relatives aux termes GO
            $goReq = "select db.db_ref "
            . "from dbref db, entries e "
            . "where e.accession = :acces "
            . "and e.accession = db.accession ";

            //Creation des ordres et affectation des noms
            $ordre1 = oci_parse($connexion, $txtReq);
            $ordre2 = oci_parse($connexion, $protReq);
            $ordre3 = oci_parse($connexion, $geneReq);
            $ordre4 = oci_parse($connexion, $keyReq);
            $ordre5 = oci_parse($connexion, $comReq);
            $ordre6 = oci_parse($connexion, $goReq);

            oci_bind_by_name($ordre1, ":acces", $ac);
            oci_bind_by_name($ordre2, ":acces", $ac);
            oci_bind_by_name($ordre3, ":acces", $ac);
            oci_bind_by_name($ordre4, ":acces", $ac);
            oci_bind_by_name($ordre5, ":acces", $ac);
            oci_bind_by_name($ordre6, ":acces", $ac);


            //Requetes
            
            //Requete 1
            oci_execute($ordre1);
            echo '<h2> Sequence information </h2> '; 
            echo '<u>Sequence :</u> <br><br>';
			while (($row = oci_fetch_array($ordre1, OCI_BOTH)) !=false) {
				 echo '<textarea id="texte">';
                 echo $row[0] -> load();
                 echo '</textarea><br><br>';
                 echo '<u>SeqLength :</u><br><br>' .$row[1].
                 '<u>SeqMass :</u><br><br>' .$row[2].
                 '<u>Specie :</u> ' .'<a href="https://www.ncbi.nlm.nih.gov/Taxonomy/Browser/wwwtax.cgi?id='.$row[3].'">'.$row[3].'</a><br><br>';                
            } 
              
            //Requete 2
            oci_execute($ordre2);
            echo '<h2>Proteins information</h2>';
            while (($row = oci_fetch_array($ordre2, OCI_BOTH)) !=false) {
                echo '<u>Prot_name_id :</u> ' . $row[0] . '<br><br><u>Protein name :</u> ' . $row[1]. '<br><br><u>Name kind :</u> ' . $row[2]. '<br><br><u>Name type :</u> ' . $row[3].'<br><br>';         
            }
            
            //Requete 3
            oci_execute($ordre3);
            echo '<h2>Gene Names</h2>';
            while (($row = oci_fetch_array($ordre3, OCI_BOTH)) !=false) {
                echo '<u>Gene name :</u> ' . $row[0] . '<br><br><u>Name type :</u> ' . $row[1] . '<br><br>'; 
            }

            //Requete 4
            oci_execute($ordre4);
            echo '<h2>Keywords associated </h2>';
			echo '<table align="center">';
            $i = 0;
            while (($row = oci_fetch_array($ordre4, OCI_BOTH)) !=false) {
                if(($i%2 == 0)) {
                    echo '</tr><tr><td>' . $row[0] . '</td>';
                } else {
                echo  '<td>' . $row[0] . '</td>'; 
                }
                $i++;
            }
            echo '</table><br><br>';
            
            //Requête 5
            oci_execute($ordre5);
            echo '<h2> Comments associated</h2>';
            while (($row = oci_fetch_array($ordre5, OCI_BOTH)) !=false) {
                echo '<u>Comment type :</u> ' . $row[0] . '<br><br><u>Details :</u> ' . $row[1] . '<br><br>'; 
            }


            //Requete 6
            oci_execute($ordre6);
            echo 'S<h2> Termes GO </h2>';
            $i = 0;
            echo '<table align="center">';
            while(($row = oci_fetch_array($ordre6, OCI_BOTH)) != false) {
                if(($i%6) == 0) {
                    echo '</tr><tr><td><a href="https://www.ebi.ac.uk/QuickGO/term/'. $row[0]. '">' . $row[0] . '</a></td>';
                } else {
                    echo '<td><a href="https://www.ebi.ac.uk/QuickGO/term/'. $row[0]. '">' . $row[0] . '</a></td>';
                }
                $i++;
            }
            echo '</table>';


            //libération de la mémoire et deconnexion
            oci_free_statement($ordre1);
            oci_free_statement($ordre2);
            oci_free_statement($ordre3);
            oci_free_statement($ordre4);
            oci_free_statement($ordre5);
            oci_free_statement($ordre6);

            oci_close($connexion);

        ?>
    </div>
</html>
