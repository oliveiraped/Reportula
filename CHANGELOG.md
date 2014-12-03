2.1.0 - 8 December - 2014
===============================================

Features:
- [Add] Reportula Language Localization Files - Portuguese, English,. 

Bugfixes:

- [fix] Fix loading jobs and volumes on dashboard. Thanks Sergio Cambra
- [fix] Adding includes or excludes to fileset fails #17. Thanks Sergio Cambra
- [fix] Loading jobs, volumes, filesets in configurator, or other tables fails. Thanks Sergio Cambra

Code Refactor
- [upg] Cleaned some unusable code.

2.0.10 - 9 Nov - 2014
===============================================

Features:
- [Add] Bacula reload Configuration Option in Menu Configuration.

Bugfixes:

- [fix] Clicking on a job ID with jobs that have no files processed.
- [fix] "Comments Read" Configuration files Fix - Thanks to DavidBuzz


2.0.9 - 7 Oct - 2014
===============================================

Bugfixes:

- [fix] Portgresql Bug Fixes missed on 2.0.8 Version


2.0.8 - 6 Oct - 2014
===============================================

Features:

- [add] Added Emails Report Tables on Intall procedure
- [add] Added Email Report Command to Send Emails
- [add] Added Report Emails Menu in Administration
             - Reportula Sends Schedules Backups Emails Reports
- [add] Added Storage Select Box on Job Cofiguration Editor
- [add] Added Pools Select Box on Job Cofiguration Editor
- [add] Added Fileset Select Box on Job Cofiguration Editor
- [add] Added Schedules Select Box on Job Cofiguration Editor

Bugfixes:

- [fix] Mysql Databases Case Sensitive Bug
- [fix] BootBox - Downgraded to 3.3.0 Version
- [fix] BaculaStats Crontab Command - HoursDiff Bug

Code Refactor

- [upg] Upgraded Laravel Framework to 4.2.x
- [upg] Upgraded Composer packages to meet Laravel 4.2.x

2.0.7 - 8 August - 2014
===============================================

Features:

- [add] Added Bacula Bconsole Console Terminal
- [add] Added Bacula Configuration Test Menu
- [add] Clients Names and Fileset Names Dropdown to Job Config Form
- [add] Write Configuration Files
- [add] Read Configuration Files
- [add] Delete Button to Configuration Items
- [add] Dropdown Button to Created new Configuration Items
- [add] Persist Extension to FancyTree on Configuration items Tree

Bugfixes:

- [fix] Fixed Rss Reader Link on Administration Dashboard
- [fix] Fixed Installer on Creation of Configuration Tables
- [fix] Fixed Database Models names CfgMessages, CfgConsole

Code Refactor

- [mod] Configurator Fileset Form Rewrited the code
- [mod] Cfg Models Extended the BaseModel


2.0.6 - 11 July - 2014
===============================================

Features:

* Added Read Configuration to Messages, Console Options in Bacula Director configuration
* Added Form Config for Messages, Console Options
* Added Button to Reload Tree Data

Bugfixes:

* Fix the Search Filter on Configurator Tree
* Configurator Reader - Reads JobsDef Resource
* Upgrade DataTables Version 1.10.1

Code Refactor

* Configuration Controller - Refactoring the code


2.0.5 - 7 July - 2014
===============================================

Features:

* Added Export Html Table to Csv,Sql,Pdf,Json,Excel,Word to all DataTables.
* Created Fileset Files and Options Forms
* Added Save Configurations for Jobs, Directors, Storages, Fileset, Schedules, Pools and Catalogs

Bugfixes:

* Change the Date Methods to respect the code upgrade of Laravel Date Module


2.0.4 - 23 May - 2014
===============================================

Features:

* Added Ajax to the read Bacula config configuration area
* Added Configuration Tree of Bacula Configuration area
* Created Form for Director, Clients, Jobs, Storages Configurations options
* Added Remember Querys caching the Sql Querys Making Reportula Faster

Bugfixes:

* Updated Laravel Framework Version
* Changed Users and Groups Model to respect Laravel needs
* Remove the profile link on the welcome admin text


2.0.3
===============================================

Bugfixes:

* Corrected Volume Date Retention Information
* Corrected Client Job Date Retention Information
* Corrected Client File Date Retention Information

2.0.2
===============================================

Bugfixes:

* Corrected Volume Date Retention Information
* Corrected Client Job Date Retention Information
* Corrected Client File Date Retention Information
* Corrected Admin User Edit
* Corrected Admin Groups Edit
* Corrected Admin New User Creation is not activated on creation
* Corrected Ldap Import Users activate login

Features:

* Added Reportula Rss Reader News to Administration Dashboard
* Added Reportula Logo


2.0.1
===============================================

Bugfixes:

* Corrected Js Files Users & Groups Administration Clients Select Boxes
* Corrected Form Population Field on Users Administration
* Corrected Groups Population Field on Users Administration

Features:

None

Documentation:

None
