<?php
/**
 * Gallery service.
 */

namespace App\Service;

use App\Entity\Gallery;
use App\Repository\GalleryRepository;
use App\Repository\PhotoRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class GalleryService.
 */
class GalleryService implements GalleryServiceInterface
{
    /**
     * Photo repository.
     */
    public PhotoRepository $photoRepository;

    /**
     * Gallery repository.
     */
    private GalleryRepository $galleryRepository;

    /**
     * Paginator.
     */
    private PaginatorInterface $paginator;

    /**
     * Constructor.
     *
     * @param GalleryRepository  $galleryRepository Gallery repository
     * @param PhotoRepository    $photoRepository   Photo repository
     * @param PaginatorInterface $paginator         Paginator
     */
    public function __construct(GalleryRepository $galleryRepository, PhotoRepository $photoRepository, PaginatorInterface $paginator)
    {
        $this->galleryRepository = $galleryRepository;
        $this->photoRepository = $photoRepository;
        $this->paginator = $paginator;
    }

    /**
     * Get paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<SlidingPagination> Paginated list
     */
    public function getPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->galleryRepository->queryAll(),
            $page,
            GalleryRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save entity.
     *
     * @param Gallery $gallery Gallery entity
     */
    public function save(Gallery $gallery): void
    {
        $this->galleryRepository->save($gallery);
    }

    /**
     * Delete entity.
     *
     * @param Gallery $gallery Gallery entity
     */
    public function delete(Gallery $gallery): void
    {
        $this->galleryRepository->delete($gallery);
    }

    /**
     * Can Gallery be deleted?
     *
     * @param Gallery $gallery Gallery entity
     *
     * @return bool Result
     */
    public function canBeDeleted(Gallery $gallery): bool
    {
        try {
            $result = $this->photoRepository->countByGallery($gallery);

            return !($result > 0);
        } catch (NoResultException|NonUniqueResultException) {
            return false;
        }
    }

    /**
     * Find by id.
     *
     * @param int $id Gallery id
     *
     * @return Gallery|null Gallery entity
     *
     * @throws NonUniqueResultException
     */
    public function findOneById(int $id): ?Gallery
    {
        return $this->galleryRepository->findOneById($id);
    }
}
