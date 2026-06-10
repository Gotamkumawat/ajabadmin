<?php
include('inc/header.php');
include('inc/sidebar.php');

$sectionSlug  = isset($section) ? $section->slug : '';
$sectionLabel = isset($section) ? $section->label : 'Section';
$sectionStatus = isset($section) ? (int)$section->status_value : 0;

$entry = isset($entry) ? $entry : null;
$currentType         = $entry && isset($entry->type_label) ? $entry->type_label : '';
$currentVisualContent= $entry && isset($entry->visual_content) ? $entry->visual_content : '';
$currentId           = $entry && isset($entry->id) ? (int)$entry->id : 0;
$currentMenuImage    = $entry && isset($entry->menu_image) ? trim((string)$entry->menu_image) : '';

// Logo is shared across all menu tabs of this section — fetch from any saved row
if ($currentMenuImage === '' && $this->db->table_exists('about')) {
    $sharedImg = $this->db->select('menu_image')
        ->from('about')
        ->where('status', $sectionStatus)
        ->where('menu_image IS NOT NULL', null, false)
        ->where("TRIM(menu_image) !=", '')
        ->order_by('id', 'DESC')->limit(1)
        ->get()->row_array();
    if (!empty($sharedImg['menu_image'])) {
        $currentMenuImage = trim((string)$sharedImg['menu_image']);
    }
}

$sectionMenusList = isset($section_menus) && is_array($section_menus) ? $section_menus : [];
?>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://unpkg.com/grapesjs/dist/css/grapes.min.css" />
</head>

