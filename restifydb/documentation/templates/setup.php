<div class="topic" id="setup">
    <h1>Application setup</h1>


    <h2>Software requirements</h2>
    <div class="block">
        <p>
            restifydb is a PHP web application. It thus needs a typical software stack.
        </p>
        <ul>
            <li>
                Any modern version of <code>Apache Web Server</code> with <code>mod_rewrite</code> support.
                Optionally, if you would like to enable GZip compression, <code>mod_deflate</code> should
                also be enabled.
            </li>
            <li>
                Any version of <code>PHP</code> greater then <code>5.4</code> with <code>FileInfo</code> support |this
                is usually enabled by
                default - the extension is named <code>php_fileinfo</code>).</li>
            <li>
                In order to be able to connect to different database engines, corresponding
                PHP modules need to be installed and configured. I am currently working on offering support
                to any data source for which a PDO extension exists. However, currently, the framework
                supports the following database engines:
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th class="col-md-6">Database engine</th>
                            <th class="col-md-6">PHP extension</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>MySQL through Mysqli</td>
                            <td><code>mysqli</code></td>
                        </tr>
                        <tr>
                            <td>MySQL through PDO</td>
                            <td><code>pdo_mysql</code></td>
                        </tr>
                        <tr>
                            <td>Oracle 10g</td>
                            <td><code>oci8</code></td>
                        </tr>
                        <tr>
                            <td>MSSQL Server</td>
                            <td><code>sqlsrv</code></td>
                        </tr>
                        <tr>
                            <td>SQLite through PDO</td>
                            <td><code>pdo_sqlite</code></td>
                        </tr>
                        <tr>
                            <td>PostgreSQL</td>
                            <td><code>pgsql</code></td>
                        </tr>
                        <tr>
                            <td>PostgreSQL through PDO</td>
                            <td><code>pdo_pgsql</code></td>
                        </tr>
                        <tr>
                            <td>IBM DB2</td>
                            <td><code>ibmdb2</code></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </li>
        </ul>
    </div>


    <h2>Installation procedure</h2>
    <div class="block">
        <p>For the sake of simplicity, we will consider that the URL of the application is
            <code>/api</code>
            (<a href="https://restifydb.com/api/countrylicious?_view=json" target="_blank"><i class="fa fa-external-link"></i></a>).</p>
        <ul>
            <li>
                Download the application. Please go to the <a href="https://restifydb.com/#download" title="download restifydb" target="_blank">download page</a>
                and select the appropriate version.
            </li>
            <li>
                The download package contains both the actual restifydb framework (in the <code>restifydb</code> folder)
                and the restifydb demos (located in the <code>demos</code> folder).
            </li>
            <li>
                Unpack all the framework's files from the installation pack into the web server's document root. It is
                up to you where you place these files. Again, for the sake os simplicity, I am assuming that
                you unpacked these files into the <code>api</code> folder inside the document root folder.
                The way you organise your web application and manage URLs and virtual hosts is beyond the
                scope of this document.
            </li>
            <li>
                *nix systems only: You need to grant the user running PHP write permissions on the following
                two folders: <code>cache</code> and <code>config</code>.
            </li>
            <li>
                Navigate to <code>/api/admin</code>. You will have to log in to the
                administration panel.
            </li>
            <li>
                Login using the default password: <code>admin</code>.
            </li>
            <li>
                Upon successfully logging in, you will be presented with a setup wizard.
            </li>
            <li>Just follow the steps presented in the wizard.</li>
        </ul>
    </div>

    <h2 id="add-ds">Adding data sources</h2>
    <div class="block">
        <p>
            Now that you have configured restifydb, it is time to start adding data sources. This is easily
            doable via the &quot;Edit Data Source&quot; dialog (<code>Configure/Configured Data Sources/Add new
                data source</code>). This allows adding a new data source. You will be able to set table
            permission - this allows exposing only certain tables from the current data source. Fill in all
            the required fields. Make sure the database connection parameters are properly set. There are a
            few considerations here:
        </p>
        <ul>
            <li>When adding a Sqlite data source, you need to only fill in the database name. This should
                contain the absolute file name and path: <code>/home/someuser/myds.db</code></li>
            <li>When adding an Oracle data source, the host name should be contain the hostname, the port
                and the database name: <code>127.0.0.1:1521/db</code>. The user name field will actually
                contain the schema name.</li>
        </ul>
        <p>
            In order to check whether the connection to the data source can be established, open the
            &quot;Configured Data Sources&quot; dialog (<code>Configure/Configured Data Sources</code>). Now
            click the little <i class="fa fa-chain fa-fw"></i> icon next to the desired data source. Once
            the process is done, you will receive an information saying whether the connection could be
            established or not. If this failed, please re-check the connection parameteres.
        </p>
        <p>
            If you open the edit data source dialog again, you will see you are not yet able to set table permissions. This is because the
            metadata information has not yet been retrieved for the selected data source. Reading metadata
            is a slow process which might take minutes in case of large databases. That is why restifydb
            caches this information in order to speed up the process.
        </p>
        <p>
            In order to trigger the re-caching process, open the &quot;Configured Data Sources&quot; dialog
            (<code>Configure/Configured Data Sources</code>). Now click the little <i class="fa fa-refresh fa-fw"></i>
            icon next to the desired data source. Once the process is done, you will receive a message
            confirming the outcome of the operation. The first thing to check for when this fails is whether
            the <code>cache</code> directory is writable by the user running PHP.
        </p>

        <p class="text-danger">
            Please bare in mind to trigger the re-caching mechanism after making changes to the data source
            structure. This is not required when making changes to data.
        </p>
    </div>

    <h2>Configuration options</h2>
    <div class="block">
        <p>
            restifydb allows you to specify both per data-source and per-table visibility options. You can
            change these settings in the &quot;Edit Data Source&quot; dialog for the desired data source. By
            default, access is permitted to every data source and every table. If you mark a data source as
            disabled, clients will no longer be able to connect to it making there applications fail.
        </p>
        <p>
            By default, the framework enables clients to perform all the four data operations (creating,
            reading, updating and deleting data). It is desirable in some cases to limit these operations.
            For this, go to the &quot;General Options&quot; menu (<code>Configure/General Options</code>)
            and specify the operations you wish to disable. Currently, this is a global setting and it is
            not configurable per data source or table.
        </p>
        <p>
            Also, in the aforementioned dialog you can specify the maximum data length when outputting large
            fields (represented by CLOB or TEXT data types). This setting is bypassed when presenting
            entities which represent records.
        </p>
    </div>

    <h2 id="err-log">Error log</h2>
    <div class="block">
        <p>
            This allows the system's administrator to see what went wrong while clients connected to the system. There
            are many cases in which restifydb's error handling mechanism will not be able to cope with the error at hand.
            In this case, a generic error message will be displayed to the user, asking him to contact the administrator.
            A unique (autogenerated) token will be presented to the user. This is stored in the application's log
            toghether with a lot of useful information (the URL)
            Please see the <a href="#errors">Error handling</a> section for additional details.
        </p>
    </div>

    <h2>Technical considerations</h2>
    <div class="block">
        <p>
            The configuration is stored in a Sqlite database inside the <code>/config</code> folder. Due to
            how the configuration is kept, it is not currently possible to easily recover the administrator
            password. If this is lost, you will have to reinitialise restifydb. You can do this by simply
            removing the <code>config/restify_config.db</code> file. Also, I encourage you to always keep
            a backup of this file.
        </p>
        <p>
            It is good practice to protect the administration area by other means as well: IP-based filtering,
            HTTP Basic Authentication (via the <code>mod_auth_basic</code> module), and so on.
        </p>
    </div>
</div>
