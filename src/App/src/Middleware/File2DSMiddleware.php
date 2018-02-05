<?php
/**
 * Created by PhpStorm.
 * User: USER_T
 * Date: 05.02.2018
 * Time: 13:04
 */

namespace rollun\app\Middleware;


use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface;
use rollun\datastore\DataStore\CsvBase;
use rollun\installer\Command;
use Symfony\Component\Filesystem\LockHandler;
use Xiag\Rql\Parser\Query;
use Zend\Diactoros\Response\EmptyResponse;

class File2DSMiddleware implements MiddlewareInterface
{
    private $dataStore;
    private $tmpDirName;

    public function __construct($dataStore,  $tmpDirName = null)
    {
        $this->dataStore = $dataStore;
        if (isset($delimeter)) {
            $this->tmpDirName = $tmpDirName;
        } else $this->tmpDirName = Command::getDataDir();
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        /** var Zend\Diactoros\UploadedFile $file */
        $file = ($request->getUploadedFiles())['file'];
        $fileName = $file->getClientFilename();
        $filePath = $this->tmpDirName . '/' . $fileName;
        $file->moveTo($filePath);
        //TODO: get delimeter from request HERE
        $delimeter = ',';
        $tmpDataStore = new CsvBase($filePath, $delimeter, new LockHandler($filePath));
        $data = $tmpDataStore->query(new Query());
        foreach ($data as $row) {
            $this->dataStore->create($row, true);
        }

        $request = $request->withAttribute(Response::class, new EmptyResponse(200))
            ->withAttribute('responseData', 'Data uploaded successfully');

        $response = $delegate->process($request);
        unlink($filePath);
        return $response;
    }
}