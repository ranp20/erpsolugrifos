<?php
class Menu {
    public function dynamicMenu_aQ(){
        
        $html = '';
        $items[] = [];
        $CI = &get_instance();
        $sess_user = $CI->session->userdata();
        
        /* VALIDACIÓN DE VISIBILIDAD DE LOS AJUSTES */
        $layout_settins = '';
        $name = '';
        if(isset($sess_user) && !empty($sess_user)){
            if($sess_user['user_id'] == 1 || $sess_user['role_id'] == 1){
                $layout_settins = '<li class="';
                $layout_settins .= $name == 'settings' ? 'active' : '';
                $layout_settins .= '">
                    <a title="Ajustes" href="' . base_url() . 'admin/settings">
                        <em class="fa fa-cog"></em>
                        <span>Ajustes</span>
                    </a>
                    </li>
                </ul>';
            }else{
                $layout_settins = '</ul>';
            }
        }

        $name = $CI->uri->segment(2);
        $html .= '<ul class="nav cSdbarNav-c__navSecond">
                    <li class="nav-item ';
        $html .= $name == 'anuncio' ? 'active' : '';
        $html .= '">
                        <a data-toggle="collapse" href="#advertisements" role="button" aria-expanded="false" aria-controls="advertisements">
                            <em class="fa fa-tags"></em>
                            <span>Anuncios</span>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse" id="advertisements">
                            <ul class="nav nav-collapse">
                                <li>
                                    <a title="Secciones" class="sub-link" href="' . base_url() . 'admin/announcements_section">
                                        <em class="fa fa-flag"></em>
                                        <span class="sub-item">Secciones</span>
                                    </a>
                                </li>
                                <li>
                                    <a title="Anuncios" class="sub-link" href="' . base_url() . 'admin/anuncio">
                                        <em class="fa fa-list"></em>
                                        <span class="sub-item">Listar</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li> 
                    <li class="';
        $html .= $name == 'anio' ? 'active' : '';
        $html .= '">
                        <a title="Años" href="' . base_url() . 'admin/anio">
                        <em class="fa fa-bookmark"></em><span>Años</span></a>
                    </li> 
                    <li class="';
        $html .= $name == 'categoria' ? 'active' : '';
        $html .= '">
                        <a title="Categoría" href="' . base_url() . 'admin/categoria/manage_categoria">
                        <em class="fa fa-cube"></em><span>Categorías</span></a>
                    </li>
                    <li class="';
        $html .= $name == 'subcategoria' ? 'active' : '';
        $html .= '">
                        <a title="Subcategoría" href="' . base_url() . 'admin/subcategoria">
                        <em class="fa fa-cubes"></em><span>Sub Categoría</span></a>
                    </li> 
                    <li class="';
        $html .= $name == 'cliente' ? 'active' : '';
        $html .= '">
                        <a title="Clientes" href="' . base_url() . 'admin/cliente">
                        <em class="fa fa-users"></em><span>Clientes</span></a>
                    </li> 
                    <li class="';
        $html .= $name == 'document_client' ? 'active' : '';
        $html .= '">
                        <a title="Documentos" href="' . base_url() . 'admin/document_client">
                        <em class="fa fa-folder"></em><span>Documentos</span></a>
                    </li>'.$layout_settins;
        
        return $html;
    }
    public function clientMenu_aQ($data){
        $html = '';
        $items[] = [];
        $CI = &get_instance();
        $name = $CI->uri->segment(2);
        
        $settings = $CI->db->order_by('config_key')->get('tbl_config')->result();
        
        $admSettings = [];
        foreach($settings as $config){
            $admSettings[$config->config_key] = $config->value;
        }
        
        $html .= '<ul class="nav s-menu cSdbarNav-c__navSecond">
                    <li class="';
        $html .= $name == 'dashboard' ? 'active' : '';
        $html .= '">
                        <a title="tablero" href="' . base_url() . 'client/dashboard">
                            <em class="fa fa-tags"></em>
                            <span>Anuncios</span>
                        </a>
                    </li>
                    <li class="nav-item ';
                    $html .= $name == 'sede' ? 'active' : '';
        $html .= '">
                        <a title="Sedes" href="' . base_url() . 'client/sede/">
                        <em class="fa fa-folder"></em><span>Documentos</span></a>
                        <!--
                        <a data-toggle="collapse" href="#advertisements" role="button" aria-expanded="false" aria-controls="advertisements">
                            <em class="fa fa-tags"></em>
                            <span>Documentos</span>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse" id="advertisements">
                            <ul class="nav nav-collapse">
                                <li>
                                    <a title="Secciones" class="sub-link" href="' . base_url() . 'admin/announcements_section">
                                        <span class="sub-item">Secciones</span>
                                    </a>
                                </li>
                                <li>
                                    <a title="Anuncios" class="sub-link" href="' . base_url() . 'admin/anuncio">
                                        <span class="sub-item">Listar</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        -->
                    </li>';
        /*          
        foreach ($data as $key => $subcat) {
            $subcat = (object) $subcat;
            $html .= '<li class="">
                        <a data-toggle="modal" data-target="#myModal" title="' . $subcat->nombre_subcategoria . '" href="' . base_url() . 'client/document/add_document/' . $subcat->subcategoria_id . '">
                        <em class="fa fa-book"></em><span>' . $subcat->nombre_subcategoria . '</span></a>
                    </li>';
        }
        */
        $url = ( isset( $_SESSION['sede'] ) && !empty( $_SESSION['sede'] )) ? 'client/subcategoria/list/5' : 'client/sede';
        $html .= '<li class="">
                        <a title="ACTIVIDADES MENSUALES" href="' . base_url() . $url. '">
                        <em class="fa fa-book"></em><span>Actividades Mensuales</span></a>
                    </li>';
        $html .= '<li class="';
        $html .= $name == 'settings' ? 'active' : '';
        $html .= '">
            <a title="Datos de Perfil" href="' . base_url() . 'client/settings/update_profile/">
            <em class="fa fa-user"></em><span>Datos de Perfil</span></a>
        </li>';
        $html .= '
            </ul>
            <div class="cNv__c__cIcons">
                <div class="cNv__c__cIcons__cC">
                    <a href="'.$admSettings['socialnetwork_facebook_url'].'" target="_blank" title="Facebook" data-toggle="tooltip" data-placement="bottom">
                        <svg class="" aria-hidden="true" focusable="false" data-prefix="fab" data-icon="facebook" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-facebook fa-w-16 fa-7x"><path fill="currentColor" d="M504 256C504 119 393 8 256 8S8 119 8 256c0 123.78 90.69 226.38 209.25 245V327.69h-63V256h63v-54.64c0-62.15 37-96.48 93.67-96.48 27.14 0 55.52 4.84 55.52 4.84v61h-31.28c-30.8 0-40.41 19.12-40.41 38.73V256h68.78l-11 71.69h-57.78V501C413.31 482.38 504 379.78 504 256z" class=""></path></svg>
                    </a>
                    <a href="'.$admSettings['socialnetwork_instagram_url'].'" target="_blank" title="Instagram" data-toggle="tooltip" data-placement="bottom">
                        <svg class="" aria-hidden="true" focusable="false" data-prefix="fab" data-icon="instagram-square" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="svg-inline--fa fa-instagram-square fa-w-14 fa-7x"><path fill="currentColor" d="M224,202.66A53.34,53.34,0,1,0,277.36,256,53.38,53.38,0,0,0,224,202.66Zm124.71-41a54,54,0,0,0-30.41-30.41c-21-8.29-71-6.43-94.3-6.43s-73.25-1.93-94.31,6.43a54,54,0,0,0-30.41,30.41c-8.28,21-6.43,71.05-6.43,94.33S91,329.26,99.32,350.33a54,54,0,0,0,30.41,30.41c21,8.29,71,6.43,94.31,6.43s73.24,1.93,94.3-6.43a54,54,0,0,0,30.41-30.41c8.35-21,6.43-71.05,6.43-94.33S357.1,182.74,348.75,161.67ZM224,338a82,82,0,1,1,82-82A81.9,81.9,0,0,1,224,338Zm85.38-148.3a19.14,19.14,0,1,1,19.13-19.14A19.1,19.1,0,0,1,309.42,189.74ZM400,32H48A48,48,0,0,0,0,80V432a48,48,0,0,0,48,48H400a48,48,0,0,0,48-48V80A48,48,0,0,0,400,32ZM382.88,322c-1.29,25.63-7.14,48.34-25.85,67s-41.4,24.63-67,25.85c-26.41,1.49-105.59,1.49-132,0-25.63-1.29-48.26-7.15-67-25.85s-24.63-41.42-25.85-67c-1.49-26.42-1.49-105.61,0-132,1.29-25.63,7.07-48.34,25.85-67s41.47-24.56,67-25.78c26.41-1.49,105.59-1.49,132,0,25.63,1.29,48.33,7.15,67,25.85s24.63,41.42,25.85,67.05C384.37,216.44,384.37,295.56,382.88,322Z" class=""></path></svg>
                    </a>
                    <a href="'.$admSettings['socialnetwork_linkedin_url'].'" target="_blank" title="Linkedin" data-toggle="tooltip" data-placement="bottom">
                        <svg class="" aria-hidden="true" focusable="false" data-prefix="fab" data-icon="instagram-square" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="currentColor" d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                    </a>
                </div>
            </div>';

        return $html;
    }
    public function dynamicMenu()
    {

        $CI = &get_instance();
        $designations_id = $CI->session->userdata('designations_id');
        $user_type = $CI->session->userdata('user_type');

        if ($user_type != 1) { // query for employee user role
            $CI->db->select('tbl_user_role.*', FALSE);
            $CI->db->select('tbl_menu.*', FALSE);
            $CI->db->from('tbl_user_role');
            $CI->db->join('tbl_menu', 'tbl_user_role.menu_id = tbl_menu.menu_id', 'left');
            $CI->db->where('tbl_user_role.designations_id', $designations_id);
            $CI->db->where('tbl_menu.status', 1);
            $CI->db->order_by('sort');
            $query_result = $CI->db->get();
            $user_menu = $query_result->result();
        } else { // get all menu for admin
            $user_menu = $CI->db->where('status', 1)->order_by('sort', 'time')->get('tbl_menu')->result();
        }
        // Create a multidimensional array to conatin a list of items and parents
        $menu = array(
            'items' => array(),
            'parents' => array()
        );

        foreach ($user_menu as $v_menu) {
            $menu['items'][$v_menu->menu_id] = $v_menu;
            $menu['parents'][$v_menu->parent][] = $v_menu->menu_id;
        }
        // Builds the array lists with data from the menu table
        return $output = $this->buildMenu(0, $menu);
    }

