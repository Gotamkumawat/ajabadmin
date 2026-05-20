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
      for (var i = 0; i < lis.length; i++) {
        var li = lis[i];
        if (li.classList && li.classList.contains('ms-helper-container')) continue;
        var txt = (li.textContent || '').toLowerCase().trim();
        // First-name basis: match the start of the option label OR the start of
        // any whitespace-separated word inside it. So "kab" matches "Kabir Das",
        // "das" matches "Kabir Das", but "abir" does NOT match (no mid-word hits).
        var match = false;
        if (q === '') {
          match = true;
        } else {
          if (txt.indexOf(q) === 0) {
            match = true;
          } else {
            var words = txt.split(/\s+/);
            for (var w = 0; w < words.length; w++) {
              if (words[w].indexOf(q) === 0) { match = true; break; }
            }
          }
        }
        if (match) {
          li.classList.remove('ms-hidden');
          li.style.setProperty('display', '', 'important');
        } else {
          li.classList.add('ms-hidden');
          li.style.setProperty('display', 'none', 'important');
        }
      }
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

      // Close detection — click on close/cancel buttons or backdrop or Esc.
      // Ignore clicks that arrive within ~400ms of opening: the originating
      // click on the Edit button bubbles up to the modal container right after
      // we show it and would otherwise immediately close the popup.
      var openedAt = Date.now();
      console.log('[adminEdit] modal opened at', openedAt, 'modal:', opts.modalId);
      $modal.on('click.__adminEdit', function (e) {
        var age = Date.now() - openedAt;
        if (age < 400) { console.log('[adminEdit] ignoring modal click (age ' + age + 'ms)', e.target); return; }
        var t = e.target;
        if (t === this) { console.log('[adminEdit] backdrop click → close'); restore(); return; }
        var $t = $(t);
        if ($t.is('.close-btn, .btn-secondary, [data-dismiss="modal"], .close') || $t.closest('.close-btn, .btn-secondary, [data-dismiss="modal"], .close').length) {
          console.log('[adminEdit] close/cancel button click → close');
          setTimeout(restore, 30);
        }
      });
      $(document).on('keydown.__adminEdit', function (e) { if (e.key === 'Escape') { console.log('[adminEdit] Esc → close'); restore(); } });
      // Bootstrap modal hidden event
      if (isBsModal) $modal.on('hidden.bs.modal.__adminEdit', function () {
        var age = Date.now() - openedAt;
        console.log('[adminEdit] hidden.bs.modal fired (age ' + age + 'ms) → close');
        restore(); $modal.off('hidden.bs.modal.__adminEdit');
      });

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

            // Only Reset remains — "Select All" and "Select None" are removed per UX request.
            var $helper = $('<li class="ms-helper-container"></li>');
            var $row = $('<div class="ms-action-row"></div>');
            var $reset = $('<button type="button" class="ms-action-btn">Reset</button>');
            $reset.on('click', function(e) { e.preventDefault(); e.stopPropagation();
              var initial = $el.data('ms-initial-vals') || [];
              $el.multiselect('deselectAll', false);
              if (initial.length) $el.multiselect('select', initial, false);
              $el.multiselect('updateButtonText'); $el.trigger('change');
            });
            $row.append($reset);
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

