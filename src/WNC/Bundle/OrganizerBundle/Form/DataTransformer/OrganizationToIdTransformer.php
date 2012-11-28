<?php
namespace WNC\Bundle\OrganizerBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;
use WNC\Bundle\OrganizerBundle\Entity\Organization;

class OrganizationToIdTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    /**
     * Transforms an object (issue) to a string (number).
     *
     * @param  Issue|null $issue
     * @return string
     */
    public function transform($issue)
    {
        if (null === $issue) {
            return "";
        }

        return $issue->getId();
    }

    /**
     * Transforms a string (number) to an object (issue).
     *
     * @param  string $id
     * @return Issue|null
     * @throws TransformationFailedException if object (issue) is not found.
     */
    public function reverseTransform($id)
    {
        if (!$id) {
            return null;
        }

        $organization = $this->om
            ->getRepository('WNCOrganizerBundle:Organization')
            ->findOneBy(array('id' => $id))
        ;

        if (null === $organization) {
            throw new TransformationFailedException(sprintf(
                'Organization with %d not exists',
                $id
            ));
        }

        return $organization;
    }
}