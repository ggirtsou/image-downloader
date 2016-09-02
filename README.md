Image Downloader
====

Allows you to download images from remote images in a cost effective way. Before downloading an image it makes a `HEAD` 
request and checks the `Content-Type`, and `Content-Length` headers in HTTP Response.

If server reports an image is at that URL, Image Downloader will issue a `GET` request and download the image.

====
TODO:
* Make this a RESTful service
