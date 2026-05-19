<?php
include('inc/header.php');
include('inc/sidebar.php');
?>

<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet" href="https://unpkg.com/grapesjs/dist/css/grapes.min.css" />
</head>

<style>
    /* Same CSS as add-person.php */
    .select2-container--default .select2-selection--multiple {
        min-height: 38px;
        border: 1px solid #ccc;
        border-radius: 6px;
        padding: 4px 6px;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        overflow: hidden;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #007bff;
        border: none;
        color: #fff;
        padding: 2px 6px;
        margin: 2px;
        border-radius: 4px;
        font-size: 12px;
        max-width: calc(100% - 10px);
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
    }

    .select2-container--default .select2-selection--multiple::-webkit-scrollbar {
        display: none;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: #fff;
        margin-right: 4px;
    }

    .multi-select-container {
        max-width: 100%;
    }

    .container {
        width: 300px;
        margin: 50px auto;
        font-family: Arial, sans-serif;
    }

    select, input, textarea {
        width: 100%;
        padding: 8px;
        margin: 10px 0;
        font-size: 14px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .form-group label {
        font-weight: 600;
        margin-bottom: 0.5rem;
        display: block;
    }

    .form-control {
        font-size: 14px;
        line-height: 1.5;
        color: #495057;
        background-color: #fff;
        border: 1px solid #ced4da;
        border-radius: 4px;
        padding: 6px 12px;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .form-control:focus {
        border-color: #80bdff;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    textarea.form-control {
        resize: vertical;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
        padding: 6px 20px;
        font-size: 16px;
        border-radius: 4px;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #004085;
    }

    .btn-primary:disabled {
        background-color: #6c757d;
        border-color: #6c757d;
        cursor: not-allowed;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__rendered li {
        margin: 1px 2px !important;
        padding: 2px 4px !important;
    }

    .cke_notification {
        display: none;
    }

    .select2-container .select2-selection--multiple {
        height: 5px;
        padding-top: 2px;
        display: flex !important;
        align-items: center !important;
        font-size: 14px !important;
        border: 1px solid #ccc !important;
        border-radius: 4px !important;
    }

    .select2-selection__placeholder {
        font-size: 14px !important;
        line-height: normal !important;
        color: #777 !important;
    }

    .save-btn-container {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 40px;
    }

    .save-btn {
        position: relative;
        top: -20px;
        padding: 8px 30px;
        font-size: 16px;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.15);
        transition: all 0.3s ease;
    }

    .ajab-menu-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(180px, 1fr));
        gap: 10px;
    }

    .ajab-menu-btn {
        border: 1px solid #ced4da;
        background: #fff;
        color: #212529;
        border-radius: 6px;
        padding: 10px;
        text-align: left;
        transition: all 0.2s ease;
    }

    .ajab-menu-btn .menu-title {
        font-size: 14px;
        font-weight: 600;
        display: block;
    }

    .ajab-menu-btn .menu-state {
        font-size: 12px;
        color: #6c757d;
        display: block;
        margin-top: 2px;
    }

    .ajab-menu-btn.active {
        border-color: #007bff;
        background: #e9f3ff;
        color: #0056b3;
    }

    #selectedMenuInfo {
        font-size: 13px;
        margin-top: 8px;
        color: #6c757d;
    }

    #gjs {
        border: 1px solid #ced4da;
        min-height: 620px;
        border-radius: 4px;
        overflow: hidden;
    }

    .gjs-one-bg {
        background-color: #f8f9fa;
    }

    .gjs-two-color {
        color: #343a40;
    }

    .gjs-three-bg {
        background-color: #007bff;
        color: #fff;
    }

    .gjs-am-file-uploader {
        border: 1px dashed #b8c2cc;
        border-radius: 6px;
        background: #fff;
    }

    .gjs-am-assets-cont {
        background: #f7f9fc;
    }

</style>

