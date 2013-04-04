DwApi
=====

Contains classes to help get information from the DaniWeb API.

- Authenticated: Class for API methods that require an access token (not started).
- Base: Base class with common/shared properties and methods.
- Credentials: Helper class to store id, credentials, code and token.
- ReadOnly: Class for open API methods.
- Rss: Class to get (filtered) RSS feeds.

Usage
=====

When using the ReadOnly/Rss class make sure you include the Base class first.

    include 'Base.class.php';
    include 'Rss.class.php';
