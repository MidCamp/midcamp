<?php
if (getenv('AMAZEEIO_BASE_URL')) {
  $options['uri'] = getenv('AMAZEEIO_BASE_URL');
}
if (getenv('AMAZEEIO_WEBROOT')) {
  $options['root'] = getenv('AMAZEEIO_WEBROOT');
}