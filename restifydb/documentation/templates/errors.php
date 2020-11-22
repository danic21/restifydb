<div class="topic" id="errors">
    <h1>Error handling</h1>


    <h2>Overview</h2>
    <div class="block">
        There are two types of exceptions which might occur in restifydb:
        <ul>
            <li><code>checked</code> - these map to foreseeable situations in which the framework might fail. The
                output will be similar to this:
                <br>
                <code>ERROR #109: This table does not exist. [Date and time: 2015-03-23 13:15:52]</code>.
                <br>
                It consists of an error code, a message describing the error and the
                date when the error took place. For more details about the possible error code, please see the following
                chapter.
            </li>
            <li><code>unchecked</code> - these are situations when an unforeseen situation occurs. This might be
                associated with a bug in the framework, a data-related issues, infrastructure problems, and so on.
                When this happens, restifydb will provide the client with a message similar to the following:
                <br>
                <code>ERROR #701: A fatal exception has occurred. Execution of the script was terminated. Please
                    contact the system administrator and communicate the following information: [Error token:
                    506F70C3] [Date and time: 2015-03-23 13:19:23]</code>
                <br>
                Please note that this message contains a unique error token which can be used by the system's
                administrator to diagnose the problem. These kinds of error will immediately appear in the application's
                <a href="#err-log">error log</a>.
            </li>
        </ul>
    </div>


    <h2>Error codes for checked exceptions</h2>
    <div class="block">
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th class="col-md-2">Error code</th>
                    <th class="col-md-8">Description</th>
                    <th class="col-md-2">HTTP code</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><code>101</code></td>
                    <td>An unsupported HTTP method was used.</td>
                    <td><code>405</code></td>
                </tr>
                <tr>
                    <td><code>102</code></td>
                    <td>No data source name was specified.</td>
                    <td><code>400</code></td>
                </tr>
                <tr>
                    <td><code>103</code></td>
                    <td>This data source cannot be found.</td>
                    <td><code>404</code></td>
                </tr>
                <tr>
                    <td><code>104</code></td>
                    <td>This table cannot be found</td>
                    <td><code>404</code></td>
                </tr>
                <tr>
                    <td><code>105</code></td>
                    <td>No primary key found. Cannot navigate a table without a primary key. Please use the _filter parameter
                        instead.
                    </td>
                    <td><code>412</code></td>
                </tr>
                <tr>
                    <td><code>108</code></td>
                    <td>Invalid URL.</td>
                    <td><code>404</code></td>
                    
                </tr>
                <tr>
                    <td><code>109</code></td>
                    <td>This table does not exist.</td>
                    <td><code>404</code></td>
                    
                </tr>
                <tr>
                    <td><code>110</code></td>
                    <td>The number of parameters should match the number of columns from the primary key.</td>
                    <td><code>412</code></td>
                    
                </tr>
                <tr>
                    <td><code>111</code></td>
                    <td>The record with the specified id(s) does not exist.</td>
                    <td><code>404</code></td>
                    
                </tr>
                <tr>
                    <td><code>112</code></td>
                    <td>The ID is mandatory for DELETE and UPDATE operations.</td>
                    <td><code>412</code></td>
                    
                </tr>
                <tr>
                    <td><code>113</code></td>
                    <td>Error deleting specified row.</td>
                    <td><code>412</code></td>
                    
                </tr>
                <tr>
                    <td><code>114</code></td>
                    <td>The column values are missing from the POST data. Please construct the request with the _data
                        parameter in the POST body and make sure the _data parameter is a well-formed JSON string.
                    </td>
                    <td><code>400</code></td>
                    
                </tr>
                <tr>
                    <td><code>115</code></td>
                    <td>One of columns specified does not exist.</td>
                    <td><code>412</code></td>
                    
                </tr>
                <tr>
                    <td><code>116</code></td>
                    <td>Error insert specified row.</td>
                    <td><code>412</code></td>
                    
                </tr>
                <tr>
                    <td><code>117</code></td>
                    <td>The column values are missing from the PUT data. Please construct the request with the _data
                        parameter in the PUT body and make sure the _data parameter is a well-formed JSON string.
                    </td>
                    <td><code>400</code></td>
                    
                </tr>
                <tr>
                    <td><code>118</code></td>
                    <td>Error updating specified row.</td>
                    <td><code>412</code></td>
                    
                </tr>
                <tr>
                    <td><code>119</code></td>
                    <td>No data source is currently configured.</td>
                    <td><code>412</code></td>
                    
                </tr>
                <tr>
                    <td><code>120</code></td>
                    <td>Invalid number of parameters in _filter clause.</td>
                    <td><code>412</code></td>
                    
                </tr>
                <tr>
                    <td><code>121</code></td>
                    <td>The _filter clause refers to a non-existing field.</td>
                    <td><code>412</code></td>
                    
                </tr>
                <tr>
                    <td><code>122</code></td>
                    <td>Syntax error in _filter clause.</td>
                    <td><code>412</code></td>
                    
                </tr>
                <tr>
                    <td><code>123</code></td>
                    <td>Invalid operators in _filter clause.</td>
                    <td><code>412</code></td>
                    
                </tr>
                <tr>
                    <td><code>124</code></td>
                    <td>The _fields clause refers to a non-existing field.</td>
                    <td><code>412</code></td>
                    
                </tr>
                <tr>
                    <td><code>125</code></td>
                    <td>The _sort clause refers to a non-existing field.</td>
                    <td><code>412</code></td>
                    
                </tr>
                <tr>
                    <td><code>900</code></td>
                    <td>restifydb is not properly configured. Please contact the system administrator.</td>
                    <td><code>412</code></td>
                    
                </tr>
                <tr>
                    <td><code>901</code></td>
                    <td>restifydb is not properly configured. Please contact the system administrator.</td>
                    <td><code>412</code></td>
                    
                </tr>
                <tr>
                    <td><code>902</code></td>
                    <td>restifydb is not properly configured. Please contact the system administrator.</td>
                    <td><code>412</code></td>
                    
                </tr>
                <tr>
                    <td><code>903</code></td>
                    <td>restifydb is not properly configured. Please contact the system administrator.</td>
                    <td><code>412</code></td>
                    
                </tr>
                <tr>
                    <td><code>201</code></td>
                    <td>No download token was specified.</td>
                    <td><code>400</code></td>
                    
                </tr>
                <tr>
                    <td><code>202</code></td>
                    <td>Invalid token.</td>
                    <td><code>400</code></td>
                    
                </tr>
                <tr>
                    <td><code>203</code></td>
                    <td>Invalid column.</td>
                    <td><code>400</code></td>
                    
                </tr>
                <tr>
                    <td><code>204</code></td>
                    <td>The specified column is not a valid primary key.</td>
                    <td><code>400</code></td>
                    
                </tr>
                <tr>
                    <td><code>205</code></td>
                    <td>The object for download was empty.</td>
                    <td><code>400</code></td>
                    
                </tr>
                <tr>
                    <td><code>301</code></td>
                    <td>The read operation has been disabled by the administrator.</td>
                    <td><code>403</code></td>
                    
                </tr>
                <tr>
                    <td><code>302</code></td>
                    <td>The update operation has been disabled by the administrator.</td>
                    <td><code>403</code></td>
                    
                </tr>
                <tr>
                    <td><code>303</code></td>
                    <td>The create operation has been disabled by the administrator.</td>
                    <td><code>403</code></td>
                    
                </tr>
                <tr>
                    <td><code>304</code></td>
                    <td>The delete operation has been disabled by the administrator.</td>
                    <td><code>403</code></td>
                    
                </tr>
                <tr>
                    <td><code>305</code></td>
                    <td>The access to this table has been disabled by the administrator.</td>
                    <td><code>403</code></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>