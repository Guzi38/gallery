<?php
/**
 * Photo voter.
 */

namespace App\Security\Voter;

use App\Entity\Photo;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class PhotoVoter.
 */
class PhotoVoter extends Voter
{
    /**
     * Edit permission.
     *
     * @const string
     */
    public const EDIT = 'EDIT';

    /**
     * View permission.
     *
     * @const string
     */
    public const VIEW = 'VIEW';

    /**
     * Delete permission.
     *
     * @const string
     */
    public const DELETE = 'DELETE';

    /**
     * Check permission.
     *
     * @const string
     */
    public const MANAGE = 'MANAGE';

    /**
     * Security helper.
     */
    private Security $security;

    /**
     * OrderVoter constructor.
     *
     * @param Security $security Security helper
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * Determines if the attribute and subject are supported by this voter.
     *
     * @param string $attribute An attribute
     * @param mixed  $subject   The subject to secure, e.g. an object the user wants to access or any other PHP type
     *
     * @return bool Result
     */
    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::VIEW, self::DELETE, self::MANAGE])
            && $subject instanceof Photo;
    }

    /**
     * Perform a single access check operation on a given attribute, subject and token.
     * It is safe to assume that $attribute and $subject already passed the "supports()" method check.
     *
     * @param string         $attribute Permission name
     * @param mixed          $subject   Object
     * @param TokenInterface $token     Security token
     *
     * @return bool Vote result
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
            case self::MANAGE:
                return $this->canManage($user);
            case self::EDIT:
                return $this->canEdit($subject, $user);
            case self::VIEW:
                return $this->canView($subject, $user);
            case self::DELETE:
                return $this->canDelete($subject, $user);
        }

        return false;
    }

    /**
     * Checks if user can edit Photo.
     *
     * @param Photo $photo Photo entity
     * @param User  $user  User
     *
     * @return bool Result
     */
    private function canEdit(Photo $photo, User $user): bool
    {
        if ($photo->getAuthor() === $user) {
            return true;
        }
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        return false;
    }

    /**
     * Checks if user can view Photo.
     *
     * @param Photo $photo Photo entity
     * @param User  $user  User
     *
     * @return bool Result
     */
    private function canView(Photo $photo, User $user): bool
    {
        return true;
    }

    /**
     * Checks if user can delete Photo.
     *
     * @param Photo $photo Photo entity
     * @param User  $user  User
     *
     * @return bool Result
     */
    private function canDelete(Photo $photo, User $user): bool
    {
        if ($photo->getAuthor() === $user) {
            return true;
        }
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        return false;
    }

    /**
     * Checks if user can delete gallery.
     *
     * @param User $user User
     *
     * @return bool Result
     */
    private function canManage(User $user): bool
    {
        return $this->security->isGranted('ROLE_ADMIN');
    }
}
