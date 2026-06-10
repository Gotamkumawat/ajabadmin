<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AboutSection extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('upload');
        $this->_ensure_schema();
    }

    // ---------------- Schema bootstrap ----------------
    private function _ensure_schema() {
        // 1) about_sections — top-level tiles on add-about page
        if (!$this->db->table_exists('about_sections')) {
            $this->db->query("CREATE TABLE IF NOT EXISTS `about_sections` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `slug` VARCHAR(120) NOT NULL,
                `label` VARCHAR(180) NOT NULL,
                `color` VARCHAR(40) NOT NULL DEFAULT 'bg-info',
                `status_value` INT(11) NOT NULL DEFAULT 0,
                `sort_order` INT(11) NOT NULL DEFAULT 0,
                `created_at` DATETIME NULL,
                PRIMARY KEY (`id`),
                UNIQUE KEY `slug` (`slug`),
                UNIQUE KEY `status_value` (`status_value`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
            $seed = [
                ['slug' => 'ajab-shahar',  'label' => 'Ajab Shahar',  'color' => 'bg-info', 'status_value' => 0, 'sort_order' => 1],
                ['slug' => 'kabir-project','label' => 'Kabir Project','color' => 'bg-info', 'status_value' => 1, 'sort_order' => 2],
            ];
            foreach ($seed as $r) {
                $r['created_at'] = date('Y-m-d H:i:s');
                $this->db->insert('about_sections', $r);
            }
        }

        // 2) section_menus — per-section dynamic menu list
        if (!$this->db->table_exists('section_menus')) {
            $this->db->query("CREATE TABLE IF NOT EXISTS `section_menus` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `section_id` INT(11) NOT NULL,
                `slug` VARCHAR(150) NOT NULL,
                `label` VARCHAR(180) NOT NULL,
                `sort_order` INT(11) NOT NULL DEFAULT 0,
                `created_at` DATETIME NULL,
                PRIMARY KEY (`id`),
                UNIQUE KEY `section_slug` (`section_id`,`slug`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

            // Migrate existing ajab_menus / kabir_menus into section_menus
            $ajab = $this->db->where('slug', 'ajab-shahar')->get('about_sections')->row();
            $kabir = $this->db->where('slug', 'kabir-project')->get('about_sections')->row();
            if ($ajab && $this->db->table_exists('ajab_menus')) {
                foreach ($this->db->order_by('sort_order','ASC')->get('ajab_menus')->result() as $m) {
                    $this->db->insert('section_menus', [
                        'section_id' => (int)$ajab->id,
                        'slug' => $m->slug, 'label' => $m->label,
                        'sort_order' => (int)$m->sort_order,
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);
                }
            } else if ($ajab) {
                foreach ([['intro','Intro',1],['translit guide','Translit Guide',2],['copyrights','Copyrights',3]] as $r) {
                    $this->db->insert('section_menus', [
                        'section_id' => (int)$ajab->id,
                        'slug' => $r[0], 'label' => $r[1], 'sort_order' => $r[2],
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);
                }
            }
            if ($kabir && $this->db->table_exists('kabir_menus')) {
                foreach ($this->db->order_by('sort_order','ASC')->get('kabir_menus')->result() as $m) {
                    $this->db->insert('section_menus', [
                        'section_id' => (int)$kabir->id,
                        'slug' => $m->slug, 'label' => $m->label,
                        'sort_order' => (int)$m->sort_order,
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);
                }
            } else if ($kabir) {
                foreach ([['intro','Intro',1],['team','Team',2],['films','Films',3],['books','Books',4],['shabad shaala','Shabad Shaala',5]] as $r) {
                    $this->db->insert('section_menus', [
                        'section_id' => (int)$kabir->id,
                        'slug' => $r[0], 'label' => $r[1], 'sort_order' => $r[2],
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);
                }
            }
        }

        // 3) ensure about.menu_id column exists for canonical FK to section_menus
        if ($this->db->table_exists('about') && !$this->db->field_exists('menu_id', 'about')) {
            $this->db->query("ALTER TABLE `about` ADD `menu_id` INT(11) NULL DEFAULT NULL");
            // Backfill menu_id from legacy ajab_type/kabir_type via section_menus
            $rows = $this->db->get('about')->result();
            foreach ($rows as $r) {
                $sec = $this->db->where('status_value', (int)$r->status)->get('about_sections')->row();
                if (!$sec) continue;
                // Position-of-old-type => Nth menu by sort_order
                $oldType = ((int)$r->status === 0) ? (int)$r->ajab_type : (int)$r->kabir_type;
                if ($oldType <= 0) continue;
                $menus = $this->db->where('section_id', (int)$sec->id)
                                  ->order_by('sort_order','ASC')->order_by('id','ASC')
                                  ->get('section_menus')->result();
                if (isset($menus[$oldType - 1])) {
                    $this->db->where('id', (int)$r->id)->update('about', ['menu_id' => (int)$menus[$oldType - 1]->id]);
                }
            }
        }
    }

    // ---------------- Helpers ----------------
    private function _section_by_slug($slug) {
        return $this->db->where('slug', $slug)->get('about_sections')->row();
    }
    private function _menu_by_id($id) {
        return $this->db->where('id', (int)$id)->get('section_menus')->row();
    }
    private function _menu_by_slug($section_id, $slug) {
        return $this->db->where('section_id', (int)$section_id)
                        ->where('LOWER(slug)', strtolower(trim((string)$slug)))
                        ->get('section_menus')->row();
    }
    private function _slugify($text) {
        $s = strtolower(preg_replace('/\s+/', ' ', trim((string)$text)));
        $s = preg_replace('/[^a-z0-9 \-]+/', '', $s);
        $s = preg_replace('/\s+/', '-', $s);
        return trim($s, '-');
    }

    private function _handle_menu_image_upload($redirect_slug) {
        if (!empty($_FILES['menu_image']['name'])) {
            $dir = FCPATH . 'images/';
            if (!is_dir($dir)) { @mkdir($dir, 0755, true); }
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
            redirect('about-section/' . $redirect_slug);
            exit;
        }
        $existing = $this->input->post('menu_image_existing');
        return ($existing !== null && $existing !== '') ? trim((string)$existing) : '';
    }

    // ---------------- Page render ----------------
    public function index($slug = null, $id = null) {
        if (!$slug) { redirect('add-about'); return; }
        $section = $this->_section_by_slug($slug);
        if (!$section) {
            $this->session->set_flashdata('error', 'Section not found.');
            redirect('add-about'); return;
        }

        $menus = $this->db->where('section_id', (int)$section->id)
                          ->order_by('sort_order','ASC')->order_by('id','ASC')
                          ->get('section_menus')->result();

        $data = ['section' => $section, 'section_menus' => $menus];

        if ($id !== null && is_numeric($id) && $this->db->table_exists('about')) {
            $row = $this->db->where('id', (int)$id)
                            ->where('status', (int)$section->status_value)
                            ->get('about')->row();
            if (!empty($row)) {
                $menu = !empty($row->menu_id) ? $this->_menu_by_id($row->menu_id) : null;
                $row->type_label = $menu ? $menu->slug : '';
                $data['entry'] = $row;
            }
        }
        $this->load->view('about-section', $data);
    }

    // ---------------- Save / Update ----------------
    public function save($slug) {
        $section = $this->_section_by_slug($slug);
        if (!$section) { $this->session->set_flashdata('error', 'Section not found.'); redirect('add-about'); return; }

        $typeSlug = $this->input->post('type', true);
        $menu = $this->_menu_by_slug($section->id, $typeSlug);
        if (!$menu) { $this->session->set_flashdata('error', 'Invalid menu selected.'); redirect('about-section/' . $slug); return; }

        $visualContent = $this->input->post('visual_content', false);
        if ($visualContent === null || trim((string)$visualContent) === '') {
            $this->session->set_flashdata('error', 'Visual content is required.');
            redirect('about-section/' . $slug); return;
        }

        $menuImage = $this->_handle_menu_image_upload($slug);

        $data = [
            'menu_id' => (int)$menu->id,
            'visual_content' => $visualContent,
            'meta_title' => $this->input->post('meta_title') ?? '',
            'meta_keywords' => $this->input->post('meta_keywords') ?? '',
            'meta_description' => $this->input->post('meta_description') ?? '',
            'menu_image' => $menuImage,
            'status' => (int)$section->status_value,
            'created_at' => date('Y-m-d H:i:s'),
        ];
        // Legacy column compat for the two seeded sections
        if ((int)$section->status_value === 0) { $data['ajab_type'] = (int)$menu->sort_order; $data['kabir_type'] = 0; }
        else if ((int)$section->status_value === 1) { $data['kabir_type'] = (int)$menu->sort_order; $data['ajab_type'] = 0; }
        else { $data['ajab_type'] = 0; $data['kabir_type'] = 0; }

        if ($this->db->insert('about', $data)) {
            if ($menuImage !== '') {
                $this->db->where('status', (int)$section->status_value)
                         ->update('about', ['menu_image' => $menuImage]);
            }
            $this->session->set_flashdata('success', $section->label . ' content saved successfully!');
        } else {
            $err = $this->db->error();
            $this->session->set_flashdata('error', !empty($err['message']) ? $err['message'] : 'Save failed.');
        }
        redirect('about-section/' . $slug);
    }

    public function update($slug, $id) {
        $section = $this->_section_by_slug($slug);
        if (!$section) { $this->session->set_flashdata('error', 'Section not found.'); redirect('add-about'); return; }
        if (empty($id) || !is_numeric($id)) { $this->session->set_flashdata('error', 'Invalid id.'); redirect('about-section/' . $slug); return; }

        $typeSlug = $this->input->post('type', true);
        $menu = $this->_menu_by_slug($section->id, $typeSlug);
        if (!$menu) { $this->session->set_flashdata('error', 'Invalid menu selected.'); redirect('about-section/' . $slug); return; }

        $visualContent = $this->input->post('visual_content', false);
        if ($visualContent === null || trim((string)$visualContent) === '') {
            $this->session->set_flashdata('error', 'Visual content is required.');
            redirect('about-section/' . $slug); return;
        }

        $menuImage = $this->_handle_menu_image_upload($slug);

        $data = [
            'menu_id' => (int)$menu->id,
            'visual_content' => $visualContent,
            'meta_title' => $this->input->post('meta_title') ?? '',
            'meta_keywords' => $this->input->post('meta_keywords') ?? '',
            'meta_description' => $this->input->post('meta_description') ?? '',
            'menu_image' => $menuImage,
            'status' => (int)$section->status_value,
        ];
        if ((int)$section->status_value === 0) { $data['ajab_type'] = (int)$menu->sort_order; $data['kabir_type'] = 0; }
        else if ((int)$section->status_value === 1) { $data['kabir_type'] = (int)$menu->sort_order; $data['ajab_type'] = 0; }
        else { $data['ajab_type'] = 0; $data['kabir_type'] = 0; }

        $this->db->where('id', (int)$id)->where('status', (int)$section->status_value);
        if ($this->db->update('about', $data)) {
            if ($menuImage !== '') {
                $this->db->where('status', (int)$section->status_value)
                         ->update('about', ['menu_image' => $menuImage]);
            }
            $this->session->set_flashdata('success', $section->label . ' content updated successfully!');
        } else {
            $err = $this->db->error();
            $this->session->set_flashdata('error', !empty($err['message']) ? $err['message'] : 'Update failed.');
        }
        redirect('about-section/' . $slug);
    }

    // ---------------- Section CRUD (top-level tiles) ----------------
    public function sections_list() {
        header('Content-Type: application/json; charset=utf-8');
        $rows = $this->db->order_by('sort_order','ASC')->order_by('id','ASC')->get('about_sections')->result();
        echo json_encode(['status' => true, 'data' => $rows]);
    }

    public function sections_create() {
        header('Content-Type: application/json; charset=utf-8');
        $label = trim((string)$this->input->post('label', true));
        if ($label === '') { echo json_encode(['status' => false, 'message' => 'Label required']); return; }
        $slug = $this->_slugify($label);
        if ($slug === '') { echo json_encode(['status' => false, 'message' => 'Invalid label']); return; }
        if ($this->db->where('slug', $slug)->get('about_sections')->row()) {
            echo json_encode(['status' => false, 'message' => 'Section with that name already exists']); return;
        }
        $maxStatus = $this->db->select_max('status_value', 'm')->get('about_sections')->row();
        $maxOrder  = $this->db->select_max('sort_order',   'm')->get('about_sections')->row();
        $data = [
            'slug' => $slug, 'label' => $label, 'color' => 'bg-info',
            'status_value' => ($maxStatus && $maxStatus->m !== null) ? ((int)$maxStatus->m + 1) : 0,
            'sort_order'   => ($maxOrder  && $maxOrder->m !== null)  ? ((int)$maxOrder->m  + 1) : 1,
            'created_at' => date('Y-m-d H:i:s'),
        ];
        if ($this->db->insert('about_sections', $data)) {
            $data['id'] = (int)$this->db->insert_id();
            echo json_encode(['status' => true, 'data' => $data]);
        } else {
            $err = $this->db->error();
            echo json_encode(['status' => false, 'message' => !empty($err['message']) ? $err['message'] : 'Insert failed']);
        }
    }

    /**
     * Rename a section. Default sections (ajab-shahar, kabir-project) are
     * locked to their original slug — only their label can change. Custom
     * sections also get their slug regenerated so the URL stays in sync.
     */
    public function sections_update($id) {
        header('Content-Type: application/json; charset=utf-8');
        $id  = (int)$id;
        $sec = $this->db->where('id', $id)->get('about_sections')->row();
        if (!$sec) { echo json_encode(['status' => false, 'message' => 'Not found']); return; }
        $label = trim((string)$this->input->post('label', true));
        if ($label === '') { echo json_encode(['status' => false, 'message' => 'Label required']); return; }
        $update = ['label' => $label];
        $isDefault = in_array($sec->slug, ['ajab-shahar','kabir-project'], true);
        if (!$isDefault) {
            $newSlug = $this->_slugify($label);
            if ($newSlug === '') { echo json_encode(['status' => false, 'message' => 'Invalid label']); return; }
            if ($newSlug !== $sec->slug) {
                $clash = $this->db->where('slug', $newSlug)->where('id !=', $id)->get('about_sections')->row();
                if ($clash) { echo json_encode(['status' => false, 'message' => 'Another section already has that name']); return; }
                $update['slug'] = $newSlug;
            }
        }
        $this->db->where('id', $id)->update('about_sections', $update);
        $row = $this->db->where('id', $id)->get('about_sections')->row_array();
        echo json_encode(['status' => true, 'data' => $row]);
    }

    public function sections_delete($id) {
        header('Content-Type: application/json; charset=utf-8');
        $id = (int)$id;
        $sec = $this->db->where('id', $id)->get('about_sections')->row();
        if (!$sec) { echo json_encode(['status' => false, 'message' => 'Not found']); return; }
        if (in_array($sec->slug, ['ajab-shahar','kabir-project'], true)) {
            echo json_encode(['status' => false, 'message' => 'Default sections cannot be deleted']); return;
        }
        $used = $this->db->where('status', (int)$sec->status_value)->count_all_results('about');
        if ($used > 0) { echo json_encode(['status' => false, 'message' => 'Section has saved content; remove it first']); return; }
        $this->db->where('section_id', $id)->delete('section_menus');
        $this->db->where('id', $id)->delete('about_sections');
        echo json_encode(['status' => true]);
    }

    // ---------------- Menus CRUD (per section) ----------------
    public function menus_list($slug) {
        header('Content-Type: application/json; charset=utf-8');
        $section = $this->_section_by_slug($slug);
        if (!$section) { echo json_encode(['status' => false, 'message' => 'Section not found']); return; }
        $rows = $this->db->where('section_id', (int)$section->id)
                         ->order_by('sort_order','ASC')->order_by('id','ASC')
                         ->get('section_menus')->result();
        echo json_encode(['status' => true, 'data' => $rows]);
    }

    public function menus_create($slug) {
        header('Content-Type: application/json; charset=utf-8');
        $section = $this->_section_by_slug($slug);
        if (!$section) { echo json_encode(['status' => false, 'message' => 'Section not found']); return; }
        $label = trim((string)$this->input->post('label', true));
        if ($label === '') { echo json_encode(['status' => false, 'message' => 'Label required']); return; }
        $menuSlug = strtolower(preg_replace('/\s+/', ' ', $label));
        if ($this->_menu_by_slug($section->id, $menuSlug)) {
            echo json_encode(['status' => false, 'message' => 'Menu already exists']); return;
        }
        $maxOrder = $this->db->select_max('sort_order','m')->where('section_id', (int)$section->id)->get('section_menus')->row();
        $data = [
            'section_id' => (int)$section->id,
            'slug' => $menuSlug, 'label' => $label,
            'sort_order' => ($maxOrder && $maxOrder->m !== null) ? ((int)$maxOrder->m + 1) : 1,
            'created_at' => date('Y-m-d H:i:s'),
        ];
        if ($this->db->insert('section_menus', $data)) {
            $data['id'] = (int)$this->db->insert_id();
            echo json_encode(['status' => true, 'data' => $data]);
        } else {
            $err = $this->db->error();
            echo json_encode(['status' => false, 'message' => !empty($err['message']) ? $err['message'] : 'Insert failed']);
        }
    }

    /**
     * Rename a menu within a section. Regenerates slug so the activate-menu
     * logic in about-section.php picks up the new value.
     */
    public function menus_update($slug, $id) {
        header('Content-Type: application/json; charset=utf-8');
        $section = $this->_section_by_slug($slug);
        if (!$section) { echo json_encode(['status' => false, 'message' => 'Section not found']); return; }
        $id    = (int)$id;
        $row   = $this->db->where('id', $id)->where('section_id', (int)$section->id)->get('section_menus')->row();
        if (!$row) { echo json_encode(['status' => false, 'message' => 'Menu not found']); return; }
        $label = trim((string)$this->input->post('label', true));
        if ($label === '') { echo json_encode(['status' => false, 'message' => 'Label required']); return; }
        $menuSlug = strtolower(preg_replace('/\s+/', ' ', $label));
        if ($menuSlug !== $row->slug) {
            $clash = $this->db->where('section_id', (int)$section->id)
                              ->where('slug', $menuSlug)
                              ->where('id !=', $id)
                              ->get('section_menus')->row();
            if ($clash) { echo json_encode(['status' => false, 'message' => 'Another menu already has that name']); return; }
        }
        $this->db->where('id', $id)->update('section_menus', ['label' => $label, 'slug' => $menuSlug]);
        $updated = $this->db->where('id', $id)->get('section_menus')->row_array();
        echo json_encode(['status' => true, 'data' => $updated]);
    }

    public function menus_delete($slug, $id) {
        header('Content-Type: application/json; charset=utf-8');
        $section = $this->_section_by_slug($slug);
        if (!$section) { echo json_encode(['status' => false, 'message' => 'Section not found']); return; }
        $id = (int)$id;
        $used = $this->db->where('menu_id', $id)->where('status', (int)$section->status_value)->count_all_results('about');
        if ($used > 0) { echo json_encode(['status' => false, 'message' => 'Menu has saved content; remove it first']); return; }
        $this->db->where('id', $id)->where('section_id', (int)$section->id)->delete('section_menus');
        echo json_encode(['status' => true]);
    }
}
