<div class="topic" id="design">
    <h1>Design considerations</h1>

    <div class="block">
        <p>
            I have built restifydb with speed in mind. However, there is a lot of room for improvement.
            There are currently some implementation details which need to be considered:
        </p>
        <ul>
            <li>
                The paradigm behind restifydb is the <code>HATEOAS</code> (Hypermedia as the Engine of
                Application State,) concept. This leverages data discovery through hypermedia.
            </li>
            <li>
                I have implemented <code>ETag</code> support in restifydb. This improves web caching
                support. The <code>ETag</code> is computed as the checksum of the output. Of course, same
                input will produce the same checksum, meaning it can be read from cache.
            </li>
            <li>
                Having <code>GZip</code> compression enabled will greatly reduce the network traffic, as
                restifydb sends only text data (in JSON or XML format). Please consider enabling the output
                compression if you wish to save bandwidth.
            </li>

        </ul>
    </div>

</div>
