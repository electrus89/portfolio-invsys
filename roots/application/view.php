<?php 
/*
	Inventory system
	
	View Subsystem
	
	This subsystem is used to generate the "view" of the webpage, and contains
	  the classes, constants, and variables required.
*/

// Colorway Array Index Constants.
const CW_HEADBG = 0;	const CW_HDHOVBG = 1;	const CW_HDHOVFG = 2;	const CW_HEADFG = 3;
const CW_FDHEAD = 4;	const CW_FDCONT = 5;	const CW_FDFOOT = 6;
const CW_CONTBG = 7;	const CW_CONTFG = 8;
const CW_MAINBG = 9;

class Colorways
{
	// A orange/gray colorway with black accents.
	static $Orange =	array (	"#E35B13", "#000000", "#FFFFFF", "#FFFFFF",		/* Header: MainBG, HoverBG, HoverFG, MainFG */
								"#AAAAAA", "#FFFFFF", "#CCCCCC",			    /* Fade: HeaderFade/TopDefault, ContentFade, FooterFade/BottomDefault */
								"#FFFFFF", "#000000",							/* Content:	ContentBG, ContentFG */
								"#CCCCCC");										/* Page: PageBG*/
								
	// A dark colorway with orange accents.
	static $Black =		array (	"#000000", "#E35B13", "#FFFFFF", "#FFFFFF",		/* Header: MainBG, HoverBG, HoverFG, MainFG */
								"#AAAAAA", "#FFFFFF", "#AAAAAA",			    /* Fade: HeaderFade/TopDefault, ContentFade, FooterFade/BottomDefault */
								"#FFFFFF", "#000000",							/* Content:	ContentBG, ContentFG */
								"#AAAAAA");										/* Page: PageBG */
}

// Factory and core information class for the UIClasses.
class GUI {
	var $colorway_array;
	var $format;
	var $documentModel;

	function __construct($colorway, $format = "css")
	{
		$this->colorway_array = $colorway;
		$this->format = $format;
		$this->documentModel = new DocumentModel();
	}

	function getUIBody()
	{
		return new UIBody($this);
	}
	
	function getUIHeader()
	{
		return new UIHeader($this);
	}
	function getUIFooter()
	{
		return new UIFooter($this);
	}
	function getUIFade()
	{
		return new UIFade($this);
	}
	function getUIContent()
	{
		return new UIContent($this);
	}
	function getUIStylesheet() 
	{
		return new UIStylesheet($this);
	}
	function getUIDocument()
	{
		return new UIDocument($this);
	}
}

// The document model informs the creation of certain elements of the GUI
//   by the UIClasses.
class DocumentModel
{
	var $page_title = "$$$";
	var $navigation = array(
		"Home" => "./"
	);
	var $content = <<<EOT
<h1>Test</h1>
<div>test</h1>
EOT;
}

// Base class for the UI part classes, to handle commonalities.
abstract class UIClass
{
	// All UI classes point back to the main implementation of the GUI class.
	var $parent_gui;
	
	// All UI classes use a constructor to refer to the 
	function __construct($gui)
	{
		$this->parent_gui = $gui;
	}
}

// Indicates that the class must implement CSS generation.
interface CSSGenerator { function getCSS(); }

// Indicates that the class must implement HTML generation.
interface HTMLGenerator { function getHTML(); }

trait CastsGeneratorToString {
	function __toString()
	{
		switch ($this->parent_gui->format)
		{
			case "css":	return $this->getCSS();
			case "html": return $this->getHTML();
		}
	}
}

// Generator for the body of the document.
class UIBody extends UIClass implements CSSGenerator, HTMLGenerator {
	use CastsGeneratorToString;
	
	function getHTML()
	{
		// This would be easy if we didn't have to deal with being called twice.
		static $x = 0;
		$x += 1;
		$answers = array("<body>","</body>");
		
		// Give the response for the appropriate execution count.
		return $answers[($x-1)%2];
	}
	
	function getCSS()
	{
		return <<<EOT
body { background: {$this->parent_gui->colorway_array[CW_MAINBG]}; margin: 0px; }

EOT;
	}
}

class UIHeader extends UIClass implements CSSGenerator, HTMLGenerator {
	use CastsGeneratorToString;
	
	function getHTML()
	{
		// Generate the HTML for the heading items.
		$listitems = "";
		foreach ($this->parent_gui->documentModel->navigation as $label => $href)
		{
			$listitems .= "<li><a href=\"{$href}\">{$label}</a></li>";
		}	
		return <<<EOT
<div id="heading">
	<img id="logo"/>
	<ul id="menu">
		{$listitems}
	</ul>
</div>
EOT;
;
	}
	
