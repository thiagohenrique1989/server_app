<?php

namespace App\Controller;

use App\Model\Server;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SearchController
 * @package App\Controller
 */
class SearchController extends AbstractController
{
    /**
     * @var InputBag
     */
    private $query;

    /**
     * @Route("/search/index")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $cachePool = new FilesystemAdapter('app');
        $cacheItem = $cachePool->getItem(Server::CACHE_KEY);
        $this->query = $request->query;

        return $this->json($this->filterData($cacheItem->get()));
    }

    /**
     * @param array $data
     * @return array
     */
    protected function filterData(array $data): array
    {
        if ($this->query->get('location')) {
            $data = array_filter($data, [$this, "filterLocation"]);
        }

        if ($this->query->get('storage_type')) {
            $data = array_filter($data, [$this, "filterStorageType"]);
        }

        if ($this->query->all('ram')) {
            $data = array_filter($data, [$this, "filterRam"]);
        }

        if ($this->query->get('storage')) {
            $data = array_filter($data, [$this, "filterStorage"]);
        }

        return array_values($data);
    }

    /**
     * @param array $item
     * @return bool
     */
    private function filterLocation(array $item): bool
    {
        return $item[Server::LOCATION] === $this->query->get('location');
    }

    /**
     * @param array $item
     * @return bool
     */
    private function filterStorageType(array $item): bool
    {
        return $item[Server::STORAGE_TYPE] === $this->query->get('storage_type');
    }

    /**
     * @param array $item
     * @return bool
     */
    private function filterRam(array $item): bool
    {
        return in_array($item[Server::RAM], $this->query->all('ram'), true);
    }

    /**
     * @param array $item
     * @return bool
     */
    private function filterStorage(array $item): bool
    {
        return $item[Server::STORAGE] <= $this->query->get('storage');
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedServices(): array
    {
        return [];
    }
}