<!-- ============================================================= -->
<!--  ADMIN-WIDE IMAGE CROP TOOL  (Cropper.js)                      -->
<!--  Auto-attaches to every <input type="file" accept="image/*">. -->
<!--  On file select -> opens crop modal -> cropped image is put    -->
<!--  back into the SAME input so the normal form upload sends the  -->
<!--  cropped file. No controller changes needed.                   -->
<!--  Skip an input by adding  data-no-crop="1"  to it.             -->
<!-- ============================================================= -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.css">
<script src="https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.js"></script>
<style>
  #adminCropModal{position:fixed;inset:0;z-index:200000;display:none;align-items:center;justify-content:center;background:rgba(0,0,0,.7);}
  #adminCropModal .acm-box{background:#fff;border-radius:8px;width:min(900px,94vw);max-height:94vh;display:flex;flex-direction:column;overflow:hidden;box-shadow:0 10px 40px rgba(0,0,0,.4);}
  #adminCropModal .acm-head{display:flex;align-items:center;justify-content:space-between;padding:12px 18px;border-bottom:1px solid #eee;}
  #adminCropModal .acm-head h3{margin:0;font-size:18px;color:#333;}
  #adminCropModal .acm-close{background:none;border:none;font-size:26px;line-height:1;cursor:pointer;color:#888;}
  #adminCropModal .acm-body{padding:14px 18px;overflow:auto;}
  #adminCropModal .acm-stage{max-height:60vh;}
  #adminCropModal .acm-stage img{max-width:100%;display:block;}
  #adminCropModal .acm-ratios{display:flex;flex-wrap:wrap;gap:8px;margin:12px 0 4px;}
  #adminCropModal .acm-ratios button{border:1px solid #c6c6c6;background:#f7f7f7;border-radius:4px;padding:6px 12px;font-size:13px;cursor:pointer;}
  #adminCropModal .acm-ratios button.active{background:#28a745;color:#fff;border-color:#28a745;}
  #adminCropModal .acm-foot{display:flex;justify-content:flex-end;gap:10px;padding:12px 18px;border-top:1px solid #eee;}
  #adminCropModal .acm-foot button{border:none;border-radius:4px;padding:9px 20px;font-size:14px;cursor:pointer;}
  #adminCropModal .acm-cancel{background:#6c757d;color:#fff;}
  #adminCropModal .acm-skip{background:#e0e0e0;color:#333;}
  #adminCropModal .acm-ok{background:#28a745;color:#fff;}
</style>
<div id="adminCropModal" aria-hidden="true">
  <div class="acm-box">
    <div class="acm-head">
      <h3>Crop Image</h3>
      <button type="button" class="acm-close" title="Cancel">&times;</button>
    </div>
    <div class="acm-body">
      <div class="acm-stage"><img id="acmImage" alt="To crop" /></div>
      <div class="acm-ratios">
        <button type="button" data-r="NaN" class="active">Free</button>
        <button type="button" data-r="1.7777777778">16:9</button>
        <button type="button" data-r="1">1:1</button>
        <button type="button" data-r="1.3333333333">4:3</button>
        <button type="button" data-r="0.75">3:4</button>
      </div>
    </div>
    <div class="acm-foot">
      <button type="button" class="acm-cancel">Cancel</button>
      <button type="button" class="acm-skip" title="Upload without cropping">Use Original</button>
      <button type="button" class="acm-ok">Crop &amp; Use</button>
    </div>
  </div>
