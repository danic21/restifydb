# restifydb World Countries Database Demo Application #

restifydb is a framework that is able to automatically map databases (from all major database engine providers) to 
REST web services. The web services can output XML or JSON. It leverages data discovery. It has advanced capabilities 
as automatic query expansion (joining based on foreign keys), automatic inward references, automatic data discovery, 
filtering, sorting and out of the box CRUD operations (create, read, update and delete data). Most of these features 
are used by our demo application built using restifydb. It also features an admin panel which makes configuring it 
quite easy.

This demo application explains how to consume data which is exposed by the restifydb. It is built using the AngularJS 
framework and there is no backend component whatsoever. Data is retrieved as JSON and consumed on the client-side. The 
facts needed to write this application were provided by http://countrylicious.com.
 
In order to make this application work, just modify the js/config.js and make it point to the URL where restifydb 
exposes the country information REST service.