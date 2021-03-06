<?php

/*
.---------------------------------------------------------------------------.
|  Software: NaoNaK Web - Content Management System                         |
|   Version: 1.0                                                            |
|   Contact: via divrezstudio.fr support pages                              |
|      Info: http://www.divrezstudio.fr/app/6-naonak-web                    |
|   Support: http://www.divrezstudio.fr/forums                              |
| ------------------------------------------------------------------------- |
|     Admin: SelMaK  (project admininistrator)                              |
|   Authors: SelMaK (Yann Cario)                                            |
|                                                                           |
|   Founder: SelMaK (Yann Cario) (original founder)                         |
| Copyright (c) 2010-2018, Divrezstudio. All Rights Reserved.               |
|                                                                           |
| ------------------------------------------------------------------------- |
|   License: Distributed under the GNU/GPLv3 License                        |
|            https://www.gnu.org/licenses/gpl-3.0.fr.html                   |
| This program is distributed in the hope that it will be useful - WITHOUT  |
| ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or     |
| FITNESS FOR A PARTICULAR PURPOSE.                                         |
| ------------------------------------------------------------------------- |

*/

namespace App\Model;

use src\Model\Model;

/**
 * Description of DepartmentTable
 *
 * @author SelMaK
 */
class DepartmentModel extends Model{
    //put your code here
    protected $model = 'pl15x_departments'; // Générer un nom de table différent

        /**
     * Permet de vérifier si un nom/titre existe déjà dans la table
     *
     * @param $name
     * @return mixed
     */
    public function scanName($name){

        return $this->queryscan("SELECT id FROM " . $this->model . " WHERE name = ?", [$name]);

    }

}