    public function clientMenu()
    {
        $CI = &get_instance();
        $user_id = $CI->session->userdata('user_id');
        $CI->db->select('tbl_client_role.*', FALSE);
        $CI->db->select('tbl_client_menu.*', FALSE);
        $CI->db->from('tbl_client_role');
        $CI->db->join('tbl_client_menu', 'tbl_client_role.menu_id = tbl_client_menu.menu_id', 'left');
        $CI->db->where('tbl_client_role.user_id', $user_id);
        $CI->db->order_by('sort');
        $query_result = $CI->db->get();
        $client_menu = $query_result->result();

        // Create a multidimensional array to conatin a list of items and parents
        $menu = array(
            'items' => array(),
            'parents' => array()
        );
        foreach ($client_menu as $v_menu) {
            $menu['items'][$v_menu->menu_id] = $v_menu;
            $menu['parents'][$v_menu->parent][] = $v_menu->menu_id;
        }
        // Builds the array lists with data from the menu table
        return $output = $this->buildMenu(0, $menu);
    }

    public function buildMenu($parent, $menu, $sub = NULL)
    {
        $html = "";
        $ul_class = "";
        if (!empty(config_item('layout-h'))) {
            $ul_class = 'horizontal_menu navbar-nav';
        }
        if (isset($menu['parents'][$parent])) {
            if (!empty($sub)) {
                $html .= "<ul id=" . $sub . " class='nav s-menu sidebar-subnav collapse'><li class=\"sidebar-subnav-header\">" . lang($sub) . "</li>\n";
            } else {
                $html .= "<ul class='nav s-menu $ul_class'>\n";
            }
            foreach ($menu['parents'][$parent] as $itemId) {
                $result = $this->active_menu_id($menu['items'][$itemId]->menu_id);
                if ($result) {
                    $active = 'active';
                } else {
                    $active = '';
                }
                if ($menu['items'][$itemId]->link == 'knowledgebase') {
                    $terget = 'target="_blank"';
                } else {
                    $terget = null;
                }
                if (!isset($menu['parents'][$itemId])) { //if condition is false only view menu
                    $html .= "<li class='" . $active . "' >\n  <a $terget title='" . lang($menu['items'][$itemId]->label) . "' href='" . base_url() . $menu['items'][$itemId]->link . "'>\n <em class='" . $menu['items'][$itemId]->icon . "'></em><span>" . lang($menu['items'][$itemId]->label) . "</span></a>\n</li> \n";
                }
                if (isset($menu['parents'][$itemId])) { //if condition is true show with submenu
                    $html .= "<li class='sub-menu " . $active . "'>\n  <a data-toggle='collapse' href='#" . $menu['items'][$itemId]->label . "'> <em class='" . $menu['items'][$itemId]->icon . "'></em><span>" . lang($menu['items'][$itemId]->label) . "</span></a>\n";
                    $html .= self::buildMenu($itemId, $menu, $menu['items'][$itemId]->label);
                    $html .= "</li> \n";
                }
            }
            $html .= "</ul> \n";
        }
        return $html;
    }

    public function active_menu_id($id)
    {
        $CI = &get_instance();
        $activeId = $CI->session->userdata('menu_active_id');
        if (!empty($activeId)) {
            foreach ($activeId as $v_activeId) {
                if ($id == $v_activeId) {
                    return TRUE;
                }
            }
        }
        return FALSE;
    }
}
