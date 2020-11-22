<div class="topic" id="intro">
    <h1>Introduction</h1>

    <h2>Short overview</h2>
    <div class="block">
        <p>
            restifydb is a framework that is able to automatically map databases (from all major database
            engine providers) to REST web services. The web services can output XML or JSON. It leverages
            data discovery. It has advanced capabilities as automatic query expansion (joining based on
            foreign keys), automatic inward references, automatic data discovery, filtering, sorting and
            out of the box CRUD operations (create, read, update and delete data). Most of these features
            are used by the <a href="https://restifydb.com/#demos" target="_blank" title="demo applications built with restifydb">demo applications</a>
            built using restifydb. It also features an administration panel which makes configuring it quite easy.
        </p>
    </div>

    <h2>Typical usecase</h2>
    <div class="block">
        <p>
            Let's assume that you have in your data warehouse a database containing information about the world
            continents, countries and largest cities. One customer of yours is interested in using this data in order to
            build a nice HTML5/Android/iOS (see an <a href="https://restifydb.com/demos/countries" target="_blank" title="world countries information application built with restifydb">example <i class="fa fa-external-link"></i></a>)
            application which will synthesize this information into graphs, tables, etc., and
            present it to the users. One solution would be for you to offer the entire database for download (via an
            SQL dump, for instance). However, this is not the best approach because data usually changes and
            you would need to publish these data dumps every time you update the information. If you have
            one thousand customers using you database, each one of them would have to re-download the
            database every time you publish a patch. restifydb can alleviate this problem by creating a
            single point of access to your data. This is how it works - in five simple steps:
        </p>
        <ol>
            <li>You install restifydb onto your web server.</li>
            <li>You configure it by adding a new data source pointing to your countries database.</li>
            <li>restifydb does the magic by exposing data as easy to consume REST web services.</li>
            <li>Your clients can now work directly with these web services, easily integrating them into
                their applications.</li>
            <li>Every time you update your data, the changes are propagated to all the applications using
                the web services exposed by restifydb.</li>
        </ol>
    </div>

    <h2>Data entities</h2>
    <div class="block">
        <p>
            restifydb operates will the following types of entities, all of which can be references by an
            unique identifier (except for one special case, please see below):
        </p>
        <ul>
            <li>
                <code>system</code>: this is the root entity. it represents the system as a whole and
                contains a collection of data sources. Its URL correspondent is the root framework URL,
                e.g.: <code>/api</code>
                (<a href="https://restifydb.com/api?_view=json" target="_blank"><i class="fa fa-external-link"></i></a>),
                given that the application is installed in the <code>api</code>
                folder.
            </li>
            <li>
                <code>data source</code>: it usually corresponds to a classical database or schema. It is parent for a
                collection of tables. It is identifiable by its context name (as specified when <a href="#add-ds">adding the
                data source</a>). Its URL counterpart would be, for instance: <code>/api/countrylicious</code>
                (<a href="https://restifydb.com/api/countrylicious?_view=json" target="_blank"><i class="fa fa-external-link"></i></a>)
                given that the data source name is <code>countrylicious</code>.
            </li>
            <li>
                <code>table</code>: it corresponds to a classical table within a database. It is a
                collection of rows which have all the same structure. It is identifiable via its name. Its
                URL counterpart could be, for instance: <code>/api/countrylicious/countries</code>
                (<a href="https://restifydb.com/api/countrylicious/countries?_view=json" target="_blank"><i class="fa fa-external-link"></i></a>)
                given that the
                table name is <code>countries</code>.
            </li>
            <li>
                <code>row</code>: it usually corresponds to a classical row within a table and it is a collection of
                fields.
            </li>
            <li>
                <code>record</code>: this corresponds to a row which can be identified via an unique value. Most often
                this unique value is represented by the primary key of the table containing the record. Its URL
                counterpart could be, for instance: <code>/api/countrylicious/countries/233</code>
                (<a href="https://restifydb.com/api/countrylicious/countries/233?_view=json" target="_blank"><i class="fa fa-external-link"></i></a>)
                given that the row ID is <code>233</code>. <span class="text-danger">If there is no primary key defined, the framework
                will not be able to expose records.</span>
            </li>
            <li>
                <code>field</code>: this corresponds to a classical field (or column) within a table. Each field of a
                record/row has an associated value (even if this is sometimes set to nil).
            </li>
        </ul>
    </div>

    <h2>Data linkage</h2>
    <div class="block">
        <p>
            When developing restifydb, I used a paradigm called <code>HATEOS</code> (Hypermedia as the
            Engine of Application State). This means that the client connecting to the service does not need
            to know anything about the structuring of the data and its interconnections. These connections are
            described by means of linkage, meaning that every relation is defined via a link to the entity
            at the other end of the relation. This is achieved through <code>href</code>s.
        </p>
        <p>
            E.g.: if the data source <code>countrylicious</code> contains a table called <code>cities</code>,
            when accessing <code>countrylicious</code> (through, its associated URL - <code>/api/countrylicious</code>
            (<a href="https://restifydb.com/api/countrylicious?_view=json" target="_blank"><i class="fa fa-external-link"></i></a>)),
            this is described by a <code>href</code> containing the location of the table object, like:
            <code>&quot;href&quot;: &quot;/api/countrylicious/cities&quot;</code>. So, the client doesn't need to know
            about the existence of the <code>cities</code> table or how to access it. The service will
            provide all these information as hypermedia contained in the response.
        </p>
    </div>
</div>
