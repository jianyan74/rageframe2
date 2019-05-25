CHANGELOG
=========

4.2.0
-----

* The `$currentUri` constructor argument of the `AbstractUriElement`, `Link` and
  `Image` classes is now optional.
* The `Crawler::children()` method will have a new `$selector` argument in version 5.0, 
  not defining it is deprecated.

3.1.0
-----

* All the URI parsing logic have been abstracted in the `AbstractUriElement` class.
  The `Link` class is now a child of `AbstractUriElement`.
* Added an `Image` class to crawl images and parse their `src` attribute,
  and `selectImage`, `image`, `images` methods in the `Crawler` (the image version of the equivalent `link` methods).

2.5.0
-----

* [BC BREAK] The default value for checkbox and radio inputs without a value attribute have changed
  from '1' to 'on' to match the HTML specification.
* [BC BREAK] The typehints on the `Link`, `Form` and `FormField` classes have been changed from
  `\DOMNode` to `DOMElement`. Using any other type of `DOMNode` was triggering fatal errors in previous
  versions. Code extending these classes will need to update the typehints when overwriting these methods.

2.4.0
-----

 * `Crawler::addXmlContent()` removes the default document namespace again if it's an only namespace.
 * added support for automatic discovery and explicit registration of document
   namespaces for `Crawler::filterXPath()` and `Crawler::filter()`
 * improved content type guessing in `Crawler::addContent()`
 * [BC BREAK] `Crawler::addXmlContent()` no longer removes the default document
   namespace

2.3.0
-----

 * added Crawler::html()
 * [BC BREAK] Crawler::each() and Crawler::reduce() now return Crawler instances instead of DomElement instances
 * added schema relative URL support to links
 * added support for HTML5 'form' attribute

2.2.0
-----

 * added a way to set raw path to the file in FileFormField - necessary for
   simulating HTTP requests

2.1.0
-----

 * added support for the HTTP PATCH method
 * refactored the Form class internals to support multi-dimensional fields
   (the public API is backward compatible)
 * added a way to get parsing errors for Crawler::addHtmlContent() and
   Crawler::addXmlContent() via libxml functions
 * added support for submitting a form without a submit button