<!-- Content Wrapper -->
<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Ajab Share</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="add_new">Home</a></li>
                        <li class="breadcrumb-item active">Add/Edit Ajab Share</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Card -->
            <div class="card card-default">
                <div class="card-header" style="padding: 4px 8px; margin: 0;">
                    <div>
                        <a href="javascript:void(0);" 
                           onclick="window.history.back();" 
                           class="btn btn-secondary" 
                           style="padding: 3px 8px; font-size: 13px; border-radius: 4px;">
                            <i class="fas fa-arrow-left" style="font-size: 13px; margin-right: 4px;"></i> Back
                        </a>
                    </div>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <?php if ($this->session->flashdata('success')): ?>
                        <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
                    <?php endif; ?>
                    <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
                    <?php endif; ?>

                    <?php
                        $currentType = isset($ajab_shahar->type_label) ? $ajab_shahar->type_label : '';
                        $currentVisualContent = isset($ajab_shahar->visual_content) ? $ajab_shahar->visual_content : '';
                        $currentId = isset($ajab_shahar->id) ? (int)$ajab_shahar->id : 0;
                        $currentMenuImage = isset($ajab_shahar->menu_image) ? trim((string)$ajab_shahar->menu_image) : '';
                        // Logo is shared across all Ajab Shahar menu tabs — fetch from any saved row
                        if ($currentMenuImage === '' && $this->db->table_exists('about')) {
                            $sharedImg = $this->db->select('menu_image')
                                ->from('about')
                                ->where('status', 0)
                                ->where('menu_image IS NOT NULL', null, false)
                                ->where("TRIM(menu_image) !=", '')
                                ->order_by('id', 'DESC')
                                ->limit(1)
                                ->get()->row_array();
                            if (!empty($sharedImg['menu_image'])) {
                                $currentMenuImage = trim((string)$sharedImg['menu_image']);
                            }
                        }
                    ?>

                    <form name="aboutForm" id="aboutForm" method="post" enctype="multipart/form-data" action="<?php echo base_url('ajab-shahar/save'); ?>" data-save-url="<?php echo base_url('ajab-shahar/save'); ?>" data-update-base-url="<?php echo base_url('ajab-shahar/update'); ?>">

                        <!-- Logo (visible across all menu tabs — kept above the menu selector) -->
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label>Logo</label>
                                <input type="hidden" name="menu_image_existing" id="menu_image_existing" value="<?php echo htmlspecialchars($currentMenuImage); ?>">
                                <input type="file" name="menu_image" id="menu_image" class="form-control" accept="image/*" style="max-width: 360px;">
                                <div id="menu_image_preview_wrap" style="margin-top:8px;<?php echo $currentMenuImage === '' ? 'display:none;' : ''; ?>">
                                    <img id="menu_image_preview" src="<?php echo $currentMenuImage !== '' ? base_url(ltrim($currentMenuImage, '/')) : ''; ?>" alt="Logo" style="max-width:200px; max-height:120px; border:1px solid #ced4da; padding:4px; border-radius:4px;">
                                    <div class="text-muted small">Current logo (shared across all menu tabs). Choose a new file to replace.</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 form-group">
                                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:6px;">
                                    <label style="margin:0;">Menu <span style="color:red">*</span></label>
                                    <button type="button" id="addAjabMenuBtn" class="btn btn-success btn-sm">
                                        <i class="fa fa-plus"></i> Add New
                                    </button>
                                </div>
                                <input type="hidden" name="type" id="type" value="<?php echo htmlspecialchars($currentType, ENT_QUOTES, 'UTF-8'); ?>">
                                <input type="hidden" id="entry_id" value="<?php echo $currentId; ?>">

                                <div id="ajabMenuSwitch" class="ajab-menu-grid">
                                    <?php
                                        $ajabMenusList = isset($ajab_menus) && is_array($ajab_menus) ? $ajab_menus : [];
                                        foreach ($ajabMenusList as $m):
                                            $slug = htmlspecialchars($m->slug, ENT_QUOTES, 'UTF-8');
                                            $label = htmlspecialchars($m->label, ENT_QUOTES, 'UTF-8');
                                    ?>
                                    <button type="button" class="ajab-menu-btn" data-type="<?php echo $slug; ?>" data-id="<?php echo (int)$m->id; ?>">
                                        <span class="menu-title"><?php echo $label; ?></span>
                                        <span class="menu-state">Not Saved</span>
                                    </button>
                                    <?php endforeach; ?>
                                </div>
                                <div id="selectedMenuInfo">Select a menu section to add/edit content.</div>
                            </div>
                        </div>

                        <!-- Add New Menu Modal -->
                        <div id="ajabAddMenuModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.45); z-index:9999; align-items:center; justify-content:center;">
                            <div style="background:#fff; width:420px; max-width:92%; border-radius:8px; padding:18px; box-shadow:0 10px 40px rgba(0,0,0,0.2);">
                                <h5 style="margin:0 0 12px;">Add New Menu</h5>
                                <div class="form-group" style="margin-bottom:12px;">
                                    <label for="ajabNewMenuName">Menu Name</label>
                                    <input type="text" id="ajabNewMenuName" class="form-control" placeholder="e.g. Resources" maxlength="100">
                                </div>
                                <div style="display:flex; gap:8px; justify-content:flex-end;">
                                    <button type="button" class="btn btn-secondary" id="ajabAddMenuCancel">Cancel</button>
                                    <button type="button" class="btn btn-primary" id="ajabAddMenuSave">Add</button>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label>Visual Content Editor</label>

                                <input type="file" id="mediaImageInput" accept="image/*" multiple style="display:none;">
                                <textarea class="form-control" name="visual_content" id="visual_content_textarea" rows="14" style="display:none;"><?php echo $currentVisualContent; ?></textarea>
                                <div id="gjs"></div>
                            </div>
                        </div>

                        <!-- Meta Data Section -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <h4>Meta Data</h4>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="meta_title">Meta Title</label>
                                            <input type="text" name="meta_title" id="meta_title" class="form-control" 
                                                   value="<?php echo isset($ajab_shahar) ? htmlspecialchars($ajab_shahar->meta_title) : ''; ?>" 
                                                   placeholder="Enter meta title">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="meta_keywords">Meta Keywords</label>
                                            <input type="text" name="meta_keywords" id="meta_keywords" class="form-control" 
                                                   value="<?php echo isset($ajab_shahar) ? htmlspecialchars($ajab_shahar->meta_keywords) : ''; ?>" 
                                                   placeholder="Enter meta keywords (comma separated)">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="meta_description">Meta Description</label>
                                            <textarea name="meta_description" id="meta_description" class="form-control" rows="4" 
                                                      placeholder="Enter meta description"><?php echo isset($ajab_shahar) ? htmlspecialchars($ajab_shahar->meta_description) : ''; ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="save-btn-container">
                            <button type="button" class="btn btn-secondary" id="loadAjabTemplate" style="margin-right:10px;">Load Starter Template</button>
                            <button type="submit" class="btn btn-primary save-btn">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://unpkg.com/grapesjs"></script>
