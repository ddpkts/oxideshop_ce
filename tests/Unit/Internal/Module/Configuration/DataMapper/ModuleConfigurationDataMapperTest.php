<?php declare(strict_types=1);
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Tests\Unit\Internal\Module\Configuration\DataMapper;

use OxidEsales\EshopCommunity\Internal\Module\Configuration\DataMapper\ModuleConfiguration\ClassExtensionsDataMapper;
use OxidEsales\EshopCommunity\Internal\Module\Configuration\DataMapper\ModuleConfiguration\ControllersDataMapper;
use OxidEsales\EshopCommunity\Internal\Module\Configuration\DataMapper\ModuleConfigurationDataMapper;
use OxidEsales\EshopCommunity\Internal\Module\Configuration\DataMapper\ModuleConfigurationDataMapperInterface;
use OxidEsales\EshopCommunity\Internal\Module\Configuration\DataMapper\ModuleConfigurationMappingKeys;
use OxidEsales\EshopCommunity\Internal\Module\Configuration\DataObject\ModuleConfiguration;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class ModuleConfigurationDataMapperTest extends TestCase
{
    public function testMapping()
    {
        $configurationData = [
            'id'          => 'moduleId',
            'path'        => 'relativePath',
            'version'     => '7.0',
            'autoActive'  => true,
            'title'       => ['en' => 'title'],
            'description' => [
                'de' => 'description de',
                'en' => 'description en',
            ],
            'lang'        => 'en',
            'thumbnail'   => 'logo.png',
            'author'      => 'author',
            'url'         => 'http://example.com',
            'email'       => 'test@example.com',
            'settings'    => [
                'version'   => '1.0',
                'templates' => [
                    'shopTemplate' => 'moduleTemplate',
                ],
            ],
            'classExtensions'    => [
                'shopClass' => 'moduleClass',
            ],
            ModuleConfigurationMappingKeys::SETTING => [
                [
                    'group'         => 'name',
                    'name'          => 'name',
                    'type'          => 'type',
                    'value'         => true,
                    'position'      => 4,
                    'constraints'   => [1, 2],
                ]
            ],
        ];

        $moduleConfigurationDataMapper = new ModuleConfigurationDataMapper();
        $moduleConfigurationDataMapper->addDataMapper(new ClassExtensionsDataMapper());

        $moduleConfiguration = new ModuleConfiguration();

        $moduleConfiguration = $moduleConfigurationDataMapper->fromData($moduleConfiguration, $configurationData);

        $expectedData = [
            'id'          => 'moduleId',
            'path'        => 'relativePath',
            'version'     => '7.0',
            'autoActive'  => true,
            'title'       => ['en' => 'title'],
            'description' => [
                'de' => 'description de',
                'en' => 'description en',
            ],
            'lang'        => 'en',
            'thumbnail'   => 'logo.png',
            'author'      => 'author',
            'url'         => 'http://example.com',
            'email'       => 'test@example.com',
            'classExtensions'    => [
                'shopClass' => 'moduleClass',
            ],
            ModuleConfigurationMappingKeys::SETTING => [
                [
                    'group'         => 'name',
                    'name'          => 'name',
                    'type'          => 'type',
                    'value'         => true,
                    'position'      => 4,
                    'constraints'   => [1, 2],
                ]
            ],
        ];

        $this->assertEquals(
            $expectedData,
            $moduleConfigurationDataMapper->toData($moduleConfiguration)
        );
    }

    /**
     * @dataProvider moduleConfigurationDataProvider
     */
    public function testToDataAndFromData(array $data, ModuleConfigurationDataMapperInterface $dataMapper)
    {

        $moduleConfiguration = new ModuleConfiguration();
        $moduleConfiguration = $dataMapper->fromData($moduleConfiguration, $data);

        $this->assertEquals(
            $data,
            $dataMapper->toData($moduleConfiguration)
        );
    }

    public function moduleConfigurationDataProvider()
    {
        return [
            [
                'data' => [
                    ModuleConfigurationMappingKeys::CONTROLLERS => [
                        'controller1' => \MyVendor\MyController\Controller1::class,
                        'controller2' => \MyVendor\MyController\Controller2::class
                    ]
                ],
                'dataMapper' => new ControllersDataMapper()

            ]
        ];
    }
}
