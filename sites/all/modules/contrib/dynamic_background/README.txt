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

Release 6.x-1.1
You don't have to add the variable $background to your template files, if you
use the CSS behaviour fields in the administration interface. This release has a
sub-module, that enables you to select a different background image for each
node among the uploaded images. You can use the CSS selectors to ensure, if no
image is selected in the node, that the module falls back to the one selected in
the administration interface.

Release 6.x-1.2
This release has a sub-module, that enables you to select a different a
background image for each panel variant among the uploaded images. As with node
background you can use CSS selectors to ensure, if no image is selected in the
node, that the module falls back to the one selected in the administration
interface.

Release 1.3
-----------
The release enables users to select one of the uploaded background images on a
tab in their user profile. The selected background will then be used when the
user is logged into the site. This extension uses CSS selectors to target the
right HTML element(s) with the user selected background image.

Requirements
------------
This module requires imagecache (http://www.drupal.org/project/imagecache) to 
be installed, as it is used by the administration interface.
