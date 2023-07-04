<?php
/**
 * Photo repository.
 */

namespace App\Repository;

use App\Entity\Gallery;
use App\Entity\Photo;
use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class PhotoRepository.
 *
 * @method Photo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Photo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Photo[]    findAll()
 * @method Photo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @extends ServiceEntityRepository<Photo>
 *
 * @psalm-suppress LessSpecificImplementedReturnType
 */
class PhotoRepository extends ServiceEntityRepository
{
    /**
     * Items per page.
     *
     * Use constants to define configuration options that rarely change instead
     * of specifying them in configuration files.
     * See https://symfony.com/doc/current/best_practices.html#configuration
     *
     * @constant int
     */
    public const PAGINATOR_ITEMS_PER_PAGE = 10;

    /**
     * Constructor.
     *
     * @param ManagerRegistry $registry Manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Photo::class);
    }

    /**
     * Save entity.
     *
     * @param Photo $photo Photo entity
     */
    public function save(Photo $photo): void
    {
        $this->_em->persist($photo);
        $this->_em->flush();
    }

    /**
     * Delete entity.
     *
     * @param Photo $photo Photo entity
     */
    public function delete(Photo $photo): void
    {
        $this->_em->remove($photo);
        $this->_em->flush();
    }

    /**
     * Count photos by gallery.
     *
     * @param Gallery $gallery Gallery
     *
     * @return int Number of tasks in gallery
     *
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function countByGallery(Gallery $gallery): int
    {
        $qb = $this->getOrCreateQueryBuilder();

        return $qb->select($qb->expr()->countDistinct('photo.id'))
            ->where('photo.gallery = :gallery')
            ->setParameter(':gallery', $gallery)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Query all records.
     *
     * @param array<string, object> $filters Filters
     *
     * @return QueryBuilder Query builder
     */
    public function queryAll(array $filters): QueryBuilder
    {
        $queryBuilder = $this->getOrCreateQueryBuilder()
            ->select(
                'partial photo.{id, createdAt, updatedAt, title, content}',
                'partial gallery.{id, title}',
                'partial tags.{id, title}',
            )
            ->join('photo.gallery', 'gallery')
            ->leftJoin('photo.tags', 'tags')
            ->orderBy('photo.updatedAt', 'DESC');

        return $this->applyFiltersToList($queryBuilder, $filters);
    }

    /**
     * Get or create new query builder.
     *
     * @param QueryBuilder|null $queryBuilder Query builder
     *
     * @return QueryBuilder Query builder
     */
    private function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?? $this->createQueryBuilder('photo');
    }

    /**
     * Apply filters to paginated list.
     *
     * @param QueryBuilder          $queryBuilder Query builder
     * @param array<string, object> $filters      Filters array
     *
     * @return QueryBuilder Query builder
     */
    private function applyFiltersToList(QueryBuilder $queryBuilder, array $filters = []): QueryBuilder
    {
        if (isset($filters['gallery']) && $filters['gallery'] instanceof Gallery) {
            $queryBuilder->andWhere('gallery = :gallery')
                ->setParameter('gallery', $filters['gallery']);
        }

        if (isset($filters['tag']) && $filters['tag'] instanceof Tag) {
            $queryBuilder->andWhere('tags IN (:tag)')
                ->setParameter('tag', $filters['tag']);
        }

        return $queryBuilder;
    }
}