<script src="https://unpkg.com/grapesjs-preset-webpage"></script>
<script src="https://unpkg.com/grapesjs-blocks-basic"></script>

<script>
$(document).ready(function() {
    let builder = null;
    let currentEntryId = '';
    let menuTypes = <?php
        $slugs = [];
        if (!empty($ajabMenusList)) { foreach ($ajabMenusList as $m) { $slugs[] = $m->slug; } }
        echo json_encode($slugs);
    ?>;
    let menuLabels = <?php
        $labels = [];
        if (!empty($ajabMenusList)) { foreach ($ajabMenusList as $m) { $labels[$m->slug] = $m->label; } }
        echo json_encode((object)$labels);
    ?>;
    const aboutApiUrl = <?php echo json_encode(base_url('Api/about')); ?>;
    const menusListUrl = <?php echo json_encode(base_url('ajab-shahar/menus')); ?>;
    const menusCreateUrl = <?php echo json_encode(base_url('ajab-shahar/menus/create')); ?>;
    const initialEntry = <?php echo json_encode([
        'id' => $currentId,
        'type' => $currentType,
        'visual_content' => $currentVisualContent
    ]); ?>;

    const menuEntries = {};
    menuTypes.forEach(function(t){ menuEntries[t] = null; });

    const starterTemplate = `
<section style="max-width: 900px; margin: 0 auto; padding: 10px 0;">
  <h2 style="font-size: 32px; margin-bottom: 16px;">Introduction to Ajab Shahar</h2>
  <p style="font-size: 16px; line-height: 1.8; margin-bottom: 12px;">
    Yaha apna intro content likhiye. Aap strong text, links aur styled paragraphs use kar sakte hain.
  </p>

  <div class="custom-slider" style="position: relative; margin: 26px 0;">
    <img src="https://via.placeholder.com/900x500" alt="Slider Image" style="width:100%; border-radius: 8px; display:block;" />
  </div>

  <h3 style="font-size: 28px; margin-top: 26px; margin-bottom: 12px;">Inspired by Satsang</h3>
  <p style="font-size: 16px; line-height: 1.8; margin-bottom: 12px;">
    Yaha dusra section likhiye. Is section me image/video embed, blockquote aur buttons add kar sakte hain.
  </p>

  <h3 style="font-size: 28px; margin-top: 26px; margin-bottom: 12px;">Research and Curation</h3>
  <p style="font-size: 16px; line-height: 1.8;">
    Is area me long-form story, references aur multimedia blocks add kar sakte hain.
  </p>

  <div style="margin-top: 24px;">
    <iframe width="100%" height="420" src="https://www.youtube.com/embed/dQw4w9WgXcQ" title="Video" frameborder="0" allowfullscreen></iframe>
  </div>
</section>`;

    const setBuilderContent = function(contentHtml) {
        if (!builder) {
            return;
        }
        builder.setComponents(contentHtml || '');
    };

    const getBuilderOutput = function() {
        if (!builder) {
            return '';
        }
        const html = builder.getHtml() || '';
        const css = builder.getCss() || '';
        const js = builder.getJs() || '';

        let output = '';
        if (css.trim()) {
            output += '<style>' + css + '</style>';
        }
        output += html;
        if (js.trim()) {
            output += '<script>' + js + '<\/script>';
        }
        return output;
    };

    const formatMenuTitle = function(type) {
        if (menuLabels && menuLabels[type]) return menuLabels[type];
        return type.charAt(0).toUpperCase() + type.slice(1);
    };

    const setMenuStates = function() {
        $('#ajabMenuSwitch .ajab-menu-btn').each(function(){
            const t = ($(this).data('type') || '').toString();
            $(this).find('.menu-state').text(menuEntries[t] ? 'Saved' : 'Not Saved');
        });
    };

    const activateMenu = function(type) {
        if (!builder || menuTypes.indexOf(type) === -1) {
            return;
        }

        $('#ajabMenuSwitch .ajab-menu-btn').removeClass('active');
        $('#ajabMenuSwitch .ajab-menu-btn[data-type="' + type + '"]').addClass('active');

        const entry = menuEntries[type];
        currentEntryId = entry && entry.id ? String(entry.id) : '';
        $('#type').val(type);
        $('#entry_id').val(currentEntryId);

        setBuilderContent(entry && entry.visual_content ? entry.visual_content : '');

        // Logo is now shared across all menu tabs — no per-tab swap needed.

        // Populate Meta fields from menu entry (auto-load when switching menu)
        $('#meta_title').val(entry && entry.meta_title ? String(entry.meta_title) : '');
        $('#meta_keywords').val(entry && entry.meta_keywords ? String(entry.meta_keywords) : '');
        $('#meta_description').val(entry && entry.meta_description ? String(entry.meta_description) : '');

        const isExisting = !!currentEntryId;
        $('.save-btn').text(isExisting ? 'Update' : 'Save');
        $('#selectedMenuInfo').text(formatMenuTitle(type) + (isExisting ? ' selected (existing entry: will update).' : ' selected (new entry: will save once).'));
    };

    // Live preview when user selects new file
    $(document).on('change', '#menu_image', function () {
        const f = this.files && this.files[0];
        if (!f) return;
        const r = new FileReader();
        r.onload = function (e) {
            $('#menu_image_preview').attr('src', e.target.result);
            $('#menu_image_preview_wrap').show();
        };
        r.readAsDataURL(f);
    });

    const hydrateFromApi = function() {
        return $.getJSON(aboutApiUrl)
            .done(function(response) {
                const menus = response && response.data && response.data.ajab_shahar && response.data.ajab_shahar.menus
                    ? response.data.ajab_shahar.menus
                    : {};

                menuTypes.forEach(function(type) {
                    const menuArray = Array.isArray(menus[type]) ? menus[type] : [];
                    menuEntries[type] = menuArray.length > 0 ? menuArray[0] : null;
                });
            })
            .fail(function() {
                if (initialEntry && initialEntry.type && menuTypes.indexOf(initialEntry.type) !== -1) {
                    menuEntries[initialEntry.type] = {
                        id: initialEntry.id || '',
                        visual_content: initialEntry.visual_content || '',
                        type_label: initialEntry.type
                    };
                }
            });
    };

    builder = grapesjs.init({
        container: '#gjs',
        fromElement: false,
        height: '620px',
        width: 'auto',
        storageManager: false,
        avoidInlineStyle: false,
        showOffsets: true,
        noticeOnUnload: false,
        plugins: ['gjs-preset-webpage', 'gjs-blocks-basic'],
        pluginsOpts: {
            'gjs-preset-webpage': {
                navbarOpts: false,
                countdownOpts: false,
                formsOpts: true,
                blocksBasicOpts: {
                    flexGrid: true
                }
            },
            'gjs-blocks-basic': {
                blocks: ['column1', 'column2', 'column3', 'text', 'link', 'image', 'video'],
                flexGrid: true,
                category: 'Basic'
            }
        },
        assetManager: {
            upload: true,
            uploadName: 'files',
            uploadText: 'Upload Images (drag/drop ya click)',
            showUrlInput: true,
            embedAsBase64: true,
            autoAdd: true,
            assets: [],
            uploadFile: function(e) {
                const files = e && e.dataTransfer ? e.dataTransfer.files : (e && e.target ? e.target.files : []);
                if (!files || !files.length) {
                    return;
                }

                Array.from(files).forEach(function(file) {
                    if (!file.type || file.type.indexOf('image/') !== 0) {
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function(event) {
                        builder.AssetManager.add({
                            src: event.target.result,
                            name: file.name,
                            type: 'image'
                        });
                    };
                    reader.readAsDataURL(file);
                });
            }
        }
    });

    builder.BlockManager.add('ajab-hero-banner', {
        label: 'Hero Banner',
        category: 'Ajab Templates',
        content: '<section style="padding:60px 20px;background:#f4f7ff;text-align:center;border-radius:12px;"><h1 style="font-size:42px;margin:0 0 14px;">Ajab Shahar Section</h1><p style="font-size:18px;line-height:1.7;max-width:760px;margin:0 auto 20px;">Apna hero intro, tagline aur description yaha likhiye.</p><a href="#" style="display:inline-block;background:#1d4ed8;color:#fff;padding:12px 24px;border-radius:6px;text-decoration:none;">Read More</a></section>'
    });

    builder.BlockManager.add('ajab-image-card', {
        label: 'Image Card',
        category: 'Ajab Templates',
        content: '<div style="max-width:360px;border:1px solid #e5e7eb;border-radius:12px;overflow:hidden;background:#fff;box-shadow:0 6px 20px rgba(0,0,0,0.08);"><img src="https://via.placeholder.com/800x450" style="width:100%;display:block;" alt="Card image" /><div style="padding:16px;"><h3 style="font-size:22px;margin:0 0 8px;">Card Title</h3><p style="margin:0;color:#4b5563;line-height:1.7;">Is card me content, image aur button add kar sakte ho.</p></div></div>'
    });

    builder.BlockManager.add('ajab-gallery-grid', {
        label: 'Image Gallery',
        category: 'Ajab Templates',
        content: '<section style="padding:16px 0;"><h3 style="margin:0 0 12px;font-size:24px;">Image Gallery</h3><div style="display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:10px;"><img src="https://via.placeholder.com/600x400" style="width:100%;border-radius:8px;" /><img src="https://via.placeholder.com/600x400" style="width:100%;border-radius:8px;" /><img src="https://via.placeholder.com/600x400" style="width:100%;border-radius:8px;" /></div></section>'
    });

    builder.BlockManager.add('ajab-slider-strip', {
        label: 'Image Slider',
        category: 'Ajab Templates',
        content: '<section style="padding:18px 0;"><h3 style="font-size:24px;margin:0 0 10px;">Slider Section</h3><div style="display:flex;overflow-x:auto;gap:10px;scroll-snap-type:x mandatory;padding-bottom:8px;"><img src="https://via.placeholder.com/900x500" style="min-width:100%;scroll-snap-align:start;border-radius:10px;" /><img src="https://via.placeholder.com/900x500" style="min-width:100%;scroll-snap-align:start;border-radius:10px;" /><img src="https://via.placeholder.com/900x500" style="min-width:100%;scroll-snap-align:start;border-radius:10px;" /></div></section>'
    });

    builder.BlockManager.add('ajab-cta-strip', {
        label: 'CTA Section',
        category: 'Ajab Templates',
        content: '<section style="padding:24px;background:#0f172a;color:#fff;border-radius:12px;text-align:center;"><h3 style="margin:0 0 8px;font-size:28px;">Custom Call to Action</h3><p style="margin:0 0 14px;font-size:16px;opacity:.9;">Apni team, campaign ya navigation ke liye CTA use karo.</p><a href="#" style="display:inline-block;background:#22c55e;color:#06240f;padding:10px 18px;border-radius:8px;text-decoration:none;font-weight:600;">Get Started</a></section>'
    });

    builder.Panels.addButton('options', {
        id: 'load-template',
        className: 'fa fa-file-text-o',
        command: 'load-template',
        attributes: { title: 'Load Starter Template' }
    });

    builder.Panels.addButton('options', {
        id: 'open-assets-manager',
        className: 'fa fa-picture-o',
        command: 'open-assets',
        attributes: { title: 'Open Media Library (upload/select images)' }
    });

    builder.Panels.addButton('views', {
        id: 'open-blocks-sidebar',
        className: 'fa fa-th-large',
        command: 'open-blocks',
        attributes: { title: 'Blocks / Templates' }
    });

    builder.Commands.add('load-template', {
        run: function(ed) {
            ed.setComponents(starterTemplate);
        }
    });

    hydrateFromApi().always(function() {
        if (!builder) {
            return;
        }

        setMenuStates();

        let defaultType = menuTypes.find(function(type) {
            return !!menuEntries[type];
        });

        if (!defaultType) {
            defaultType = ($('#type').val() || '').trim();
        }

        if (menuTypes.indexOf(defaultType) === -1) {
            defaultType = 'intro';
        }

        activateMenu(defaultType);
    });

    // ---- Add New Menu modal ----
    const $addModal = $('#ajabAddMenuModal');
    const openAddModal = function(){
        $('#ajabNewMenuName').val('');
        $addModal.css('display','flex');
        setTimeout(function(){ $('#ajabNewMenuName').focus(); }, 50);
    };
    const closeAddModal = function(){ $addModal.hide(); };

    $('#addAjabMenuBtn').on('click', openAddModal);
    $('#ajabAddMenuCancel').on('click', closeAddModal);
    $addModal.on('click', function(e){ if (e.target === this) closeAddModal(); });

    $('#ajabAddMenuSave').on('click', function(){
        const name = ($('#ajabNewMenuName').val() || '').trim();
        if (!name) {
            Swal.fire({icon:'warning', title:'Missing name', text:'Please enter a menu name'});
            return;
        }
        const $btn = $(this).prop('disabled', true).text('Adding...');
        $.post(menusCreateUrl, { label: name }, function(resp){
            if (resp && resp.status && resp.data) {
                const slug = String(resp.data.slug || '').toLowerCase();
                const label = String(resp.data.label || name);
                if (menuTypes.indexOf(slug) === -1) {
                    menuTypes.push(slug);
                    menuLabels[slug] = label;
                    menuEntries[slug] = null;
                    const $btnEl = $('<button type="button" class="ajab-menu-btn"></button>')
                        .attr('data-type', slug)
                        .attr('data-id', resp.data.id || '')
                        .append($('<span class="menu-title"></span>').text(label))
                        .append($('<span class="menu-state"></span>').text('Not Saved'));
                    $('#ajabMenuSwitch').append($btnEl);
                }
                closeAddModal();
                activateMenu(slug);
                Swal.fire({icon:'success', title:'Menu added', timer:1200, showConfirmButton:false});
            } else {
                const msg = (resp && resp.message) ? resp.message : 'Failed to add menu';
                Swal.fire({icon:'error', title:'Error', text: msg});
            }
        }, 'json').fail(function(){
            Swal.fire({icon:'error', title:'Network error', text:'Could not reach server'});
        }).always(function(){
            $btn.prop('disabled', false).text('Add');
        });
    });

    $('#ajabMenuSwitch').on('click', '.ajab-menu-btn', function() {
        const type = ($(this).data('type') || '').toString();
        activateMenu(type);
    });

    $('#loadAjabTemplate').on('click', function() {
        if (!builder) {
            return;
        }
        builder.setComponents(starterTemplate);
    });

    // Form validation
    $('#aboutForm').on('submit', function(e) {
        e.preventDefault();

        const selectedType = ($('#type').val() || '').trim();
        if (!selectedType) {
            Swal.fire({
                icon: 'warning',
                title: 'Missing Input',
                text: 'Please select menu section',
                confirmButtonText: 'OK'
            });
            return false;
        }

        const content = (getBuilderOutput() || '').trim();
        if (!content) {
            Swal.fire({
                icon: 'warning',
                title: 'Missing Input',
                text: 'Please create content in Visual Content Editor',
                confirmButtonText: 'OK'
            });
            return false;
        }

        $('#visual_content_textarea').val(content);

        const $form = $(this);
        const saveUrl = ($form.data('save-url') || '').toString();
        const updateBaseUrl = ($form.data('update-base-url') || '').toString();
        const entryId = ($('#entry_id').val() || '').toString().trim();

        if (entryId) {
            $form.attr('action', updateBaseUrl + '/' + entryId);
        } else {
            $form.attr('action', saveUrl);
        }

        this.submit();
    });
});
</script>

<?php include('inc/footer.php'); ?>