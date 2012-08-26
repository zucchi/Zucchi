<?php
namespace Zucchi\Service;

use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\EventManager\EventManagerAwareInterface;
use Zucchi\Event\ProviderTrait as EventProvider;
use Zucchi\Debug\Debug;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Collections\Criteria;


class AbstractService implements EventManagerAwareInterface
{
    use EventProvider;
    
    /**
     * 
     * @var unknown_type
     */
    protected $serviceManager;
    
    /**
     * @var Doctrine\ORM\EntityManager
     */
    protected $entityManager;
    
    /**
     * The entity for the service
     */
    protected $entity;
    
    /**
     * get a list of Entities or just the specific entity
     * @param array $filter
     */
    public function get(array $filter = array())
    {
        if (!$this->entityName) {
            throw new \RuntimeException('No Entity defined for ' . get_called_class() . ' service');
        }
        
        
        $em = $this->entityManager;
        $qb = $em->createQueryBuilder();
        $qb->select('e')
           ->from($this->entityName, 'e')
           ->where("REGEXP('identity', 'ma') = 0");
        
        $result = $qb->getQuery()->execute();
        Debug::dump($result);
    }
    
    /**
     * set the entity manager
     * @param EntityManager $em
     * @return $this
     */
    public function setEntityManager(EntityManager $em)
    {
        $this->entityManager = $em;
        return $this;
    }
    
    /**
     * get the currently set Entity Manager
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->em;
    }
    
    /**
     * 
     * @param ServiceManager $serviceManager
     * @return \Zucchi\Service\AbstractService
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
        return $this;
    }
}