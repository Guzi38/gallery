<?php
/**
 * User service interface.
 */

namespace App\Service;

use App\Entity\User;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface UserServiceInterface.
 */
interface UserServiceInterface
{
    /**
     * Get paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function createPaginatedList(int $page): PaginationInterface;

    /**
     * Save entity.
     *
     * @param User $user User entity
     */
    public function save(User $user): void;

    /**
     * Edit entity.
     *
     * @param User $user User entity
     */
    public function edit(User $user): void;

    /**
     * Remove entity.
     *
     * @param User $user User entity
     */
    public function remove(User $user): void;

    /**
     * Find user.
     *
     * @param string $email Email
     */
    public function findOneBy(string $email): ?User;
}
