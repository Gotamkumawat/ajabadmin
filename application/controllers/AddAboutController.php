<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AddAboutController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('upload');
        $this->load->model('AddAboutModel');
    }

    public function addAbout() {
        $this->load->view('about-header');
    }

    private function mapAjabTypeToInt($typeLabel) {
        $this->_ensure_ajab_menus_table();
        $normalized = strtolower(trim((string)$typeLabel));
        if ($normalized === '') return null;
        $row = $this->db->select('id')->where('LOWER(slug)', $normalized)->get('ajab_menus')->row();
        return $row ? (int)$row->id : null;
    }

    private function _ensure_ajab_menus_table() {
        if ($this->db->table_exists('ajab_menus')) return;
        $sql = "CREATE TABLE IF NOT EXISTS `ajab_menus` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `slug` VARCHAR(100) NOT NULL,
            `label` VARCHAR(150) NOT NULL,
            `sort_order` INT(11) NOT NULL DEFAULT 0,
            `created_at` DATETIME NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `slug` (`slug`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        $this->db->query($sql);
        $seed = [
            ['slug' => 'intro',          'label' => 'Intro',          'sort_order' => 1],
            ['slug' => 'translit guide', 'label' => 'Translit Guide', 'sort_order' => 2],
            ['slug' => 'copyrights',     'label' => 'Copyrights',     'sort_order' => 3],
        ];
        foreach ($seed as $r) {
            $r['created_at'] = date('Y-m-d H:i:s');
            $this->db->insert('ajab_menus', $r);
        }
    }

    public function get_ajab_menus() {
        $this->_ensure_ajab_menus_table();
        header('Content-Type: application/json; charset=utf-8');
        $rows = $this->db->order_by('sort_order', 'ASC')->order_by('id', 'ASC')->get('ajab_menus')->result();
        echo json_encode(['status' => true, 'data' => $rows]);
    }

    public function create_ajab_menu() {
        $this->_ensure_ajab_menus_table();
        header('Content-Type: application/json; charset=utf-8');
        $label = trim((string)$this->input->post('label', true));
        if ($label === '') {
            echo json_encode(['status' => false, 'message' => 'Label is required']); return;
        }
        $slug = strtolower(preg_replace('/\s+/', ' ', trim($label)));
        $exists = $this->db->where('LOWER(slug)', $slug)->get('ajab_menus')->row();
        if ($exists) {
            echo json_encode(['status' => false, 'message' => 'Menu already exists', 'data' => $exists]); return;
        }
        $maxRow = $this->db->select_max('sort_order', 'maxOrder')->get('ajab_menus')->row();
        $next = ($maxRow && $maxRow->maxOrder !== null) ? ((int)$maxRow->maxOrder + 1) : 1;
        $data = [
            'slug' => $slug,
            'label' => $label,
            'sort_order' => $next,
            'created_at' => date('Y-m-d H:i:s'),
        ];
        if ($this->db->insert('ajab_menus', $data)) {
            $data['id'] = (int)$this->db->insert_id();
            echo json_encode(['status' => true, 'data' => $data]);
        } else {
            $err = $this->db->error();
            echo json_encode(['status' => false, 'message' => !empty($err['message']) ? $err['message'] : 'Insert failed']);
        }
    }

    public function delete_ajab_menu($id) {
        $this->_ensure_ajab_menus_table();
        header('Content-Type: application/json; charset=utf-8');
        $id = (int)$id;
        if ($id <= 0) { echo json_encode(['status' => false, 'message' => 'Invalid id']); return; }
        $used = $this->db->where('ajab_type', $id)->where('status', 0)->count_all_results('about');
        if ($used > 0) {
            echo json_encode(['status' => false, 'message' => 'Cannot delete: menu has saved content. Remove content first.']);
            return;
        }
        $this->db->where('id', $id)->delete('ajab_menus');
        echo json_encode(['status' => true]);
    }

    private function mapKabirTypeToInt($typeLabel) {
        $this->_ensure_kabir_menus_table();
        $normalized = strtolower(trim((string)$typeLabel));
        if ($normalized === '') return null;
        $row = $this->db->select('id')->where('LOWER(slug)', $normalized)->get('kabir_menus')->row();
        return $row ? (int)$row->id : null;
    }

    private function _ensure_kabir_menus_table() {
        if ($this->db->table_exists('kabir_menus')) return;
        $sql = "CREATE TABLE IF NOT EXISTS `kabir_menus` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `slug` VARCHAR(100) NOT NULL,
            `label` VARCHAR(150) NOT NULL,
            `sort_order` INT(11) NOT NULL DEFAULT 0,
            `created_at` DATETIME NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `slug` (`slug`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        $this->db->query($sql);
        $seed = [
            ['slug' => 'intro',         'label' => 'Intro',         'sort_order' => 1],
            ['slug' => 'team',          'label' => 'Team',          'sort_order' => 2],
            ['slug' => 'films',         'label' => 'Films',         'sort_order' => 3],
            ['slug' => 'books',         'label' => 'Books',         'sort_order' => 4],
            ['slug' => 'shabad shaala', 'label' => 'Shabad Shaala', 'sort_order' => 5],
        ];
        foreach ($seed as $r) {
            $r['created_at'] = date('Y-m-d H:i:s');
            $this->db->insert('kabir_menus', $r);
        }
    }

    public function get_kabir_menus() {
        $this->_ensure_kabir_menus_table();
        header('Content-Type: application/json; charset=utf-8');
        $rows = $this->db->order_by('sort_order', 'ASC')->order_by('id', 'ASC')->get('kabir_menus')->result();
        echo json_encode(['status' => true, 'data' => $rows]);
    }

    public function create_kabir_menu() {
        $this->_ensure_kabir_menus_table();
        header('Content-Type: application/json; charset=utf-8');
        $label = trim((string)$this->input->post('label', true));
        if ($label === '') {
            echo json_encode(['status' => false, 'message' => 'Label is required']); return;
        }
        $slug = strtolower(preg_replace('/\s+/', ' ', trim($label)));
        $exists = $this->db->where('LOWER(slug)', $slug)->get('kabir_menus')->row();
        if ($exists) {
            echo json_encode(['status' => false, 'message' => 'Menu already exists', 'data' => $exists]); return;
        }
        $maxRow = $this->db->select_max('sort_order', 'maxOrder')->get('kabir_menus')->row();
        $next = ($maxRow && $maxRow->maxOrder !== null) ? ((int)$maxRow->maxOrder + 1) : 1;
        $data = [
            'slug' => $slug,
            'label' => $label,
            'sort_order' => $next,
            'created_at' => date('Y-m-d H:i:s'),
        ];
        if ($this->db->insert('kabir_menus', $data)) {
            $data['id'] = (int)$this->db->insert_id();
            echo json_encode(['status' => true, 'data' => $data]);
        } else {
            $err = $this->db->error();
            echo json_encode(['status' => false, 'message' => !empty($err['message']) ? $err['message'] : 'Insert failed']);
        }
    }

    public function delete_kabir_menu($id) {
        $this->_ensure_kabir_menus_table();
        header('Content-Type: application/json; charset=utf-8');
        $id = (int)$id;
        if ($id <= 0) { echo json_encode(['status' => false, 'message' => 'Invalid id']); return; }
        // Block deletion if any kabir content rows reference this menu
        $used = $this->db->where('kabir_type', $id)->where('status', 1)->count_all_results('about');
        if ($used > 0) {
            echo json_encode(['status' => false, 'message' => 'Cannot delete: menu has saved content. Remove content first.']);
            return;
        }
        $this->db->where('id', $id)->delete('kabir_menus');
        echo json_encode(['status' => true]);
    }

    private function _handle_menu_image_upload($redirectOnError = 'ajab-shahar') {
        // Upload new file if present, else return existing path from POST
        if (!empty($_FILES['menu_image']['name'])) {
            $dir = FCPATH . 'images/';
            if (!is_dir($dir)) { @mkdir($dir, 0755, true); }
            $this->load->library('upload');
            $cfg = [
                'upload_path'   => $dir,
                'allowed_types' => 'jpg|jpeg|png|gif|webp|avif|svg',
                'max_size'      => 4096,
                'file_name'     => time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $_FILES['menu_image']['name']),
            ];
            $this->upload->initialize($cfg);
            if ($this->upload->do_upload('menu_image')) {
                $up = $this->upload->data();
                return 'images/' . $up['file_name'];
            }
            $this->session->set_flashdata('error', $this->upload->display_errors());
            redirect($redirectOnError);
            exit;
        }
        $existing = $this->input->post('menu_image_existing');
        return ($existing !== null && $existing !== '') ? trim((string)$existing) : '';
    }

    public function save_ajab_shahar() {
        @file_put_contents(FCPATH . 'about_debug.log',
            "[".date('Y-m-d H:i:s')."] save_ajab_shahar POST keys: ".implode(',', array_keys($_POST))."\n"
            ."POST[meta_title]=".(isset($_POST['meta_title'])?var_export($_POST['meta_title'],true):'(unset)')."\n"
            ."POST[meta_keywords]=".(isset($_POST['meta_keywords'])?var_export($_POST['meta_keywords'],true):'(unset)')."\n"
            ."POST[meta_description]=".(isset($_POST['meta_description'])?var_export(substr((string)$_POST['meta_description'],0,200),true):'(unset)')."\n"
            ."POST[visual_content]=".(isset($_POST['visual_content'])?'(present, len='.strlen((string)$_POST['visual_content']).')':'(unset)')."\n"
            ."---\n", FILE_APPEND);
        if (!$this->db->table_exists('about')) {
            $this->session->set_flashdata('error', 'about table not found.');
            redirect('ajab-shahar');
            return;
        }

        $typeLabel = $this->input->post('type', true);
        $typeValue = $this->mapAjabTypeToInt($typeLabel);
        // Visual content from canonical field (legacy fallback to meta_description for old forms)
        $visualContent = $this->input->post('visual_content', false);
        if ($visualContent === null || trim((string)$visualContent) === '') {
            $visualContent = $this->input->post('meta_description', false);
        }

        if ($typeValue === null) {
            $this->session->set_flashdata('error', 'Invalid type selected.');
            redirect('ajab-shahar');
            return;
        }

        if (trim((string)$visualContent) === '') {
            $this->session->set_flashdata('error', 'Visual content is required.');
            redirect('ajab-shahar');
            return;
        }

        $menuImage = $this->_handle_menu_image_upload('ajab-shahar');

        $data = [
            'ajab_type' => $typeValue,
            'visual_content' => $visualContent,
            'meta_title' => $this->input->post('meta_title') ?? '',
            'meta_keywords' => $this->input->post('meta_keywords') ?? '',
            'meta_description' => $this->input->post('meta_description') ?? '',
            'menu_image' => $menuImage,
            'status' => 0,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $insert = $this->db->insert('about', $data);
        if ($insert) {
            // Logo is shared across all Ajab Shahar tabs — propagate to all status=0 rows
            if ($menuImage !== '') {
                $this->db->where('status', 0)->update('about', ['menu_image' => $menuImage]);
            }
            $this->session->set_flashdata('success', 'Ajab Shahar content saved successfully!');
        } else {
            $dbError = $this->db->error();
            $message = !empty($dbError['message']) ? $dbError['message'] : 'Failed to save Ajab Shahar content.';
            $this->session->set_flashdata('error', $message);
        }

        redirect('ajab-shahar');
    }

    public function update_ajab_shahar($id) {
        @file_put_contents(FCPATH . 'about_debug.log',
            "[".date('Y-m-d H:i:s')."] update_ajab_shahar id=$id POST keys: ".implode(',', array_keys($_POST))."\n"
            ."POST[meta_title]=".(isset($_POST['meta_title'])?var_export($_POST['meta_title'],true):'(unset)')."\n"
            ."POST[meta_keywords]=".(isset($_POST['meta_keywords'])?var_export($_POST['meta_keywords'],true):'(unset)')."\n"
            ."POST[meta_description]=".(isset($_POST['meta_description'])?var_export(substr((string)$_POST['meta_description'],0,200),true):'(unset)')."\n"
            ."POST[visual_content]=".(isset($_POST['visual_content'])?'(present, len='.strlen((string)$_POST['visual_content']).')':'(unset)')."\n"
            ."---\n", FILE_APPEND);
        if (!$this->db->table_exists('about')) {
            $this->session->set_flashdata('error', 'about table not found.');
            redirect('ajab-shahar');
            return;
        }

        if (empty($id) || !is_numeric($id)) {
            $this->session->set_flashdata('error', 'Invalid Ajab Shahar ID.');
            redirect('ajab-shahar');
            return;
        }

        $typeLabel = $this->input->post('type', true);
        $typeValue = $this->mapAjabTypeToInt($typeLabel);
        // Visual content from canonical field (legacy fallback to meta_description for old forms)
        $visualContent = $this->input->post('visual_content', false);
        if ($visualContent === null || trim((string)$visualContent) === '') {
            $visualContent = $this->input->post('meta_description', false);
        }

        if ($typeValue === null) {
            $this->session->set_flashdata('error', 'Invalid type selected.');
            redirect('ajab-shahar');
            return;
        }

        if (trim((string)$visualContent) === '') {
            $this->session->set_flashdata('error', 'Visual content is required.');
            redirect('ajab-shahar');
            return;
        }

        $menuImage = $this->_handle_menu_image_upload('ajab-shahar');

        $data = [
            'ajab_type' => $typeValue,
            'visual_content' => $visualContent,
            'meta_title' => $this->input->post('meta_title') ?? '',
            'meta_keywords' => $this->input->post('meta_keywords') ?? '',
            'meta_description' => $this->input->post('meta_description') ?? '',
            'menu_image' => $menuImage,
            'status' => 0
        ];

        $this->db->where('id', (int)$id);
        $this->db->where('status', 0);
        $update = $this->db->update('about', $data);

        if ($update) {
            // Logo is shared across all Ajab Shahar tabs — propagate to all status=0 rows
            if ($menuImage !== '') {
                $this->db->where('status', 0)->update('about', ['menu_image' => $menuImage]);
            }
            $this->session->set_flashdata('success', 'Ajab Shahar content updated successfully!');
        } else {
            $dbError = $this->db->error();
            $message = !empty($dbError['message']) ? $dbError['message'] : 'Failed to update Ajab Shahar content.';
            $this->session->set_flashdata('error', $message);
        }

        redirect('ajab-shahar');
    }

    public function save_kabir_project() {
        if (!$this->db->table_exists('about')) {
            $this->session->set_flashdata('error', 'about table not found.');
            redirect('kabir-project');
            return;
        }

        $typeLabel = $this->input->post('type', true);
        $typeValue = $this->mapKabirTypeToInt($typeLabel);
        // Visual content from canonical field (legacy fallback to meta_description for old forms)
        $visualContent = $this->input->post('visual_content', false);
        if ($visualContent === null || trim((string)$visualContent) === '') {
            $visualContent = $this->input->post('meta_description', false);
        }

        if ($typeValue === null) {
            $this->session->set_flashdata('error', 'Invalid type selected.');
            redirect('kabir-project');
            return;
        }

        if (trim((string)$visualContent) === '') {
            $this->session->set_flashdata('error', 'Visual content is required.');
            redirect('kabir-project');
            return;
        }

        $menuImage = $this->_handle_menu_image_upload('kabir-project');

        $data = [
            'kabir_type' => $typeValue,
            'visual_content' => $visualContent,
            'meta_title' => $this->input->post('meta_title') ?? '',
            'meta_keywords' => $this->input->post('meta_keywords') ?? '',
            'meta_description' => $this->input->post('meta_description') ?? '',
            'menu_image' => $menuImage,
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $insert = $this->db->insert('about', $data);
        if ($insert) {
            // Logo is shared across all Kabir Project tabs — propagate to all status=1 rows
            if ($menuImage !== '') {
                $this->db->where('status', 1)->update('about', ['menu_image' => $menuImage]);
            }
            $this->session->set_flashdata('success', 'Kabir Project content saved successfully!');
        } else {
            $dbError = $this->db->error();
            $message = !empty($dbError['message']) ? $dbError['message'] : 'Failed to save Kabir Project content.';
            $this->session->set_flashdata('error', $message);
        }

        redirect('kabir-project');
    }

    public function update_kabir_project($id) {
        if (!$this->db->table_exists('about')) {
            $this->session->set_flashdata('error', 'about table not found.');
            redirect('kabir-project');
            return;
        }

        if (empty($id) || !is_numeric($id)) {
            $this->session->set_flashdata('error', 'Invalid Kabir Project ID.');
            redirect('kabir-project');
            return;
        }

        $typeLabel = $this->input->post('type', true);
        $typeValue = $this->mapKabirTypeToInt($typeLabel);
        // Visual content from canonical field (legacy fallback to meta_description for old forms)
        $visualContent = $this->input->post('visual_content', false);
        if ($visualContent === null || trim((string)$visualContent) === '') {
            $visualContent = $this->input->post('meta_description', false);
        }

        if ($typeValue === null) {
            $this->session->set_flashdata('error', 'Invalid type selected.');
            redirect('kabir-project');
            return;
        }

        if (trim((string)$visualContent) === '') {
            $this->session->set_flashdata('error', 'Visual content is required.');
            redirect('kabir-project');
            return;
        }

        $menuImage = $this->_handle_menu_image_upload('kabir-project');

        $data = [
            'kabir_type' => $typeValue,
            'visual_content' => $visualContent,
            'meta_title' => $this->input->post('meta_title') ?? '',
            'meta_keywords' => $this->input->post('meta_keywords') ?? '',
            'meta_description' => $this->input->post('meta_description') ?? '',
            'menu_image' => $menuImage,
            'status' => 1
        ];

        $this->db->where('id', (int)$id);
        $this->db->where('status', 1);
        $update = $this->db->update('about', $data);

        if ($update) {
            // Logo is shared across all Kabir Project tabs — propagate to all status=1 rows
            if ($menuImage !== '') {
                $this->db->where('status', 1)->update('about', ['menu_image' => $menuImage]);
            }
            $this->session->set_flashdata('success', 'Kabir Project content updated successfully!');
        } else {
            $dbError = $this->db->error();
            $message = !empty($dbError['message']) ? $dbError['message'] : 'Failed to update Kabir Project content.';
            $this->session->set_flashdata('error', $message);
        }

        redirect('kabir-project');
    }

    public function save() {
        $image = '';
        if (!empty($_FILES['image']['name'])) {
            $upload_path = FCPATH . 'uploads/';
            if (!is_dir($upload_path)) {
                @mkdir($upload_path, 0755, true);
            }
            if (!is_dir($upload_path) || !is_writable($upload_path)) {
                $this->session->set_flashdata('error', 'Upload path is not valid or writable. Please check the uploads folder permissions.');
                redirect('about-header');
            }
            $config['upload_path'] = $upload_path;
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size'] = 2048;
            $config['file_name'] = time() . '_' . str_replace(' ', '_', $_FILES['image']['name']);
            $this->upload->initialize($config);
            if ($this->upload->do_upload('image')) {
                $image = $this->upload->data('file_name');
            } else {
                $this->session->set_flashdata('error', 'Failed to upload image: ' . $this->upload->display_errors());
                redirect('about-header');
            }
        }

        $data = array(
            'menu_selection' => $this->input->post('menu_selection'),
            'select_menu' => $this->input->post('select_menu'),
            'new_menu' => $this->input->post('new_menu'),
            'image' => $image,
            'select_menu2' => $this->input->post('select_menu2'),
            'add_new_menu' => $this->input->post('add_new_menu'),
            'sub_menu_tab_content' => $this->input->post('sub_menu_tab_content'),
            'is_published' => $this->input->post('is_published'),
            'meta_data_keyword' => $this->input->post('meta_data_keyword'),
            'meta_description' => $this->input->post('meta_description'),
            'date_of_upload' => date('Y-m-d H:i:s')
        );

        $insert = $this->AddAboutModel->insert_about($data);
        if ($insert) {
            $this->session->set_flashdata('success', 'About header saved successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to save about header: ' . $this->db->_error_message());
        }
        redirect('about-header');
    }

    public function edit($id) {
        $data['about_header'] = $this->AddAboutModel->get_about_by_id($id);
        if (!$data['about_header']) {
            $this->session->set_flashdata('error', 'About header not found!');
            redirect('about-header-list');
        }
        $this->load->view('about-header', $data);
    }

    public function update($id) {
        $image = $this->input->post('existing_image');
        if (!empty($_FILES['image']['name'])) {
            $config['upload_path'] = FCPATH . 'uploads/';
            if (!is_dir($config['upload_path'])) { @mkdir($config['upload_path'], 0755, true); }
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size'] = 2048;
            $config['file_name'] = time() . '_' . str_replace(' ', '_', $_FILES['image']['name']);
            $this->load->library('upload', $config);
            if ($this->upload->do_upload('image')) {
                $image = $this->upload->data('file_name');
                $existing_about = $this->AddAboutModel->get_about_by_id($id);
                $existingPath = FCPATH . 'uploads/' . $existing_about->image;
                if ($existing_about->image && file_exists($existingPath)) {
                    @unlink($existingPath);
                }
            } else {
                $this->session->set_flashdata('error', 'Failed to upload image: ' . $this->upload->display_errors());
                redirect('about-header/edit/' . $id);
            }
        }

        $data = array(
            'menu_selection' => $this->input->post('menu_selection'),
            'select_menu' => $this->input->post('select_menu'),
            'new_menu' => $this->input->post('new_menu'),
            'image' => $image,
            'select_menu2' => $this->input->post('select_menu2'),
            'add_new_menu' => $this->input->post('add_new_menu'),
            'sub_menu_tab_content' => $this->input->post('sub_menu_tab_content'),
            'is_published' => $this->input->post('is_published'),
            'meta_data_keyword' => $this->input->post('meta_data_keyword'),
            'meta_description' => $this->input->post('meta_description'),
            'date_of_upload' => date('Y-m-d H:i:s')
        );

        $update = $this->AddAboutModel->update_about($id, $data);
        if ($update) {
            $this->session->set_flashdata('success', 'About header updated successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to update about header: ' . $this->db->_error_message());
        }
        redirect('about-header-list');
    }

    public function delete($id) {
        $about = $this->AddAboutModel->get_about_by_id($id);
        if ($about->image && file_exists('./Uploads/' . $about->image)) {
            unlink('./Uploads/' . $about->image);
        }
        $delete = $this->AddAboutModel->delete_about($id);
        if ($delete) {
            echo json_encode(['status' => 'success', 'message' => 'About header deleted successfully!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete about header: ' . $this->db->_error_message()]);
        }
    }

    public function subHeader() {
        $data['about_headers'] = $this->AddAboutModel->get_all_about();
        $this->load->view('about-sub-header', $data);
    }

    public function sub_header_save() {
        $data = array(
            'subheader_name' => $this->input->post('subheader_name'),
            'subheader_text' => $this->input->post('subheader_text'),
            'sort_order_no' => $this->input->post('sort_order_no'),
            'about_header_id' => $this->input->post('about_header_id'),
            'is_published' => $this->input->post('is_published')
        );

        $insert = $this->AddAboutModel->insert_sub_header($data);
        if ($insert) {
            $this->session->set_flashdata('success', 'Sub header saved successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to save sub header: ' . $this->db->_error_message());
        }
        redirect('about-sub-header');
    }

    public function sub_header_edit($id) {
        $data['sub_header'] = $this->AddAboutModel->get_sub_header_by_id($id);
        $data['about_headers'] = $this->AddAboutModel->get_all_about();
        if (!$data['sub_header']) {
            $this->session->set_flashdata('error', 'Sub header not found!');
            redirect('about-sub-header-list');
        }
        $this->load->view('about-sub-header', $data);
    }

    public function sub_header_update($id) {
        $data = array(
            'subheader_name' => $this->input->post('subheader_name'),
            'subheader_text' => $this->input->post('subheader_text'),
            'sort_order_no' => $this->input->post('sort_order_no'),
            'about_header_id' => $this->input->post('about_header_id'),
            'is_published' => $this->input->post('is_published')
        );

        $update = $this->AddAboutModel->update_sub_header($id, $data);
        if ($update) {
            $this->session->set_flashdata('success', 'Sub header updated successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to update sub header: ' . $this->db->_error_message());
        }
        redirect('about-sub-header-list');
    }

    public function sub_header_delete($id) {
        $delete = $this->AddAboutModel->delete_sub_header($id);
        if ($delete) {
            echo json_encode(['status' => 'success', 'message' => 'Sub header deleted successfully!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete sub header: ' . $this->db->_error_message()]);
        }
    }

    public function aboutImage() {
        $data['about_headers'] = $this->AddAboutModel->get_all_about();
        $this->load->view('about-images', $data);
    }

    public function about_images_save() {
        $data = array(
            'thumbnail_url' => $this->input->post('thumbnail_url'),
            'image_description' => $this->input->post('image_description'),
            'sort_order_no' => $this->input->post('sort_order_no'),
            'about_header_id' => $this->input->post('about_header_id'),
            'is_published' => $this->input->post('is_published')
        );

        $insert = $this->AddAboutModel->insert_about_images($data);
        if ($insert) {
            $this->session->set_flashdata('success', 'About image saved successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to save about image: ' . $this->db->_error_message());
        }
        redirect('about-images');
    }

    public function about_image_edit($id) {
        $data['about_image'] = $this->AddAboutModel->get_about_image_by_id($id);
        $data['about_headers'] = $this->AddAboutModel->get_all_about();
        if (!$data['about_image']) {
            $this->session->set_flashdata('error', 'About image not found!');
            redirect('about-image-list');
        }
        $this->load->view('about-images', $data);
    }

    public function about_image_update($id) {
        $data = array(
            'thumbnail_url' => $this->input->post('thumbnail_url'),
            'image_description' => $this->input->post('image_description'),
            'sort_order_no' => $this->input->post('sort_order_no'),
            'about_header_id' => $this->input->post('about_header_id'),
            'is_published' => $this->input->post('is_published')
        );

        $update = $this->AddAboutModel->update_about_image($id, $data);
        if ($update) {
            $this->session->set_flashdata('success', 'About image updated successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to update about image: ' . $this->db->_error_message());
        }
        redirect('about-image-list');
    }

    public function about_image_delete($id) {
        $delete = $this->AddAboutModel->delete_about_image($id);
        if ($delete) {
            echo json_encode(['status' => 'success', 'message' => 'About image deleted successfully!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete about image: ' . $this->db->_error_message()]);
        }
    }

    public function fetch_about_header() {
        $about_headers = $this->AddAboutModel->get_all_about();
        $data = [];
        $sl_no = 1;
        foreach($about_headers as $e) {
            $data[] = [
                'id' => $e->id,
                'sl_no' => $sl_no++,
                'date_of_upload' => $e->date_of_upload ?? date('Y-m-d H:i:s'),
                'new_menu' => $e->new_menu,
                'menu_selection' => $e->menu_selection,
                'is_published' => $e->is_published,
                'action' => '<a href="'.base_url('about-header/edit/'.$e->id).'" class="btn btn-sm btn-primary">Edit</a> 
                             <button class="btn btn-sm btn-danger delete-about-header" data-id="'.$e->id.'">Delete</button>'
            ];
        }
        echo json_encode(['data' => $data]);
    }

    public function index() {
        $this->load->view('about-sub-header-list');
    }

    public function fetch_sub_header() {
        $sub_headers = $this->AddAboutModel->get_all_sub_header();
        $data = [];
        $sl_no = 1;
        foreach($sub_headers as $e) {
            $about_header = $this->AddAboutModel->get_about_by_id($e->about_header_id);
            $data[] = [
                'id' => $e->id,
                'sl_no' => $sl_no++,
                'subheader_name' => $e->subheader_name,
                'subheader_text' => $e->subheader_text,
                'sort_order_no' => $e->sort_order_no,
                'about_header_id' => $e->about_header_id,
                'about_header_name' => $about_header ? $about_header->new_menu : 'N/A',
                'is_published' => $e->is_published,
                'action' => '<a href="'.base_url('about-sub-header/edit/'.$e->id).'" class="btn btn-sm btn-primary">Edit</a> 
                             <button class="btn btn-sm btn-danger delete-sub-header" data-id="'.$e->id.'">Delete</button>'
            ];
        }
        echo json_encode(['data' => $data]);
    }

    public function fetch_about_images() {
        $about_images = $this->AddAboutModel->get_all_about_images();
        $data = [];
        $sl_no = 1;
        foreach($about_images as $e) {
            $about_header = $this->AddAboutModel->get_about_by_id($e->about_header_id);
            $data[] = [
                'id' => $e->id,
                'sl_no' => $sl_no++,
                'thumbnail_url' => $e->thumbnail_url,
                'image_description' => $e->image_description,
                'sort_order_no' => $e->sort_order_no,
                'about_header_id' => $e->about_header_id,
                'about_header_name' => $about_header ? $about_header->new_menu : 'N/A',
                'is_published' => $e->is_published,
                'action' => '<a href="'.base_url('about-image/edit/'.$e->id).'" class="btn btn-sm btn-primary">Edit</a> 
                             <button class="btn btn-sm btn-danger delete-about-image" data-id="'.$e->id.'">Delete</button>'
            ];
        }
        echo json_encode(['data' => $data]);
    }

    public function fetch_ajab_share_list() {
        $data = [];
        if (!$this->db->table_exists('about')) {
            echo json_encode(['data' => $data]);
            return;
        }

        $rows = $this->db->where('status', 0)->order_by('id', 'DESC')->get('about')->result();
        $typeMap = [
            1 => 'Intro',
            2 => 'Translit Guide',
            3 => 'Copyrights'
        ];

        $sl_no = 1;
        foreach ($rows as $row) {
            $plain = trim(strip_tags((string)$row->visual_content));
            if (mb_strlen($plain, 'UTF-8') > 120) {
                $plain = mb_substr($plain, 0, 120, 'UTF-8') . '...';
            }

            $data[] = [
                'id' => $row->id,
                'sl_no' => $sl_no++,
                'date_of_upload' => !empty($row->created_at) ? date('d-m-Y H:i', strtotime($row->created_at)) : '-',
                'type' => isset($typeMap[(int)$row->ajab_type]) ? $typeMap[(int)$row->ajab_type] : '-',
                'visual_content' => $plain
            ];
        }

        echo json_encode(['data' => $data]);
    }

    public function fetch_kabir_project_list() {
        $data = [];
        if (!$this->db->table_exists('about')) {
            echo json_encode(['data' => $data]);
            return;
        }

        $rows = $this->db->where('status', 1)->order_by('id', 'DESC')->get('about')->result();
        $typeMap = [
            1 => 'Intro Team',
            2 => 'Films',
            3 => 'Books',
            4 => 'Shabad Shaala'
        ];

        $sl_no = 1;
        foreach ($rows as $row) {
            $plain = trim(strip_tags((string)$row->visual_content));
            if (mb_strlen($plain, 'UTF-8') > 120) {
                $plain = mb_substr($plain, 0, 120, 'UTF-8') . '...';
            }

            $data[] = [
                'id' => $row->id,
                'sl_no' => $sl_no++,
                'date_of_upload' => !empty($row->created_at) ? date('d-m-Y H:i', strtotime($row->created_at)) : '-',
                'type' => isset($typeMap[(int)$row->kabir_type]) ? $typeMap[(int)$row->kabir_type] : '-',
                'visual_content' => $plain
            ];
        }

        echo json_encode(['data' => $data]);
    }

    public function delete_ajab_share($id) {
        if (empty($id) || !is_numeric($id)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid ID']);
            return;
        }

        $this->db->where('id', (int)$id);
        $this->db->where('status', 0);
        $deleted = $this->db->delete('about');

        if ($deleted) {
            echo json_encode(['status' => 'success', 'message' => 'Ajab Shahar entry deleted successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete Ajab Shahar entry']);
        }
    }

    public function delete_kabir_project($id) {
        if (empty($id) || !is_numeric($id)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid ID']);
            return;
        }

        $this->db->where('id', (int)$id);
        $this->db->where('status', 1);
        $deleted = $this->db->delete('about');

        if ($deleted) {
            echo json_encode(['status' => 'success', 'message' => 'Kabir Project entry deleted successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete Kabir Project entry']);
        }
    }
}