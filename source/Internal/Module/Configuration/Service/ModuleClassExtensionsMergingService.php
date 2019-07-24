<?php declare(strict_types=1);
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Internal\Module\Configuration\Service;

use OxidEsales\EshopCommunity\Internal\Adapter\Exception\ModuleConfigurationNotFoundException;
use OxidEsales\EshopCommunity\Internal\Module\Configuration\DataObject\ClassExtensionsChain;
use OxidEsales\EshopCommunity\Internal\Module\Configuration\DataObject\ModuleConfiguration;
use OxidEsales\EshopCommunity\Internal\Module\Configuration\DataObject\ShopConfiguration;
use OxidEsales\EshopCommunity\Internal\Module\Configuration\DataObject\ModuleConfiguration\ClassExtension;
use OxidEsales\EshopCommunity\Internal\Module\Configuration\Exception\ExtensionNotInChainException;

/**
 * @internal
 */
class ModuleClassExtensionsMergingService implements ModuleClassExtensionsMergingServiceInterface
{
    /**
     * @param ShopConfiguration   $shopConfiguration
     * @param ModuleConfiguration $moduleConfiguration
     *
     * @return ClassExtensionsChain
     * @throws ExtensionNotInChainException
     * @throws ModuleConfigurationNotFoundException
     */
    public function merge(
        ShopConfiguration $shopConfiguration,
        ModuleConfiguration $moduleConfiguration
    ): ClassExtensionsChain {

        $chain = $shopConfiguration->getClassExtensionsChain();

        if (!$shopConfiguration->hasModuleConfiguration($moduleConfiguration->getId())) {
            $chain->addExtensions($moduleConfiguration->getClassExtensions());
        } else {
            $chain = $this->addNewModuleExtensionsToChain($moduleConfiguration, $shopConfiguration, $chain);
            $chain = $this->replaceExistingModuleExtensionsInChain($moduleConfiguration, $shopConfiguration, $chain);
            $chain = $this->removeDeletedModuleExtensionsFromChain($moduleConfiguration, $shopConfiguration, $chain);
        }

        return $chain;
    }

    /**
     * @param ModuleConfiguration  $moduleConfiguration
     * @param ShopConfiguration    $shopConfiguration
     * @param ClassExtensionsChain $classExtensionChain
     *
     * @return ClassExtensionsChain
     * @throws ModuleConfigurationNotFoundException
     * @throws ExtensionNotInChainException
     */
    private function removeDeletedModuleExtensionsFromChain(
        ModuleConfiguration $moduleConfiguration,
        ShopConfiguration $shopConfiguration,
        ClassExtensionsChain $classExtensionChain
    ): ClassExtensionsChain {
        $existentModuleConfiguration = $shopConfiguration->getModuleConfiguration(
            $moduleConfiguration->getId()
        );

        foreach ($existentModuleConfiguration->getClassExtensions() as $extension) {
            if (!$this->hasShopClassNamespace($extension, $moduleConfiguration->getClassExtensions())) {
                $classExtensionChain->removeExtension($extension);
            }
        }

        return $classExtensionChain;
    }

    /**
     * @param ModuleConfiguration  $moduleConfiguration
     * @param ShopConfiguration    $shopConfiguration
     * @param ClassExtensionsChain $chain
     *
     * @return ClassExtensionsChain
     * @throws ModuleConfigurationNotFoundException
     */
    private function replaceExistingModuleExtensionsInChain(
        ModuleConfiguration $moduleConfiguration,
        ShopConfiguration $shopConfiguration,
        ClassExtensionsChain $chain
    ): ClassExtensionsChain {
        $existentModuleConfiguration = $shopConfiguration->getModuleConfiguration(
            $moduleConfiguration->getId()
        );

        foreach ($existentModuleConfiguration->getClassExtensions() as $existingExtension) {
            foreach ($moduleConfiguration->getClassExtensions() as $newExtension) {
                if ($this->areExtensionsSame($existingExtension, $newExtension)) {
                    $this->replaceExistingExtension($chain, $existingExtension, $newExtension);
                }
            }
        }

        return $chain;
    }

    /**
     * @param ModuleConfiguration  $moduleConfiguration
     * @param ShopConfiguration    $shopConfiguration
     * @param ClassExtensionsChain $chain
     *
     * @return ClassExtensionsChain
     * @throws ModuleConfigurationNotFoundException
     */
    private function addNewModuleExtensionsToChain(
        ModuleConfiguration $moduleConfiguration,
        ShopConfiguration $shopConfiguration,
        ClassExtensionsChain $chain
    ): ClassExtensionsChain {
        foreach ($moduleConfiguration->getClassExtensions() as $classExtension) {
            $existentModuleConfiguration = $shopConfiguration->getModuleConfiguration(
                $moduleConfiguration->getId()
            );

            if (!$existentModuleConfiguration->extendsShopClass($classExtension->getShopClassNamespace())) {
                $chain->addExtensionToChain($classExtension);
            }
        }

        return $chain;
    }

    /**
     * @param ClassExtension $existingClassExtension
     * @param ClassExtension[]          $newClassExtensions
     *
     * @return bool
     */
    private function hasShopClassNamespace(ClassExtension $existingClassExtension, array $newClassExtensions): bool
    {
        foreach ($newClassExtensions as $newExtension) {
            if ($newExtension->getShopClassNamespace() === $existingClassExtension->getShopClassNamespace()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param ClassExtension $existingExtension
     * @param ClassExtension $newExtension
     *
     * @return bool
     */
    private function areExtensionsSame(ClassExtension $existingExtension, ClassExtension $newExtension): bool
    {
        return $existingExtension->getShopClassNamespace() === $newExtension->getShopClassNamespace()
               && $existingExtension->getModuleExtensionClassNamespace() !==
                  $newExtension->getModuleExtensionClassNamespace();
    }

    /**
     * Converts e.g. the chain [Class1, ClassOld, Class3] to [Class1, ClassNew, Class3]. Keeping the order is important
     * as the order can be changed in OXID eShop admin.
     *
     * @param ClassExtensionsChain $chain
     * @param ClassExtension       $existingExtension
     * @param ClassExtension       $newExtension
     */
    private function replaceExistingExtension(
        ClassExtensionsChain $chain,
        ClassExtension $existingExtension,
        ClassExtension $newExtension
    ): void {
        $classExtensionChain = $chain->getChain();
        $shopClassNamespaceInChain = $classExtensionChain[$existingExtension->getShopClassNamespace()];
        foreach ($shopClassNamespaceInChain as $key => $existingExtensionInChain) {
            if ($existingExtensionInChain === $existingExtension->getModuleExtensionClassNamespace()) {
                $classExtensionChain[$existingExtension->getShopClassNamespace()][$key] =
                    $newExtension->getModuleExtensionClassNamespace();
            }
        }
        
        $chain->setChain($classExtensionChain);
    }
}
