<?php

namespace Gkirtsou;

use Gkirtsou\Exceptions\ImageTooBigException;
use Gkirtsou\Exceptions\InvalidImageException;
use Gkirtsou\Exceptions\InvalidRequestException;
use Gkirtsou\Interfaces\ImageDownloaderInterface;
use Gkirtsou\Interfaces\ValidatorInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\StreamInterface;

/**
 * Class ImageDownloader
 * @package Gkirtsou
 */
class ImageDownloader implements ImageDownloaderInterface
{
    /**
     * Curl connect timeout @see http://docs.guzzlephp.org/en/latest/request-options.html#connect-timeout
     * @var int Curl connect timeout
     */
    protected $connectTimeout = 3;

    /**
     * Curl timeout @see http://docs.guzzlephp.org/en/latest/request-options.html#timeout
     * @var int Curl timeout
     */
    protected $timeout = 3;

    /**
     * If set to false will not check Content-Type header if it returns an image
     * @var bool
     */
    protected $doContentTypeCheck = true;

    /**
     * Crawler user agent @see https://en.wikipedia.org/wiki/User_agent
     * @var string
     */
    protected $userAgent = 'https://github.com/trivialmatters/image-downloader';

    /**
     * Max image size to download (in MB). Use 0 to disable check
     * @var int max image size in MB
     */
    protected $maxSize = 5;

    /** @var ClientInterface Crawler Client */
    private $client;

    /** @var StreamInterface */
    private $fileSystem;

    /** @var ValidatorInterface|\Gkirtsou\Validator\ContentTypeValidator */
    private $contentTypeValidator;

    /** @var ValidatorInterface|\Gkirtsou\Validator\ContentLengthValidator */
    private $contentLengthValidator;

    /** @var \Psr\Http\Message\ResponseInterface */
    private $response;

    /**
     * ImageDownloader constructor.
     * @param ClientInterface    $client                 Http Client
     * @param StreamInterface    $fileSystem             File System
     * @param ValidatorInterface $contentTypeValidator   Content Type Validator
     * @param ValidatorInterface $contentLengthValidator Content Length Validator
     */
    public function __construct(
        ClientInterface $client,
        StreamInterface $fileSystem,
        ValidatorInterface $contentTypeValidator,
        ValidatorInterface $contentLengthValidator)
    {
        $this->client = $client;
        $this->fileSystem = $fileSystem;
        $this->contentTypeValidator = $contentTypeValidator;
        $this->contentLengthValidator = $contentLengthValidator;
    }

    /**
     * @inheritdoc
     * @return bool
     */
    public function download(string $url) : bool
    {
        if (false === $this->makeRequest($url)) {
            return false;
        }

        if ($this->isSuccessful() && $this->isImage() && $this->isSizeAcceptable()) {
            return true;
        }

        return false;
    }

    /**
     * Makes request to remote server. First it makes a HEAD request to determine the Content-Type
     * and Content-Length from the HTTP Response. If HEAD request is supported in remote server,
     * validation checks are performed against the headers. If all checks passed, it performs a GET
     * request, and downloads the image.
     *
     * @param string $url    Url to download image from
     * @param string $method HTTP Method to use. Defaults to HEAD. Allowed method: GET|HEAD
     * @throws InvalidRequestException If request method method other than HEAD/GET given
     * @return bool
     */
    protected function makeRequest(string $url, string $method = 'HEAD') : bool
    {
        if (!in_array($method, ['HEAD', 'GET'])) {
            throw new InvalidRequestException('Request type must be HEAD or GET');
        }

        try {
            $config = [
                RequestOptions::CONNECT_TIMEOUT => $this->connectTimeout,
                RequestOptions::TIMEOUT => $this->timeout,
                RequestOptions::ALLOW_REDIRECTS => true,
                RequestOptions::STREAM => true,
                RequestOptions::HEADERS => ['User-Agent' => $this->userAgent],
            ];

            if ($method === 'GET') {
                $config[RequestOptions::SINK] = $this->fileSystem;
            }

            $this->response = $this->client->request($method, $url, $config);
        } catch (ServerException $e) {
            return false;
        } catch (ClientException $e) {
            // HEAD request is not allowed on this server, retry with a GET
            if ($method === 'HEAD' && $e->getResponse()->getStatusCode() === 405) {
                return $this->makeRequest('GET');
            }

            // something went wrong with the request
            return false;
        }

        return true;
    }

    /**
     * Use ContentTypeValidator is header is present and check is enabled.
     * @throws InvalidImageException
     * @return bool
     */
    protected function isImage()
    {
        // Skip check since Content-Type header found in response, or if check is disabled
        if (!$this->response->hasHeader('Content-Type') || $this->doContentTypeCheck === false) {
            return true;
        }

        $contentType = $this->response->getHeaderLine('Content-Type');
        $validator = $this->contentTypeValidator->setContentType($contentType);

        if (false === $validator->isValid()) {
            throw new InvalidImageException('Disallowed mime type of remote website: %s', $contentType);
        }

        return true;
    }

    /**
     * Use ContentLengthValidator if Content-Length header is present and check is enabled.
     * Checks if image size is not too big.
     *
     * @throws ImageTooBigException
     * @return bool
     */
    protected function isSizeAcceptable()
    {
        // Skip check because Content-Length was not reported by remote server, or check disabled
        if (!$this->response->hasHeader('Content-Length') || $this->maxSize === 0) {
            return true;
        }

        $length = $this->response->getHeaderLine('Content-Length');
        $validator = $this->contentLengthValidator
            ->setMaxSize($this->maxSize)
            ->setContentLength($length);

        if (false === $validator->isValid()) {
            throw new ImageTooBigException(sprintf(
                'Response is too large: %sMB, allowed: %sMB',
                $validator->convertBytesToMb($length),
                $this->maxSize
            ));
        }

        return true;
    }

    /**
     * Checks if HTTP Status code is Successful (2xx | 304)
     *
     * This part of code is borrowed from Guzzle v3.
     * @see https://github.com/Guzzle3/http/blob/master/Message/Response.php#L747-L755
     *
     * @return bool
     */
    protected function isSuccessful()
    {
        $statusCode = $this->response->getStatusCode();

        return ($statusCode >= 200 && $statusCode < 300) || $statusCode == 304;
    }
}
