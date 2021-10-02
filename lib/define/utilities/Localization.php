<?php
declare(strict_types=1);
namespace Define\Utilities;

use Define\Traits\SingletonTrait;

if(DIRECT_ACCESS != true) die("Direct access is forbidden.");

/**
 * LOCALIZATION
 *
 * Singleton localization implementation.
 *
 * @category Define
 * @package Utilities
 * @author Nitesh Apte <me@niteshapte.com>
 * @copyright 2017 Nitesh Apte
 * @version 2.0.0
 * @since 1.0.0
 * @license https://www.gnu.org/licenses/gpl.txt GNU General Public License v3
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
class Localization implements IUtilities {

    // Yes, keep it singleton
    use SingletonTrait;

    /**
     * Load path of language file
     *
     * @param string $language
     * @return string
     */
    public function loadLanguage(string $language = "en") : string {
        return file_exists(LOCALIZATION_PATH.$language.'/'.$language.'.php') ? LOCALIZATION_PATH.$language.'/'.$language.'.php' : LOCALIZATION_PATH.DEFAULT_LANGUAGE.'/'.DEFAULT_LANGUAGE.'.php';
    }
}