<style>
    .form-group label { font-weight: 600; margin-bottom: 0.5rem; display: block; }
    .save-btn-container { display:flex; justify-content:flex-end; margin-bottom:0; }
    .save-btn { position:relative; top:-20px; padding:8px 30px; font-size:16px; border-radius:5px;
        box-shadow:0 2px 5px rgba(0,0,0,0.15); transition:all 0.3s ease; }
    .section-menu-grid { display:grid; grid-template-columns:repeat(5,minmax(140px,1fr)); gap:10px; }
    .section-menu-btn { border:1px solid #ced4da; background:#fff; color:#212529; border-radius:6px;
        padding:10px; text-align:left; transition:all 0.2s ease; position:relative; }
    .section-menu-btn .menu-title { font-size:14px; font-weight:600; display:block; }
    .section-menu-btn .menu-state { font-size:12px; color:#6c757d; display:block; margin-top:2px; }
    .section-menu-btn.active { border-color:#007bff; background:#e9f3ff; color:#0056b3; }
    .section-menu-btn .menu-del { position:absolute; top:4px; right:6px; font-size:11px;
        color:#c0392b; opacity:.6; cursor:pointer; background:transparent; border:none; }
    .section-menu-btn .menu-del:hover { opacity:1; }
    #selectedMenuInfo { font-size:13px; margin-top:8px; color:#6c757d; }
    #gjs { border:1px solid #ced4da; min-height:620px; border-radius:4px; overflow:hidden; }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?php echo htmlspecialchars($sectionLabel); ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('add-about') ?>">Add About</a></li>
                        <li class="breadcrumb-item active"><?php echo htmlspecialchars($sectionLabel); ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-default">
                <div class="card-header" style="padding:4px 8px; margin:0;">
                    <a href="<?= base_url('add-about') ?>" class="btn btn-secondary" style="padding:3px 8px; font-size:13px; border-radius:4px;">
                        <i class="fas fa-arrow-left" style="font-size:13px; margin-right:4px;"></i> Back
                    </a>
                </div>
                <div class="card-body">
                    <?php if ($this->session->flashdata('success')): ?>
                        <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
                    <?php endif; ?>
                    <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
                    <?php endif; ?>

                    <form name="aboutForm" id="aboutForm" method="post" enctype="multipart/form-data"
                          action="<?php echo base_url('about-section/' . $sectionSlug . '/save'); ?>"
                          data-save-url="<?php echo base_url('about-section/' . $sectionSlug . '/save'); ?>"
                          data-update-base-url="<?php echo base_url('about-section/' . $sectionSlug . '/update'); ?>">
                        <!-- Logo (shared across all menu tabs) -->
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label>Logo</label>
                                <input type="hidden" name="menu_image_existing" id="menu_image_existing" value="<?php echo htmlspecialchars($currentMenuImage); ?>">
                                <input type="file" name="menu_image" id="menu_image" class="form-control" accept="image/*" style="max-width:360px;">
                                <div id="menu_image_preview_wrap" style="margin-top:8px;<?php echo $currentMenuImage === '' ? 'display:none;' : ''; ?>">
                                    <img id="menu_image_preview" src="<?php echo $currentMenuImage !== '' ? base_url(ltrim($currentMenuImage,'/')) : ''; ?>"
                                         alt="Logo" style="max-width:200px; max-height:120px; border:1px solid #ced4da; padding:4px; border-radius:4px;">
                                    <div class="text-muted small">Current logo (shared across all menu tabs). Choose a new file to replace.</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 form-group">
                                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:6px;">
                                    <label style="margin:0;">Menu <span style="color:red">*</span></label>
                                    <button type="button" id="addSectionMenuBtn" class="btn btn-success btn-sm">
                                        <i class="fa fa-plus"></i> Add New
                                    </button>
                                </div>
                                <input type="hidden" name="type" id="type" value="<?php echo htmlspecialchars($currentType, ENT_QUOTES, 'UTF-8'); ?>">
                                <input type="hidden" id="entry_id" value="<?php echo $currentId; ?>">

                                <div id="sectionMenuSwitch" class="section-menu-grid">
                                    <?php foreach ($sectionMenusList as $m):
                                        $slug = htmlspecialchars($m->slug, ENT_QUOTES, 'UTF-8');
                                        $label = htmlspecialchars($m->label, ENT_QUOTES, 'UTF-8'); ?>
                                    <!-- Menu card is a <button> with edit/delete <span> icons inside;
                                         click on those spans is intercepted in JS (stopPropagation) so
                                         only the icon's own action runs, not the parent activateMenu(). -->
                                    <button type="button" class="section-menu-btn" data-type="<?php echo $slug; ?>" data-id="<?php echo (int)$m->id; ?>" data-label="<?php echo $label; ?>" style="padding-right:48px;">
                                        <span class="menu-title"><?php echo $label; ?></span>
                                        <span class="menu-state">Not Saved</span>
                                        <span class="menu-edit-btn"   role="button" tabindex="0"
                                              data-id="<?php echo (int)$m->id; ?>" data-slug="<?php echo $slug; ?>" data-label="<?php echo $label; ?>"
                                              title="Edit menu"
                                              style="position:absolute; top:6px; right:28px; color:#1f6feb; cursor:pointer; font-size:13px; padding:2px 4px; opacity:.85; line-height:1;">
                                            <i class="fa fa-edit"></i>
                                        </span>
                                        <span class="menu-delete-btn" role="button" tabindex="0"
                                              data-id="<?php echo (int)$m->id; ?>" data-slug="<?php echo $slug; ?>"
                                              title="Delete menu"
                                              style="position:absolute; top:6px; right:8px; color:#dc3545; cursor:pointer; font-size:13px; padding:2px 4px; opacity:.85; line-height:1;">
                                            <i class="fa fa-times"></i>
                                        </span>
                                    </button>
                                    <?php endforeach; ?>
                                </div>
                                <div id="selectedMenuInfo">Select a menu section to add/edit content.</div>
                            </div>
                        </div>

                        <!-- Add New Menu Modal -->
                        <div id="sectionAddMenuModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.45); z-index:9999; align-items:center; justify-content:center;">
                            <div style="background:#fff; width:420px; max-width:92%; border-radius:8px; padding:18px; box-shadow:0 10px 40px rgba(0,0,0,0.2);">
                                <h5 style="margin:0 0 12px;">Add New Menu</h5>
                                <div class="form-group" style="margin-bottom:12px;">
                                    <label for="sectionNewMenuName">Menu Name</label>
                                    <input type="text" id="sectionNewMenuName" class="form-control" placeholder="e.g. Workshops" maxlength="100">
                                </div>
                                <div style="display:flex; gap:8px; justify-content:flex-end;">
                                    <button type="button" class="btn btn-secondary" id="sectionAddMenuCancel">Cancel</button>
                                    <button type="button" class="btn btn-primary" id="sectionAddMenuSave">Add</button>
                                </div>
                            </div>
                        </div>

                        <!-- Edit Menu Modal -->
                        <div id="sectionEditMenuModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.45); z-index:9999; align-items:center; justify-content:center;">
                            <div style="background:#fff; width:420px; max-width:92%; border-radius:8px; padding:18px; box-shadow:0 10px 40px rgba(0,0,0,0.2);">
                                <h5 style="margin:0 0 12px;">Edit Menu</h5>
                                <input type="hidden" id="sectionEditMenuId" value="">
                                <input type="hidden" id="sectionEditMenuOldSlug" value="">
                                <div class="form-group" style="margin-bottom:12px;">
                                    <label for="sectionEditMenuName">Menu Name</label>
                                    <input type="text" id="sectionEditMenuName" class="form-control" maxlength="100">
                                </div>
                                <div style="display:flex; gap:8px; justify-content:flex-end;">
                                    <button type="button" class="btn btn-secondary" id="sectionEditMenuCancel">Cancel</button>
                                    <button type="button" class="btn btn-primary" id="sectionEditMenuSave">Update</button>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label>Visual Content Editor</label>
                                <textarea class="form-control" name="visual_content" id="visual_content_textarea" rows="14" style="display:none;"><?php echo $currentVisualContent; ?></textarea>
                                <div id="gjs"></div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <h4>Meta Data</h4>
                                <div class="card"><div class="card-body">
                                    <div class="form-group">
                                        <label for="meta_title">Meta Title</label>
                                        <input type="text" name="meta_title" id="meta_title" class="form-control"
                                               value="<?php echo $entry ? htmlspecialchars($entry->meta_title) : ''; ?>" placeholder="Enter meta title">
                                    </div>
                                    <div class="form-group">
                                        <label for="meta_keywords">Meta Keywords</label>
                                        <input type="text" name="meta_keywords" id="meta_keywords" class="form-control"
                                               value="<?php echo $entry ? htmlspecialchars($entry->meta_keywords) : ''; ?>" placeholder="Enter meta keywords (comma separated)">
                                    </div>
                                    <div class="form-group">
                                        <label for="meta_description">Meta Description</label>
                                        <textarea name="meta_description" id="meta_description" class="form-control" rows="4" placeholder="Enter meta description"><?php echo $entry ? htmlspecialchars($entry->meta_description) : ''; ?></textarea>
                                    </div>
                                </div></div>
                            </div>
                        </div>

                        <div class="save-btn-container">
                            <button type="submit" class="btn btn-primary save-btn">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
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
        foreach ($sectionMenusList as $m) { $slugs[] = $m->slug; }
        echo json_encode($slugs);
    ?>;
    let menuLabels = <?php
        $labels = [];
        foreach ($sectionMenusList as $m) { $labels[$m->slug] = $m->label; }
        echo json_encode((object)$labels);
    ?>;
    const menusCreateUrl     = <?php echo json_encode(base_url('about-section/' . $sectionSlug . '/menus/create')); ?>;
    const menusUpdateBaseUrl = <?php echo json_encode(base_url('about-section/' . $sectionSlug . '/menus/update')); ?>;
    const menusDeleteBaseUrl = <?php echo json_encode(base_url('about-section/' . $sectionSlug . '/menus/delete')); ?>;
    const initialEntry = <?php echo json_encode([
        'id' => $currentId, 'type' => $currentType, 'visual_content' => $currentVisualContent
    ]); ?>;

    const menuEntries = {};
    menuTypes.forEach(function(t){ menuEntries[t] = null; });

    const setBuilderContent = function(html){ if (builder) builder.setComponents(html || ''); };

    const getBuilderOutput = function(){
        if (!builder) return '';
        const html = builder.getHtml() || '', css = builder.getCss() || '', js = builder.getJs() || '';
        let out = '';
        if (css.trim()) out += '<style>' + css + '</style>';
        out += html;
        if (js.trim()) out += '<script>' + js + '<\/script>';
        return out;
    };

    const formatMenuTitle = function(t){ return menuLabels && menuLabels[t] ? menuLabels[t] : (t.charAt(0).toUpperCase()+t.slice(1)); };

    const setMenuStates = function(){
        $('#sectionMenuSwitch .section-menu-btn').each(function(){
            const t = ($(this).data('type') || '').toString();
            $(this).find('.menu-state').text(menuEntries[t] ? 'Saved' : 'Not Saved');
        });
    };

    const activateMenu = function(type){
        if (!builder || menuTypes.indexOf(type) === -1) return;
        $('#sectionMenuSwitch .section-menu-btn').removeClass('active');
        $('#sectionMenuSwitch .section-menu-btn[data-type="' + type + '"]').addClass('active');
        const entry = menuEntries[type];
        currentEntryId = entry && entry.id ? String(entry.id) : '';
        $('#type').val(type);
        $('#entry_id').val(currentEntryId);
        setBuilderContent(entry && entry.visual_content ? entry.visual_content : '');
        $('#meta_title').val(entry && entry.meta_title ? String(entry.meta_title) : '');
        $('#meta_keywords').val(entry && entry.meta_keywords ? String(entry.meta_keywords) : '');
        $('#meta_description').val(entry && entry.meta_description ? String(entry.meta_description) : '');
        const isExisting = !!currentEntryId;
        $('.save-btn').text(isExisting ? 'Update' : 'Save');
        $('#selectedMenuInfo').text(formatMenuTitle(type) + (isExisting ? ' selected (existing entry: will update).' : ' selected (new entry: will save once).'));
    };

    $(document).on('change', '#menu_image', function(){
        const f = this.files && this.files[0]; if (!f) return;
        const r = new FileReader();
        r.onload = function(e){ $('#menu_image_preview').attr('src', e.target.result); $('#menu_image_preview_wrap').show(); };
        r.readAsDataURL(f);
    });

    // Hydrate from existing rows of this section in `about` table via Api/about
    const aboutApiUrl = <?php echo json_encode(base_url('Api/about')); ?>;
    const sectionSlug = <?php echo json_encode($sectionSlug); ?>;
    const hydrateFromApi = function(){
        return $.getJSON(aboutApiUrl).done(function(resp){
            const sections = resp && resp.data && resp.data.sections ? resp.data.sections : null;
            if (!sections || !sections[sectionSlug]) return;
            const menus = sections[sectionSlug].menus || {};
            menuTypes.forEach(function(t){
                const arr = Array.isArray(menus[t]) ? menus[t] : [];
                menuEntries[t] = arr.length > 0 ? arr[0] : null;
            });
        });
    };

    builder = grapesjs.init({
        container:'#gjs', fromElement:false, height:'620px', width:'auto',
        storageManager:false, avoidInlineStyle:false, showOffsets:true, noticeOnUnload:false,
        plugins:['gjs-preset-webpage','gjs-blocks-basic'],
        pluginsOpts:{
            'gjs-preset-webpage':{ navbarOpts:false, countdownOpts:false, formsOpts:true, blocksBasicOpts:{ flexGrid:true } },
            'gjs-blocks-basic':{ blocks:['column1','column2','column3','text','link','image','video'], flexGrid:true, category:'Basic' }
        },
        assetManager:{ upload:true, uploadName:'files', showUrlInput:true, embedAsBase64:true, autoAdd:true, assets:[] }
    });

    hydrateFromApi().always(function(){
        if (!builder) return;
        setMenuStates();
        let defaultType = menuTypes.find(function(t){ return !!menuEntries[t]; });
        if (!defaultType) defaultType = ($('#type').val() || '').trim().toLowerCase();
        if (menuTypes.indexOf(defaultType) === -1) defaultType = menuTypes[0] || '';
        if (defaultType) activateMenu(defaultType);
    });

    $('#sectionMenuSwitch').on('click', '.section-menu-btn', function(e){
        // Edit/Delete icons live INSIDE this button; ignore clicks that originated on them.
        if (e.target && e.target.closest && e.target.closest('.menu-edit-btn, .menu-delete-btn')) return;
        activateMenu(($(this).data('type') || '').toString());
    });

    // ----- Per-menu Delete -----
    $('#sectionMenuSwitch').on('click', '.menu-delete-btn', function(e){
        e.preventDefault(); e.stopPropagation();
        const $btn  = $(this);
        const id    = $btn.data('id');
        const slug  = String($btn.data('slug') || '');
        const $wrap = $btn.closest('.section-menu-btn');
        const label = $wrap.find('.menu-title').text().trim();
        Swal.fire({
            title: 'Delete this menu?',
            text: 'Menu "' + label + '" will be removed. This cannot be undone.',
            icon: 'warning', showCancelButton: true, confirmButtonText: 'Delete'
        }).then(function(r){
            if (!r.isConfirmed) return;
            $.post(menusDeleteBaseUrl + '/' + id, {}, function(resp){
                if (resp && resp.status) {
                    // Drop it from the in-memory lists so activateMenu() doesn't try to re-render it.
                    const idx = menuTypes.indexOf(slug);
                    if (idx !== -1) menuTypes.splice(idx, 1);
                    delete menuLabels[slug];
                    delete menuEntries[slug];
                    $wrap.remove();
                    // If we just deleted the currently active menu, fall back to the first remaining one.
                    if (($('#type').val() || '') === slug) {
                        $('#type').val('');
                        if (menuTypes.length) activateMenu(menuTypes[0]);
                        else $('#selectedMenuInfo').show().text('Select a menu section to add/edit content.');
                    }
                    Swal.fire({icon:'success', title:'Deleted', timer:900, showConfirmButton:false});
                } else {
                    Swal.fire({icon:'error', title:'Error', text:(resp && resp.message) ? resp.message : 'Delete failed'});
                }
            }, 'json').fail(function(){
                Swal.fire({icon:'error', title:'Network error', text:'Could not reach server'});
            });
        });
    });

    // ----- Per-menu Edit -----
    const $editMenuModal = $('#sectionEditMenuModal');
    const closeEditMenuModal = function(){ $editMenuModal.hide(); };
    $('#sectionEditMenuCancel').on('click', closeEditMenuModal);
    $editMenuModal.on('click', function(e){ if (e.target === this) closeEditMenuModal(); });

    $('#sectionMenuSwitch').on('click', '.menu-edit-btn', function(e){
        e.preventDefault(); e.stopPropagation();
        const $btn   = $(this);
        const id     = $btn.data('id');
        const slug   = String($btn.data('slug') || '');
        const label  = String($btn.data('label') || $btn.closest('.section-menu-btn').find('.menu-title').text().trim());
        $('#sectionEditMenuId').val(id);
        $('#sectionEditMenuOldSlug').val(slug);
        $('#sectionEditMenuName').val(label);
        $editMenuModal.css('display','flex');
        setTimeout(function(){ $('#sectionEditMenuName').focus().select(); }, 50);
    });

    $('#sectionEditMenuSave').on('click', function(){
        const id      = ($('#sectionEditMenuId').val() || '').trim();
        const oldSlug = ($('#sectionEditMenuOldSlug').val() || '').trim();
        const label   = ($('#sectionEditMenuName').val() || '').trim();
        if (!id) return;
        if (!label) { Swal.fire({icon:'warning', title:'Missing name', text:'Please enter a menu name'}); return; }
        const $btn = $(this).prop('disabled', true).text('Updating...');
        $.post(menusUpdateBaseUrl + '/' + id, { label: label }, function(resp){
            if (resp && resp.status && resp.data) {
                const d = resp.data;
                const newSlug = String(d.slug || '').toLowerCase();
                const newLabel = String(d.label || label);
                // Re-key the in-memory menu maps from oldSlug -> newSlug.
                const idx = menuTypes.indexOf(oldSlug);
                if (idx !== -1) menuTypes[idx] = newSlug;
                if (oldSlug !== newSlug) {
                    menuLabels[newSlug] = newLabel;
                    delete menuLabels[oldSlug];
                    menuEntries[newSlug] = menuEntries[oldSlug] || null;
                    delete menuEntries[oldSlug];
                } else {
                    menuLabels[newSlug] = newLabel;
                }
                // Refresh DOM attributes + visible text on the menu card.
                const $wrap = $('.menu-edit-btn[data-id="'+ id +'"]').closest('.section-menu-btn');
                $wrap.find('.section-menu-btn').attr('data-type', newSlug).attr('data-label', newLabel);
                $wrap.find('.menu-edit-btn').attr('data-slug', newSlug).attr('data-label', newLabel);
                $wrap.find('.menu-delete-btn').attr('data-slug', newSlug);
                $wrap.find('.menu-title').text(newLabel);
                // If we just renamed the active menu, point #type at the new slug.
                if (($('#type').val() || '') === oldSlug) {
                    $('#type').val(newSlug);
                }
                closeEditMenuModal();
                Swal.fire({icon:'success', title:'Updated', timer:1000, showConfirmButton:false});
            } else {
                Swal.fire({icon:'error', title:'Error', text:(resp && resp.message) ? resp.message : 'Update failed'});
            }
        }, 'json').fail(function(){
            Swal.fire({icon:'error', title:'Network error', text:'Could not reach server'});
        }).always(function(){ $btn.prop('disabled', false).text('Update'); });
    });

    // Add New Menu modal
    const $addModal = $('#sectionAddMenuModal');
    const openAddModal = function(){ $('#sectionNewMenuName').val(''); $addModal.css('display','flex'); setTimeout(function(){ $('#sectionNewMenuName').focus(); }, 50); };
    const closeAddModal = function(){ $addModal.hide(); };
    $('#addSectionMenuBtn').on('click', openAddModal);
    $('#sectionAddMenuCancel').on('click', closeAddModal);
    $addModal.on('click', function(e){ if (e.target === this) closeAddModal(); });

    $('#sectionAddMenuSave').on('click', function(){
        const name = ($('#sectionNewMenuName').val() || '').trim();
        if (!name) { Swal.fire({icon:'warning', title:'Missing name', text:'Please enter a menu name'}); return; }
        const $btn = $(this).prop('disabled', true).text('Adding...');
        $.post(menusCreateUrl, { label: name }, function(resp){
            if (resp && resp.status && resp.data) {
                const slug = String(resp.data.slug || '').toLowerCase();
                const label = String(resp.data.label || name);
                if (menuTypes.indexOf(slug) === -1) {
                    menuTypes.push(slug);
                    menuLabels[slug] = label;
                    menuEntries[slug] = null;
                    // Build the menu card to match the server-rendered structure
                    // exactly (same data-* attrs, padding, and Edit/Delete icons),
                    // so newly-added menus get the same Edit/Delete affordance
                    // without requiring a page reload.
                    const newId = resp.data.id || '';
                    const $btnEl = $('<button type="button" class="section-menu-btn"></button>')
                        .attr('data-type', slug)
                        .attr('data-id', newId)
                        .attr('data-label', label)
                        .attr('style', 'padding-right:48px;')
                        .append($('<span class="menu-title"></span>').text(label))
                        .append($('<span class="menu-state"></span>').text('Not Saved'))
                        .append(
                            $('<span class="menu-edit-btn" role="button" tabindex="0" title="Edit menu"></span>')
                                .attr('data-id', newId).attr('data-slug', slug).attr('data-label', label)
                                .attr('style', 'position:absolute; top:6px; right:28px; color:#1f6feb; cursor:pointer; font-size:13px; padding:2px 4px; opacity:.85; line-height:1;')
                                .append('<i class="fa fa-edit"></i>')
                        )
                        .append(
                            $('<span class="menu-delete-btn" role="button" tabindex="0" title="Delete menu"></span>')
                                .attr('data-id', newId).attr('data-slug', slug)
                                .attr('style', 'position:absolute; top:6px; right:8px; color:#dc3545; cursor:pointer; font-size:13px; padding:2px 4px; opacity:.85; line-height:1;')
                                .append('<i class="fa fa-times"></i>')
                        );
                    $('#sectionMenuSwitch').append($btnEl);
                }
                closeAddModal();
                activateMenu(slug);
                Swal.fire({icon:'success', title:'Menu added', timer:1200, showConfirmButton:false});
            } else {
                Swal.fire({icon:'error', title:'Error', text:(resp && resp.message) ? resp.message : 'Failed to add menu'});
            }
        }, 'json').fail(function(){
            Swal.fire({icon:'error', title:'Network error', text:'Could not reach server'});
        }).always(function(){ $btn.prop('disabled', false).text('Add'); });
    });

    $('#aboutForm').on('submit', function(e){
        e.preventDefault();
        const selectedType = ($('#type').val() || '').trim();
        if (!selectedType) { Swal.fire({icon:'warning', title:'Missing Input', text:'Please select menu section'}); return false; }
        const content = (getBuilderOutput() || '').trim();
        if (!content) { Swal.fire({icon:'warning', title:'Missing Input', text:'Please create content in Visual Content Editor'}); return false; }
        $('#visual_content_textarea').val(content);
        const $form = $(this);
        const saveUrl = ($form.data('save-url') || '').toString();
        const updateBaseUrl = ($form.data('update-base-url') || '').toString();
        const entryId = ($('#entry_id').val() || '').toString().trim();
        if (entryId) $form.attr('action', updateBaseUrl + '/' + entryId);
        else $form.attr('action', saveUrl);
        this.submit();
    });
});
</script>

<?php include('inc/footer.php'); ?>
