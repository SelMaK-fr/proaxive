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
namespace App\Entity;
use src\Entity\Entity;

/**
 * Description of StatusEntity
 *
 * @author SelMaK
 */
class StatusEntity extends Entity{

    /**
     * Genere une URL de l'item Post
     */

    public function geturlAdmin(){

        $url = '/admin/edit-status/'. $this->id;
        return $url;

    }
    /**
     * Explore les statuts
     * @return
     */
    
    public function getStatus(){
        $var = '<span class="label" style="background-color:#'.$this->bgcolor.'">'.$this->title.'</span>';
        return $var;

    }
}
