<?php
// LIT UN FICHIER XML ET RETOURNE SON CONTENU DANS UN TABLEAU
// Création : Bernard Martin-Rabaud
// E-mail : info@ediweb.org
// Le 27/02/2004

// ces définitions vous permettent de modifier le nom de ces clefs
define("_XML_BALISE", "balise_xml");
define("_XML_VALEUR", "valeur_xml");
define("_XML_ATTRS", "attributs_xml");
define("_XML_TEXTE", "texte_xml");

function xmlEnTableau($fichier, $options=null) {
    // fonction principale appelée par le script utilisateur
    $texte = xmlEnTexte($fichier);
    if ($texte) {
        list($xml_struct, $xml_index) = lireXML($texte);
        if (controleXmlOK($xml_struct, $xml_index))
            return xmlStructEnTableau($xml_struct, $options);
        else return null;
    }
    else return null;
}
    
function xmlEnTexte($fichier_xml) {
    // supprime les espaces autour des tags
    // et transforme le fichier en un texte sans retour à la ligne
    $lignes = file($fichier_xml);
    $texte = "";
    foreach ($lignes as $ligne) $texte .= trim($ligne);
    return $texte;
}

function lireXML($texte) {
    // lit le code XML et le convertit en 2 tableaux $valeurs et $index
    $p = xml_parser_create();
    xml_parser_set_option($p, XML_OPTION_CASE_FOLDING, false);
    xml_parse_into_struct($p, $texte, $valeurs, $index);
    xml_parser_free($p);
    return array($valeurs, $index);
}

function controleXmlOK($valeurs, $index) {
    // contrôle la validité du fichier XML
    foreach ($index as $balises) {
        $ouvert = 0;
        foreach ($balises as $balise) {
            $type = $valeurs[$balise]["type"];
            if ($type =="open") $ouvert++;
            elseif ($type == "close") $ouvert--;
        }
        if ($ouvert == 0) return true;
    }
    return false;
}

function xmlStructEnTableau($xml_struct, $options=null) {
    // boucle sur les éléments de $xml_struct (1er tableau généré par la fonction xml_parse_into_struct())
    // pour transformer ce tableau en un tableau utilisable pour PHP
    $pile = new PileXml();
    if ($options) list($balises, $niveaux_assoc) = lireOptions($options); 
    else {
        $balises = null;
        $niveaux_assoc = null;
    }
    $tableau = array();
    $nom_tab = "\$tableau";
    for ($i=0;$i<sizeof($xml_struct);$i++) {
        if ($i == 0) $pile->empiler($nom_tab, $xml_struct[0]["tag"]);
        elseif ($pile->estVide()) break;
        else {
            $xml_elt = $xml_struct[$i];
            switch ($xml_elt["type"]) {
                case "open" :
                    $nom_tab = $pile->sommetValeur();
                    if ($niveaux_assoc && in_array($xml_elt["level"]-1, $niveaux_assoc)) 
                        $nom_tab .= "[\"" . $xml_elt["tag"] . "\"]";
                    else $nom_tab .= "[" . $pile->incrementerFils() . "]";
                    if (isset($xml_elt["attributes"])) {
                        eval($nom_tab . "[\"" . _XML_ATTRS . "\"]=" . "\$xml_elt[\"attributes\"]" . ";");
                        if ($balises) eval($nom_tab . "[\"" . _XML_BALISE . "\"]=\"" . $xml_elt["tag"] . "\";");
                        $nom_tab .= "[\"" . _XML_VALEUR . "\"]";
                    }
                    elseif ($balises) {
                        eval($nom_tab . "[\"" . _XML_BALISE . "\"]=\"" . $xml_elt["tag"] . "\";");
                        $nom_tab .= "[\"" . _XML_VALEUR . "\"]";
                    }
                    eval($nom_tab . "=array();");
                    $pile->empiler($nom_tab, $xml_elt["tag"]);
                    if (isset($xml_elt["value"])) {
                        $ligne = array(0 => array("tag" => _XML_TEXTE, "type" => "cdata", "level" => $xml_elt["level"]+1, "value" => $xml_elt["value"]));
                        array_splice($xml_struct, $i+1, 0, $ligne);
                    }
                    break;
                case "complete" :
                case "cdata" :  
                    $nom_tab = $pile->sommetValeur();
                    $xml_tag = isset($xml_elt["tag"]) ? $xml_elt["tag"] : _XML_TEXTE;
                    if ($niveaux_assoc && in_array($xml_elt["level"]-1, $niveaux_assoc)) 
                        $fils = $nom_tab . "[\"$xml_tag\"]";
                    else $fils = $nom_tab . "[" . $pile->incrementerFils() . "]";
                    if (isset($xml_elt["attributes"])) {
                        eval($fils . "[\"" . _XML_ATTRS . "\"]=" . "\$xml_elt[\"attributes\"]" . ";");
                        if ($balises) eval($fils . "[\"" . _XML_BALISE . "\"]=\"$xml_tag\";");
                        $fils .= "[\"" . _XML_VALEUR . "\"]";
                    }
                    elseif ($balises) {
                        eval($fils . "[\"" . _XML_BALISE . "\"]=\"$xml_tag\";");
                        $fils .= "[\"" . _XML_VALEUR . "\"]";
                    }
                    if (isset($xml_elt["value"])){
	                    if (is_numeric($xml_elt["value"])) eval($fils . "=" . $xml_elt["value"] . ";");
	                    else eval($fils . "=\"" . htmlspecialchars($xml_elt["value"]) . "\";");
	                }else{
		                eval($fils . "=\"\";");
	                }
                    break;
                case "close" :
                    if ($pile->sommetComparerBalise($xml_elt["tag"])) $nom_tab = $pile->depiler();
                    else break;
            }
        }
    }
    if ($pile->estVide() && ($i == sizeof($xml_struct))) return $tableau;
    else return null;
}

