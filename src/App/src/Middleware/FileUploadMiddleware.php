<?php
/**
 * Created by PhpStorm.
 * User: USER_T
 * Date: 26.01.2018
 * Time: 18:25
 */

namespace rollun\app\Middleware;


use Interop\Http\ServerMiddleware\DelegateInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface;
use rollun\datastore\DataStore\CsvBase;
use rollun\datastore\DataStore\Interfaces\DataStoresInterface;
use Symfony\Component\Filesystem\LockHandler;
use Xiag\Rql\Parser\Query;
use Zend\Diactoros\Response\EmptyResponse;


class FileUploadMiddleware extends ContainerAwareMiddleware
{

    private $tmpDirName = 'C:\OSPanel\domains\rollun-skeleton\data\tmp';

    /**
     * Process an incoming server request and return a response, optionally delegating
     * to the next middleware component to create the response.
     *
     * @param ServerRequestInterface $request
     * @param DelegateInterface $delegate
     * @return ResponseInterface
     * @throws \Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        /** var Zend\Diactoros\UploadedFile $file */
        $file = ($request->getUploadedFiles())['file'];
        $fileName = $file->getClientFilename();
        $filePath = $this->tmpDirName . '/' . $fileName;
        $file->moveTo($filePath);
        $delimeter = $request->getAttribute('delimeter');
        if (!isset($delimeter)) {
            $delimeter = ',';
        }
        $tmpDataStore = new CsvBase($filePath, $delimeter, new LockHandler($filePath));
        $datastoreName = $this->getDataStoreName($request);
        $resultStore = $this->container->get($datastoreName);
        if (!is_a($resultStore, DataStoresInterface::class, true)) {
            throw new \Exception("'$datastoreName' is not a valid DataStore name");
        }
        $data = $tmpDataStore->query(new Query());
        foreach ($data as $row) {
            $resultStore->create($row, true);
        }

        $request = $request->withAttribute(Response::class, new EmptyResponse(200))
                            ->withAttribute('responseData', 'Data uploaded successfully');

        unlink($filePath);
        $response = $delegate->process($request);
        return $response;
    }

    protected function getDataStoreName(ServerRequestInterface $request)
    {
        $path = ltrim($request->getUri()->getPath(), "/");
        $datastoreName = explode('/', $path)[1];
        return $datastoreName;
    }
}