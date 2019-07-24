<?php declare(strict_types=1);
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Internal\Module\Configuration\DataMapper\ModuleConfiguration;

use OxidEsales\EshopCommunity\Internal\Module\Configuration\DataMapper\ModuleConfigurationDataMapperInterface;
use OxidEsales\EshopCommunity\Internal\Module\Configuration\DataObject\ModuleConfiguration;
use OxidEsales\EshopCommunity\Internal\Module\Configuration\DataObject\ModuleConfiguration\ClassExtension;
use OxidEsales\EshopCommunity\Internal\Module\Configuration\DataMapper\ModuleConfigurationMappingKeys;

/**
 * Class ClassExtensionsDataMapper
 */
class ClassExtensionsDataMapper implements ModuleConfigurationDataMapperInterface
{
    public function toData(ModuleConfiguration $configuration): array
    {
        $data = [];

        if ($configuration->hasClassExtensions()) {
            $data[ModuleConfigurationMappingKeys::CLASS_EXTENSIONS] = $this->getClassExtensions($configuration);
        }

        return $data;
    }

    public function fromData(ModuleConfiguration $moduleConfiguration, array $data): ModuleConfiguration
    {
        if (isset($data[ModuleConfigurationMappingKeys::CLASS_EXTENSIONS])) {
            $this->setClassExtensions($moduleConfiguration, $data[ModuleConfigurationMappingKeys::CLASS_EXTENSIONS]);
        }
        return $moduleConfiguration;
    }

    private function getClassExtensions(ModuleConfiguration $configuration): array
    {
        $extensions = [];

        foreach ($configuration->getClassExtensions() as $extension) {
            $extensions[$extension->getShopClassNamespace()] = $extension->getModuleExtensionClassNamespace();
        }

        return $extensions;
    }

    private function setClassExtensions(ModuleConfiguration $moduleConfiguration, array $extensions)
    {
        foreach ($extensions as $shopClass => $moduleClass) {
            $moduleConfiguration->addClassExtension(new ClassExtension(
                $shopClass,
                $moduleClass
            ));
        }
    }
}
