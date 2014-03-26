<?php

return array(
	'account_suffix' => "@corp.fccn.pt",

	'domain_controllers' => array("dc6.corp.fccn.pt"), // An array of domains may be provided for load balancing.

	'base_dn' => 'DC=corp,DC=fccn,DC=pt',

	'admin_username' => 'poliveira',

	'admin_password' => '',
	'real_primary_group' => true, // Returns the primary group (an educated guess).

	'use_ssl' => false, // If TLS is true this MUST be false.

	'use_tls' => false, // If SSL is true this MUST be false.

	'recursive_groups' => true,
);