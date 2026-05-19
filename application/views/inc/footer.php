<!-- <footer class="main-footer">
    <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 3.1.0
    </div>
  </footer> -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- jQuery UI 1.11.4 -->
<script src="<?php echo base_url('plugins/jquery-ui/jquery-ui.min.js'); ?>"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="<?php echo base_url('plugins/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
<!-- Select2 (used by admin forms) -->
<script src="<?php echo base_url('plugins/select2/js/select2.full.min.js'); ?>"></script>
<!-- ChartJS -->
<script src="<?php echo base_url('plugins/chart.js/Chart.min.js'); ?>"></script>
<!-- Sparkline -->
<script src="<?php echo base_url('plugins/sparklines/sparkline.js'); ?>"></script>
<!-- JQVMap -->
<script src="<?php echo base_url('plugins/jqvmap/jquery.vmap.min.js'); ?>"></script>
<script src="<?php echo base_url('plugins/jqvmap/maps/jquery.vmap.usa.js'); ?>"></script>
<!-- jQuery Knob Chart -->
<script src="<?php echo base_url('plugins/jquery-knob/jquery.knob.min.js'); ?>"></script>
<!-- daterangepicker -->
<script src="<?php echo base_url('plugins/moment/moment.min.js'); ?>"></script>
<script src="<?php echo base_url('plugins/daterangepicker/daterangepicker.js'); ?>"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="<?php echo base_url('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js'); ?>"></script>
<!-- Summernote -->
<script src="<?php echo base_url('plugins/summernote/summernote-bs4.min.js'); ?>"></script>
<!-- overlayScrollbars -->
<script src="<?php echo base_url('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js'); ?>"></script>
<!-- Bootstrap4 Duallistbox -->
<script src="<?php echo base_url('plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js'); ?>"></script>
<!-- InputMask -->
<script src="<?php echo base_url('plugins/inputmask/jquery.inputmask.min.js'); ?>"></script>
<!-- bootstrap color picker -->
<script src="<?php echo base_url('plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js'); ?>"></script>
<!-- Bootstrap Switch -->
<script src="<?php echo base_url('plugins/bootstrap-switch/js/bootstrap-switch.min.js'); ?>"></script>
<!-- BS-Stepper -->
<script src="<?php echo base_url('plugins/bs-stepper/js/bs-stepper.min.js'); ?>"></script>
<!-- dropzonejs -->
<script src="<?php echo base_url('plugins/dropzone/min/dropzone.min.js'); ?>"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url('dist/js/adminlte.min.js'); ?>"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url('dist/js/demo.js'); ?>"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="<?php echo base_url('dist/js/pages/dashboard.js'); ?>"></script>

<script src="https://cdn.ckeditor.com/4.22.1/standard-all/ckeditor.js"></script>
<script>
    // Suppress CKEditor "version not secure" notification globally
    if (typeof CKEDITOR !== 'undefined') {
        CKEDITOR.config.versionCheck = false;
        CKEDITOR.on('instanceReady', function (ev) {
            if (ev.editor && ev.editor.showNotification === undefined) return;
            // Hide any version-check notifications
            var notifs = document.querySelectorAll('.cke_notifications_area .cke_notification_warning');
            notifs.forEach(function (n) { n.style.display = 'none'; });
        });
    }