	function getCSS()
	{
		return <<<EOT
div#heading { background: {$this->parent_gui->colorway_array[CW_HEADBG]} ; padding: 2px 0px 2px 0px; height: 34px; }
div#heading img#logo { height: 32px; width: 32px; background: red; position: relative; bottom: -2px; left: 2px; }
div#heading ul#menu { display: inline; padding: 0px; }
div#heading ul#menu li { display: inline; margin: 0px;}
div#heading ul#menu li a { color: {$this->parent_gui->colorway_array[CW_HEADFG]}; text-decoration: none; font-family: sans-serif; padding: 4px; position: relative; bottom: 4px; }
div#heading ul#menu li a:hover { background: {$this->parent_gui->colorway_array[CW_HDHOVBG]}; color: {$this->parent_gui->colorway_array[CW_HDHOVFG]}; }

EOT;	
	}
}

class UIFooter extends UIClass implements CSSGenerator, HTMLGenerator {
	use CastsGeneratorToString;

	function getHTML()
	{
		// Nothing much but static text for now.
		return <<<EOT
		<div id="footing">
			<div id="copyright">Sample application utilizing MariaDB, PHP-FPM, and Nginx developed by Joshua Washburn during May 2022</div>
		</div>
EOT;
	}

	function getCSS()
	{
		return <<<EOT
div#footing div#copyright { font-family: sans-serif; font-size: 10pt; }

EOT;	
	}
}

class UIFade extends UIClass implements CSSGenerator, HTMLGenerator {
	use CastsGeneratorToString;

	function getHTML()
	{
		// <div class="ui-fade" location="top"> </div>
		static $x = 0;
		$x += 1;
		$answers = array("top","bottom");
		
		// Give the response for the appropriate execution count.
		return "<div class=\"ui-fade\" location=\"".$answers[($x-1)%2]."\"></div>";
	}

	function getCSS()
	{
		return <<<EOT
div div.ui-fade { height: 10px; display: block; }
div div[location="top"].ui-fade { background-color: {$this->parent_gui->colorway_array[CW_FDHEAD]} ;background-image: linear-gradient({$this->parent_gui->colorway_array[CW_FDHEAD]},{$this->parent_gui->colorway_array[CW_FDCONT]});}
div div[location="bottom"].ui-fade { background-color: {$this->parent_gui->colorway_array[CW_FDFOOT]}; background-image: linear-gradient({$this->parent_gui->colorway_array[CW_FDCONT]},{$this->parent_gui->colorway_array[CW_FDFOOT]});}

EOT;
	}
}

class UIContent extends UIClass implements CSSGenerator, HTMLGenerator {
	use CastsGeneratorToString;
	
	function getHTML()
	{
		$fade = array(
			$this->parent_gui->getUIFade(),
			$this->parent_gui->getUIFade()
		);
		return 	<<<EOT
			<div id="page">
			{$fade[0]}
			<div id="content">
			{$this->parent_gui->documentModel->content}
			</div>
			{$fade[1]}
			</div>
EOT;
	}
	
	function getCSS()
	{
		return <<<EOT
div#page { background: {$this->parent_gui->colorway_array[CW_CONTBG]}; color: {$this->parent_gui->colorway_array[CW_CONTFG]} }
div#page div#content { margin: 0px 4px 0px 4px; font-family: sans-serif; }
div#content { padding: 5px; }
div#content h1, div#content h2, div#content h3, div#content h4, div#content h5 { margin: 3px 0px 3px 0px; }

EOT;
	}
}

class UIDocument extends UIClass implements HTMLGenerator {
	use CastsGeneratorToString;
	function getHTML()
	{
		// The body generate function is called twice as there's a start and end.
		//   Fortunately, we don't have to deal with that difference here.
		$body = array(
						$this->parent_gui->getUIBody(),
						$this->parent_gui->getUIBody()
					);
		
		// Heading is called once as the generate function automatically handles the 
		//   menu.
		$heading = $this->parent_gui->getUIHeader(); 
		
		// Content is called once as the generate function automatically handles the
		//   fade, as well as grabbing the content.
		$content = $this->parent_gui->getUIContent();

		// Footing is called once. It's pretty simple.
		$footing = $this->parent_gui->getUIFooter(); 

		return <<<EOT
<html>
    <head>
        <title>{$this->parent_gui->documentModel->page_title} - Inventory System</title>
		<link rel="stylesheet" href="style.php" type="text/css"></link>
    </head>
	{$body[0]}
	{$heading}
	{$content}
	{$footing}
	{$body[1]}
</html>
EOT;
	}
}

class UIStylesheet extends UIClass implements CSSGenerator {
	use CastsGeneratorToString;
	function getCSS()
	{
		$x = <<<EOT
/*
Stylesheet

Generated by the View Subsystem
*/

EOT;
		// Get the body styles...
		$x .= $this->parent_gui->getUIBody();
		// ... then the Header styles...
		$x .= $this->parent_gui->getUIHeader();
		// ... and then the footing styles...
		$x .= $this->parent_gui->getUIFooter();
		// ... the fade styles ...
		$x .= $this->parent_gui->getUIFade();
		// ... and finally the Content styles.
		$x .= $this->parent_gui->getUIContent();
		// and send it all to the caller.
		return $x;
	}
}