<div class="topic" id="crud">
    <h1>Accessing the webservices</h1>

    <h2>Brief introduction</h2>
    <div class="block">
        <p>
            restifydb exposes data via REST web services. The representational state transfer (REST)
            paradigm has been introduced as a lightweight, simpler alternative to SOAP (and its description
            language - WSDL). In REST, data access methods are mapped to URLs and HTTP verbs. For instance,
            reading the <code>countries</code> data would translate into <code>GET /api/countrylicious/countries</code>
            (<a href="https://restifydb.com/api/countrylicious/countries?_view=json" target="_blank"><i class="fa fa-external-link"></i></a>).
            One could easily guess that deleting a specific country is as simple as doing a
            <code>DELETE /api/countrylicious/countries/233</code> (where <code>233</code> is the unique
            identifier of the country to be deleted).
        </p>
    </div>

    <h2>Supported HTTP verbs</h2>
    <div class="block">
        <p>
            restifydb supports the following HTTP verbs, each one corresponding to a CRUD operation:
        </p>

        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th class="col-md-2">HTTP verb</th>
                    <th class="col-md-3">CRUD operation</th>
                    <th class="col-md-7">Example</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><code>POST</code></td>
                    <td>Creates a row/record</td>
                    <td><code>POST /api/countrylicious/cities<br>_data={&quot;name&quot;: &quot;Seattle&quot;, &quot;countryid&quot;: &quot;233&quot;}</code><br>
                        Creates a new city record (Seattle/US) into the <code>cities</code> table.
                    </td>
                </tr>
                <tr>
                    <td><code>GET</code></td>
                    <td>Reads data from system, data source, table and record</td>
                    <td><code>GET /api/countrylicious/cities</code>
                        (<a href="https://restifydb.com/api/countrylicious/cities?_view=json" target="_blank"><i class="fa fa-external-link"></i></a>)
                        <br>
                        Reads data from the <code>cities</code> table. Data is serialised into the
                        requested output type (see Output types and content negotiation) and returned to the
                        caller.
                    </td>
                </tr>
                <tr>
                    <td><code>PUT</code></td>
                    <td>Updates a record</td>
                    <td><code>PUT /api/countrylicious/cities/1<br>_data={&quot;name&quot;: &quot;Andorra&quot;,
                            &quot;countryid&quot;: &quot;155&quot;, &quot;population&quot;: &quot;20500&quot;}</code>
                        <br>
                        Updates the city record with the unique identifier equal to <code>1</code> from the
                        <code>cities</code> table. The new city will be Andorra/Andorra. Please note: while working
                        with records, a primary key must exist.
                    </td>
                </tr>
                <tr>
                    <td><code>DELETE</code></td>
                    <td>Deletes a record</td>
                    <td><code>DELETE /api/mydatasource/customers/5</code>
                        <br>
                        Deletes the customer record with the unique identifier equal to <code>5</code> from the
                        <code>customers</code> table. Please note: while working with records, a primary key must exist.
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>


    <h2>Output types and content negotiation</h2>
    <div class="block">
        <p>
            restifydb can currently serialise the output into two different highly used formats:
        </p>
        <ul>
            <li>
                <code>JSON</code> (JavaScript Object Notation) -
                <a href="https://restifydb.com/api/countrylicious?_view=json" target="_blank"><i class="fa fa-external-link"></i></a>
                - this is the preferred data format for
                clients integrated into browser applications because it can be natively de-serialized into
                JavaScript objects by any modern browser.
            </li>
            <li>
                <code>XML</code> (Extensible Markup Language) -
                <a href="https://restifydb.com/api/countrylicious?_view=xml" target="_blank"><i class="fa fa-external-link"></i></a>.
            </li>
        </ul>
        <p>
            When outputting data to the calling parties, restifydb needs to decide in which format it should
            serialise this output. This is called content negotiation. In the framework it works as follows:
        </p>
        <ol>
            <li>
                It first checks whether a URL parameter called <code>_view</code> exists and its value is
                set to either <code>json</code> or <code>xml</code>.
            </li>
            <li>
                If the <code>_view</code> URL parameter is missing, the framework will check if the caller
                is sending the <code>HTTP_ACCEPT</code> HTTP header. If this matches to either
                <code>application/xml</code> or <code>application/json</code>, this value will be used as
                output type.
            </li>
            <li>
                If all of the above fail, the JSON output type is used by default.
            </li>
        </ol>
    </div>


    <h2>Working with URL parameters</h2>
    <div class="block">
        <p>
            There are several URL parameters which can be specified when calling the restifydb web services. Except for
            the <code>_view</code> parameter, all of
            them only refer to reading data and are preserved when building URLs in the same context (e.g.: if you
            specify a <code>_count</code> parameter to limit the number of rows retrieved when reading a table, this
            parameter will be preserved when restifydb constructs the URLs needed to navigate through the given table:
            previous page, next page, etc.).
        </p>
        <p>
            Example: the following URl <code>GET /api/countrylicious/countries?_view=json&amp;_count=20&amp;_start=120</code>
            (<a href="https://restifydb.com/api/countrylicious/countries?_view=json&_count=20&_start=120" target="_blank"><i class="fa fa-external-link"></i></a>)
            will trigger a read operation on the <code>countries</code> table. It instructs the framework to serialize the
            output as JSON, and to fetch 20 rows starting from the 120<sup>th</sup> row.
        </p>
        <p class="text-danger">
            It is important to URL-encode these parameter values when making calls to the web services. By doing this, you
            avoid errors which are usually hard to spot. Example: if you would like to search for a city named
            <code>Aalborg</code> in the <code>cities</code> table, you should do <code>GET /api/countrylicious/cities?_filter=name%3D%3DAalborg</code>
            (<a href="https://restifydb.com/api/countrylicious/cities?_view=json&_expand=no&_filter=name%3D%3DAalborg" target="_blank"><i class="fa fa-external-link"></i></a>).
            Please note the URL-encoded filter expression which translates into <code>name==Aalborg</code>.
        </p>
    </div>

    <h2 id="pk">Primary key support</h2>
    <div class="block">
        <p>
            As previously stated, in order for restifydb to be able to work with records (both for reading and writing),
            these records need to be uniquely identifiable. This means that the underlying table needs to have a primary
            key defined. This will be used to generate the record's access URL.
        </p>
        <p>
            Here's an example: the <code>countries</code> table is constructed in such a way that it has a primary key
            consisting of the country's identifier. Thus, single countries can be referred to as records:
            <code>GET /api/countrylicious/countries/164</code>
            (<a href="https://restifydb.com/api/countrylicious/countries/164?_view=json" target="_blank"><i class="fa fa-external-link"></i></a>).
        </p>
        <p>
            There are cases, however, in which the primary key is composite - composed by more than one column. In this
            situation restifydb will expose records from such a table by concatenating the composite key's field value.
            As an example: <code>GET /api/datasource/table/21_and_34</code> - the primary key is composed of two columns.
            The field values are in this case, <code>21</code> and <code>34</code>. Please note that when accessing such
            records, the order of the composing fields needs to be respected. The current separator is <code>_and_</code>.
        </p>
    </div>

</div>