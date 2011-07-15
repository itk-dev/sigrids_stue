Dynamic background
------------------
Enables site administrators to upload a range of images and select one as 
background on the site. This enables non html/css aware administrators to 
quickly change the background image on a site from within the administrator 
interface.

The selected background image is available as either the variable $background 
in page.tpl.php or as the path /background.css. The variable can be placed
in the body tag in page.tpl.php and the css can be added to your themes info
file.

From 6.x-1.1 you don't have to add the variable to your templet files, if you
use the CSS behaviour fields in the administration interface. The new release
has a sub-module, that enables you to select a different background image for
each node among the uploaded images. You can use the CSS selector to ensure
that, if no image is selected in the node, that the module falls back to the one
selected in the administration interface.

Requirements
------------
This module requires imagecache (http://www.drupal.org/project/imagecache) to 
be installed, as it is used by the administration interface.
