# restifydb Data Source Browser Demo Application #

restifydb is a framework that is able to automatically map databases (from all major database engine providers) to 
REST web services. The web services can output XML or JSON. It leverages data discovery. It has advanced capabilities 
as automatic query expansion (joining based on foreign keys), automatic inward references, automatic data discovery, 
filtering, sorting and out of the box CRUD operations (create, read, update and delete data). Most of these features 
are used by our demo application built using restifydb. It also features an admin panel which makes configuring it 
quite easy.

This application connects to restifydb and presents the data in a human-readable way. It follows the usual data 
structuring supported in the framework: data sources, tables, individual rows. Data is paged, can be filtered and 
sorted. The debug info allows the user to understand how the REST services are structured.
 
In order to make this application work, just modify the Config.php and make it point to the URL where restifydb 
exposes the data sources as REST services.