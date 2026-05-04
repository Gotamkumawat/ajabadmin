<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class NewsController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database(); // Database connect
        $this->load->helper('url'); 
        $this->load->model('NewsModel'); // Model load
        $this->load->library('session');
		if (!$this->session->userdata('logged_in')) {
			redirect('login');
		}
    }

    public function add_news() {
        $this->load->view('add-news'); // View load
    }

    public function edit($id) {
        $data['news'] = $this->NewsModel->get_news_by_id($id);
        if (!$data['news']) {
            show_404();
        }
        
        // Decode popup items JSON (primary: news_content; fallback: legacy popup_item JSON)
        $popupItems = [];

        $primary = null;
        if (!empty($data['news']['news_content'])) {
            $decoded = json_decode($data['news']['news_content'], true);
            if (is_array($decoded)) {
                $primary = $decoded;
            }
        }

        $legacy = null;
        if (!empty($data['news']['popup_item'])) {
            $decodedLegacy = json_decode($data['news']['popup_item'], true);
            if (is_array($decodedLegacy)) {
                // Normalize associative legacy payloads to a numeric array
                $legacy = array_values($decodedLegacy);
            }
        }

        if (is_array($primary) && !empty($primary)) {
            $popupItems = $primary;
        }

        // If legacy has more items than primary or primary missing, prefer legacy
        if (is_array($legacy) && (empty($popupItems) || count($legacy) > count($popupItems))) {
            $popupItems = $legacy;
        }

        $data['news']['popup_items_array'] = $popupItems;
        
        $this->load->view('add-news', $data);
    }

    public function save() {
        $id = $this->input->post('id');
        // Process only one popup item
        $popup_items = [];
        $category = $this->input->post("item_1_category");

        $existingFirst = null;
        if (!empty($id)) {
            $row = $this->NewsModel->get_news_by_id($id);
            if (!empty($row['news_content'])) {
                $decoded = json_decode($row['news_content'], true);
                if (is_array($decoded) && isset($decoded[0]) && is_array($decoded[0])) {
                    $existingFirst = $decoded[0];
                }
            }
        }

        if (!empty($category)) {
            $item = [
                'category' => $category,
                'title' => $this->input->post("item_1_title"),
                'second_title' => $this->input->post("item_1_second_title"),
                'content' => $this->input->post("item_1_content"),
                'published' => $this->input->post("item_1_published"),
                'sequence_order' => $this->input->post("item_1_sequence_order"),
                'show_on_home' => $this->input->post("item_1_show_on_home"),
                'image_size' => $this->input->post("item_1_image_size")
            ];
            // Handle file uploads based on category (on edit, keep previous files if no new upload)
            if ($category === 'single') {
                $uploaded = $this->handle_file_upload("item_1_image");
                if (!empty($uploaded)) {
                    $item['image'] = $uploaded;
                } elseif (is_array($existingFirst) && !empty($existingFirst['image'])) {
                    $item['image'] = $existingFirst['image'];
                }
            } elseif ($category === 'multiple') {
                $uploaded = $this->handle_multiple_file_upload("item_1_images");
                if (!empty($uploaded)) {
                    $item['images'] = $uploaded;
                } elseif (is_array($existingFirst) && !empty($existingFirst['images']) && is_array($existingFirst['images'])) {
                    $item['images'] = $existingFirst['images'];
                }
            } elseif ($category === 'video') {
                $vid = trim((string) $this->input->post("item_1_video"));
                if ($vid !== '') {
                    $item['video_url'] = $vid;
                } elseif (is_array($existingFirst) && !empty($existingFirst['video_url'])) {
                    $item['video_url'] = $existingFirst['video_url'];
                }
            }
            $popup_items[] = $item;
        }

        $pubRaw = $this->input->post("item_1_published");
        $publishedInt = ($pubRaw === '1' || $pubRaw === 1) ? 1 : 0;
        $publishStatus = ($pubRaw === '1' || $pubRaw === 1) ? 'Yes' : (($pubRaw === '0' || $pubRaw === 0) ? 'No' : null);

        $data = [
            // Count of popup items saved
            'popup_item' => count($popup_items),
            // Store full JSON payload in news_content for retrieval/editing
            'news_content' => json_encode($popup_items),
            'news_title' => $this->input->post("item_1_title"),
            'news_second_title' => $this->input->post("item_1_second_title"),
            'published' => $publishedInt,
            'publish_status' => $publishStatus,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($id) {
            // Update existing record
            $update = $this->NewsModel->update_news($id, $data);
            if ($update) {
                $this->session->set_flashdata('success', 'News updated successfully!');
            } else {
                $this->session->set_flashdata('error', 'Error updating news.');
            }
            redirect('news-list');
        } else {
            // Insert new record
            $data['created_at'] = date('Y-m-d H:i:s');
            $insert = $this->NewsModel->insert_news($data);
            if ($insert) {
                $this->session->set_flashdata('success', 'News saved successfully!');
            } else {
                $this->session->set_flashdata('error', 'Error saving news.');
            }
            redirect('news-list');
        }
    }

    private function handle_file_upload($field_name) {
        if (!empty($_FILES[$field_name]['name'])) {
            $config['upload_path'] = './uploads/news/';
            $config['allowed_types'] = 'gif|jpg|jpeg|png|webp';
            $config['max_size'] = 5120; // 5MB
            $config['file_name'] = time() . '_' . $_FILES[$field_name]['name'];

            if (!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'], 0777, true);
            }

            $this->load->library('upload', $config);
            
            if ($this->upload->do_upload($field_name)) {
                return 'uploads/news/' . $this->upload->data('file_name');
            }
        }
        return null;
    }

    private function handle_multiple_file_upload($field_name) {
        $uploaded_files = [];
        if (!empty($_FILES[$field_name]['name'][0])) {
            $files_count = count($_FILES[$field_name]['name']);
            
            for ($i = 0; $i < $files_count; $i++) {
                $_FILES['file']['name'] = $_FILES[$field_name]['name'][$i];
                $_FILES['file']['type'] = $_FILES[$field_name]['type'][$i];
                $_FILES['file']['tmp_name'] = $_FILES[$field_name]['tmp_name'][$i];
                $_FILES['file']['error'] = $_FILES[$field_name]['error'][$i];
                $_FILES['file']['size'] = $_FILES[$field_name]['size'][$i];

                $config['upload_path'] = './uploads/news/';
                $config['allowed_types'] = 'gif|jpg|jpeg|png|webp';
                $config['max_size'] = 5120;
                $config['file_name'] = time() . '_' . $i . '_' . $_FILES['file']['name'];

                if (!is_dir($config['upload_path'])) {
                    mkdir($config['upload_path'], 0777, true);
                }

                $this->load->library('upload', $config);
                
                if ($this->upload->do_upload('file')) {
                    $uploaded_files[] = 'uploads/news/' . $this->upload->data('file_name');
                }
            }
        }
        return $uploaded_files;
    }

    public function fetch_news() {
        $news = $this->NewsModel->get_all_news();
        $data = [];
        $sl_no = 1;

        foreach ($news as $e) {
            // Try to get popup item titles from news_content JSON
            $popup_titles = [];
            if (!empty($e->news_content)) {
                $items = json_decode($e->news_content, true);
                if (is_array($items)) {
                    foreach ($items as $item) {
                        if (!empty($item['title'])) {
                            $popup_titles[] = $item['title'];
                        }
                    }
                }
            }
            $popup_item_display = !empty($popup_titles) ? implode(', ', $popup_titles) : '';
            $data[] = [
                'id' => $e->id, // Use actual database ID
                'news_title' => $e->news_title,
                'popup_item' => $popup_item_display,
                'published' => $e->publish_status ? 'Yes' : 'No', // Use publish_status for display
                'action' => '<a href="' . base_url('NewsController/edit/' . $e->id) . '" class="btn btn-sm btn-primary">Edit</a> 
                             <a href="' . base_url('NewsController/delete/' . $e->id) . '" class="btn btn-sm btn-danger">Delete</a>'
            ];
            $sl_no++;
        }

        echo json_encode(['data' => $data]);
    }

    public function delete($id) {
        $delete = $this->NewsModel->delete_news($id);
        if ($delete) {
            $this->session->set_flashdata('success', 'News deleted successfully!');
        } else {
            $this->session->set_flashdata('error', 'Error deleting news.');
        }
        redirect('news-list');
    }
}