api = 2
core = 7.x

; Include the definition for how to build Drupal core directly, including patches:
includes[] = http://drupalcode.org/project/simplytest.git/blob_plain/:/drupal-org-core.make

; Download the simplytest.me install profile and recursively build all its dependencies:
projects[simplytest][type] = profile
projects[simplytest][download][type] = git
projects[simplytest][download][url] = http://git.drupal.org/project/simplytest.git
projects[simplytest][download][branch] = 7.x-1.x
