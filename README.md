DaniWeb API
===========

Contains classes to help get information from the DaniWeb API.

- DwApiBase: Base class with common/shared properties and methods.
- DwApiCredentials: Helper class to store id, credentials, code and token.
- DwApiOAuth: Class for API methods that require an access token (not started).
- DwApiOpen: Class for open API methods.
- DwApiRss: Class to get (filtered) RSS feeds.

Usage
=====

When using the a class make sure you include the Base class first.

    include 'DwApiBase.class.php';
    include 'DwApiRss.class.php';