</div>
<script>
(function () {
  if (window.__adminCropInit) return;
  window.__adminCropInit = true;

  var modal   = document.getElementById('adminCropModal');
  var imgEl   = document.getElementById('acmImage');
  var btnOk   = modal.querySelector('.acm-ok');
  var btnSkip = modal.querySelector('.acm-skip');
  var btnCx   = modal.querySelector('.acm-cancel');
  var btnX    = modal.querySelector('.acm-close');
  var ratioBtns = modal.querySelectorAll('.acm-ratios button');

  var cropper = null;
  var activeInput = null;
  var origFile = null;
  var objUrl = null;

  function shouldHandle(input) {
    if (!input || input.type !== 'file') return false;
    if (input.hasAttribute('data-no-crop')) return false;
    if (input.dataset.acmDone === '1') return false;          // re-entrancy guard
    if (input.multiple) return false;                          // single image only
    var acc = (input.getAttribute('accept') || '').toLowerCase();
    return acc.indexOf('image') !== -1;
  }

  function cleanup() {
    if (cropper) { try { cropper.destroy(); } catch (e) {} cropper = null; }
    if (objUrl) { URL.revokeObjectURL(objUrl); objUrl = null; }
    imgEl.removeAttribute('src');
    activeInput = null; origFile = null;
  }

  function closeModal() {
    modal.style.display = 'none';
    modal.setAttribute('aria-hidden', 'true');
    cleanup();
  }

  function setRatio(r) {
    ratioBtns.forEach(function (b) { b.classList.remove('active'); });
    if (cropper) cropper.setAspectRatio(r);
  }

  ratioBtns.forEach(function (b) {
    b.addEventListener('click', function () {
      ratioBtns.forEach(function (x) { x.classList.remove('active'); });
      b.classList.add('active');
      var v = parseFloat(b.getAttribute('data-r'));
      if (cropper) cropper.setAspectRatio(isNaN(v) ? NaN : v);
    });
  });

  function openModalFor(input, file) {
    activeInput = input;
    origFile = file;
    if (objUrl) URL.revokeObjectURL(objUrl);
    objUrl = URL.createObjectURL(file);
    imgEl.src = objUrl;
    modal.style.display = 'flex';
    modal.setAttribute('aria-hidden', 'false');
    ratioBtns.forEach(function (x) { x.classList.remove('active'); });
    ratioBtns[0].classList.add('active'); // Free
    imgEl.onload = function () {
      if (cropper) { try { cropper.destroy(); } catch (e) {} }
      cropper = new Cropper(imgEl, {
        viewMode: 1,
        autoCropArea: 1,
        movable: true,
        zoomable: true,
        background: true,
        responsive: true
      });
    };
  }

  // Put a Blob back into the original <input type=file> as a real File
  function setInputFile(input, blob, name) {
    try {
      var dt = new DataTransfer();
      var f = new File([blob], name, { type: blob.type || 'image/jpeg', lastModified: Date.now() });
      dt.items.add(f);
      input.dataset.acmDone = '1';        // prevent re-trigger from the change we cause
      input.files = dt.files;
      input.dispatchEvent(new Event('change', { bubbles: true }));
      // allow future selections to be cropped again
      setTimeout(function () { input.dataset.acmDone = '0'; }, 0);
    } catch (e) {
      console.error('[adminCrop] could not set cropped file, keeping original', e);
    }
  }

  btnOk.addEventListener('click', function () {
    if (!cropper || !activeInput) { closeModal(); return; }
    var input = activeInput;
    var srcName = (origFile && origFile.name) ? origFile.name : 'image.jpg';
    var outType = (origFile && origFile.type && origFile.type.indexOf('png') !== -1) ? 'image/png' : 'image/jpeg';
    var canvas = cropper.getCroppedCanvas({ maxWidth: 4096, maxHeight: 4096, imageSmoothingQuality: 'high' });
    if (!canvas) { closeModal(); return; }
    canvas.toBlob(function (blob) {
      if (blob) setInputFile(input, blob, srcName);
      closeModal();
    }, outType, 0.92);
  });

  // Keep the originally selected file, just close
  btnSkip.addEventListener('click', function () { closeModal(); });
  btnCx.addEventListener('click', function () { clearAndClose(); });
  btnX.addEventListener('click', function () { clearAndClose(); });
  modal.addEventListener('click', function (e) { if (e.target === modal) clearAndClose(); });

  // Cancel = discard the selection entirely
  function clearAndClose() {
    if (activeInput) {
      try {
        var dt = new DataTransfer();
        activeInput.dataset.acmDone = '1';
        activeInput.files = dt.files;
        setTimeout(function () { if (activeInput) activeInput.dataset.acmDone = '0'; }, 0);
      } catch (e) {}
    }
    closeModal();
  }

  // Delegated listener catches inputs added dynamically too
  document.addEventListener('change', function (e) {
    var input = e.target;
    if (!shouldHandle(input)) return;
    var file = input.files && input.files[0];
    if (!file || !/^image\//i.test(file.type)) return;
    if (typeof Cropper === 'undefined') return; // library failed to load -> upload as-is
    openModalFor(input, file);
  }, true);
})();
</script>

<!-- ============================================================= -->
<!--  ADMIN-WIDE THUMBNAIL EXCERPT — 50-word hard limit              -->
<!--  Attaches to every  <input name="thumbnail_excerpt">  and       -->
<!--  <input name="thumbnailexcerpt"> across the admin panel:        -->
<!--    • Adds a "Limit: 50 words" hint next to the box              -->
<!--    • Adds a live "X / 50 words" counter under the box           -->
<!--    • HARD-CAPS input at 50 words (extra words are stripped on   -->
<!--      input/paste so the 51st word can never be entered)         -->
<!-- ============================================================= -->
<style>
  .te-side-note { display:inline-block; margin-left:10px; color:#6c757d; font-size:12px; font-style:italic; white-space:nowrap; }
  .te-counter   { display:block; margin-top:4px; color:#6c757d; font-size:12px; }
  .te-counter.is-near { color:#b58900; }
  .te-counter.is-max  { color:#d9534f; font-weight:600; }
</style>
<script>
(function () {
  if (window.__teInit) return;
  window.__teInit = true;

  var LIMIT = 50;

  function wordsOf(s) {
    s = String(s == null ? '' : s).replace(/\s+/g, ' ').trim();
    if (s === '') return [];
    return s.split(' ');
  }

  function clampToLimit(value) {
    var w = wordsOf(value);
    if (w.length <= LIMIT) return null; // already within limit
    // Preserve a single trailing space if the user just typed one (so the next
    // keypress can finish a word naturally up to the cap).
    var trailingSpace = /\s$/.test(value || '');
    return w.slice(0, LIMIT).join(' ') + (trailingSpace ? ' ' : '');
  }

  function makeCounter(input) {
    var c = document.createElement('small');
    c.className = 'te-counter';
    // Put the counter right after the input. If a wrapper around the input is
    // a flex row, append to its parent's parent so it sits on a new line below.
    var host = input.parentElement || input;
    host.appendChild(c);
    return c;
  }

  function makeSideNote(input) {
    // Avoid double-adding when this runs more than once.
    if (input.dataset.teSidenote === '1') return;
    input.dataset.teSidenote = '1';
    var note = document.createElement('span');
    note.className = 'te-side-note';
    note.textContent = '(Limit: ' + LIMIT + ' words)';
    if (input.nextSibling) {
      input.parentNode.insertBefore(note, input.nextSibling);
    } else {
      input.parentNode.appendChild(note);
    }
  }

  function updateCounter(input, counter) {
    var n = wordsOf(input.value).length;
    counter.textContent = n + ' / ' + LIMIT + ' words';
    counter.classList.toggle('is-max',  n >= LIMIT);
    counter.classList.toggle('is-near', n >= Math.max(1, LIMIT - 10) && n < LIMIT);
  }

  function attach(input) {
    if (!input || input.dataset.teBound === '1') return;
    if (input.tagName !== 'INPUT' && input.tagName !== 'TEXTAREA') return;
    input.dataset.teBound = '1';

    // Tighten initial value if a server-side record already had > LIMIT words.
    var clamped = clampToLimit(input.value);
    if (clamped !== null) input.value = clamped;

    makeSideNote(input);
    var counter = makeCounter(input);
    updateCounter(input, counter);

    function enforce() {
      var c = clampToLimit(input.value);
      if (c !== null) {
        // Preserve caret position relative to the end of the value when possible.
        input.value = c;
      }
      updateCounter(input, counter);
    }

    // Handle every way text can change: typing, paste, drag-drop, autofill, IME.
    input.addEventListener('input', enforce);
    input.addEventListener('paste', function () { setTimeout(enforce, 0); });
    input.addEventListener('drop',  function () { setTimeout(enforce, 0); });
    input.addEventListener('change', enforce);
    // Block adding more words via plain typing once at the cap (lets users still
    // edit/delete inside existing text — only blocks expansion).
    input.addEventListener('keydown', function (e) {
      if (e.ctrlKey || e.metaKey || e.altKey) return;
      if (e.key && e.key.length === 1) {
        var w = wordsOf(input.value).length;
        if (w >= LIMIT) {
          // Allow space/letters only if they're modifying inside an existing word.
          // Simpler approach: block any keystroke that would *start* a new word
          // (i.e. typing while the last char is whitespace, or pressing space).
          var endsWithSpace = /\s$/.test(input.value);
          if (e.key === ' ' || endsWithSpace) {
            e.preventDefault();
          }
        }
      }
    });
  }

  function scan(root) {
    var inputs = (root || document).querySelectorAll(
      'input[name="thumbnail_excerpt"], input[name="thumbnailexcerpt"], textarea[name="thumbnail_excerpt"], textarea[name="thumbnailexcerpt"]'
    );
    for (var i = 0; i < inputs.length; i++) attach(inputs[i]);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function () { scan(document); });
  } else {
    scan(document);
  }
  // Re-scan after a moment to catch any inputs rendered late by other scripts.
  setTimeout(function () { scan(document); }, 500);
})();
</script>
</body>
</html>
