/*Requete 1*/

SELECT e.accession, p.prot_name
    FROM Protein_names p, Entries e, Comments c, Prot_name_2_prot p2, Proteins p1
    WHERE  e.accession = p1.accession
    AND  p1.accession = p2.accession
    AND  p2.prot_name_id = p.prot_name_id
    AND  c.accession = e.accession
    AND  p.name_kind = 'recommendedName'
    AND c.txt_c like '%cardiac%';


/*Requete 2*/

    FROM Protein_names p, Entries e, Keywords k, Prot_name_2_prot p2, Proteins p1, Entries_2_keywords k2
    WHERE  e.accession = p1.accession
    AND  p1.accession = p2.accession
    AND  p2.prot_name_id = p.prot_name_id
    AND  e.accession = k2.accession
    AND  k2.kw_id = k.kw_id
    AND  p.name_kind = 'recommendedName'
    AND k.kw_label like '%Long QT syndrome%';


/*Requete 3*/

SELECT e.accession, p1.seqLength
FROM Entries e, Proteins p1
WHERE p1.accession = e.accession
AND p1.seqLength >= (SELECT MAX(p2.seqLength)
                        FROM Proteins p2);


/*Requete 4*/

SELECT e.accession, COUNT(DISTINCT gene_name)
	FROM Entries e, Gene_names g1, entry_2_gene_name g2
	WHERE e.accession = g2.accession
	AND g2.gene_name_id = g1.gene_name_id
	GROUP BY e.accession
	HAVING COUNT(DISTINCT gene_name) > 2;


/*Requete 5*/

SELECT e.accession, p.prot_name, p.name_kind
    FROM Protein_names p, Entries e, Prot_name_2_prot p2, Proteins p1
    WHERE  e.accession = p1.accession
    AND  p1.accession = p2.accession
    AND  p2.prot_name_id = p.prot_name_id
    AND  p.prot_name LIKE '%channel%';


/*Requete 6*/

SELECT e.accession, p.prot_name
    FROM Protein_names p, Entries e, Prot_name_2_prot p2, Proteins p1, Keywords k1, Keywords k2, Entries_2_keywords k3,Entries_2_keywords k4
    WHERE  e.accession = p1.accession
    AND  p1.accession = p2.accession
    AND  p2.prot_name_id = p.prot_name_id
    AND e.accession = k3.accession
    AND e.accession = k4.accession
    AND k3.kw_id = k1.kw_id
    AND k4.kw_id = k2.kw_id
    AND p.name_kind = 'recommendedName'
    AND  k1.kw_label LIKE '%Long QT syndrome%'
    AND  k2.kw_label LIKE '%Short QT syndrome%';


/*Requete 7*/

SELECT distinct db.db_ref 
    FROM dbref db, entries e1, entries e2, Entries_2_keywords e2k1,Entries_2_keywords e2k2, Keywords k
    WHERE db.accession = e1.accession
    AND e1.accession = e2k1.accession
    AND e2.accession = e2k2.accession
    AND e1.accession != e2.accession
    AND e2k1.kw_id = k.kw_id
    AND e2k2.kw_id = k.kw_id
    AND k.kw_label LIKE '%Long QT syndrome%';
