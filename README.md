DaniWeb API
===========

Contains classes to help get information from the DaniWeb API.

- DwApiBase: Base class with common/shared properties and methods.
- DwApiRss: Class to get (filtered) RSS feeds (extends DwApiBase).
- DwApiOpen: Class for open API methods (extends DwApiRss).
- DwApiOAuth: Class for API methods that require an access token (extends DwApiOpen).

- DwApiCredentials: Helper class to store id, credentials, code and token (unused).
