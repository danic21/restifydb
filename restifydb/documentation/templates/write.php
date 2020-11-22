<div class="topic" id="write">
    <h1>Modifying data</h1>

    <h2>Creating a record</h2>
    <div class="block">
        <p>
            If you would like to create a new record, you must do a <code>POST</code> on the table you wish to create
            this record specifying the field values in the <code>POST</code> body. The parameter containing these values
            should be called <code>_data</code> and should represent an valid JSON object with members representing
            field names and values representing field values.
        </p>
        <p>
            Here is an example: in order to insert a new record into the <code>cities</code> table, you should do a
            <code>POST /api/countrylicious/cities</code> and set the method's body to
            <code>_data={&quot;id&quot;: 4999, &quot;countryid&quot;: 155, &quot;name&quot;: &quot;Some City in Andorra&quot;, &quot;population&quot;: 100}</code>
            (this should be URL-encoded, please see below).
        </p>
        <p>
            If the operation succeeds, the response will be populated with two important fields:
        </p>
        <ul>
            <li><code>affectedRows</code> - returns the number of rows successfully inserted. In case of success, it
                should be set to <code>1</code>.</li>
            <li>
                <code>lastInsertedValue</code> - if there is a primary key on the table, it will return the value of the
                field representing it in the newly inserted record. This is useful when dealing with auto generated IDs,
                where the database takes care of populated the primary key fields (auto increment, sequence, etc).
            </li>
        </ul>

        <p class="text-danger">
            Please make sure to send the <code>Content-Type: application/x-www-form-urlencoded</code> header when
            creating records. Please do not forget to URL-encode to value of the <code>_data</code> parameter.
        </p>
    </div>


    <h2>Modifying a record</h2>
    <div class="block">
        <p>
            If you would like to modify an existing record, you must do a <code>PUT</code> on the record you wish to
            modify specifying the field values in the <code>PUT</code> body. The parameter containing these values
            should be called <code>_data</code> and should represent an valid JSON object with members representing
            field names and values representing field values.
        </p>

        <p>
            The modify (update) operation is idempotent, meaning it will produce the same result no matter how many times
            it has been invoked.
        </p>

        <p>
            Here is an example: in order to modify a record from the <code>cities</code> table, you should do a
            <code>PUT /api/countrylicious/cities/4999</code> and set the method's body to
            <code>_data={&quot;countryid&quot;: 155, &quot;name&quot;: &quot;Another City in Andorra&quot;, &quot;population&quot;: 101}</code>
            (this should be URL-encoded, please see below). This will modify the city having the unique identifier set
            to <code>4999</code>.
        </p>

        <p class="text-danger">
            Please note that in order to be able to modify records, the underlying table needs to have a primary key
            defined. Please see the <a href="#pk">Primary key support</a> section for additional details.
        </p>

        <p class="text-danger">
            Please make sure to send the <code>Content-Type: application/x-www-form-urlencoded</code> header when
            creating records. Please do not forget to URL-encode to value of the <code>_data</code> parameter.
        </p>
    </div>


    <h2>Deleting a record</h2>
    <div class="block">
        <p>
            Deleting a record in restifydb is easy. All you need to know is the URL uniquely identifying the record and
            issue a <code>DELETE</code> action. For example, if you wish to delete the city of Klagenfurt, identified by
            its id (<code>341</code>), you need to do <code>DELETE /api/countrylicious/cities/341</code>. If the
            operation succeeds, the response will contain a field called <code>affectedRows</code>, showing the number
            of successfully deleted rows.
        </p>

        <p class="text-danger">
            Please note that in order to be able to delete records, the underlying table needs to have a primary key
            defined. Please see the <a href="#pk">Primary key support</a> section for additional details.
        </p>
    </div>

</div>