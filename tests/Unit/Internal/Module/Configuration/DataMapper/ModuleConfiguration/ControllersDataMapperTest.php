<?php declare(strict_types=1);
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Tests\Unit\Internal\Module\Configuration\DataMapper\ModuleConfiguration;

use OxidEsales\EshopCommunity\Internal\Module\Configuration\DataMapper\ModuleConfiguration\ClassExtensionsDataMapper;
use OxidEsales\EshopCommunity\Internal\Module\Configuration\DataMapper\ModuleConfiguration\ControllersDataMapper;
use OxidEsales\EshopCommunity\Internal\Module\Configuration\DataMapper\ModuleConfigurationMappingKeys;
use OxidEsales\EshopCommunity\Internal\Module\Configuration\DataObject\ModuleConfiguration;
use PHPUnit\Framework\TestCase;

/**
 * Class ClassExtensionsDataMapperTest
 */
class ControllersDataMapperTest extends TestCase
{
    public function testToDataAndFromData()
    {
        $classExtensionData = [
            ModuleConfigurationMappingKeys::CONTROLLERS => [
                'controller1' => \MyVendor\MyController\Controller1::class,
                'controller2' => \MyVendor\MyController\Controller2::class
            ]
        ];

        $classExtensionsDataMapper = new ControllersDataMapper();

        $moduleConfiguration = new ModuleConfiguration();
        $moduleConfiguration = $classExtensionsDataMapper->fromData($moduleConfiguration, $classExtensionData);

        $this->assertEquals(
            [
                ModuleConfigurationMappingKeys::CONTROLLERS => [
                    'controller1' => \MyVendor\MyController\Controller1::class,
                    'controller2' => \MyVendor\MyController\Controller2::class
                ]
            ],
            $classExtensionsDataMapper->toData($moduleConfiguration)
        );
    }

}

