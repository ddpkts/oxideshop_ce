<?php
/**
 * This file is part of OXID eShop Community Edition.
 *
 * OXID eShop Community Edition is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OXID eShop Community Edition is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OXID eShop Community Edition.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @link      http://www.oxid-esales.com
 * @copyright (C) OXID eSales AG 2003-2016
 * @version   OXID eShop CE
 */

namespace OxidEsales\EshopCommunity\Core\Service;

/**
 * Manages application servers information.
 *
 * @internal Do not make a module extension for this class.
 * @see      http://oxidforge.org/en/core-oxid-eshop-classes-must-not-be-extended.html
 */
interface ApplicationServerServiceInterface
{
    /**
     * Returns all servers information array from configuration.
     *
     * @return \OxidEsales\Eshop\Core\DataObject\ApplicationServer[]
     */
    public function loadAppServerList();

    /**
     * Load the application server for given id.
     *
     * @param string $id The id of the application server to load.
     *
     * @return \OxidEsales\Eshop\Core\DataObject\ApplicationServer
     */
    public function loadAppServer($id);

    /**
     * Removes server node information.
     *
     * @param string $serverId
     */
    public function deleteAppServerById($serverId);

    /**
     * Saves application server data.
     *
     * @param \OxidEsales\Eshop\Core\DataObject\ApplicationServer $appServer
     */
    public function saveAppServer($appServer);

    /**
     * Returns an array of all only active application servers.
     *
     * @return array
     */
    public function loadActiveAppServerList();

    /**
     * Renews application server information when it is call in admin area and
     * if it is outdated or if it does not exist.
     */
    public function updateAppServerInformationInAdmin();

    /**
     * Renews application server information when it is call in frontend and
     * if it is outdated or if it does not exist.
     */
    public function updateAppServerInformationInFrontend();
}