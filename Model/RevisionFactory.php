<?php
namespace PluginCompany\CmsRevisions\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\ObjectManagerInterface;

abstract class RevisionFactory
{
    /**
     * Object Manager instance
     *
     * @var ObjectManagerInterface
     */
    protected $_objectManager = null;

    /**
     * Instance name to create
     *
     * @var string
     */
    protected $_instanceName = null;

    protected $cmsEntityRepository;

    public function __construct(
        ObjectManagerInterface $objectManager,
        $cmsEntityRepository,
        $instanceName
    )
    {
        $this->cmsEntityRepository = $cmsEntityRepository;
        $this->_objectManager = $objectManager;
        $this->_instanceName = $instanceName;
    }

    /**
     * Create class instance and set CMS block data based in ID
     *
     * @param $entityId
     * @return Block
     */
    protected function createFromEntityId($entityId)
    {
        return $this->createFromEntity(
            $this->getCmsEntityUsingId($entityId)
        );
    }

    private function getCmsEntityUsingId($entityId)
    {
        return $this->cmsEntityRepository->getById($entityId);
    }

    /**
     * Create class instance and set CMS block data
     *
     * @param AbstractModel $entity
     * @param array $data
     * @return Block
     */
    protected function createFromEntity(AbstractModel $entity, $data = [])
    {
        return $this->create($data)->setData(
                $this->getCmsEntityData($entity)
            );
    }

    /**
     * @param AbstractModel $entity
     * @return array
     * @internal param CmsBlock $block
     */
    private function getCmsEntityData(AbstractModel $entity)
    {
        return $entity->setCreationTime(null)->getData();
    }

    /**
     * Create class instance with specified parameters
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data = array())
    {
        return $this->_objectManager->create($this->_instanceName, $data);
    }
}
