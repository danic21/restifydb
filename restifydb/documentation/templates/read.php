<div class="topic" id="read">
    <h1>Reading data</h1>

    <div class="block">
        <p>
            For the sake of simplicity, we will consider that the URL of the application is
            <code>/api</code>.
        </p>
        <p class="text-danger">
            Please note that if you disable the &quot;read operation&quot; from the administrative panel, clients will
            not be able to do read requests.
        </p>
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th class="col-md-4">Operation</th>
                    <th class="col-md-3">Method and URL</th>
                    <th class="col-md-5">Explanation</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>Read the list of configured data sources from the system</td>
                    <td><code>GET /api</code>
                        (<a href="https://restifydb.com/api?_view=json" target="_blank"><i class="fa fa-external-link"></i></a>)
                    </td>
                    <td>
                        Retrieves the list of all the configured data sources in the system. Please note that
                        if you mark a data source as disabled, it will not be accessible nor will it appear
                        in this list.
                    </td>
                </tr>

                <tr>
                    <td>Read the list of tables from a data source</td>
                    <td><code>GET /api/countrylicious</code>
                        (<a href="https://restifydb.com/api/countrylicious?_view=json" target="_blank"><i class="fa fa-external-link"></i></a>)
                    </td>
                    <td>
                        Retrieves the list of all the tables (and views) in the data source. Please note that
                        if you mark a table as disabled, it will not be accessible nor will it appear
                        in this list.
                    </td>
                </tr>

                <tr>
                    <td>Read the list of rows from a table
                        <br>
                        <small>Please also see the next sections of the current chapter for details about paging,
                            filtering, and sorting data.</small>
                    </td>
                    <td><code>GET /api/countrylicious/regions</code>
                        (<a href="https://restifydb.com/api/countrylicious/regions?_view=json" target="_blank"><i class="fa fa-external-link"></i></a>)
                    </td>
                    <td>
                        Retrieves the list of all the tables (and views) in the data source. Please note that
                        if you mark a table as disabled, it will not be accessible nor will it appear
                        in this list.
                    </td>
                </tr>

                <tr>
                    <td>Read a record
                        <br>
                        <small>Please also see the <a href="#pk">Primary key support</a> section. </small>
                    </td>
                    <td><code>GET /api/countrylicious/regions/8</code>
                        (<a href="https://restifydb.com/api/countrylicious/regions/8?_view=json" target="_blank"><i class="fa fa-external-link"></i></a>)
                    </td>
                    <td>
                        Retrieves record with the unique identifier equals to <code>idvalue</code>. Please note that
                        if the parent table is marked as disabled, the record will not be accessible.
                    </td>
                </tr>

                </tbody>
            </table>
        </div>


        <h2 id="query-exp">Query expansion</h2>
        <div class="block">
            <p>
                Query expansion refers to a feature in restifydb which allows to eagerly load matching records from
                tables connected with the current table by the means of foreign key constraints. As an example, if you
                talk have a table containing cities, one possible field in the <code>cities</code> table might be the id
                of the country this city is found in, <code>countryid</code>. If an <code>countries</code> table exists and
                the <code>countryid</code> field in the <code>cities</code> table is connected to the <code>countryid</code>
                field in the <code>countries</code> table via a foreign key constraint, this means that when fetching rows
                or records from <code>cities</code>, the framework could automatically load the corresponding row or
                record from the <code>countries</code> table
                (<a href="https://restifydb.com/api/countrylicious/cities?_view=json&_expand=yes" target="_blank"><i class="fa fa-external-link"></i></a>
                - please see the <code>outReference</code> property of the <code>countryId</code> field).
                Thus, the composite record will be retrieved containing all
                the needed information in one request (no need to make a separate request to get the artist information
                from the <code>artists</code> table).
            </p
            <p>
                The query expansion mechanism is enabled by default. It can be controlled with the <code>_expand</code>
                URL parameter by specifying one of the following values: <code>yes</code> and <code>no</code> to enable
                it, respectively disable it.
            </p>
            <p>
                Example: <code>GET /api/countrylicious/cities?_expand=no</code>
                (<a href="https://restifydb.com/api/countrylicious/cities?_view=json&_expand=no" target="_blank"><i class="fa fa-external-link"></i></a>)
                will retrieve the rows from the
                <code>cities</code> table without any information about the country (except for the <code>countryid</code>
                which is part of the <code>cities</code> table).
            </p>
        </div>


        <h2 id="inward">Inward references</h2>
        <div class="block">
            <p>
                The opposite of query expansion is called inward referencing. When a foreign key constraint is defined
                between tables <code>cities</code> and <code>countries</code> (meaning that a city points to / belongs to
                a country), it is said that the <code>countries</code>
                (<a href="https://restifydb.com/api/countrylicious/countries?_view=json" target="_blank"><i class="fa fa-external-link"></i></a>
                - please see the <code>countryId</code> field) table has an inward reference from the
                <code>cities</code> table. This is represented in restifydb via the <code>inRreferences</code>
                collection property attached to every field which has such pointers. This is represented by a collection
                because multiple foreign inward references might point to the same field.
            </p>
        </div>


        <h2>Output structure</h2>
        <div class="block">
            <p>
                The output of the data-access web services exposed by restifydb is easy to understand. The output will
                have a similar structure every time, irrespective on the entity being presented. The root property is
                always called <code>restifydb</code>. This will always contain three properties:
            </p>
            <ul>
                <li><code>self</code> - defining the properties of the current entity, name and access URL.</li>
                <li><code>rows</code> - a collection of child entities. Each row has a <code>values</code> property.</li>
                <li><code>rowCount</code> - the total number of child entities. When reading tables, this is very
                    important as it represents the total number of rows retrieved when applying the curren filtering
                    criteria.</li>
            </ul>
            <p>
                When the type entity of the entity being retrieved is other than <code>system</code>, a <code>parent</code>
                property will always be present. This defines the property of the parent entity, name and access URL.
            </p>
            <p>
                When reading data from tables, restifydb will expose two convenience properties:
            </p>
            <ul>
                <li><code>ownFields</code> - returns the comma-separated names of all the fields from the current table.
                    This is useful for avoiding introspections on the output structure (XML or JSON), thus optimizing
                    your code.
                </li>
                <li>
                    <code>foreignFields</code> - when <a href="#query-exp">query expansion</a> is enabled and
                    connections to other tables exist, restifydb will give the list of comma-separated fields names from
                    the connected tables. This is useful for avoiding introspections on the output structure (XML or
                    JSON), thus optimizing your code. This is expressed as a collection, as multiple fields might have
                    connections.
                </li>
            </ul>

            <div>
                <pre id="output"><ul class="first"><li><i class="fa fa-minus-square-o toggler"></i> <span class="node">restify</span><div class="collapseable"><ul><li><i class="fa fa-minus-square-o toggler"></i> <span class="node">self</span><div class="collapseable"><ul><li><i class="fa fa-square-o"></i> <span class="key">href</span> = <a href="https://restifydb.com/demos/viewer/index.php?url=aHR0cHM6Ly9yZXN0aWZ5ZGIuY29tL2FwaS9jb3VudHJ5bGljaW91cy9jb3VudHJpZXMvP192aWV3PWpzb24mX2V4cGFuZD15ZXM" title="Click to follow" target="_blank">&quot;https://restifydb.com/api/countrylicious/countries/?_view=json&amp;_expand=yes&quot;</a></li><li><i class="fa fa-square-o"></i> <span class="key">name</span> = <span class="value">&quot;countries&quot;</span></li></ul></div></li><li><i class="fa fa-minus-square-o toggler"></i> <span class="node">parent</span><div class="collapseable"><ul><li><i class="fa fa-square-o"></i> <span class="key">href</span> = <a href="https://restifydb.com/demos/viewer/index.php?url=aHR0cHM6Ly9yZXN0aWZ5ZGIuY29tL2FwaS9jb3VudHJ5bGljaW91cy8_X3ZpZXc9anNvbiZfZXhwYW5kPXllcw" title="Click to follow" target="_blank">&quot;https://restifydb.com/api/countrylicious/?_view=json&amp;_expand=yes&quot;</a></li><li><i class="fa fa-square-o"></i> <span class="key">name</span> = <span class="value">&quot;Country Information&quot;</span></li></ul></div></li><li><i class="fa fa-square-o"></i> <span class="key">rowCount</span> = <span class="value">&quot;195&quot;</span></li><li><i class="fa fa-square-o"></i> <span class="key">start</span> = <span class="value">&quot;120&quot;</span></li><li><i class="fa fa-square-o"></i> <span class="key">offset</span> = <span class="value">&quot;2&quot;</span></li><li><i class="fa fa-square-o"></i> <span class="key">currentPage</span> = <span class="value">&quot;61&quot;</span></li><li><i class="fa fa-square-o"></i> <span class="key">pageCount</span> = <span class="value">&quot;98&quot;</span></li><li><i class="fa fa-minus-square-o toggler"></i> <span class="node">nextPage</span><div class="collapseable"><ul><li><i class="fa fa-square-o"></i> <span class="key">href</span> = <a href="https://restifydb.com/demos/viewer/index.php?url=aHR0cHM6Ly9yZXN0aWZ5ZGIuY29tL2FwaS9jb3VudHJ5bGljaW91cy9jb3VudHJpZXMvP19zdGFydD0xMjImX2NvdW50PTImX2V4cGFuZD15ZXMmX3ZpZXc9anNvbg" title="Click to follow" target="_blank">&quot;https://restifydb.com/api/countrylicious/countries/?_start=122&amp;_count=2&amp;_expand=yes&amp;_view=json&quot;</a></li></ul></div></li><li><i class="fa fa-minus-square-o toggler"></i> <span class="node">previousPage</span><div class="collapseable"><ul><li><i class="fa fa-square-o"></i> <span class="key">href</span> = <a href="https://restifydb.com/demos/viewer/index.php?url=aHR0cHM6Ly9yZXN0aWZ5ZGIuY29tL2FwaS9jb3VudHJ5bGljaW91cy9jb3VudHJpZXMvP19zdGFydD0xMTgmX2NvdW50PTImX2V4cGFuZD15ZXMmX3ZpZXc9anNvbg" title="Click to follow" target="_blank">&quot;https://restifydb.com/api/countrylicious/countries/?_start=118&amp;_count=2&amp;_expand=yes&amp;_view=json&quot;</a></li></ul></div></li><li><i class="fa fa-minus-square-o toggler"></i> <span class="node">firstPage</span><div class="collapseable"><ul><li><i class="fa fa-square-o"></i> <span class="key">href</span> = <a href="https://restifydb.com/demos/viewer/index.php?url=aHR0cHM6Ly9yZXN0aWZ5ZGIuY29tL2FwaS9jb3VudHJ5bGljaW91cy9jb3VudHJpZXMvP19zdGFydD0wJl9jb3VudD0yJl9leHBhbmQ9eWVzJl92aWV3PWpzb24" title="Click to follow" target="_blank">&quot;https://restifydb.com/api/countrylicious/countries/?_start=0&amp;_count=2&amp;_expand=yes&amp;_view=json&quot;</a></li></ul></div></li><li><i class="fa fa-minus-square-o toggler"></i> <span class="node">lastPage</span><div class="collapseable"><ul><li><i class="fa fa-square-o"></i> <span class="key">href</span> = <a href="https://restifydb.com/demos/viewer/index.php?url=aHR0cHM6Ly9yZXN0aWZ5ZGIuY29tL2FwaS9jb3VudHJ5bGljaW91cy9jb3VudHJpZXMvP19zdGFydD0xOTQmX2NvdW50PTImX2V4cGFuZD15ZXMmX3ZpZXc9anNvbg" title="Click to follow" target="_blank">&quot;https://restifydb.com/api/countrylicious/countries/?_start=194&amp;_count=2&amp;_expand=yes&amp;_view=json&quot;</a></li></ul></div></li><li><i class="fa fa-square-o"></i> <span class="key">ownFields</span> = <span class="value">&quot;countryid,regionid,isocode,name,link,history,fullname,capital,population,area,density,location,gdp,climate,currency,currency_code&quot;</span></li><li><i class="fa fa-minus-square-o toggler"></i> <span class="node">foreignFields</span><div class="collapseable"><ul><li><i class="fa fa-minus-square-o toggler"></i> <span class="node">regionid</span><div class="collapseable"><ul><li><i class="fa fa-square-o"></i> <span class="key">regions</span> = <span class="value">&quot;regionid,name,link&quot;</span></li></ul></div></li></ul></div></li><li><i class="fa fa-minus-square-o toggler"></i> <span class="node">rows</span><div class="collapseable"><ul><li><i class="fa fa-minus-square-o toggler"></i> <span class="node">0</span><div class="collapseable"><ul><li><i class="fa fa-square-o"></i> <span class="key">href</span> = <a href="https://restifydb.com/demos/viewer/index.php?url=aHR0cHM6Ly9yZXN0aWZ5ZGIuY29tL2FwaS9jb3VudHJ5bGljaW91cy9jb3VudHJpZXMvMTYzP192aWV3PWpzb24mX2V4cGFuZD15ZXM" title="Click to follow" target="_blank">&quot;https://restifydb.com/api/countrylicious/countries/163?_view=json&amp;_expand=yes&quot;</a></li><li><i class="fa fa-minus-square-o toggler"></i> <span class="node">values</span><div class="collapseable"><ul><li><i class="fa fa-minus-square-o toggler"></i> <span class="node">countryid</span><div class="collapseable"><ul><li><i class="fa fa-square-o"></i> <span class="key">value</span> = <span class="value">&quot;163&quot;</span></li><li><i class="fa fa-minus-square-o toggler"></i> <span class="node">inRreferences</span><div class="collapseable"><ul><li><i class="fa fa-minus-square-o toggler"></i> <span class="node">0</span><div class="collapseable"><ul><li><i class="fa fa-square-o"></i> <span class="key">name</span> = <span class="value">&quot;cities&quot;</span></li><li><i class="fa fa-square-o"></i> <span class="key">href</span> = <a href="https://restifydb.com/demos/viewer/index.php?url=aHR0cHM6Ly9yZXN0aWZ5ZGIuY29tL2FwaS9jb3VudHJ5bGljaW91cy9jaXRpZXMvP19maWx0ZXI9Y291bnRyeWlkJTNEJTNEMTYzJl92aWV3PWpzb24mX2V4cGFuZD15ZXM" title="Click to follow" target="_blank">&quot;https://restifydb.com/api/countrylicious/cities/?_filter=countryid%3D%3D163&amp;_view=json&amp;_expand=yes&quot;</a></li></ul></div></li></ul></div></li></ul></div></li><li><i class="fa fa-minus-square-o toggler"></i> <span class="node">regionid</span><div class="collapseable"><ul><li><i class="fa fa-square-o"></i> <span class="key">value</span> = <span class="value">&quot;8&quot;</span></li><li><i class="fa fa-minus-square-o toggler"></i> <span class="node">outReference</span><div class="collapseable"><ul><li><i class="fa fa-square-o"></i> <span class="key">name</span> = <span class="value">&quot;regions&quot;</span></li><li><i class="fa fa-square-o"></i> <span class="key">href</span> = <a href="https://restifydb.com/demos/viewer/index.php?url=aHR0cHM6Ly9yZXN0aWZ5ZGIuY29tL2FwaS9jb3VudHJ5bGljaW91cy9yZWdpb25zLzg_X3ZpZXc9anNvbiZfZXhwYW5kPXllcw" title="Click to follow" target="_blank">&quot;https://restifydb.com/api/countrylicious/regions/8?_view=json&amp;_expand=yes&quot;</a></li><li><i class="fa fa-minus-square-o toggler"></i> <span class="node">values</span><div class="collapseable"><ul><li><i class="fa fa-square-o"></i> <span class="key">regionid</span> = <span class="value">&quot;8&quot;</span></li><li><i class="fa fa-square-o"></i> <span class="key">name</span> = <span class="value">&quot;Europe&quot;</span></li><li><i class="fa fa-square-o"></i> <span class="key">link</span> = <span class="value">&quot;europe&quot;</span></li></ul></div></li></ul></div></li></ul></div></li><li><i class="fa fa-minus-square-o toggler"></i> <span class="node">isocode</span><div class="collapseable"><ul><li><i class="fa fa-square-o"></i> <span class="key">value</span> = <span class="value">&quot;CZE&quot;</span></li></ul></div></li><li><i class="fa fa-minus-square-o toggler"></i> <span class="node">name</span><div class="collapseable"><ul><li><i class="fa fa-square-o"></i> <span class="key">value</span> = <span class="value">&quot;Czech Republic&quot;</span></li></ul></div></li><li><i class="fa fa-minus-square-o toggler"></i> <span class="node">link</span><div class="collapseable"><ul><li><i class="fa fa-square-o"></i> <span class="key">value</span> = <span class="value">&quot;czech-republic&quot;</span></li></ul></div></li><li><i class="fa fa-minus-square-o toggler"></i> <span class="node">history</span><div class="collapseable"><ul><li><i class="fa fa-square-o"></i> <span class="key">value</span> = <span class="value">&quot;At the close of World War I, the Czechs and Slovaks of the former Austro-Hungarian Empire merged to form Czechoslovakia. During the interwar years, having rejected a federal system, the new country's predominantly Czech leaders were frequently preoccupied with meeting the increasingly strident demands of other ethnic minorities within the republic, most notably the Slovaks, the Sudeten Germans, and the Ruthenians (Ukrainians). On the eve of World War II, Nazi Germany occupied the territory that today comprises the Czech Republic and Slovakia became an independent state allied with Germany. After the war, a reunited but truncated Czechoslovakia (less Ruthenia) fell within the Soviet sphere of influence. In 1968, an invasion by Warsaw Pact troops ended the efforts of the country's leaders to liberalize communist rule and create &amp;amp;quot;socialism with a human face,&amp;amp;quot; ushering in a period of repression known as &amp;amp;quot;normalization.&amp;amp;quot; The peaceful &amp;amp;quot;Velvet Revolution&amp;amp;quot; swept the Communist Party from power at the end of 1989 and inaugurated a return to democratic rule and a market economy. On 1 January 1993, the country underwent a nonviolent &amp;amp;quot;velvet divorce&amp;amp;quot; into its two national components, the Czech Republic and Slovakia. The Czech Republic joined NATO in 1999 and the European Union in 2004.&quot;</span></li></ul></div></li><li><i class="fa fa-minus-square-o toggler"></i> <span class="node">fullname</span><div class="collapseable"><ul><li><i class="fa fa-square-o"></i> <span class="key">value</span> = <span class="value">&quot;Czech Republic&quot;</span></li></ul></div></li><li><i class="fa fa-minus-square-o toggler"></i> <span class="node">capital</span><div class="collapseable"><ul><li><i class="fa fa-square-o"></i> <span class="key">value</span> = <span class="value">&quot;Prague&quot;</span></li></ul></div></li><li><i class="fa fa-minus-square-o toggler"></i> <span class="node">population</span><div class="collapseable"><ul><li><i class="fa fa-square-o"></i> <span class="key">value</span> = <span class="value">&quot;10627448&quot;</span></li></ul></div></li><li><i class="fa fa-minus-square-o toggler"></i> <span class="node">area</span><div class="collapseable"><ul><li><i class="fa fa-square-o"></i> <span class="key">value</span> = <span class="value">&quot;78867&quot;</span></li></ul></div></li><li><i class="fa fa-minus-square-o toggler"></i> <span class="node">density</span><div class="collapseable"><ul><li><i class="fa fa-square-o"></i> <span class="key">value</span> = <span class="value">&quot;134.75&quot;</span></li></ul></div></li><li><i class="fa fa-minus-square-o toggler"></i> <span class="node">location</span><div class="collapseable"><ul><li><i class="fa fa-square-o"></i> <span class="key">value</span> = <span class="value">&quot;Central Europe, between Germany, Poland, Slovakia, and Austria&quot;</span></li></ul></div></li><li><i class="fa fa-minus-square-o toggler"></i> <span class="node">gdp</span><div class="collapseable"><ul><li><i class="fa fa-square-o"></i> <span class="key">value</span> = <span class="value">&quot;285599989760&quot;</span></li></ul></div></li><li><i class="fa fa-minus-square-o toggler"></i> <span class="node">climate</span><div class="collapseable"><ul><li><i class="fa fa-square-o"></i> <span class="key">value</span> = <span class="value">&quot;temperate; cool summers; cold, cloudy, humid winters&quot;</span></li></ul></div></li><li><i class="fa fa-minus-square-o toggler"></i> <span class="node">currency</span><div class="collapseable"><ul><li><i class="fa fa-square-o"></i> <span class="key">value</span> = <span class="value">&quot;koruny (CZK)&quot;</span></li></ul></div></li><li><i class="fa fa-minus-square-o toggler"></i> <span class="node">currency_code</span><div class="collapseable"><ul><li><i class="fa fa-square-o"></i> <span class="key">value</span> = <span class="value">&quot;CZK&quot;</span></li></ul></div></li></ul></div></li></ul></div></li><li><i class="fa fa-minus-square-o toggler"></i> <span class="node">1</span><div class="collapseable"><ul><li><i class="fa fa-square-o"></i> <span class="key">href</span> = <a href="https://restifydb.com/demos/viewer/index.php?url=aHR0cHM6Ly9yZXN0aWZ5ZGIuY29tL2FwaS9jb3VudHJ5bGljaW91cy9jb3VudHJpZXMvMTY0P192aWV3PWpzb24mX2V4cGFuZD15ZXM" title="Click to follow" target="_blank">&quot;https://restifydb.com/api/countrylicious/countries/164?_view=json&amp;_expand=yes&quot;</a></li><li><i class="fa fa-minus-square-o toggler"></i> <span class="node">values</span><div class="collapseable"><ul><li><i class="fa fa-minus-square-o toggler"></i> <span class="node">countryid</span><div class="collapseable"><ul><li><i class="fa fa-square-o"></i> <span class="key">value</span> = <span class="value">&quot;164&quot;</span></li><li><i class="fa fa-minus-square-o toggler"></i> <span class="node">inRreferences</span><div class="collapseable"><ul><li><i class="fa fa-minus-square-o toggler"></i> <span class="node">0</span><div class="collapseable"><ul><li><i class="fa fa-square-o"></i> <span class="key">name</span> = <span class="value">&quot;cities&quot;</span></li><li><i class="fa fa-square-o"></i> <span class="key">href</span> = <a href="https://restifydb.com/demos/viewer/index.php?url=aHR0cHM6Ly9yZXN0aWZ5ZGIuY29tL2FwaS9jb3VudHJ5bGljaW91cy9jaXRpZXMvP19maWx0ZXI9Y291bnRyeWlkJTNEJTNEMTY0Jl92aWV3PWpzb24mX2V4cGFuZD15ZXM" title="Click to follow" target="_blank">&quot;https://restifydb.com/api/countrylicious/cities/?_filter=countryid%3D%3D164&amp;_view=json&amp;_expand=yes&quot;</a></li></ul></div></li></ul></div></li></ul></div></li><li><i class="fa fa-minus-square-o toggler"></i> <span class="node">regionid</span><div class="collapseable"><ul><li><i class="fa fa-square-o"></i> <span class="key">value</span> = <span class="value">&quot;8&quot;</span></li><li><i class="fa fa-minus-square-o toggler"></i> <span class="node">outReference</span><div class="collapseable"><ul><li><i class="fa fa-square-o"></i> <span class="key">name</span> = <span class="value">&quot;regions&quot;</span></li><li><i class="fa fa-square-o"></i> <span class="key">href</span> = <a href="https://restifydb.com/demos/viewer/index.php?url=aHR0cHM6Ly9yZXN0aWZ5ZGIuY29tL2FwaS9jb3VudHJ5bGljaW91cy9yZWdpb25zLzg_X3ZpZXc9anNvbiZfZXhwYW5kPXllcw" title="Click to follow" target="_blank">&quot;https://restifydb.com/api/countrylicious/regions/8?_view=json&amp;_expand=yes&quot;</a></li><li><i class="fa fa-minus-square-o toggler"></i> <span class="node">values</span><div class="collapseable"><ul><li><i class="fa fa-square-o"></i> <span class="key">regionid</span> = <span class="value">&quot;8&quot;</span></li><li><i class="fa fa-square-o"></i> <span class="key">name</span> = <span class="value">&quot;Europe&quot;</span></li><li><i class="fa fa-square-o"></i> <span class="key">link</span> = <span class="value">&quot;europe&quot;</span></li></ul></div></li></ul></div></li></ul></div></li><li><i class="fa fa-minus-square-o toggler"></i> <span class="node">isocode</span><div class="collapseable"><ul><li><i class="fa fa-square-o"></i> <span class="key">value</span> = <span class="value">&quot;DNK&quot;</span></li></ul></div></li><li><i class="fa fa-minus-square-o toggler"></i> <span class="node">name</span><div class="collapseable"><ul><li><i class="fa fa-square-o"></i> <span class="key">value</span> = <span class="value">&quot;Denmark&quot;</span></li></ul></div></li><li><i class="fa fa-minus-square-o toggler"></i> <span class="node">link</span><div class="collapseable"><ul><li><i class="fa fa-square-o"></i> <span class="key">value</span> = <span class="value">&quot;denmark&quot;</span></li></ul></div></li><li><i class="fa fa-minus-square-o toggler"></i> <span class="node">history</span><div class="collapseable"><ul><li><i class="fa fa-square-o"></i> <span class="key">value</span> = <span class="value">&quot;Once the seat of Viking raiders and later a major north European power, Denmark has evolved into a modern, prosperous nation that is participating in the general political and economic integration of Europe. It joined NATO in 1949 and the EEC (now the EU) in 1973. However, the country has opted out of certain elements of the European Union's Maastricht Treaty, including the European Economic and Monetary Union (EMU), European defense cooperation, and issues concerning certain justice and home affairs.&quot;</span></li></ul></div></li><li><i class="fa fa-minus-square-o toggler"></i> <span class="node">fullname</span><div class="collapseable"><ul><li><i class="fa fa-square-o"></i> <span class="key">value</span> = <span class="value">&quot;Kingdom of Denmark&quot;</span></li></ul></div></li><li><i class="fa fa-minus-square-o toggler"></i> <span class="node">capital</span><div class="collapseable"><ul><li><i class="fa fa-square-o"></i> <span class="key">value</span> = <span class="value">&quot;Copenhagen&quot;</span></li></ul></div></li><li><i class="fa fa-minus-square-o toggler"></i> <span class="node">population</span><div class="collapseable"><ul><li><i class="fa fa-square-o"></i> <span class="key">value</span> = <span class="value">&quot;5569077&quot;</span></li></ul></div></li><li><i class="fa fa-minus-square-o toggler"></i> <span class="node">area</span><div class="collapseable"><ul><li><i class="fa fa-square-o"></i> <span class="key">value</span> = <span class="value">&quot;43094&quot;</span></li></ul></div></li><li><i class="fa fa-minus-square-o toggler"></i> <span class="node">density</span><div class="collapseable"><ul><li><i class="fa fa-square-o"></i> <span class="key">value</span> = <span class="value">&quot;129.229995728&quot;</span></li></ul></div></li><li><i class="fa fa-minus-square-o toggler"></i> <span class="node">location</span><div class="collapseable"><ul><li><i class="fa fa-square-o"></i> <span class="key">value</span> = <span class="value">&quot;Northern Europe, bordering the Baltic Sea and the North Sea, on a peninsula north of Germany (Jutland); also includes several major islands (Sjaelland, Fyn, and Bornholm)&quot;</span></li></ul></div></li><li><i class="fa fa-minus-square-o toggler"></i> <span class="node">gdp</span><div class="collapseable"><ul><li><i class="fa fa-square-o"></i> <span class="key">value</span> = <span class="value">&quot;211300007936&quot;</span></li></ul></div></li><li><i class="fa fa-minus-square-o toggler"></i> <span class="node">climate</span><div class="collapseable"><ul><li><i class="fa fa-square-o"></i> <span class="key">value</span> = <span class="value">&quot;temperate; humid and overcast; mild, windy winters and cool summers&quot;</span></li></ul></div></li><li><i class="fa fa-minus-square-o toggler"></i> <span class="node">currency</span><div class="collapseable"><ul><li><i class="fa fa-square-o"></i> <span class="key">value</span> = <span class="value">&quot;Danish kroner (DKK)&quot;</span></li></ul></div></li><li><i class="fa fa-minus-square-o toggler"></i> <span class="node">currency_code</span><div class="collapseable"><ul><li><i class="fa fa-square-o"></i> <span class="key">value</span> = <span class="value">&quot;DKK&quot;</span></li></ul></div></li></ul></div></li></ul></div></li></ul></div></li></ul></div></li></ul></pre>
            </div>

            <p>
                The XML structure is fairly similar.
                The only semantic difference between the JSON and XML versions is the fact that XML does not support
                unnamed nodes (the equivalent of JSON arrays). In this case, the serialisation engine performs a small
                trick and replaces the unnamed nodes with nodes named as the singular of the parent node. For example,
                the <code>&lt;rows&gt;</code> parent node will contain subnodes named <code>&lt;row&gt;</code>.
            </p>
        </div>


        <h2>Paging data</h2>
        <div class="block">
            <p>
                restifydb will automatically help clients in paging data. It does so by providing hypermedia links to
                paging resources such as the first and last page, previous and next page. Paging is only enabled when
                the row count is greater than the current page size.
            </p>
            <p>
                In the context of paging, the output will contain several additional properties:
            </p>
            <ul>
                <li><code>rowCount</code> - the total number of rows available</li>
                <li><code>currentPage</code> - the number of the page currently being presented</li>
                <li><code>pageCount</code> - the total number of pages. This is computed by dividing the
                    <code>rowCount</code> to <code>offset</code>.</li>
                <li><code>nextPage</code>, <code>previousPage</code>, <code>firstPage</code> and <code>lastPage</code>
                    represent the cursors needed to navigate through the entire rows collection. The are expressed via
                    URL resources.
                </li>
            </ul>
            <p>
                When reading table data without specifying URL control parameters, the framework will by default
                retrieve only the first twenty rows. If you wish to control this behaviour, you can use the following
                URL parameters:
            </p>
            <ul>
                <li>
                    <code>_start</code>: this parameter specifies the index of the first row to fetch. The default value
                    is <code>0</code>. This parameter useful for paging data.
                </li>
                <li>
                    <code>_count</code>: this parameter controls how many rows are being fetched. The default value is
                    <code>20</code>. The maximum value for this parameter is <code>50</code>.
                </li>
            </ul>
            <p>
                Example: if you want to fetch 10 rows starting with the row with the index 940 from the cities table, you
                can do this: <code>GET /api/countrylicious/cities?_start=20&amp;_count=940</code>
                (<a href="https://restifydb.com/api/countrylicious/cities?_view=json&_start=940&_count=10&_expand=no" target="_blank"><i class="fa fa-external-link"></i></a>).
            </p>
        </div>

        <h2>Selecting which fields should be retrieved</h2>
        <div class="block">
            <p>
                By default, when making a table read call, restifydb will fetch every single field in the table, plus
                every single field in all of the connected tables, if query expansion is turned on. This behaviour can
                be controlled using the <code>_fields</code> URL parameter. The syntax of the <code>_fields</code>
                parameters is straightforward: <code>field1[,field2,[,field3...]]</code>. All you have to do is specify
                which fields the system needs to fetch.
            </p>

            <p>
                Example: in order to retrieve only the country names and its capitals, you need do
                <code>GET /api/countrylicious/countries?_fields=name,capital</code>
                (<a href="https://restifydb.com/api/countrylicious/countries?_view=json&_fields=name,capital" target="_blank"><i class="fa fa-external-link"></i></a>).
            </p>

            <p class="text-danger">
                Please note that the <code>field</code> (referred in the fields expressions) must be a valid field
                from the table being read. Currently, restifydb does not allow limiting foreign fields. When using the
                <code>_fields</code> parameter, the query expansion is automatically disabled.
            </p>
        </div>

        <h2>Filtering data</h2>
        <div class="block">
            <p>
                When reading table rows, clients can apply filtering, thus limiting the number of rows being fetched.
                This is controlled using the <code>_filter</code> URL parameter. The filtering mechanism supports
                concatenating filtering conditions using the <code>&amp;&amp;</code> and <code>||</code> logical
                operators (corresponding to <code>AND</code> and <code>OR</code> operators). A filtering condition is a
                boolean expression of the form <code>field[operator]value</code>. There are several operator supported
                in restifydb:
            </p>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th class="col-md-2">Operator (URL-encoded)</th>
                        <th class="col-md-2">Meaning</th>
                        <th class="col-md-8">Example</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><code>==<br>(%3D%3D)</code></td>
                        <td>Equality operator
                            <br><code>field==value</code>
                        </td>
                        <td>
                            Returns <code>TRUE</code> if the value of the field matches exactly the value specified in the filter.
                            Example: <code>GET /api/countrylicious/countries?_filter=name%3D%3DBelgium</code>
                            (<a href="https://restifydb.com/api/countrylicious/countries?_view=json&_filter=name%3D%3DBelgium" target="_blank"><i class="fa fa-external-link"></i></a>)
                            - searches the <code>countries</code> table for a country named <code>Belgium</code>.
                        </td>
                    </tr>
                    <tr>
                        <td><code>!=<br>(%21%3D)</code></td>
                        <td>Inequality operator
                            <br><code>field!=value</code>
                        </td>
                        <td>
                            Returns <code>TRUE</code> if the value of the field does not match the value specified in the filter.
                            Example: <code>GET /api/countrylicious/countries?_filter=regionid%21%3D8</code>
                            (<a href="https://restifydb.com/api/countrylicious/countries?_view=json&_filter=regionid%21%3D8" target="_blank"><i class="fa fa-external-link"></i></a>)
                            - searches for <code>countries</code> that are not in <code>Europe</code>.
                        </td>
                    </tr>
                    <tr>
                        <td><code>&lt;<br>(%3C)</code></td>
                        <td>Lower-then operator
                            <br><code>field&lt;value</code>
                        </td>
                        <td>
                            Returns <code>TRUE</code> if the value of the field is lower then the value specified in the filter.
                            Example: <code>GET /api/countrylicious/countries?_filter=population%3C10000</code>
                            (<a href="https://restifydb.com/api/countrylicious/cities?_view=json&_filter=population%3C10000" target="_blank"><i class="fa fa-external-link"></i></a>)
                            - searches for <code>cities</code> that have the population lower then <code>10,000</code>
                            inhabitants.
                        </td>
                    </tr>
                    <tr>
                        <td><code>&lt;=<br>(%3C%3D)</code></td>
                        <td>Lower-then or equal operator
                            <br><code>field&lt;=value</code>
                        </td>
                        <td>
                            Returns <code>TRUE</code> if the value of the field is lower or equal to the value specified in the filter.
                            Example: <code>GET /api/countrylicious/countries?_filter=area%3C%3D181</code>
                            (<a href="https://restifydb.com/api/countrylicious/countries?_view=json&_filter=area%3C%3D181" target="_blank"><i class="fa fa-external-link"></i></a>)
                            - searches for <code>countries</code> that have the area lower then or equal to <code>181</code>
                            sq km.
                        </td>
                    </tr>
                    <tr>
                        <td><code>&gt;<br>(%3E)</code></td>
                        <td>Greater-then operator
                            <br><code>field&gt;value</code>
                        </td>
                        <td>
                            Returns <code>TRUE</code> if the value of the field is greater then the value specified in the filter.
                            Example: <code>GET /api/countrylicious/cities?_filter=population%3E10000000</code>
                            (<a href="https://restifydb.com/api/countrylicious/cities?_view=json&_filter=population%3E10000000" target="_blank"><i class="fa fa-external-link"></i></a>)
                            - searches for <code>cities</code> that have the population greater then <code>10,000,0000</code>
                            inhabitants.
                        </td>
                    </tr>
                    <tr>
                        <td><code>&gt;=<br>(%3E%3D)</code></td>
                        <td>Greater-then or equal operator
                            <br><code>field&gt;=value</code>
                        </td>
                        <td>
                            Returns <code>TRUE</code> if the value of the field is greater then or equal to the value specified in the filter.
                            Example: <code>GET /api/countrylicious/countries?_filter=area%3E%3D9984670</code>
                            (<a href="https://restifydb.com/api/countrylicious/countries?_view=json&_filter=area%3E%3D9984670" target="_blank"><i class="fa fa-external-link"></i></a>)
                            - searches for <code>countries</code> that have the area greater then or equal to <code>998,4670</code>
                            inhabitants.
                        </td>
                    </tr>
                    <tr>
                        <td><code>~~<br>(%7E%7)</code></td>
                        <td>Contains
                            <br><code>field~~value</code>
                        </td>
                        <td>
                            Returns <code>TRUE</code> if the value of the contains the value specified in the filter.
                            This is the equivalent of the SQL <code>LIKE</code> operator.
                            Example: <code>GET /api/countrylicious/countries/?_filter=name%7E%7Eunited</code>
                            (<a href="https://restifydb.com/api/countrylicious/countries?_view=json&_filter=name%7E%7Eunited" target="_blank"><i class="fa fa-external-link"></i></a>)
                            - searches for all <code>countries</code> containing <code>united</code> in their name.
                        </td>
                    </tr>
                    </tbody>
                </table>

                <p class="text-danger">
                    Please note that the <code>field</code> (referred in the filter expressions) must be a valid field
                    from the table being read. Currently, restifydb does not allow filtering on foreign fields, even
                    when query expansion is enabled.
                </p>

                <p class="text-danger">
                    Please do not forget to URL-encode the <code>_filter</code> parameter's value when making calls to the
                    web services.
                </p>

                <p>
                    The filtering conditions can be combined in any way. However, as the framework does not currently
                    support paranthesis in the filter expression, it does not make much sense combining <code>AND</code>
                    and <code>OR</code> logical operators. However, chaining <code>AND</code>s or <code>OR</code>s makes
                    perfect sense. Here's a more complex example for filtering: <code>area&gt;500000&amp;&amp;population&gt;50000000&amp;&amp;regionid==8</code>
                    (<a href="https://restifydb.com/api/countrylicious/countries?_view=json&_filter=area>500000%26%26population>50000000%26%26regionid%3D%3D8" target="_blank"><i class="fa fa-external-link"></i></a>)
                    - this searches for all the European countries which have an area larger then 500,000 sq km
                    and a population of more then 50,000,000 inhabitants.
                </p>
            </div>


        </div>

        <h2>Sorting data</h2>
        <div class="block">
            <p>
                Sorting rows in using restifydb is easy. All you need to do is use the <code>_sort</code> URL parameter.
                The syntax for this parameter is similar to the one of the <code>ORDER BY</code> SQL command
                - the two behave alike. The syntax of the <code>_sort</code> parameter is:
                <code>field1[ ASC|DESC][,field2[ ASC|DESC]...]</code>. The sorting direction can be controlled by the
                <code>ASC</code> or <code>DESC</code> keywords which stand for &quot;ascending&quot; and
                &quot;descending&quot;. By default, the sorting order is ascending - when the sort direction is not specified.
                The filter conditions can be chained in order to specify multiple sorting criteria.
            </p>
            <p>
                Example: if you would like to get countries sorted by continent and in descending order by area, you should
                call <code>GET /api/countrylicious/countries?_sort=regionid+asc%2Carea+desc</code>
                (<a href="https://restifydb.com/api/countrylicious/countries?_view=json&_sort=regionid+asc%2Carea+desc" target="_blank"><i class="fa fa-external-link"></i></a>).
            </p>
            <p class="text-danger">
                Please note that the <code>field</code> (referred in the sorting expressions) must be a valid field
                from the table being read. Currently, restifydb does not allow sorting on foreign fields, even
                when query expansion is enabled.
            </p>

            <p class="text-danger">
                Please do not forget to URL-encode the <code>_sort</code> parameter's value when making calls to the
                web services.
            </p>
        </div>
    </div>
</div>
