<?php
/*
 * Information for connection on OVH API
 *
 *
 * Script Name
 *** OVH-Cloud
 * Script Description
 *** OVH-Cloud Storage
 * Application Key
 *** <application_key>
 * Application Secret
 *** <application_secret>
 * Consumer Key
 *** <consumer_key>
 * Service Name
 *** <service_name>
 * Container Id
 *** <container_id>
 */

// Informations about your application
// SCRIPT NAME : OVH-Cloud-Storage-Website
define("APPLICATION_KEY", "<application_key>", true);
define("APPLICATION_SECRET", "<application_secret>", true);
define("CONSUMER_KEY", "<consumer_key>", true);


define("END_POINT", "ovh-eu", true);

define("SERVICE_NAME", "<service_name>", true);
define("CONTAINER_ID", "<container_id>", true);

define("PROJECT_URL", "/cloud/project/".SERVICE_NAME."/storage/".CONTAINER_ID, true);

?>