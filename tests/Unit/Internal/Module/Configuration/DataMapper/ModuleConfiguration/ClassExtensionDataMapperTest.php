<?php declare(strict_types=1);
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Tests\Unit\Internal\Module\Configuration\DataMapper\ModuleConfiguration;

use OxidEsales\EshopCommunity\Internal\Module\Configuration\DataMapper\ModuleConfiguration\ClassExtensionsDataMapper;
use OxidEsales\EshopCommunity\Internal\Module\Configuration\DataMapper\ModuleConfigurationMappingKeys;
use OxidEsales\EshopCommunity\Internal\Module\Configuration\DataObject\ModuleConfiguration;
use PHPUnit\Framework\TestCase;

/**
 * Class ClassExtensionsDataMapperTest
 */
class ClassExtensionsDataMapperTest extends TestCase
{
    public function testToDataAndFromData()
    {
        $classExtensionData = [
            ModuleConfigurationMappingKeys::CLASS_EXTENSIONS => [
                'shopClass1' => 'moduleClass1',
                'shopClass2' => 'moduleClass2'
            ]
        ];

        $classExtensionsDataMapper = new ClassExtensionsDataMapper();

        $moduleConfiguration = new ModuleConfiguration();
        $moduleConfiguration = $classExtensionsDataMapper->fromData($moduleConfiguration, $classExtensionData);

        $this->assertEquals(
            [
                ModuleConfigurationMappingKeys::CLASS_EXTENSIONS => [
                    'shopClass1' => 'moduleClass1',
                    'shopClass2' => 'moduleClass2'
                ]
            ],
            $classExtensionsDataMapper->toData($moduleConfiguration)
        );
    }

}