</script>
<!-- Bootstrap Multiselect plugin — loaded after Select2 to ensure same jQuery instance -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.min.js"></script>
<script>
  $(function () {
    // ===== Global Bootstrap Multiselect search filter (delegated to document) =====
    $(document).on('click mousedown mouseup keydown keypress keyup input', '.ms-search-input', function(e) {
      e.stopPropagation();
    });
    $(document).on('input keyup', '.ms-search-input', function() {
      var q = (this.value || '').toLowerCase().trim();
      var $dd = $(this).closest('.multiselect-container.dropdown-menu');
      if (!$dd.length) return;
      var lis = $dd[0].children;
      var shown = 0, hidden = 0;
      for (var i = 0; i < lis.length; i++) {
        var li = lis[i];
        if (li.classList && li.classList.contains('ms-helper-container')) continue;
        var txt = (li.textContent || '').toLowerCase();
        if (q === '' || txt.indexOf(q) !== -1) {
          li.classList.remove('ms-hidden');
          li.style.setProperty('display', '', 'important');
          shown++;
        } else {
          li.classList.add('ms-hidden');
          li.style.setProperty('display', 'none', 'important');
          hidden++;
        }
      }
      console.log('[ms-search] q=', q, 'shown=', shown, 'hidden=', hidden);
    });

    /**
     * ADMIN-WIDE: Real-time refresh helper for any multi-select after option add/select.
     * Use this everywhere a new <option> is added dynamically (Add New buttons, AJAX, etc.)
     *   __adminRefreshSelect('#myselect');           // refresh, keep current selection
     *   __adminRefreshSelect('#myselect', '42');     // refresh AND select id 42
     *   __adminRefreshSelect('#myselect', ['1','2']); // multi-select multiple ids
     */
    window.__adminRefreshSelect = function (selector, addSelected) {
        var $el = (typeof selector === 'string') ? $(selector) : $(selector);
        if (!$el || !$el.length) return;

        // 1) Apply additional selections if requested
        if (addSelected !== undefined && addSelected !== null) {
            var addArr = Array.isArray(addSelected) ? addSelected : [String(addSelected)];
            var current = $el.val();
            if (!Array.isArray(current)) current = current ? [String(current)] : [];
            addArr.forEach(function (v) {
                v = String(v);
                if (current.indexOf(v) === -1) current.push(v);
            });
            $el.val(current);
        }

        // 2) Refresh widget — try every possible plugin attached
        try {
            // Bootstrap Multiselect (0.9.x stores under both 'multiselect' and 'bs.multiselect' keys)
            if ($.fn.multiselect && ($el.data('multiselect') || $el.data('bs.multiselect') || $el.next('.btn-group').find('.multiselect').length)) {
                $el.multiselect('rebuild');
                return;
            }
        } catch (e) { /* fall through */ }
        try {
            // Select2
            if ($.fn.select2 && $el.data('select2')) {
                $el.trigger('change.select2');
                return;
            }
        } catch (e) { /* fall through */ }
        // Plain change for any other listeners
        $el.trigger('change');
    };

    /**
     * ADMIN-WIDE: Open an existing "Add New" modal in EDIT mode for the currently
     * selected single option in a select.
     *
     * What it does:
     *   1) Validates exactly one item is selected in opts.selectId.
     *   2) Opens opts.modalId (the same modal used for Add New).
     *   3) Switches the modal title to opts.editTitle (default: "Edit").
     *   4) Pre-fills opts.fields[*] inputs with the current option's text (only the
     *      first field gets the option label by default; rest left blank unless
     *      a hidden data-* on the option provides values).
     *   5) Hides the original Add save button (opts.addSaveBtnId) and shows an
     *      injected Update button that POSTs to opts.updateUrl with the field
     *      values + the row id, then updates the option label + refreshes widget.
     *   6) When the modal closes (cancel/×/click-outside), it restores everything
     *      so the next "Add New" click works exactly as before.
     *
     * Required:
     *   selectId        '#singer'
     *   modalId         '#addSingerModal'
     *   addSaveBtnId    '#addSinger'           (the existing Add save button)
     *   updateUrl       BASE+'song/ajax_update_person'
     *   fields          [{ inputId:'#addSingerName', postKey:'name', primary:true }, { inputId:'#addSingerLink', postKey:'hyperlink' }]
     *
     * Optional:
     *   editTitle       'Edit Singer'           (modal H2 swap)
     *   titleSelector   '.modal-header h2,.modal-header h5,.modal-title'
     *   extraPayload    { type_id: 1 }
     *   labelFromResp   function(resp){ return resp.fullName || resp.label }
     */
    window.__adminEditOption = function (opts) {
      opts = opts || {};
      var $el = $(opts.selectId);
      if (!$el.length) { console.warn('[adminEdit] select not found:', opts.selectId); return; }
      var vals = $el.val();
      if (!vals) vals = [];
      if (!Array.isArray(vals)) vals = [vals];
      vals = vals.filter(function (v) { return v !== '' && v !== null && v !== undefined; });
      if (vals.length === 0) {
        if (window.Swal) Swal.fire({ icon: 'info', title: 'Select an item', text: 'Please select exactly one item to edit.' });
        else alert('Please select one item to edit.');
        return;
      }
      if (vals.length > 1) {
        if (window.Swal) Swal.fire({ icon: 'info', title: 'Pick one', text: 'Edit works on a single selection. Please select only one item.' });
        else alert('Edit works on a single selection.');
        return;
      }
      var id = String(vals[0]);
      var $opt = $el.find('option[value="' + id.replace(/(["\\])/g, '\\$1') + '"]');
      if (!$opt.length) return;
      var currentText = $.trim($opt.text());

      // Fallback to the simple prompt if no modal config supplied
      if (!opts.modalId) {
        var entered = window.prompt(opts.promptLabel || 'Edit Title', currentText);
        if (entered !== null && $.trim(entered) !== '' && entered !== currentText) {
          var post0 = {}; post0[opts.idPostKey || 'id'] = id; post0[opts.payloadKey || 'name'] = $.trim(entered);
          if (opts.extraPayload) for (var k0 in opts.extraPayload) post0[k0] = opts.extraPayload[k0];
          $.post(opts.updateUrl, post0, function (resp) {
            if (resp && (resp.success === true || resp.status === 'success' || resp.status === true)) {
              var lbl = (resp.fullName || resp.label || resp.name || resp.word_transliteration || resp.occupation_name) || entered;
              $opt.text(lbl);
              window.__adminRefreshSelect($el, id);
              if (window.Swal) Swal.fire({ icon:'success', title:'Updated', timer:1000, showConfirmButton:false });
            } else if (window.Swal) Swal.fire({ icon:'error', title:'Error', text:(resp && resp.message) ? resp.message : 'Update failed' });
          }, 'json');
        }
        return;
      }

      var $modal = $(opts.modalId);
      if (!$modal.length) { console.warn('[adminEdit] modal not found:', opts.modalId); return; }
      var $addBtn = opts.addSaveBtnId ? $(opts.addSaveBtnId) : $();
      var fields = Array.isArray(opts.fields) ? opts.fields : [];
      if (fields.length === 0) { console.warn('[adminEdit] no fields configured'); return; }

      // ----- Snapshot original state to restore on close -----
      var titleSel = opts.titleSelector || '.modal-header h2, .modal-header h5, .modal-title, h2, h5';
      var $title = $modal.find(titleSel).first();
      var origTitle = $title.length ? $title.text() : '';
      var addBtnDisplay = $addBtn.length ? $addBtn.css('display') : '';

      // ----- Pre-fill fields -----
      // Only the primary field is auto-filled with the option label; others left untouched (user can blank/keep)
      fields.forEach(function (f) {
        var $inp = $(f.inputId);
        if (!$inp.length) return;
        if (f.primary) $inp.val(currentText);
        // optional data-* prefill from option (e.g. <option data-hyperlink="https://...">)
        if (f.optionDataKey) {
          var dv = $opt.attr('data-' + f.optionDataKey);
          if (dv != null) $inp.val(dv);
        }
      });

      // ----- Inject Update button next to existing Add button -----
      if ($addBtn.length) $addBtn.hide();
      var $updateBtn = $modal.find('.__admin_edit_update_btn');
      if (!$updateBtn.length) {
        $updateBtn = $('<button type="button" class="btn btn-primary __admin_edit_update_btn">Update</button>');
        if ($addBtn.length) $addBtn.after($updateBtn); else $modal.find('.modal-footer, .modal-actions').first().append($updateBtn);
      }
      $updateBtn.show().prop('disabled', false).text('Update');

      // Swap title
      if ($title.length) $title.text(opts.editTitle || ('Edit ' + (origTitle || 'Item').replace(/^Add\s*New\s*/i, '')));

      // Show modal — Bootstrap .modal (has .fade) vs custom display:flex dialog
      var isBsModal = $modal.hasClass('modal') && ($modal.hasClass('fade') || $modal.attr('role') === 'dialog' || $modal.attr('data-bs-backdrop') != null);
      if (isBsModal) {
        try {
          if (window.bootstrap && bootstrap.Modal) bootstrap.Modal.getOrCreateInstance($modal[0]).show();
          else if ($.fn.modal) $modal.modal('show');
          else $modal.css('display', 'block');
        } catch (e) { $modal.css('display', 'block'); }
      } else {
        // Custom dialog — force on top of sidebar/AdminLTE chrome
        $modal.css({
          'display': 'flex',
          'position': 'fixed',
          'inset': '0',
          'z-index': 99999,
          'align-items': 'center',
          'justify-content': 'center',
          'background': $modal.css('background-color') && $modal.css('background-color') !== 'rgba(0, 0, 0, 0)' ? $modal.css('background-color') : 'rgba(0,0,0,0.45)'
        });
        $modal.children().first().css('z-index', 100000);
      }

      // ----- Restore on close -----
      var restored = false;
      var restore = function () {
        if (restored) return; restored = true;
        if ($title.length) $title.text(origTitle);
        $updateBtn.remove();
        if ($addBtn.length) $addBtn.css('display', addBtnDisplay || '');
        // clear primary/option-derived fields so next Add starts blank
        fields.forEach(function (f) {
          if (f.primary || f.optionDataKey) { try { $(f.inputId).val(''); } catch(e){} }
        });
        $modal.off('click.__adminEdit');
        $(document).off('keydown.__adminEdit');
        // Force-hide modal AND strip our injected inline styles so original Add flow works again
        if (!isBsModal) {
          $modal.css({
            'display': 'none',
            'position': '', 'inset': '', 'z-index': '',
            'align-items': '', 'justify-content': '', 'background': ''
          });
          $modal.children().first().css('z-index', '');
        } else {
          try {
            if (window.bootstrap && bootstrap.Modal) { var inst = bootstrap.Modal.getInstance($modal[0]); if (inst) inst.hide(); }
            else if ($.fn.modal) $modal.modal('hide');
            else $modal.hide();
          } catch (e) { $modal.hide(); }
        }
      };

      // Close detection — click on close/cancel buttons or backdrop or Esc
      $modal.on('click.__adminEdit', function (e) {
        var t = e.target;
        if (t === this) { restore(); return; }
        var $t = $(t);
        if ($t.is('.close-btn, .btn-secondary, [data-dismiss="modal"], .close') || $t.closest('.close-btn, .btn-secondary, [data-dismiss="modal"], .close').length) {
          setTimeout(restore, 30);
        }
      });
      $(document).on('keydown.__adminEdit', function (e) { if (e.key === 'Escape') restore(); });
      // Bootstrap modal hidden event
      if (isBsModal) $modal.on('hidden.bs.modal.__adminEdit', function () { restore(); $modal.off('hidden.bs.modal.__adminEdit'); });

      // ----- Update handler -----
      $updateBtn.on('click', function () {
        var post = {};
        post[opts.idPostKey || 'id'] = id;
        var primaryVal = '';
        fields.forEach(function (f) {
          var v = $.trim($(f.inputId).val() || '');
          if (f.primary) primaryVal = v;
          if (f.postKey) post[f.postKey] = v;
        });
        if (primaryVal === '') {
          if (window.Swal) Swal.fire({ icon:'warning', title:'Missing input', text:'Please fill in the main field' });
          else alert('Please fill in the main field');
          return;
        }
        if (opts.extraPayload) for (var k in opts.extraPayload) post[k] = opts.extraPayload[k];

        $updateBtn.prop('disabled', true).text('Saving...');
        $.post(opts.updateUrl, post, function (resp) {
          var ok = resp && (resp.success === true || resp.status === 'success' || resp.status === true);
          if (!ok) {
            var msg = (resp && resp.message) ? resp.message : 'Update failed';
            if (window.Swal) Swal.fire({ icon:'error', title:'Error', text:msg });
            else alert(msg);
            $updateBtn.prop('disabled', false).text('Update');
            return;
          }
          var lbl = (typeof opts.labelFromResp === 'function')
            ? opts.labelFromResp(resp)
            : (resp.fullName || resp.label || resp.name || resp.word_transliteration || resp.occupation_name || primaryVal);
          $opt.text(lbl);
          window.__adminRefreshSelect($el, id);
          restore();
          if (window.Swal) Swal.fire({ icon:'success', title:'Updated', timer:1100, showConfirmButton:false });
        }, 'json').fail(function () {
          if (window.Swal) Swal.fire({ icon:'error', title:'Network error', text:'Could not reach server' });
          else alert('Could not reach server');
          $updateBtn.prop('disabled', false).text('Update');
        });
      });
    };

    // Admin-wide multi-select handler:
    //   - Multi-select fields (.select2 + multiple) → Bootstrap Multiselect (old angular-multi-select look)
    //   - Single-select fields → Select2
    window.__adminInitMultiSelect = function($el) {
      $el = $($el);
      if (!$el.length) return;
      var isMultiple = $el.prop('multiple');
      var ph = $el.attr('data-placeholder') || (isMultiple ? 'Select options' : 'Select');

      if (isMultiple && $.fn.multiselect) {
        if ($el.data('bs.multiselect') || $el.next('.btn-group').find('.multiselect').length) return;
        // Destroy any existing Select2 first (to avoid double widgets)
        if ($el.data('select2')) {
          try { $el.select2('destroy'); } catch(e) {}
        }
        // Also remove orphan Select2 container that may remain from page-specific code
        $el.siblings('.select2-container').remove();
        $el.multiselect({
          nonSelectedText: ph,
          nSelectedText: ' selected',
          allSelectedText: 'All selected',
          enableFiltering: false,
          includeSelectAllOption: false,
          buttonWidth: '200px',
          maxHeight: 380,
          numberDisplayed: 5,
          buttonContainer: '<div class="btn-group" style="width:200px;" />',
          templates: {
            button: '<button type="button" class="multiselect dropdown-toggle" data-toggle="dropdown" style="display:block;position:relative;width:200px;max-width:200px;min-height:38px;max-height:220px;overflow-y:auto;overflow-x:hidden;text-align:center;cursor:pointer;border:1px solid #c6c6c6;padding:6px 24px 6px 10px;font-size:14px;border-radius:4px;color:#555;white-space:normal;word-wrap:break-word;word-break:break-word;background-color:#fff;background-image:linear-gradient(#fff,#f7f7f7);box-shadow:none;box-sizing:border-box;line-height:1.5;"><span class="multiselect-selected-text" style="display:block;white-space:normal;word-wrap:break-word;line-height:1.5;width:100%;text-align:center;"></span><b class="caret" style="position:absolute;right:8px;top:16px;display:inline-block;width:0;height:0;border-top:4px solid #333;border-right:4px solid transparent;border-left:4px solid transparent;"></b></button>'
          },
          buttonText: function(options) {
            if (options.length === 0) return ph;
            var labels = [];
            options.each(function() { labels.push($(this).text().trim()); });
            return labels.join(', ');
          },
          onChange: function() { $el.trigger('change'); },
          onSelectAll: function() { $el.trigger('change'); },
          onDeselectAll: function() { $el.trigger('change'); },
          onDropdownShown: function() {
            var $dropdown = $el.next('.btn-group').find('.multiselect-container.dropdown-menu');
            if (!$dropdown.length || $dropdown.find('.ms-helper-container').length) return;
            $el.data('ms-initial-vals', $el.val() ? $el.val().slice() : []);

            var $helper = $('<li class="ms-helper-container"></li>');
            var $row = $('<div class="ms-action-row"></div>');
            var $all = $('<button type="button" class="ms-action-btn">Select All</button>');
            var $none = $('<button type="button" class="ms-action-btn">Select None</button>');
            var $reset = $('<button type="button" class="ms-action-btn">Reset</button>');
            $all.on('click', function(e) { e.preventDefault(); e.stopPropagation();
              $el.multiselect('selectAll', false); $el.multiselect('updateButtonText'); $el.trigger('change'); });
            $none.on('click', function(e) { e.preventDefault(); e.stopPropagation();
              $el.multiselect('deselectAll', false); $el.multiselect('updateButtonText'); $el.trigger('change'); });
            $reset.on('click', function(e) { e.preventDefault(); e.stopPropagation();
              var initial = $el.data('ms-initial-vals') || [];
              $el.multiselect('deselectAll', false);
              if (initial.length) $el.multiselect('select', initial, false);
              $el.multiselect('updateButtonText'); $el.trigger('change');
            });
            $row.append($all).append($none).append($reset);
            $helper.append($row);

            var $search = $('<input type="text" class="ms-search-input" placeholder="Search..." autocomplete="off" />');
            $helper.append($search);
            $dropdown.prepend($helper);

            setTimeout(function() { try { $search[0].focus(); } catch(e) {} }, 50);
          }
        });
        // Mark with both data keys so different code paths can detect attached widget
        $el.data('bs.multiselect', true);
        return;
      }

      // Single-select → Select2
      if ($el.data('select2')) return;
      if ($el.attr('data-skip-select2') === 'true') return;
      $el.select2({ placeholder: ph, allowClear: !isMultiple });
    };

    // Run multiple times to overcome page-specific Select2 inits that may race after this
    function __runAdminInit() {
      $('.select2').each(function() { window.__adminInitMultiSelect(this); });
    }
    setTimeout(__runAdminInit, 0);
    setTimeout(__runAdminInit, 200);
    setTimeout(__runAdminInit, 600);
    setTimeout(__runAdminInit, 1500);
    $(window).on('load', function() {
      setTimeout(__runAdminInit, 100);
      setTimeout(__runAdminInit, 800);
    });

    //Initialize Select2 Elements
    try { $('.select2bs4').select2({ theme: 'bootstrap4' }); } catch (e) {}

    //Datemask dd/mm/yyyy (skip if inputmask plugin missing)
    if ($.fn.inputmask) {
      $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' });
      $('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' });
      $('[data-mask]').inputmask();
    }

    //Date picker (guarded — plugins not loaded on every page)
    if ($.fn.datetimepicker) {
      $('#reservationdate').datetimepicker({ format: 'L' });
      $('#reservationdatetime').datetimepicker({ icons: { time: 'far fa-clock' } });
    }
    if ($.fn.daterangepicker) {
      $('#reservation').daterangepicker();
      $('#reservationtime').daterangepicker({
        timePicker: true,
        timePickerIncrement: 30,
        locale: { format: 'MM/DD/YYYY hh:mm A' }
      });
    }
    //Date range as a button (guarded)
    if ($.fn.daterangepicker && typeof moment !== 'undefined') {
      $('#daterange-btn').daterangepicker(
        {
          ranges   : {
            'Today'       : [moment(), moment()],
            'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month'  : [moment().startOf('month'), moment().endOf('month')],
            'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
          },
          startDate: moment().subtract(29, 'days'),
          endDate  : moment()
        },
        function (start, end) {
          $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }
      );
    }

    //Timepicker (guarded)
    if ($.fn.datetimepicker) { $('#timepicker').datetimepicker({ format: 'LT' }); }

    //Bootstrap Duallistbox (guarded)
    if ($.fn.bootstrapDualListbox) { $('.duallistbox').bootstrapDualListbox(); }

    //Colorpicker (guarded)
    if ($.fn.colorpicker) {
      $('.my-colorpicker1').colorpicker();
      $('.my-colorpicker2').colorpicker();
      $('.my-colorpicker2').on('colorpickerChange', function(event) {
        $('.my-colorpicker2 .fa-square').css('color', event.color.toString());
      });
    }

    if ($.fn.bootstrapSwitch) {
      $("input[data-bootstrap-switch]").each(function(){
        $(this).bootstrapSwitch('state', $(this).prop('checked'));
      });
    }

  })
  // BS-Stepper Init (only on pages that have a .bs-stepper element)
  document.addEventListener('DOMContentLoaded', function () {
    var stepperEl = document.querySelector('.bs-stepper');
    if (stepperEl && typeof Stepper !== 'undefined') {
      window.stepper = new Stepper(stepperEl);
    }
  })

  // DropzoneJS Demo Code Start (only run if #template exists on this page)
  if (typeof Dropzone !== 'undefined') Dropzone.autoDiscover = false

  // Get the template HTML and remove it from the doumenthe template HTML and remove it from the doument
  var previewNode = document.querySelector("#template")
  if (!previewNode || typeof Dropzone === 'undefined') {
    // Skip Dropzone demo on pages without the template element
  } else {
  previewNode.id = ""
  var previewTemplate = previewNode.parentNode.innerHTML
  previewNode.parentNode.removeChild(previewNode)

  var myDropzone = new Dropzone(document.body, { // Make the whole body a dropzone
    url: "/target-url", // Set the url
    thumbnailWidth: 80,
    thumbnailHeight: 80,
    parallelUploads: 20,
    previewTemplate: previewTemplate,
    autoQueue: false, // Make sure the files aren't queued until manually added
    previewsContainer: "#previews", // Define the container to display the previews
    clickable: ".fileinput-button" // Define the element that should be used as click trigger to select files.
  })

  myDropzone.on("addedfile", function(file) {
    // Hookup the start button
    file.previewElement.querySelector(".start").onclick = function() { myDropzone.enqueueFile(file) }
  })

  // Update the total progress bar
  myDropzone.on("totaluploadprogress", function(progress) {
    document.querySelector("#total-progress .progress-bar").style.width = progress + "%"
  })

  myDropzone.on("sending", function(file) {
    // Show the total progress bar when upload starts
    document.querySelector("#total-progress").style.opacity = "1"
    // And disable the start button
    file.previewElement.querySelector(".start").setAttribute("disabled", "disabled")
  })

  // Hide the total progress bar when nothing's uploading anymore
  myDropzone.on("queuecomplete", function(progress) {
    document.querySelector("#total-progress").style.opacity = "0"
  })

  // Setup the buttons for all transfers
  // The "add files" button doesn't need to be setup because the config
  // `clickable` has already been specified.
  document.querySelector("#actions .start").onclick = function() {
    myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED))
  }
  document.querySelector("#actions .cancel").onclick = function() {
    myDropzone.removeAllFiles(true)
  }
  } // end else (Dropzone demo block)
  // DropzoneJS Demo Code End
</script>
<!-- <script>
  var app = angular.module("cartoonApp", []);

  app.controller("cartoonCtrl", function($scope) {
      $scope.message = "✅ AngularJS is working!";
  });
</script> -->



<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script src="<?= base_url('assets/js/add-couplet.js') ?>"></script>
</body>
</html>
