Dynamic background
------------------
Enables site administrators to upload a range of images and select one as 
background on the site. This enables non html/css aware administrators to 
quickly change the background image on a site from within the administrator 
interface.

The selected background image is available as either the variable $background 
in page.tpl or as the path /background.css. The variable can be placed in the 
body tag in page.tpl and the css can be added to your themes info file.

Requirements
------------
This module requires imagecache (http://www.drupal.org/project/imagecache) to 
be installed, as it is used by the administration interface.