function lireOptions($options) {
    // détermine les options pour la fonction xmlStructEnTableau()
    // 2 types d'option :
    // "balises" stocke la balise XML dans un champ "balise" et sa valeur (sccalaire ou tableau) dans un champ "valeur"
    // "assoc=xxx" génère des tableaux associatifs pour les niveaux indiqués 
    if ($options == "balises") return array(true, null);
    elseif (strpos($options, "assoc") !== false) {
            $pos = strpos($options, "=");
            $niveaux = explode("+", substr($options, $pos+1));
            if ($niveaux[sizeof($niveaux)-1] == "") {
                $dernier = $niveaux[sizeof($niveaux)-2];
                $niveaux[sizeof($niveaux)-1] = $dernier+1;
                for ($i=$dernier+2;$i<=10;$i++) $niveaux[] = $i;
            }
            return array(null, $niveaux);
        }
    else return array(null, null);
}

// classe PileXml servant à la fonction xmlStructEnTableau

class PileXml {
    var $elements;
    
    function PileXml() {
        $this->elements = array();
    }
    
    // méthodes publiques

    function empiler($valeur, $balise) {
        $obj = new ElementPile($valeur, $balise);
        array_push($this->elements, $obj);
    }

    function depiler() {
        if ($this->estVide()) return null;
        else {
            $obj = array_pop($this->elements);
            return $obj;
        }
    }
    
    function sommetValeur() {
        return $this->elements[$this->sommet()]->retournerValeur();
    }
    
    function incrementerFils() {
        if ($this->estVide()) return null;
        else return $this->elements[$this->sommet()]->incrementerFils();
    }

    function sommetComparerBalise($nom_tag) {
        return $this->elements[$this->sommet()]->comparerBalise($nom_tag);
    }

    function estVide() {
        if (sizeof($this->elements)) return false;
        else return true;
    }

    // méthode privée
    
    function sommet() {
        return sizeof($this->elements) - 1;
    }
    
}

// classe des éléments de la pile

class ElementPile {
    var $valeur;
    var $balise;
    var $nb_fils;

    function ElementPile($valeur, $balise) {
        $this->valeur = $valeur;
        $this->balise = $balise;
        $this->nb_fils = 0;
    }

    // méthodes publiques

    function incrementerFils() {
        $fils = $this->nb_fils++;
        return $fils;
    }
    
    function retournerValeur() {
        return $this->valeur;
    }
    
    function comparerBalise($balise) {
        return ($this->balise == $balise);
    }

}

    
?>