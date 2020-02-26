<?php

////////////////////////////////////////////////////////////////////////
////////// Variables ///////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////

$durl = "https://data.ng-london.org.uk/";
$rapi = "https://scientific.ng-london.org.uk/api/api-pids-v.1.0.3.php";
$srv = "https://media.ng-london.org.uk";

$public_folder = "https://research.ng-london.org.uk/ng/";
$tpath = $public_folder."tools";
$im_path = $public_folder."graphics/";
$css_path = $public_folder."css";
$js_path = $public_folder."js";

$d3Path = "./d3-pmm/";

$default_scripts = array(
	"js-scripts" => array (
		"jquery" => $tpath."/jquery-3.2.1/jquery-3.2.1.min.js",
		"tether" => $tpath."/tether-1.4.0/js/tether.js",
		"bootstrap" => $tpath."/bootstrap-4.3.1/js/bootstrap.js",
		"ie10" => $tpath."/bootstrap-4.3.1/js/ie10-viewport-bug-workaround.js"),
	"css-scripts" => array(
		"bootstrap" => $tpath."/bootstrap-4.3.1/css/bootstrap.min.css"));
		
$data = file("/home/joe/Dropbox/D8.5_triples.csv");
$raw = getRaw($data);

$groups = array(
	"1" => array(
		"title" => "Objects, Production Events, Creators and Further Events",
		"which" => array("object", "production", "artist", "event"),
		"comment" => "<p>Within this presentation, in the most generic sense an <b>object</b> is any physical or conceptual thing that
needs to be discussed, examined or described. This can include complete cultural heritage objects
such as paintings, sculptures, books & manuscripts, shards of pot, or even whole buildings. A specific
<b>object</b> can also be a fragment, section or part of a complete cultural heritage object, such as a
painting stretcher, scraps of paper, pieces of broken sculpture, and flakes of glaze/paint, or even
analytical samples that have been deliberately removed. Actual museum objects, like paintings, are
composed of several different sub-objects, such as paint layers, canvas, nail, etc. All of these
different sub-objects can be described individually, if required.</p>
								<p>Within this presentation, in the most generic sense a <b>creator/actor</b> is any person, groups or
organisation involved with or responsible for the activities in a given event. This can include
artists, authors and even conservation scientists who might take a sample from an object or carry
out analytical work.</p>
<p><b>Further events</b> in an object’s life can cover a wide range of activities, including the commissioning
of the work, its movement, any alterations or conservation treatments, additional changes of
ownership, etc. Describing, in detail, the full range of these <b>further events</b> goes beyond the scope
of this presentation, but the generic relationships common to most of them have been described.</p>
<p>Providing a full list of all the possible types of objects and creators that may be considered within
conservation documentation goes well beyond the scope of this presentation. Where appropriate
reference to and use of existing vocabularies xviii will be used within this work-package to define or
identify these types.</p>"),
	"2" => array(
		"title" => "Taking Samples",
		"which" => array("sampling"),
		"comment" => "<p>In the process of taking a sample from a heritage object a new object, the sample, is created. The
main documentation issues to consider during a sampling procedure are to capture details and the
location of the sample site and to record the reason and description of the actual part removal
event, additional textual statements may also be needed and can be added as required.</p>"),
	"3" => array(
		"title" => "Simple Image based Examinations",
		"which" => array("imageexam"),
		"comment" => "
			Images are routinely taken to document cultural heritage objects and samples. These images can be
			captured using a wide range of imaging devices and procedures, from simple visible images to X-
			radiographs. This example is intended to describe the generic relationships, rather than any more
			specific details, methods or procedures of each process. Also, the schematic listed here is intended 
			to cover the creation of an analogue film and it relationship to the image it
			carries, the additional digitisation event has not been specifically modelled, but it would closely
			follow the relationships included in the description of other events listed <a href=\"?group=1\">here</a>. 
			When more detailed interpretations are required from individual pixel intensities or related spectral 
			information, images need to be considered more as array of data points, with each pixel being described 
			as an individual analytical measurement, which is considered further in the models listed <a href=\"?group=5\">here</a>."),
	"4" => array(
		"title" => "Defining Areas of Interest and Image Annotation",
		"which" => array("areaofinterest"),
		"comment" => "A large part of conservation documentation is related to describing or examining specific parts of
an object, <b>Areas of Interest</b>. These <b>Areas</b> are commonly described as free text but is often
helpful to mark them down by directly annotating appropriate images. Image annotation involves
the identification of each specific <b>area of interest</b> on an image and capturing its specific location,
size, and shape. The annotation can then be linked to a simple description or more complex further
documentation, as required, in some form of presentation. This example shows the common
relationships shared between defining “Areas of Interest” and annotating images."),
	"5" => array(
		"title" => "Describing Measurements and Analytical Examinations",
		"which" => array("analysis", "number", "data", "versions"),
		"comment" => "Conservation or Heritage science makes use of a wide range of analytical processes to examine,
measure, and identify objects and the materials that they are composed of. The data gathered can
range from a simple number to a complex multi-dimensional data array or images and spectra. In
this section the generic relationships and events involved in all these processes have been broken
down into stages, represented by a few specific examples. For more complex forms of analysis
several of these stages would need to be joined together and, in some cases, this would need to be
done several times. In the schematic diagrams shown in this section the term <b>Dimension</b> has been
used to cover all types of single or groups of measured values, from simple widths and heights to
more complex spectra and 3D data cubes. In addition, to allow the definition of further relationships
the CIDOC CRM term <b>Information Object</b> has also been used to represent <b>data sets</b> as a whole."),
	"6" => array(
		"title" => "Full knowledge graph",
		"which" => array("all"),
		"comment" => "This example includes a combination of all of the other examples models provided here, 
		showing how they connect together to form a larger knowledge network based on shared objects, people and events.
		This model is quite large and can be a bit harder to interpret.")
	);

////////////////////////////////////////////////////////////////////////
////////// Functions ///////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////


function getRaw($data)
	{	
	$model = array("all", "The full presentation of all of the data presented");
	$output = array();
	$output[$model[0]]["model"] = $model[0];
	$output[$model[0]]["comment"] = $model[1];	
	$output[$model[0]]["count"] = 0;	
	
	$no = 0;
	$bn = 0;
	$tn = 0;
	$ono = 0;
	$bnew = false;
	$bba = array();
	$bbano = 1;

	foreach ($data as $k => $line) 
		{	
		$trip = explode ("\t", $line);
		$trip = array_map('trim', $trip);
		// Increment triple number
		$tn++;
	
		if(preg_match("/^[\/][\/][ ]Model[:][\s]*([a-zA-Z0-9 ]+)[\s]*[\/][\/](.+)$/", $line, $m))
			{$model = array(trim($m[1]), trim($m[2]));
			 $output[$model[0]]["model"] = $model[0];
			 $output[$model[0]]["comment"] = $model[1];
			 $output[$model[0]]["count"] = 0;}
		
		if (isset($trip[2])) { // Ignore comments and empty lines
		
			// All Blank Nodes need to be numbered to be unique
			if ($trip[0] == "_Blank Node" and $trip[1] == "crm:P2.has type" and !$bnew)
				{$bn++;
				 $bnew=true;}
			
			// Ensure subsequent Blank Nodes are seen as new. 
			if ($trip[1] == "crm:P2.has type" AND $trip[0] != "_Blank Node")
				{$bnew=false;}
								
			if ($trip[0] == "_Blank Node")
				{$trip[0] = "_Blank Node-N".$bn;}
			else if (preg_match("/^_Blank Node[-]([0-9]+)$/", $trip[0], $m))
				{$trip[0] = "_Blank Node-N".($bn-$m[1]);}
				
			// Current process is assuming that the subject and the object can not both be Blank Nodes
			if ($trip[2] == "_Blank Node")
				{$trip[2] = "_Blank Node-N".$bn;
				 $bnew=false;}
			else if (preg_match("/^_Blank Node[-]([0-9]+)$/", $trip[2], $m))
				{$trip[2] = "_Blank Node-N".($bn-$m[1]);}
										
			$trip[1] = $trip[1]."-N".$tn;
			
			$output["all"]["triples"][] = $trip;
			$output["all"]["count"]++;
			$output[$model[0]]["triples"][] = $trip;
			$output[$model[0]]["count"]++;
		}
	else //Empty lines will force a new Blank node to be considered
		{$bnew=false;}
			
	if ($trip[0] == "// Stop")
		{break;}
	}	

	// Move "all" to the end of the list
	$output["all"] = array_shift($output);
	return ($output);
	}
	
function getModels($raw, $g=false)
	{
	global $groups;
	
	$output = array();
	
	foreach ($raw as $k => $a) 
		{
		if($g) {
			if (in_array($a["model"], $groups[$g]["which"]))
				{$output[] = array($a["model"],$a["comment"],$a["count"]);}}
		else {$output[] = array($a["model"],$a["comment"],$a["count"]);}
		}
		
	return ($output);
	}


function D3_formatData($selected)
	{
	$output = array();

	foreach ($selected["triples"] as $k => $trip) 
		{
		$dtrip = $trip;
		
		//Hide the unique numbers on the nodes from the display
		foreach ($dtrip as $j => $v)
			{if(preg_match("/^(.+)-N[0-9]+$/", $v, $m))
				{$dtrip[$j] = trim($m[1]);}}
		
		if (count_chars($dtrip[2]) > 60)
		 {$dtrip[2] = wordwrap($dtrip[2], 60, "\n", true);}
							
		if (!isset($output[$trip[0]]))
			{$output[$trip[0]] = D3_processNode($trip[0], $dtrip[0]);}
		if (!isset($output[$trip[1]]))
			{$output[$trip[1]] = D3_processNode($trip[1], $dtrip[1], 1);}
		if (!isset($output[$trip[2]]))
			{$output[$trip[2]] = D3_processNode($trip[2], $dtrip[2]);}

		$output[$trip[1]]["depends"][] = $trip[0];
		$output[$trip[2]]["depends"][] = $trip[1];
		}	
		
	return($output);
	}	

function D3_processNode ($v, $dv, $prop=false)
	{	
	$diagclasses = array(
		"crm:E22.Man-Made Object" => "object",
		"crm:E31.Document" => "object",
		"ng:Further Events" => "ePID",
		"ngo:002-0432-0000" => "ePID"
		);
	
	$diagCmatches = array(
		"aat[:].+" => "type",
		"tgn[:].+" => "type",
		"ulan[:].+" => "type",
		"wd[:].+" => "type",
		"ng[:].+" => "oPID",
		"ngo[:].+" => "oPID",
		"_Blank.+" => "oPID",
		"http.+" => "url",
		"crm[:]E5[.].+" => "event",
		"crm[:]E12[.].+" => "event",
		"crm[:]E.+" => "object",
		"[\"].+[\"]" => "note"
		);
	
	if(preg_match("/^([a-z]+)[:][^\/].+$/", $v, $m))
		{$g = $m[1];}
	else if(preg_match("/^http[s]{0,1}[:].+$/", $v, $m))
		{$g = "url";}
	else if(preg_match("/^_Blank.+$/", $v, $m))
		{$g = "bn";}
	else if(preg_match("/^Note.+$/", $v, $m))
		{$g = "note";}
	else
		{$g = "lit";}
		
	if ($prop) {
		if ($g == "note")
			{$cls = "note";}
		else
			{$cls = "property";}}
	else {
		if(isset($diagclasses[$v])) {$cls = $diagclasses[$v];}
		else {
			$cls = "literal";
			foreach ($diagCmatches as $k => $cur)
				{if(preg_match("/^".$k."$/", $v, $m))
					{$cls = $cur;
					break;}
				}}}
	if (!$prop) {$g = false;}
		
	$output = array(
		"type" => $cls,
		"name" => $v,
		"display" => $dv,
		"group" => $g,
		"depends" => array()
		);
	
	return ($output);		
	}
	
function Mermaid_formatData ($selected)
	{
	ob_start();
	echo <<<END

graph LR

classDef crm stroke:#333333,fill:#DCDCDC,color:#333333,rx:5px,ry:5px;
classDef thing stroke:#2C5D98,fill:#D0E5FF,color:#2C5D98,rx:5px,ry:5px;
classDef event stroke:#6B9624,fill:#D0DDBB,color:#6B9624,rx:5px,ry:5px;
classDef oPID stroke:#2C5D98,fill:#2C5D98,color:white,rx:5px,ry:5px;
classDef ePID stroke:#6B9624,fill:#6B9624,color:white,rx:5px,ry:5px;
classDef aPID stroke:black,fill:#FFFF99,rx:20px,ry:20px;
classDef type stroke:red,fill:#B51511,color:white,rx:5px,ry:5px;
classDef name stroke:orange,fill:#FEF3BA,rx:20px,ry20px;
classDef literal stroke:black,fill:#FFB975,rx:2px,ry:2px,max-width:100px;
classDef classstyle stroke:black,fill:white;
classDef url stroke:#2C5D98,fill:white,color:#2C5D98,rx:5px,ry:5px;
classDef note stroke:#2C5D98,fill:#D8FDFF,color:#2C5D98,rx:5px,ry:5px;

END;
	$defTop = ob_get_contents();
	ob_end_clean(); // Don't send output to client	

	$defs = "";
	//$defs .= "<h1>".$selected["comment"]."</h1>";
	$defs .= "<div class=\"mermaid\">".$defTop;
	
	$things = array();
	$no = 0;
	$crm = 0;
		
	//
	foreach ($selected["triples"] as $k => $t) 
		{if(preg_match("/^(crm:E.+)$/", $t[2], $m))
			{$selected["triples"][$k][2] = $t[2]."-".$crm;
			 $crm++;}
		 if(preg_match("/^(.+)-N[0-9]+$/", $t[1], $m))
			{$selected["triples"][$k][1] = trim($m[1]);}}		//	*/
		
	foreach ($selected["triples"] as $k => $t) 
		{			
		if (count_chars($t[2]) > 60)
			{$use = wordwrap($t[2], 60, "<br/>", true);}
		else
			{$use = $t[2];}
			
		if(preg_match("/^(crm[:].+)[-][0-9]+$/", $use, $m))
			{$use = $m[1];}
			
		if(isset($t[3]))
			{$fcs = explode ("@@", $t[3]);}
		else
			{$fcs = array(false, false);}
								
		if (!isset($things[$t[0]]))
			{$things[$t[0]] = "O".$no;
			 $defs .= Mermaid_defThing($t[0], $no, $fcs[0]);
			 $no++;}
			 
		if (!isset($things[$t[2]]))
			{$things[$t[2]] = "O".$no;
			 $defs .= Mermaid_defThing($t[2], $no, $fcs[1]);
			 $no++;}		
					 					
		$defs .= $things[$t[0]]." -- ".$t[1]. " -->".$things[$t[2]]."[\"".$use."\"]\n";		
		}
	$defs .= ";</div>";
	
	return ($defs);
	}	

function Mermaid_defThing ($var, $no, $fc=false)
	{	
	$diagCmatches = array(
		"aat[:].+" => "type",
		"wd[:].+" => "type",
		"ulan[:].+" => "type",
		"tgn[:].+" => "type",
		"ng[:].+" => "oPID",
		"ngo[:].+" => "oPID",
		"ngi[:].+" => "oPID",
		"_Blank.+" => "thing",
		"http.+" => "url",
		"crm[:]E.+" => "crm",
		"[\"].+[\"]" => "note"
		);
		 
	if ($fc) {$cls = $fc;}
	else {
		$cls = "literal";
		foreach ($diagCmatches as $k => $cur)
			{
			if(preg_match("/^".$k."$/", $var, $m))
				{$cls = $cur;
				 break;}}}	 
	$code  = "O".$no;
	$str = "\n$code(\"$var\")\nclass $code $cls;\n";
		 
	if(preg_match("/^http.+$/", $var, $m))
		{$str .= "click ".$code." \"$var\" \"Tooltip\"\n";}		
	else if(preg_match("/^ngo[:]([0-9A-Z]{3}[-].+)$/", $var, $m))
		{$str .= "click ".$code." \"http://data.ng-london.org.uk/resource/$m[1]\" \"Tooltip\"\n";}
	else if(preg_match("/^ng[:]([0-9A-Z]{4}[-].+)$/", $var, $m))
		{$str .= "click ".$code." \"http://data.ng-london.org.uk/$m[1]\" \"Tooltip\"\n";}
	else if(preg_match("/^aat[:](.+)$/", $var, $m))
		{$str .= "click ".$code." \"http://vocab.getty.edu/aat/$m[1]\" \"Tooltip\"\n";}
	else if(preg_match("/^tgn[:](.+)$/", $var, $m))
		{$str .= "click ".$code." \"http://vocab.getty.edu/tgn/$m[1]\" \"Tooltip\"\n";}
	else if(preg_match("/^ulan[:](.+)$/", $var, $m))
		{$str .= "click ".$code." \"http://vocab.getty.edu/ulan/$m[1]\" \"Tooltip\"\n";}
	else if(preg_match("/^wd[:](.+)$/", $var, $m))
		{$str .= "click ".$code." \"https://www.wikidata.org/wiki/$m[1]\" \"Tooltip\"\n";}
	
	return ($str);
	}


function Mermaid_displayModel($defs)
	{
	$title = "";
	
	ob_start();
echo <<<END

</END>

END;
	$styles = ob_get_contents();
	ob_end_clean(); // Don't send output to client	

	ob_start();
echo <<<END
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html> <!--<![endif]-->
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=Edge">
        <meta charset="utf-8">
        <title>$title</title>
        <link rel="stylesheet" href="https://mermaidjs.github.io/mermaid-live-editor/src.96cd87af.css">
    <style>
g a 
			{color:inherit;}
			
		$styles
  <style>
          <div class="center-div">
        <div id="split-container">
            <a class="btn btn-default nav-button" id="nav-home" href="./">
                Home
            </a>            
            <a class="btn btn-default nav-button" style="left:80px;" id="nav-models" href="./?page=models">
                Models
            </a>
            <div id="graph-container">
                		$defs
            </div>
        </div>
        </div>


  <script src="./node_modules/mermaid/dist/mermaid.min.js"></script>
  <script>mermaid.initialize({startOnLoad:true, flowchart: { 
    curve: 'basis'
  }});</script>  
END;
	$html = ob_get_contents();
	ob_end_clean(); // Don't send output to client	

	echo $html;
	exit;
	}
	
function OLD_Mermaid_displayModel($defs, $title="")
	{
	global $d3Path;
	ob_start();
echo <<<END

body
{
  #background-color: #fcfcfc;
}


.list
{
	left:80px;
}

g a 
			{color:inherit;}

.nav-button {
    position: absolute;
    top: 8px;
    left: 8px;
}

.btn {
    display: inline-block;
    padding: 6px 12px;
    margin-bottom: 0;
    font-size: 14px;
    font-weight: normal;
    line-height: 1.428571429;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    cursor: pointer;
    background-image: none;
    border: 1px solid transparent;
    border-radius: 4px;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    -o-user-select: none;
    user-select: none;
}

.btn-default {
    color: #333333;
    background-color: #ffffff;
    border-color: #cccccc;
}

.btn-default:hover, .btn-default:focus, .btn-default:active, .btn-default.active, .open .dropdown-toggle.btn-default {
    color: #333333;
    background-color: #ebebeb;
    border-color: #adadad;
}

END;
	$styles = ob_get_contents();
	ob_end_clean(); // Don't send output to client	

	ob_start();
echo <<<END
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html> <!--<![endif]-->
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=Edge">
        <meta charset="utf-8">
        <title>$title</title>
        
        <link rel="stylesheet" href="https://mermaidjs.github.io/mermaid-live-editor/src.96cd87af.css">
    <style>			
		$styles
		</style>
  </head>
    <body>
    <div class="center-div">
        <div id="split-container">
            <a class="btn btn-default nav-button" id="nav-home" href="./">
                Home
            </a>            
            <a class="btn btn-default nav-button" style="left:80px;" id="nav-models" href="./?page=models">
                Models
            </a>
            <div id="graph-container">
                <div id="graph">	$defs</div>
            </div>
        </div>
        </div>
        
	
  <script src="./node_modules/mermaid/dist/mermaid.min.js"></script>
  <script>mermaid.initialize({startOnLoad:true, flowchart: { 
    curve: 'basis'
  }});</script>  
    </body>
</html>
END;
	$html = ob_get_contents();
	ob_end_clean(); // Don't send output to client	

	return ($html);
	}
	
	
function D3_displayModel ($title, $dataset_qs, $json)
	{
	global $d3Path;
	
	ob_start();
	echo <<<END
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html> <!--<![endif]-->
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=Edge">
        <meta charset="utf-8">
        <title>$title</title>
        <link rel="stylesheet" href="${d3Path}bootstrap.css">
        <link rel="stylesheet" href="${d3Path}style.css">
        <link rel="stylesheet" href="${d3Path}svg.css">
    <style>
			body
{
  #background-color: #fcfcfc;
}
.center-div
{
  position: absolute;
  margin: auto;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  width: 100%;
  height: 100%;
  #background-color: #ccc;
  border-radius: 3px;
}

.list
{
	left:80px;
}
    </style>
    </head>
    <body>
        <!--[if lt IE 9]>
        <div class="unsupported-browser">
            This website does not fully support your browser.  Please get a
            better browser (Firefox or <a href="/chrome/">Chrome</a>, or if you
            must use Internet Explorer, make it version 9 or greater).
        </div>
        <![endif]-->
        <div class="center-div">
        <div id="split-container">
            <a class="btn btn-default nav-button" id="nav-home" href="./">
                Home
            </a>            
            <a class="btn btn-default nav-button" style="left:80px;" id="nav-models" href="./?page=models">
                Models
            </a>
            <a class="btn btn-default nav-button"  style="left:160px;"  id="nav-list" href="${dataset_qs}&d3list=1">
                View list
            </a>
            <div id="graph-container">
                <div id="graph"></div>
            </div>
            <div id="docs-container">
                <a id="docs-close" href="#">&times;</a>
                <div id="docs" class="docs"></div>
            </div>
        </div>
        </div>
        <script src="${d3Path}jquery/jquery-3.4.1.min.js"></script>
        <script src="https://d3js.org/d3.v3.js"></script>
        <script src="${d3Path}colorbrewer.js"></script>
        <script src="${d3Path}lib/geometry.js"></script>
        <script>
            var config = $json;
        </script>
        <script src="${d3Path}script_v2.0.js"></script>
    </body>
</html>

END;
	$html = ob_get_contents();
	ob_end_clean(); // Don't send output to client	
	
	 
	echo $html;	
	exit;	
	}	
	
	
function D3_displayList ($title, $dataset_qs, $data)
	{
	global $d3Path;

	$dstr = "";
	foreach ($data as $obj) {
    $id = get_id_string($obj['name']);
    $dstr .= "<div class=\"docs\" id=\"$id\">$obj[docs]</div>\n";
		}

	ob_start();
	echo <<<END
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html> <!--<![endif]-->
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=Edge">
        <meta charset="utf-8">
        <title>$title</title>
        <link rel="stylesheet" href="${d3Path}bootstrap.css">
        <link rel="stylesheet" href="${d3Path}style.css">
        <link rel="stylesheet" href="${d3Path}print.css">
    </head>
    <body>
				<a class="btn btn-default nav-button" id="nav-home" href="./">
                Home
            </a>            
            <a class="btn btn-default nav-button" style="left:80px;" id="nav-models" href="./?page=models">
                Models
            </a>
            <a class="btn btn-default nav-button"  style="left:160px;"  id="nav-graph" href="$dataset_qs">
                View Graph
            </a>
        <div id="docs-list">
					$dstr
        </div>
    </body>
</html>

END;
	$html = ob_get_contents();
	ob_end_clean(); // Don't send output to client	
	echo $html;	
	exit;	
	}	

function buildExamplePage ($page=false, $dataset=false, $group=false)
	{
	global $tpath, $paths, $durl, $im_path, $raw, $config, $data, $groups;

	$pd = array(
    "extra_js_scripts" => array(
        $tpath."/bootstrap-4.3.1/js/offcanvas.js",
        $tpath."/jquery.json-viewer/json-viewer/jquery.json-viewer.js"),
    "extra_css_scripts" => array(
        $tpath."/bootstrap-4.3.1/css/offcanvas.css",
        $tpath."/jquery.json-viewer/json-viewer/jquery.json-viewer.css"),
    "metaDescription" => "NG API System",
    "metaKeywords" => "National Gallery|Paintings|Semantics|Open Linked Data|API|CIDOC|crm",
    "metaTitle" => "NG Example CRM Modelling",
    "extra_onload" => "",
    "extra_js" => "",
    "logo_link" => "./");

	if (!isset($page)) {$page = "home";}

	$pages = array(
		"home", 
		"models",
		"data",
		"about"
		);
	$psel = array_fill(0, count($pages), false);
	
	if ($group)
		{$psel[1] = true;}
	else
		{$psel[array_search($page, $pages)] = true;}

	$navItems = array(
		array("Home", "?page=home", $psel[0], false),
		array("Models", "?page=models", $psel[1], false),
		array("Data", "?page=data", $psel[2], false),
		array("About", "?page=about", $psel[3], false),
		);
    
   $extraNav = '<form class="form-inline my-2 my-lg-0" method="post" action="'.$durl.'">
       <input class="form-control mr-sm-2" name="search" id="search" type="text" placeholder="Search">
       <input type="hidden" name="page" value="search">
       <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
       </form>';
   $extraNav = false;   
   $pd["licence"] = '<a href="https://www.nationalgallery.org.uk/terms-of-use">
    <img height="16" alt="National Gallery - Terms of Use" title="National Gallery - Terms of Use"
      src="'.$im_path.'/ng-logo-black-100x40.png"/>
</a><a href="http://rightsstatements.org/vocab/InC-EDU/1.0/">
    <img height="16" alt="In Copyright - Educational Use Permitted" title="In Copyright - Educational Use Permitted"
      src="'.$im_path.'InC-EDU.dark-white-interior-blue-type.png"/>
</a><a rel="license" href="http://creativecommons.org/licenses/by-nc-nd/4.0/"><img alt="Creative Commons Licence" style="border-width:0" src="https://i.creativecommons.org/l/by-nc-nd/4.0/88x31.png" /></a>
';

  $pd["topNavbar"] = buildBSNavBar ($navItems, $extraNav);
	$pd["offcanvas"] = array();

		
	$pd["extra_css"] = ".div-wrapper {
    display: block;
    max-height: 2000px;
    overflow-y: auto;
    -ms-overflow-style: -ms-autohiding-scrollbar;
}";
	
	if ($page == "examples")
		{		
		
		$list = "";
		$mods = getModels($raw);
					
		$rows = array();					
		$rows[] = array (
					"class" => "col-12 col-lg-12",
          "content" => "<h2>Introduction</h2>
          <p>The Json formatted data presented by this Beta API is currently under development, this page presents some examples of how the data will be formatted.</p>
          <p><b>The Json formatted data is relativly stable but still might be subject to changes and should only be used for testing purposes.</b></p>
          ");
          
    
		foreach ($mods as $nm => $a)
			{
			//$pd["extra_js"] .= "$('#json-renderer-".$pid[1]."').jsonViewer($jdata);";     
			$rows[] = array (
						"class" => "col-12 col-lg-12",
						"content" => "<h4>$a[1] ($a[2] - triples)</h4>".
							"<div class=\"card\"><div class=\"card-body\">".
							"<div class=\"div-wrapper\" style=\"max-height:400px;\">".
							"<a href=\"?dataset=$a[0]\">D3</a> -  <a href=\"?dataset=$a[0]&mermaid=1\">Mermaid</a>".
							"</div></div></div><br/>");
			}
			
		$pd["grid"] = array(
			"topjumbotron" => "<h2>API - Json Examples</h2>",			
			"rows" => array($rows));
		}	
	else if ($dataset)
		{	
		$pd["fluid"] = true;
		if	(isset($_GET["mermaid"]))
			{	
			if (isset($raw[$dataset]))
				{$selected = $raw[$dataset];}
			
			$dif = 370;
			$pd["extra_js_scripts"][] = "./node_modules/mermaid/dist/mermaid.min.js";
			//$pd["extra_css_scripts"][] = "https://mermaidjs.github.io/mermaid-live-editor/src.96cd87af.css";			
			$pd["extra_onload"] .= "mermaid.initialize({startOnLoad:true, flowchart: {curve: 'basis'}});";
			$pd["extra_css"] .= "
			.full_height {
				height: 100%;
				height: -moz-calc(100vh - ${dif}px);
				height: -webkit-calc(100vh - ${dif}px);
				height: -o-calc(100vh - ${dif}px);
				height: calc(100vh - ${dif}px);
				overflow: scroll;
				}
			g a 
			{color:inherit;}";
    
			$def = Mermaid_formatData ($selected);
			echo OLD_Mermaid_displayModel($def);
			exit;
			$rows = array();
			$rows[] = array (
						"class" => "col-12 col-lg-12 full_height",
						"content" => $def);
			
			$pd["grid"] = array(
			"topjumbotron" => "<h2>$selected[comment]</h2>",			
			"rows" => array($rows));
			}
		else if (isset($_GET["d3list"]))
			{
			require_once './d3-pmm/common.php';
			read_data();
			
			D3_displayList ($title, $dataset_qs, $data);	
			}
		else
			{
			require_once './d3-pmm/common.php';
			
			read_config();
			$json = json_encode($config);
			
			D3_displayModel ($title, $dataset_qs, $json);

			}	
		}
	else if ($page == "about")
		{		
		ob_start();
	echo <<<END
<p>Conservation and Heritage Science documentation are overarching terms used to cover the
description and record of any relevant materials, activities, processes, people, places, and events
involved in the history of a cultural heritage object. Cataloguing an object’s composition, condition
and how it has degraded or been damaged overtime, along with all the work that has been done to
retard, treat and study this degradation process. So, this work can cover the full range of historic
activities right up to the ever-increasing detail and complexity of modern scientific analytical
techniques. The amount of time and expertise needed to record everything in a meaningful and re-
usable fashion can rapidly progress past the level that is realistic for the many different specialists
working in this field. A balance must be established between the effort and time required and the
future benefit of the recorded information; key to this process is making use of appropriate tools to
maximise the efficiency of the documentation process.</p>

<p>Over the last decade large amounts of time and money have been invested in the development of
conservation-related documentation systems[@@] and digital tools[@@], to help with this documentation
process. Some of this work has taken place within specific institutions while other larger projects,
most notably the Andrew W. Mellon Foundation funded ConservationSpace[@@] and ResearchSpace[@@]
projects, have been developed through multi-institutional collaborations. However, these new tools
and systems are often developed for specific purposes and can still require a fair amount of technical
know-how or support to implement, populate and access. Also, even with all this work, many
specialists in the field are still using simple digital file folders or are stuck with older institution-
specific databases that struggle to meet their current requirements. In many cases people are also
still working with analogue filling systems.</p>

<p>Additional research is often required to provide a bridge between current working practice and new,
complete integrated digital solution. The work presented here was carried out to provide a range of worked, practical
examples of how data gathered during the study and examination of heritage objects can be described in relation 
to the CIDOC CRM[@@] ontology, to aid with future data mapping and data interoperability processes.</p>

<p>The CIDOC CRM has continued to undergo development during this project, the references included
in this document have been updated to try to reflect the latest draft version at the time of writing, which was version
6.2.7[@@]. In addition to the development of the core CRM and
number of additional compatible models[@@] have been developed to provide additional ontological
models for specific areas of research. The work presented in this deliverable could overlap with
several of these extensions, particularly the CRMsci[@@], which is being developed to help model
scientific observations. However, as this CRMsci extension is still under active development it was
decided that the work included in this deliverable would be limited to the core CRM ontology. This
was done to examine how flexible the main standard could be and to help highlight where new
simplifications or clarifications could enhance the included models.</p>

<p>The work presented here is also intended to develop and evolve over time to reflex input from the wider semantic community, 
with new versions of the model being included to correct for any identified issues or errors and improve the scope of the examples provided.</p>

<p><b><i>The initial work of creating the semantic models was carried out as part of the IPERION-CH H2020 project[@@], 
but the reformatting of the models and the creation of this web resource has been carried out as part of 
the SSHOC H2020 project[@@].</i></b></p>
END;
		$home = ob_get_contents();
		ob_end_clean(); // Don't send output to client	
			
		$footnotes = array(
			"http://www.rembrandtdatabase.org/, http://www.lucascranach.org/, http://boschproject.org/, http://cima.ng-london.org.uk/documentation (Raphael Research Resource).",
			"Such as Image viewers (e.g. http://iiif.io/), ontologies (e.g. http://www.cidoc-crm.org/) and open linked vocabularies (e.g. http://www.getty.edu/research/tools/vocabularies/lod/).",
			"http://www.conservationspace.org/, https://sites.google.com/site/conservationspace/",
			"http://www.researchspace.org/",
			"<b>The CIDOC Conceptual Reference Model (CRM) is a theoretical and practical tool for information integration in the field of cultural heritage.</b> - http://www.cidoc-crm.org/.",
			"http://www.cidoc-crm.org/Version/version-6.2.7",
			"http://www.cidoc-crm.org/collaborations",
			"http://www.cidoc-crm.org/crmsci/",
			"<b>IPERION CH Integrated Platform for the European Research Infrastructure ON Cultural Heritage</b> - http://www.iperionch.eu/. This intial work was published as part of one of the project deliverables - <b>IPERION CH Delivaerable D8.5 - Completed example of prototype designs for integration of various types of documentation and analytical data generated for a single object</b> - https://ec.europa.eu/research/participants/documents/downloadPublic?documentIds=080166e5c8d1c525&appId=PPGMS",
			"<b>SSHOC - Social Sciences & Humanities Open Cloud</b> - https://sshopencloud.eu/",
			);
			
		$home = parseFootNotes ($home, $footnotes, 1);
		$eur = eulogorow ();
		
		$pd["grid"] = array(
			"topjumbotron" => "<h2>National Gallery - CIDOC CRM Modelling Examples</h2>",
			"bottomjumbotron" => "",//<h1>Goodbye, world!</h1> <p>We hoped you liked this great page.</p>",
			"rows" => array(
				array(
					array (
						"class" => "col-12 col-lg-12",
						"content" => $home),
					$eur
					)));
		}
	else if ($page == "models")
		{	
		$comment = "<b>This presentation does not cover all the possible relationships relating to conservation
documentation but concentrates on examples of the main properties and values required to
document and organise technical and analytical examinations of cultural heritage objects.</b>
Therefore, the semantic description of objects, their creators and common events will be minimal
and will not explore the additional concepts more specifically related to detailed conservation
treatments, art history, provenance or exhibitions. However, in addition to the demonstrating the
semantic relationships between data types the examples included in this document have been
extended to demonstrate how real literal values, numbers, dates, names and text can be connected
to the semantic model. The data included in this deliverable has been taken from the online <a href=\"https://cima.ng-london.org.uk/documenttion\">Raphael
Research Resource</a>.</p>
<p>In the following examples each of the sets of relationships are presented in the
form of a flow diagram, which indicates and explains the entities being linked together and the
properties connecting them. At this time each flow diagram can be rendered in two different ways: 
one using an interactive 3D display (<a href=\"https://github.com/nylen/d3-process-map\">D3 Process Map</a>)
and one using a simpler 2D presentation (<a href=\"https://github.com/mermaid-js/mermaid\">Mermaid</a>).";
		
		$rows = array( 0 => 
			array (
				"class" => "col-12 col-lg-12",
				"content" => $comment));
				
		$crows = "";
		foreach ($groups as $g => $a)
			{
			ob_start();			
			echo <<<END
				<tr>
					<td style="text-align:right;">
						<a class="btn btn-outline-dark btn-block" href="?group=$g" role="button">$a[title]</a>
					</td>
				</tr>
END;
			$crows .= ob_get_contents();
			ob_end_clean(); // Don't send output to client			
			}
			
		$rows[] = array (
				"class" => "col-12 col-lg-12",	
				"content" => '<table width="100%">'.$crows.'</table></br>');
					
		$pd["grid"] = array(
			"topjumbotron" => "<h2>National Gallery - CIDOC CRM Modelling Examples</h2>",
			"bottomjumbotron" => "",//<h1>Goodbye, world!</h1> <p>We hoped you liked this great page.</p>",
			"rows" => array($rows));
		}
	
	else if ($page == "data")
		{	
		$comment = "<h2>Data preparation</h2>
<p>This site has been setup to present a series of examples of how the CIDOC CRM can be used to model actual heritage data, however it is not meant to be a static resource. The models that are presented here are expected to change overtime as a result of further research and discussion and potentially new models may be added. The goal is to create a resource that aims to present a <i>current</i> view of the consensus of how the CIDOC CRM is used to model data in practical systems.</p>

<p>In order to make the process of editing the models as easy as possible they are created dynamically from a simple list of tab separated triples as shown here. Simple changes to this root data file will result in the presented models changing, as all of the required processing and formatting, for these complex presentations, are carried out automatically each time a model is loaded.</p>

<p>In order to allow others to participate in future discussions, and help update these models, the raw files are going to be uploaded to <a href=\"https://github.com/jpadfield/cidoc-crm.examples\">GitHub</a>. This will allow details of all issues, comments and even older versions of the models to be publicly organised and presented.</p>
<center><a href=\"https://github.com/jpadfield/cidoc-crm.examples\"><img style=\"position:relative;width:150px;\" src=\"${im_path}github logo.png\"/></a></center>
";
		
		ob_start();
		echo <<<END
<pre style="overflow: hidden;border: 2px solid black;padding: 10px;">
////////////////////////////////////////////////////////////////////////
// Model: artist // Primary relationships describing an Artist
// D8.5 Figure 4
////////////////////////////////////////////////////////////////////////
ng:0F6J-0001-0000-0000	crm:P2.has type	crm:E21.Person	aPID@@crm
ng:0F6J-0001-0000-0000	crm:P2.has type	aat:300411314
aat:300411314	rdfs:label	artist painters@en
ng:0F6J-0001-0000-0000	crm:P2.has type	aat:300024987
aat:300024987	rdfs:label	architechts@en
ng:0F6J-0001-0000-0000	owl:sameAs	ulan:500023578
ulan:500023578	rdfs:label	Raphael@en
ng:0F6J-0001-0000-0000	owl:sameAs	wd:Q5597
wd:Q5597	rdfs:label	Raphael@en
ng:0F6J-0001-0000-0000	rdfs:seeAlso	https://cima.ng-london.org.uk/documentation
https://cima.ng-london.org.uk/documentation	rdfs:label	Raphael Research Resource@en
ng:0F6J-0001-0000-0000	rdfs:comment	Free Text@en
ng:0F6J-0001-0000-0000	crm:P14.performed	ngo:002-0432-0000	aPID@@ePID

_Blank Node	crm:P2.has type	crm:E41.Appellation
ng:0F6J-0001-0000-0000	crm:P131.is identified by	_Blank Node
_Blank Node	rdfs:label	Raphael@en

_Blank Node	crm:P2.has type	crm:E74.Group	aPID@@crm
ng:0F6J-0001-0000-0000	crm:P15.was influenced by	_Blank Node
_Blank Node	owl:sameAs	aat:300107304
aat:300107304	rdfs:label	Ancient Italian@en

_Blank Node	crm:P2.has type	crm:E67 Birth	event@@crm
ng:0F6J-0001-0000-0000	crm:P98.was born	_Blank Node

_Blank Node	crm:P2.has type	crm:E53 Place
_Blank Node-1	crm:P7.took place at	_Blank Node
_Blank Node	owl:sameAs	tgn:7003994
_Blank Node	owl:sameAs	wd:Q2759
tgn:7003994	rdfs:label	Urbino (inhabited place)@en
</pre>
END;
		$example_txt = ob_get_contents();
		ob_end_clean(); // Don't send output to client
							
		$pd["grid"] = array(
			"topjumbotron" => "<h2>National Gallery - CIDOC CRM Modelling Examples</h2>",
			"bottomjumbotron" => "",//<h1>Goodbye, world!</h1> <p>We hoped you liked this great page.</p>",
			"rows" => array(
				array(
					array (
						"class" => "col-6 col-lg-6",
						"content" => $comment),
					array (
						"class" => "col-6 col-lg-6",
						"content" => $example_txt),
					)));
		}		
	else if ($page == "group")
		{
		if (!isset($groups[$group]))
			{$group = 1;}
			
		$pd["grid"] = grouppage ($groups[$group]["title"], $groups[$group]["comment"], $group);
		}
	else //home
		{
		ob_start();
	echo <<<END
<p>In order to connect existing work together and to identify where the development of practical
examples of documentation software could be most informative, the relationships
between the various types of documentary and analytical data generated for generic and specific
situations need to be described. This presentation provides examples of how real objects can 
be semantically described and linked to the images and results of their analytical examination.</p>
<center><a class="btn btn-outline-primary nav-button" style="left:80px;" id="nav-models" role=button" href="./?page=models">Explore Models</a></center><br/>

<p><a href="http://www.cidoc-crm.org"><img style="position:relative;width:75px;float:left;margin-right:5px;" src="${im_path}cidoccrm_logo.png"/></a>
All of the linking properties and classes presented in the included examples are taken from the current[@@]
release and development version of the CIDOC-CRM[@@] ontology. References to external control
vocabularies or thesauri[@@] have also been included to demonstrate how links between internal documentation
systems and external resources could be modelled.</p>
END;
		$home = ob_get_contents();
		ob_end_clean(); // Don't send output to client	
			
		$footnotes = array(
			"January 2020",
			"The CIDOC Conceptual Reference Model (CRM) is a theoretical and practical tool for information integration in the
field of cultural heritage. - http://www.cidoc-crm.org . Current versions of the CIDOC CRM can be found at http://www.cidoc-
crm.org/versions-of-the-cidoc-crm.",
			"The two main external LOD vocabularies/thesauri referenced in this document are the Getty’s AAT ( http://www.getty.edu/research/tools/vocabularies/aat ) and Wikidata ( https://www.wikidata.org )"
			);
			
		$home = parseFootNotes ($home, $footnotes, 1);
	
		$pd["grid"] = array(
			"topjumbotron" => "<h2>National Gallery - CIDOC CRM Modelling Examples</h2>",
			"bottomjumbotron" => "",//<h1>Goodbye, world!</h1> <p>We hoped you liked this great page.</p>",
			"rows" => array(
				array(
					array (
						"class" => "col-12 col-lg-12",
						"content" => "<h2>Integrating documentation details and analytical data generated for an object</h2>")
					),
				array(
					array (
						"class" => "col-6 col-lg-6",
						"content" => $home),
					array (
						"class" => "col-6 col-lg-6",
						"content" => "<img style=\"position:relative;width:100%;\" src=\"${im_path}example_knowledge_graph.png\"/>"),
					)));
		}
						
	$pd["body"] = buildSimpleBSGrid ($pd["grid"]);    
	////////////////////////////////////////////////////////////////////////
	// Run function ////////////////////////////////////////////////////////    
	header('Content-type: text/html; Charset=utf-8');
	echo buildBootStrapNGPage ($pd);
	}

$fcount = 1;

function countFootNotes($matches) {
  global $fcount;
  $out = '<sup><a id="ref'.$fcount.'" href="#section'.$fcount.'">['.$fcount.']</a></sup>';
  $fcount++;
  return($out);
}

function addLinks($matches) {
  $out = "<a href='$matches[0]'>$matches[0]</a>";
  return($out);
}

function parseFootNotes ($text, $footnotes, $sno=1)
	{
	global $fcount;
	$fcounts = $sno;
	
	$text = preg_replace_callback('/\[[@][@]\]/', 'countFootNotes', $text);
	$text = $text . "<div style=\"font-size:smaller;\"><ul>";
	foreach ($footnotes as $j => $str)
		{$k = $j + 1;
		 $str = preg_replace_callback('/http[^\s]+/', 'addLinks', $str);
		 $text = $text."<li id=\"section${k}\"><a href=\"#ref${k}\">[${k}]</a> $str</li>";}
	
	$text = $text . "</ul></div>";
	
	return ($text);	
	}	
		
function grouppage ($title, $comment, $group)
	{
	global $raw;
	
	$rows = array( 0 => 
			array (
				"class" => "col-12 col-lg-12",
				"content" => $comment));
				
		$mods = getModels($raw, $group);

		$crows = "";
		foreach ($mods as $nm => $a)
			{
			ob_start();			
			echo <<<END
				<tr>
					<td><h4>$a[1] ($a[2] - triples)</h4></td>
					<td style="text-align:right;white-space: nowrap;">
						<div class="btn-group" role="group" aria-label="Basic example">
						<a class="btn btn-outline-primary" href="?dataset=$a[0]" role="button">D3 Model</a>
						<a class="btn btn-outline-success" href="?dataset=$a[0]&mermaid=1" role="button">Mermaid Model</a>
						</div
					</td>
				</tr>
END;
			$crows .= ob_get_contents();
			ob_end_clean(); // Don't send output to client			
			}	
			
		$rows[] = array (
				"class" => "col-12 col-lg-12",	
				"content" => '<table width="100%">'.$crows.'</table></br>');
					
		$grid = array(
			"topjumbotron" => "<h2>$title</h2>",
			"bottomjumbotron" => "",//<h1>Goodbye, world!</h1> <p>We hoped you liked this great page.</p>",
			"rows" => array($rows));
			
	return ($grid);
	}
	
function getRemoteJsonDetails ($uri, $format=false, $decode=false)
	{if ($format) {$uri = $uri.".".$format;}
	 $fc = file_get_contents($uri);
	 if ($decode)
		{$output = json_decode($fc, true);}
	 else
		{$output = $fc;}
	 return ($output);}
	 
	
function eulogorow ()
	{
	global $im_path;
	
	ob_start();			
	echo <<<END
		<img src="${im_path}iperion-ch-eu-tag2.png" style="height:32px;">
		<img src="${im_path}sshoc-eu-tag2.png" style="height:32px;">
END;
	$html = ob_get_contents();
	ob_end_clean(); // Don't send output to client				
			
	$row =array (
		"class" => "col-12 col-lg-12",
		"content" => $html);
		
	return($row);		
	}
//////////////////////////////////////////////////

function buildSimpleBSGrid ($bdDetails = array())
		{
		ob_start();
		
		if (isset($bdDetails["topjumbotron"]))
			{echo "<div class=\"jumbotron\">".$bdDetails["topjumbotron"].
				"</div>";}
		
		if (isset($bdDetails["rows"])) 
			{
			foreach ($bdDetails["rows"] as $k => $row)
				{
				echo "<div class=\"row\">";	
				
				foreach ($row as $j => $col)
					{if (!isset($col["class"])) {$col["class"] ="col-6 col-lg-4";}
					 if (!isset($col["content"])) {$col["content"] ="Default Text";}
					 echo "<div class=\"$col[class]\">".$col["content"]."</div><!--/span-->";}
				
				echo "</div><!--/row-->    ";
				}
			}
		
		if (isset($bdDetails["bottomjumbotron"]) and $bdDetails["bottomjumbotron"])
			{echo "<div class=\"jumbotron\">".$bdDetails["bottomjumbotron"].
				"</div>";}
		else
			{echo "<br/>";}
		
		$html = ob_get_contents();
		ob_end_clean(); // Don't send output to client		
		
		return($html);
		}
		
function buildBSNavBar ($navItems=array(), $extra="")
	{
	$navhtml = "<ul class=\"navbar-nav\">";
	
	foreach ($navItems as $k => $ia)
		{			 
		if(is_array($ia[0])) //do dropdown
			{
			$d = $ia[0];			
			$navhtml .= "<li class=\"nav-item dropdown\"><a class=\"nav-link ".
				"dropdown-toggle\" href=\"$d[1]\" id=\"dropdown{$d[4]}\" ".
				"data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\ ".
				"\"false\">{$d[0]}</a><div class=\"dropdown-menu\" aria-labelledby".
				"=\"dropdown{$d[4]}\">";
			foreach ($ia[1] as $label => $link)
				{$navhtml .= "<a class=\"dropdown-item\" href=\"$link\">$label</a>";}     
       $navhtml .= "</div></li>";
			}
		else
			{	
			if($ia[2]) 
				{$current = " <span class=\"sr-only\">(current)</span>";
				 $active = " active";}
			else 
				{$current = "";
				 $active = "";}
			 			
			if($ia[3]) 
				{$disabled .= " disabled";
				 $href = "";}
			else
				{$disabled = "";
				 $href = "href=\"$ia[1]\"";}
				 
			$navhtml .= "<li class=\"nav-item$active\"><a class=\"nav-link".
				"{$disabled}\" $href>$ia[0]$current</a></li>";
			}
		}
	$navhtml .= "</ul>";
	
		ob_start();			
	echo <<<END
			<div class="collapse navbar-collapse" id="navbarsExampleDefault">
        $navhtml
        $extra        
      </div>
END;
	$topNavbar = ob_get_contents();
ob_end_clean(); // Don't send output to client
	
	return($topNavbar);
	
	}
	
function buildBootStrapNGPage ($pageDetails=array())
	{
	global $default_scripts, $tpath, $im_path;

	$defaults = array(
		"metaDescription" => "The National Gallery, London, ".
			"Scientific Department, is involved with research within a wide ".
			"range of fields, this page presents an example of some of the ".
			"work carried out.",
		"metaKeywords" => "The National Gallery, London, ".
			"National Gallery London, Scientific, Research, Heritage, Culture",
		"metaAuthor" => "Joseph Padfield| joseph.padfield@ng-london.org.uk |".
			"National Gallery | London UK | website@ng-london.org.uk |".
			" www.nationalgallery.org.uk",
		"metaTitle" => "NG Test Page",
		"metaFavIcon" => "https://www.nationalgallery.org.uk/custom/ng/img/icons/favicon.ico",
		"extra_js_scripts" => array(), 
		"extra_css_scripts" => array(),
		"extra_css" => "",
		"extra_js" => "",
		"logo_link" => "",
		"logo_path" => "$im_path/ng-logo-white-100x40.png",
		"logo_style" => "",//"height='32px';",
		"extra_onload" => "",
		//"extra_resize" => "", // probably will not need this any more 
		"topNavbar" => "",
		"body" => "",
		"fluid" => false,
		"offcanvas" => false,
		"footer" => "&copy; The National Gallery 2020</p>",
		"footer2" => false,
		"licence" => false
		);
	 
	$pageDetails = array_merge($defaults, $pageDetails);

	$pageDetails["css_scripts"] = array_merge(
		$default_scripts["css-scripts"], $pageDetails["extra_css_scripts"]);
		
	$cssScripts = "";
	foreach ($pageDetails["css_scripts"] as $k => $path)
		{$cssScripts .="
	<link href=\"$path\" rel=\"stylesheet\" type=\"text/css\">";}
	
		
	$pageDetails["js_scripts"] = array_merge(
		$default_scripts["js-scripts"], $pageDetails["extra_js_scripts"]);
		
	$jsScripts = "";
	foreach ($pageDetails["js_scripts"] as $k => $path)
		{$jsScripts .="
	<script src=\"$path\"></script>";}

	if ($pageDetails["licence"])
			{$tofu = '<div style="white-space: nowrap;color:gray;">'.$pageDetails["licence"].'</div>';}
	else
			{$tofu = '<div>This site was developed and is maintained by: 
				<a href="mailto:joseph.padfield@ng-london.org.uk" 
					title="Joseph Padfield, The National Gallery Scientific Department">Joseph Padfield</a>.
					<a href="http://www.nationalgallery.org.uk/terms-of-use">Terms of Use</a></div>';}

	if ($pageDetails["topNavbar"])
		{
		ob_start();			
		echo <<<END
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
      <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <a class="navbar-brand"  href="$pageDetails[logo_link]">
  		<img id="page-logo" class="logo" title="Logo" src="$pageDetails[logo_path]" 
				style="$pageDetails[logo_style]" alt="The National Gallery"/>
		  </a>
			$pageDetails[topNavbar]
			
    <span class="navbar-text">
      <a href="http://www.iperionch.eu/">
				<img id="ex-logo1" class="logo" style="height:32px;" title="IPERION-CH" src="${im_path}IPERION-CH_logo_trans.png" 
				style="$pageDetails[logo_style]" alt="IPERION CH | Integrated Platform for the European Research Infrastructure ON Cultural Heritage"/>
		  </a>
      <a href="https://sshopencloud.eu/">
				<img id="ex-logo2" class="logo" style="height:32px;" title="Logo" src="${im_path}sshoc-logo.png" 
				style="$pageDetails[logo_style]" alt="SSHOC | Social Sciences & Humanities Open Cloud"/>
		  </a>
    </span>
    </nav>
END;
		$pageDetails["topNavbar"] = ob_get_contents();
		ob_end_clean(); // Don't send output to client
		}
			
	if($pageDetails["offcanvas"])
		{
		$oc = $pageDetails["offcanvas"];
		$offcanvasClass = "row-offcanvas row-offcanvas-right";
		$offcanvasToggle = "<p class=\"float-right hidden-md-up\"> ".
			"<button type=\"button\" class=\"btn btn-primary btn-sm\" ".
			"data-toggle=\"offcanvas\">{$pageDetails["offcanvas"][0]}</button>".
			"</p>";
		$sidepanel = "<div class=\"{$pageDetails["offcanvas"][2]} sidebar-offcanvas\" ".
			"id=\"{$pageDetails["offcanvas"][1]}\"><div class=\"list-group\">";
		
		$active = "active";	
		foreach ($pageDetails["offcanvas"][3] as $k => $a)
			{$sidepanel .= "<a href=\"$a[1]\" class=\"list-group-item link-extra $active\">".
				"$a[0]</a>";
			 $active = "";}
		$sidepanel .= "</div></div><!--/span-->";
		$ocw = "9";
		}
	else
		{$offcanvasClass = "";
		 $offcanvasToggle = "";
		 $sidepanel = "";
		 $ocw = "12";}

 	
	if ($pageDetails["footer"] or $pageDetails["licence"])
		{
		ob_start();			
		echo <<<END
  <footer>
		<div class="container-fluid">
			<div class="row">
				<div class="col-5" style="text-align:left;">$pageDetails[footer]</div>
				<div class="col-2" style="text-align:center;">$pageDetails[footer2]</div>
				<div class="col-5" style="text-align:right;">$pageDetails[licence]</div>
			</div>
		</div>        
  </footer>
END;
		$pageDetails["footer"] = ob_get_contents();
		ob_end_clean(); // Don't send output to client
		}
		
	/*if ($pageDetails["footer"])
		{
		ob_start();			
		echo <<<END
	<hr>
      <footer>
        $pageDetails[footer]
      </footer>
END;
		$pageDetails["footer"] = ob_get_contents();
		ob_end_clean(); // Don't send output to client
		}*/
  
  if($pageDetails["fluid"]) {$containerClass = "container-fluid";}
  else {$containerClass = "container";}
  
  $fn = "function"; 
	ob_start();			
	echo <<<END
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="$pageDetails[metaDescription]" />
		<meta name="keywords" content="$pageDetails[metaKeywords]" />
    <meta name="author" content="$pageDetails[metaAuthor]" />
    <link rel="icon" href="$pageDetails[metaFavIcon]">
    <title>$pageDetails[metaTitle]</title>
    $cssScripts
    <style type="text/css">
    $pageDetails[extra_css]
    </style>
  </head>

  <body onload="onLoad();">
		<div class="$containerClass">
			$pageDetails[topNavbar]
			<div class="row $offcanvasClass">
			 <div class="col-12 col-md-$ocw">          
				$offcanvasToggle
				$pageDetails[body]
			</div><!--/span-->
			
			$sidepanel
			</div><!--/row-->
			
			$pageDetails[footer]
    </div><!--/.container-->
    
    $jsScripts
    <script type="text/javascript">
			$pageDetails[extra_js]
			$fn onLoad() {
				$pageDetails[extra_onload]
				}
    </script>
  </body>
</html>
END;
	$page_html = ob_get_contents();
	ob_end_clean(); // Don't send output to client

	return ($page_html);
	}	


function getJsonDBDetails ($pid)
	{global $pid_ldb;
	 $output = array();	
	 if (!is_array($pid))
		{$row = $pid_ldb->select(
		"SELECT * FROM `pid_dump` WHERE `pid_name` = ?", array($pid));
		if ($row)
			{$output = json_decode($row["pid_json"], true);}}
	 else
		{$qs = array_pad(array(), count($pid), "?");
		 $rows = $pid_ldb->selectAll(
			"SELECT * FROM `pid_dump` WHERE `pid_name` IN (".implode(",",$qs).")", $pid);		 
		 if($rows) {
			 if (count($rows) == 1)
				{$output = json_decode($rows[0]["pid_json"], true);}
			 else
				{$output["type"] = "group";
				 foreach ($rows as $k => $row)
					{$output["group"][$row["pid_name"]] = json_decode($row["pid_json"], true);}}}
		}	 
		return ($output);}	
	 
function getLocalJsonDetails ($link)
	{$fc = file_get_contents($link);
	 $output = json_decode($fc, true);
	 return ($output);}	


	

?>
