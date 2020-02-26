<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="NG API System" />
		<meta name="keywords" content="National Gallery|Paintings|Semantics|Open Linked Data|API|CIDOC|crm" />
    <meta name="author" content="Joseph Padfield| joseph.padfield@ng-london.org.uk |National Gallery | London UK | website@ng-london.org.uk | www.nationalgallery.org.uk" />
    <link rel="icon" href="https://www.nationalgallery.org.uk/custom/ng/img/icons/favicon.ico">
    <title>NG Example CRM Modelling</title>
    
	<link href="https://research.ng-london.org.uk/ng/tools/bootstrap-4.3.1/css/bootstrap.min.css" rel="stylesheet" type="text/css">
	<link href="https://research.ng-london.org.uk/ng/tools/bootstrap-4.3.1/css/offcanvas.css" rel="stylesheet" type="text/css">
	<link href="https://research.ng-london.org.uk/ng/tools/jquery.json-viewer/json-viewer/jquery.json-viewer.css" rel="stylesheet" type="text/css">
    <style type="text/css">
    .div-wrapper {
    display: block;
    max-height: 2000px;
    overflow-y: auto;
    -ms-overflow-style: -ms-autohiding-scrollbar;
}
    </style>
  </head>

  <body onload="onLoad();">
		<div class="container">
			<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
      <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <a class="navbar-brand"  href="./">
  		<img id="page-logo" class="logo" title="Logo" src="https://research.ng-london.org.uk/ng/graphics//ng-logo-white-100x40.png" 
				style="" alt="The National Gallery"/>
		  </a>
						<div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav"><li class="nav-item active"><a class="nav-link" href="?page=home">Home <span class="sr-only">(current)</span></a></li><li class="nav-item"><a class="nav-link" href="?page=models">Models</a></li><li class="nav-item"><a class="nav-link" href="?page=data">Data</a></li><li class="nav-item"><a class="nav-link" href="?page=about">About</a></li></ul>
                
      </div>
			
    <span class="navbar-text">
      <a href="http://www.iperionch.eu/">
				<img id="ex-logo1" class="logo" style="height:32px;" title="IPERION-CH" src="https://research.ng-london.org.uk/ng/graphics/IPERION-CH_logo_trans.png" 
				style="" alt="IPERION CH | Integrated Platform for the European Research Infrastructure ON Cultural Heritage"/>
		  </a>
      <a href="https://sshopencloud.eu/">
				<img id="ex-logo2" class="logo" style="height:32px;" title="Logo" src="https://research.ng-london.org.uk/ng/graphics/sshoc-logo.png" 
				style="" alt="SSHOC | Social Sciences & Humanities Open Cloud"/>
		  </a>
    </span>
    </nav>
			<div class="row ">
			 <div class="col-12 col-md-12">          
				
				<div class="jumbotron"><h2>National Gallery - CIDOC CRM Modelling Examples</h2></div><div class="row"><div class="col-12 col-lg-12"><h2>Integrating documentation details and analytical data generated for an object</h2></div><!--/span--></div><!--/row-->    <div class="row"><div class="col-6 col-lg-6"><p>In order to connect existing work together and to identify where the development of practical
examples of documentation software could be most informative, the relationships
between the various types of documentary and analytical data generated for generic and specific
situations need to be described. This presentation provides examples of how real objects can 
be semantically described and linked to the images and results of their analytical examination.</p>
<center><a class="btn btn-outline-primary nav-button" style="left:80px;" id="nav-models" role=button" href="./?page=models">Explore Models</a></center><br/>

<p><a href="http://www.cidoc-crm.org"><img style="position:relative;width:75px;float:left;margin-right:5px;" src="https://research.ng-london.org.uk/ng/graphics/cidoccrm_logo.png"/></a>
All of the linking properties and classes presented in the included examples are taken from the current<sup><a id="ref1" href="#section1">[1]</a></sup>
release and development version of the CIDOC-CRM<sup><a id="ref2" href="#section2">[2]</a></sup> ontology. References to external control
vocabularies or thesauri<sup><a id="ref3" href="#section3">[3]</a></sup> have also been included to demonstrate how links between internal documentation
systems and external resources could be modelled.</p><div style="font-size:smaller;"><ul><li id="section1"><a href="#ref1">[1]</a> January 2020</li><li id="section2"><a href="#ref2">[2]</a> The CIDOC Conceptual Reference Model (CRM) is a theoretical and practical tool for information integration in the
field of cultural heritage. - <a href='http://www.cidoc-crm.org'>http://www.cidoc-crm.org</a> . Current versions of the CIDOC CRM can be found at <a href='http://www.cidoc-'>http://www.cidoc-</a>
crm.org/versions-of-the-cidoc-crm.</li><li id="section3"><a href="#ref3">[3]</a> The two main external LOD vocabularies/thesauri referenced in this document are the Getty’s AAT ( <a href='http://www.getty.edu/research/tools/vocabularies/aat'>http://www.getty.edu/research/tools/vocabularies/aat</a> ) and Wikidata ( <a href='https://www.wikidata.org'>https://www.wikidata.org</a> )</li></ul></div></div><!--/span--><div class="col-6 col-lg-6"><img style="position:relative;width:100%;" src="https://research.ng-london.org.uk/ng/graphics/example_knowledge_graph.png"/></div><!--/span--></div><!--/row-->    <br/>
			</div><!--/span-->
			
			
			</div><!--/row-->
			
			  <footer>
		<div class="container-fluid">
			<div class="row">
				<div class="col-5" style="text-align:left;">&copy; The National Gallery 2020</p></div>
				<div class="col-2" style="text-align:center;"></div>
				<div class="col-5" style="text-align:right;"><a href="https://www.nationalgallery.org.uk/terms-of-use">
    <img height="16" alt="National Gallery - Terms of Use" title="National Gallery - Terms of Use"
      src="https://research.ng-london.org.uk/ng/graphics//ng-logo-black-100x40.png"/>
</a><a href="http://rightsstatements.org/vocab/InC-EDU/1.0/">
    <img height="16" alt="In Copyright - Educational Use Permitted" title="In Copyright - Educational Use Permitted"
      src="https://research.ng-london.org.uk/ng/graphics/InC-EDU.dark-white-interior-blue-type.png"/>
</a><a rel="license" href="http://creativecommons.org/licenses/by-nc-nd/4.0/"><img alt="Creative Commons Licence" style="border-width:0" src="https://i.creativecommons.org/l/by-nc-nd/4.0/88x31.png" /></a>
</div>
			</div>
		</div>        
  </footer>
    </div><!--/.container-->
    
    
	<script src="https://research.ng-london.org.uk/ng/tools/jquery-3.2.1/jquery-3.2.1.min.js"></script>
	<script src="https://research.ng-london.org.uk/ng/tools/tether-1.4.0/js/tether.js"></script>
	<script src="https://research.ng-london.org.uk/ng/tools/bootstrap-4.3.1/js/bootstrap.js"></script>
	<script src="https://research.ng-london.org.uk/ng/tools/bootstrap-4.3.1/js/ie10-viewport-bug-workaround.js"></script>
	<script src="https://research.ng-london.org.uk/ng/tools/bootstrap-4.3.1/js/offcanvas.js"></script>
	<script src="https://research.ng-london.org.uk/ng/tools/jquery.json-viewer/json-viewer/jquery.json-viewer.js"></script>
    <script type="text/javascript">
			
			function onLoad() {
				
				}
    </script>
  </body>
</html>
