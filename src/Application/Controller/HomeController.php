<?php

namespace Cievs\Application\Controller;

use Cievs\Domain\Repository\EstadoRepository;

use League\Csv\Reader;
use League\Csv\Statement;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\UploadedFileInterface;

final class HomeController extends BaseController
{
    protected EstadoRepository $repository;

    protected ContainerInterface $container;

    public function __construct(ContainerInterface $container, EstadoRepository $repository)
    {
        $this->container  = $container;
        $this->repository = $repository;

        parent::__construct($container);
    }

    public function index(Request $request, Response $response, array $args = []): Response
    {
        $this->logger->info("Home page action dispatched");

        return $this->render($request, $response, 'home/index');
    }

    public function import(Request $request, Response $response, array $args = []): Response
    {
        return $this->render($request, $response, 'home/import');
    }

    public function upload(Request $request, Response $response, array $args = []): Response
    {
        $directory = ROOT_PATH . '/data';

        $uploadedFiles = $request->getUploadedFiles();
        $uploadedFile  = $uploadedFiles['arquivo'];

        if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
            $filename = $this->moveUploadedFile($directory, $uploadedFile);

            $csv = Reader::createFromPath($directory .DIRECTORY_SEPARATOR . $filename, 'r');
            $csv->setHeaderOffset(0);

            foreach ($csv as $row) {
                $result = $this->repository->import($row['nome'], $row['sigla'], $row['total']);

                if (! $result) {
                    dump('Encontrei um erro'); die;
                }
            }

            dump('Uploaded: ' . $filename ); die;
        }
    }

    private function moveUploadedFile(string $directory, UploadedFileInterface $uploadedFile)
    {
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);

        if ($uploadedFile->getClientMediaType() !== 'text/csv') {
            throw \Exception("Tipo de arquivo invalido");
        }

        if ($extension !== 'csv') {
            throw \Exception("Tipo de arquivo invalido");
        }

        $basename = bin2hex(random_bytes(8));
        $filename = sprintf('%s.%0.8s', $basename, $extension);

        $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

        return $filename;
    }
}
