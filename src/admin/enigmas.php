<?php include 'session.php';
include 'functions.php';
if (!checkPermissions()) {
    goHome();
}
$_TITLE = 'Enigma Devices';
include 'header.php'; ?>
<div class="wrapper" <?php if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                            echo ' style="display: none;"';
                        } ?>>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <?php include 'topbar.php'; ?>
                    </div>
                    <h4 class="page-title"><?php echo $_['enigma_devices']; ?></h4>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <?php if (isset($_STATUS) && $_STATUS == STATUS_SUCCESS) { ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        Device has been added / modified.
                    </div>
                <?php } ?>
                <div class="card">
                    <div class="card-body" style="overflow-x:auto;">
                        <div id="collapse_filters" class="form-group row mb-4 <?php echo $rMobile ? 'collapse' : ''; ?>">
                            <div class="col-md-3">
                                <input type="text" class="form-control" id="e2_search" value="<?php echo isset(CoreUtilities::$rRequest['search']) ? htmlspecialchars(CoreUtilities::$rRequest['search']) : ''; ?>" placeholder="<?php echo $_['search_devices']; ?>...">
                            </div>
                            <label class="col-md-2 col-form-label text-center" for="e2_reseller">Filter Results &nbsp; <button type="button" class="btn btn-light waves-effect waves-light btn-xs" onClick="clearOwner();"><i class="mdi mdi-close"></i></button></label>
                            <div class="col-md-3">
                                <select id="e2_reseller" class="form-control" data-toggle="select2">
                                    <?php if (isset(CoreUtilities::$rRequest['owner']) && ($rOwner = getRegisteredUser(intval(CoreUtilities::$rRequest['owner'])))) { ?>
                                        <option value="<?php echo intval($rOwner['id']); ?>" selected="selected"><?php echo $rOwner['username']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select id="e2_filter" class="form-control" data-toggle="select2">
                                    <option value="" <?php if (!isset(CoreUtilities::$rRequest['filter'])) {
                                                            echo 'selected';
                                                        } ?>><?php echo $_['no_filter']; ?></option>
                                    <option value="1" <?php if (isset(CoreUtilities::$rRequest['filter']) && CoreUtilities::$rRequest['filter'] == 1) {
                                                            echo 'selected';
                                                        } ?>><?php echo $_['active']; ?></option>
                                    <option value="2" <?php if (isset(CoreUtilities::$rRequest['filter']) && CoreUtilities::$rRequest['filter'] == 2) {
                                                            echo 'selected';
                                                        } ?>><?php echo $_['disabled']; ?></option>
                                    <option value="3" <?php if (isset(CoreUtilities::$rRequest['filter']) && CoreUtilities::$rRequest['filter'] == 3) {
                                                            echo 'selected';
                                                        } ?>><?php echo $_['banned']; ?></option>
                                    <option value="4" <?php if (isset(CoreUtilities::$rRequest['filter']) && CoreUtilities::$rRequest['filter'] == 4) {
                                                            echo 'selected';
                                                        } ?>><?php echo $_['expired']; ?></option>
                                    <option value="5" <?php if (isset(CoreUtilities::$rRequest['filter']) && CoreUtilities::$rRequest['filter'] == 5) {
                                                            echo 'selected';
                                                        } ?>><?php echo $_['trial']; ?></option>
                                </select>
                            </div>
                            <label class="col-md-1 col-form-label text-center" for="e2_show_entries"><?php echo $_['show']; ?></label>
                            <div class="col-md-1">
                                <select id="e2_show_entries" class="form-control" data-toggle="select2">
                                    <?php foreach (array(10, 25, 50, 250, 500, 1000) as $rShow) { ?>
                                        <option value="<?php echo $rShow; ?>" <?php if ((isset(CoreUtilities::$rRequest['entries']) && CoreUtilities::$rRequest['entries'] == $rShow) || (!isset(CoreUtilities::$rRequest['entries']) && $rSettings['default_entries'] == $rShow)) {
                                                                                    echo 'selected';
                                                                                } ?>><?php echo $rShow; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <table id="datatable-users" class="table table-striped table-borderless dt-responsive nowrap font-normal">
                            <thead>
                                <tr>
                                    <th class="text-center"><?php echo $_['id']; ?></th>
                                    <th><?php echo $_['username']; ?></th>
                                    <th class="text-center"><?php echo $_['mac_address']; ?></th>
                                    <th class="text-center">Public IP</th>
                                    <th><?php echo $_['owner']; ?></th>
                                    <th class="text-center"><?php echo $_['status']; ?></th>
                                    <th class="text-center"><?php echo $_['online']; ?></th>
                                    <th class="text-center"><?php echo $_['trial']; ?></th>
                                    <th class="text-center"><?php echo $_['expiration']; ?></th>
                                    <th class="text-center">Last Connection</th>
                                    <th class="text-center"><?php echo $_['actions']; ?></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>
<script id="scripts">
			var resizeObserver = new ResizeObserver(entries => $(window).scroll());
			$(document).ready(function() {
				resizeObserver.observe(document.body)
				$("form").attr('autocomplete', 'off');
				$(document).keypress(function(event) {
					if (event.which == 13 && event.target.nodeName != "TEXTAREA") return false;
				});
				$.fn.dataTable.ext.errMode = 'none';
				var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
				elems.forEach(function(html) {
					var switchery = new Switchery(html, {
						'color': '#414d5f'
					});
					window.rSwitches[$(html).attr("id")] = switchery;
				});
				setTimeout(pingSession, 30000);
				<?php if (!$rMobile && $rSettings['header_stats']): ?>
					headerStats();
				<?php endif; ?>
				bindHref();
				refreshTooltips();
				$(window).scroll(function() {
					if ($(this).scrollTop() > 200) {
						if ($(document).height() > $(window).height()) {
							$('#scrollToBottom').fadeOut();
						}
						$('#scrollToTop').fadeIn();
					} else {
						$('#scrollToTop').fadeOut();
						if ($(document).height() > $(window).height()) {
							$('#scrollToBottom').fadeIn();
						} else {
							$('#scrollToBottom').hide();
						}
					}
				});
				$("#scrollToTop").unbind("click");
				$('#scrollToTop').click(function() {
					$('html, body').animate({
						scrollTop: 0
					}, 800);
					return false;
				});
				$("#scrollToBottom").unbind("click");
				$('#scrollToBottom').click(function() {
					$('html, body').animate({
						scrollTop: $(document).height()
					}, 800);
					return false;
				});
				$(window).scroll();
				$(".nextb").unbind("click");
				$(".nextb").click(function() {
					var rPos = 0;
					var rActive = null;
					$(".nav .nav-item").each(function() {
						if ($(this).find(".nav-link").hasClass("active")) {
							rActive = rPos;
						}
						if (rActive !== null && rPos > rActive && !$(this).find("a").hasClass("disabled") && $(this).is(":visible")) {
							$(this).find(".nav-link").trigger("click");
							return false;
						}
						rPos += 1;
					});
				});
				$(".prevb").unbind("click");
				$(".prevb").click(function() {
					var rPos = 0;
					var rActive = null;
					$($(".nav .nav-item").get().reverse()).each(function() {
						if ($(this).find(".nav-link").hasClass("active")) {
							rActive = rPos;
						}
						if (rActive !== null && rPos > rActive && !$(this).find("a").hasClass("disabled") && $(this).is(":visible")) {
							$(this).find(".nav-link").trigger("click");
							return false;
						}
						rPos += 1;
					});
				});
				(function($) {
					$.fn.inputFilter = function(inputFilter) {
						return this.on("input keydown keyup mousedown mouseup select contextmenu drop", function() {
							if (inputFilter(this.value)) {
								this.oldValue = this.value;
								this.oldSelectionStart = this.selectionStart;
								this.oldSelectionEnd = this.selectionEnd;
							} else if (this.hasOwnProperty("oldValue")) {
								this.value = this.oldValue;
								this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
							}
						});
					};
				}(jQuery));
				<?php if ($rSettings['js_navigate']): ?>
					$(".navigation-menu li").mouseenter(function() {
						$(this).find(".submenu").show();
					});
					delParam("status");
					$(window).on("popstate", function() {
						if (window.rRealURL) {
							if (window.rRealURL.split("/").reverse()[0].split("?")[0].split(".")[0] != window.location.href.split("/").reverse()[0].split("?")[0].split(".")[0]) {
								navigate(window.location.href.split("/").reverse()[0]);
							}
						}
					});
				<?php endif; ?>
				$(document).keydown(function(e) {
					if (e.keyCode == 16) {
						window.rShiftHeld = true;
					}
				});
				$(document).keyup(function(e) {
					if (e.keyCode == 16) {
						window.rShiftHeld = false;
					}
				});
				document.onselectstart = function() {
					if (window.rShiftHeld) {
						return false;
					}
				}
			});

			<?php 
		echo '        var rClearing = false;' . "\r\n" . '        var rSelected = [];' . "\r\n\r\n\t\t" . 'function api(rID, rType, rConfirm=false) {' . "\r\n" . '            if ((window.rSelected) && (window.rSelected.length > 0)) {' . "\r\n" . '                $.toast("Individual actions disabled in multi-select mode.");' . "\r\n" . '                return;' . "\r\n" . '            }' . "\r\n" . '            if ((rType == "delete") && (!rConfirm)) {' . "\r\n" . '                new jBox("Confirm", {' . "\r\n" . '                    confirmButton: "Delete",' . "\r\n" . '                    cancelButton: "Cancel",' . "\r\n" . '                    content: "';
		echo $_['device_delete_confirm'];
		echo '",' . "\r\n" . '                    confirm: function () {' . "\r\n" . '                        api(rID, rType, true);' . "\r\n" . '                    }' . "\r\n" . '                }).open();' . "\r\n" . '            } else if ((rType == "convert") && (!rConfirm)) {' . "\r\n" . '                new jBox("Confirm", {' . "\r\n" . '                    confirmButton: "Convert",' . "\r\n" . '                    cancelButton: "Cancel",' . "\r\n" . '                    content: "Are you sure you want to conver this Device to a User Line?",' . "\r\n" . '                    confirm: function () {' . "\r\n" . '                        api(rID, rType, true);' . "\r\n" . '                    }' . "\r\n" . '                }).open();' . "\r\n\t\t\t" . '} else {' . "\r\n" . '                rConfirm = true;' . "\r\n" . '            }' . "\r\n" . '            if (rConfirm) {' . "\r\n" . '                $.getJSON("./api?action=enigma&sub=" + rType + "&e2_id=" + rID, function(data) {' . "\r\n" . '                    if (data.result === true) {' . "\r\n" . '                        if (rType == "delete") {' . "\r\n" . '                            $.toast("';
		echo $_['device_confirmed_1'];
		echo '");' . "\r\n" . '                        } else if (rType == "enable") {' . "\r\n" . '                            $.toast("';
		echo $_['device_confirmed_2'];
		echo '");' . "\r\n" . '                        } else if (rType == "disable") {' . "\r\n" . '                            $.toast("';
		echo $_['device_confirmed_3'];
		echo '");' . "\r\n" . '                        } else if (rType == "unban") {' . "\r\n" . '                            $.toast("';
		echo $_['device_confirmed_4'];
		echo '");' . "\r\n" . '                        } else if (rType == "ban") {' . "\r\n" . '                            $.toast("';
		echo $_['device_confirmed_5'];
		echo '");' . "\r\n" . '                        } else if (rType == "convert") {' . "\r\n" . '                            if (data.line_id) {' . "\r\n" . '                                navigate("line?id=" + data.line_id);' . "\r\n" . '                            } else {' . "\r\n" . '                                $.toast("Failed to convert to user line.");' . "\r\n" . '                            }' . "\r\n" . '                        }' . "\r\n" . '                        $("#datatable-users").DataTable().ajax.reload(null, false);' . "\r\n" . '                    } else {' . "\r\n" . '                        $.toast("';
		echo $_['error_occured'];
		echo '");' . "\r\n" . '                    }' . "\r\n" . '                });' . "\r\n" . '            }' . "\r\n\t\t" . '}' . "\r\n" . '        function multiAPI(rType, rConfirm=false) {' . "\r\n" . '            if (rType == "clear") {' . "\r\n" . '                if ("#header_stats") {' . "\r\n" . '                    $("#header_stats").show();' . "\r\n" . '                }' . "\r\n" . '                window.rSelected = [];' . "\r\n" . '                $(".multiselect").hide();' . "\r\n" . "                \$(\"#datatable-users tr\").removeClass('selectedfilter').removeClass('ui-selected').removeClass(\"selected\");" . "\r\n" . '                return;' . "\r\n" . '            }' . "\r\n" . '            if ((rType == "delete") && (!rConfirm)) {' . "\r\n" . '                new jBox("Confirm", {' . "\r\n" . '                    confirmButton: "Delete",' . "\r\n" . '                    cancelButton: "Cancel",' . "\r\n" . '                    content: "Are you sure you want to delete these devices?",' . "\r\n" . '                    confirm: function () {' . "\r\n" . '                        multiAPI(rType, true);' . "\r\n" . '                    }' . "\r\n" . '                }).open();' . "\r\n" . '            } else if ((rType == "purge") && (!rConfirm)) {' . "\r\n" . '                new jBox("Confirm", {' . "\r\n" . '                    confirmButton: "Kill",' . "\r\n" . '                    cancelButton: "Cancel",' . "\r\n" . '                    content: "Are you sure you want to kill all connections?",' . "\r\n" . '                    confirm: function () {' . "\r\n" . '                        multiAPI(rType, true);' . "\r\n" . '                    }' . "\r\n" . '                }).open();' . "\r\n" . '            } else if ((rType == "convert") && (!rConfirm)) {' . "\r\n" . '                new jBox("Confirm", {' . "\r\n" . '                    confirmButton: "Convert",' . "\r\n" . '                    cancelButton: "Cancel",' . "\r\n" . '                    content: "Are you sure you want to convert these devices to normal lines?",' . "\r\n" . '                    confirm: function () {' . "\r\n" . '                        multiAPI(rType, true);' . "\r\n" . '                    }' . "\r\n" . '                }).open();' . "\r\n\t\t\t" . '} else {' . "\r\n" . '                rConfirm = true;' . "\r\n" . '            }' . "\r\n" . '            if (rConfirm) {' . "\r\n" . '                $.getJSON("./api?action=multi&type=enigma&sub=" + rType + "&ids=" + JSON.stringify(window.rSelected), function(data) {' . "\r\n" . '                    if (data.result == true) {' . "\r\n" . '                        if (rType == "convert") {' . "\r\n" . '                            $.toast("Devices have been converted to normal lines.");' . "\r\n" . '                        } else if (rType == "purge") {' . "\r\n" . '                            $.toast("Connections have been killed.");' . "\r\n" . '                        } else if (rType == "delete") {' . "\r\n" . '                            $.toast("Devices have been deleted.");' . "\r\n" . '                            refreshTable();' . "\r\n" . '                        } else if (rType == "ban") {' . "\r\n" . '                            $.toast("Devices have been banned.");' . "\r\n" . '                        } else if (rType == "unban") {' . "\r\n" . '                            $.toast("Devices have been unbanned.");' . "\r\n" . '                        } else if (rType == "enable") {' . "\r\n" . '                            $.toast("Devices have been enabled.");' . "\r\n" . '                        } else if (rType == "disable") {' . "\r\n" . '                            $.toast("Devices have been disabled.");' . "\r\n" . '                        }' . "\r\n" . '                        $("#datatable-users").DataTable().ajax.reload(null, false);' . "\r\n" . '                    } else {' . "\r\n" . '                        $.toast("An error occured while processing your request.");' . "\r\n" . '                    }' . "\r\n" . '                }).fail(function() {' . "\r\n" . '                    $.toast("An error occured while processing your request.");' . "\r\n" . '                });' . "\r\n" . '                multiAPI("clear");' . "\r\n" . '            }' . "\r\n\t\t" . '}' . "\r\n\t\t" . 'function getFilter() {' . "\r\n\t\t\t" . 'return $("#e2_filter").val();' . "\r\n\t\t" . '}' . "\r\n\t\t" . 'function getReseller() {' . "\r\n\t\t\t" . 'return $("#e2_reseller").val();' . "\r\n\t\t" . '}' . "\r\n\t\t" . 'function clearFilters() {' . "\r\n\t\t\t" . 'window.rClearing = true;' . "\r\n\t\t\t" . "\$(\"#e2_search\").val(\"\").trigger('change');" . "\r\n\t\t\t" . "\$('#e2_filter').val(\"\").trigger('change');" . "\r\n\t\t\t" . "\$('#e2_reseller').val(\"\").trigger('change');" . "\r\n\t\t\t" . "\$('#e2_show_entries').val(\"";
		echo (intval($rSettings['default_entries']) ?: 10);
		echo "\").trigger('change');" . "\r\n\t\t\t" . 'window.rClearing = false;' . "\r\n\t\t\t" . "\$('#datatable-users').DataTable().search(\$(\"#e2_search\").val());" . "\r\n\t\t\t" . "\$('#datatable-users').DataTable().page.len(\$('#e2_show_entries').val());" . "\r\n\t\t\t" . "\$(\"#datatable-users\").DataTable().page(0).draw('page');" . "\r\n\t\t\t" . '$("#datatable-users").DataTable().ajax.reload( null, false );' . "\r\n" . '            delParams(["search", "filter", "owner", "page", "entries"]);' . "\r\n\t\t\t" . 'checkClear();' . "\r\n\t\t" . '}' . "\r\n" . '        function checkClear() {' . "\r\n\t\t\t" . 'if (!hasParams(["search", "filter", "owner"])) {' . "\r\n\t\t\t\t" . '$("#clearFilters").prop("disabled", true);' . "\r\n\t\t\t" . '} else {' . "\r\n\t\t\t\t" . '$("#clearFilters").prop("disabled", false);' . "\r\n\t\t\t" . '}' . "\r\n\t\t" . '}' . "\r\n\t\t" . 'function refreshTable() {' . "\r\n\t\t\t" . '$("#datatable-users").DataTable().ajax.reload( null, false );' . "\r\n\t\t" . '}' . "\r\n" . '        function clearOwner() {' . "\r\n" . "            \$('#e2_reseller').val(\"\").trigger('change');" . "\r\n" . '        }' . "\r\n" . '        var rSearch;' . "\r\n\t\t" . '$(document).ready(function() {' . "\r\n\t\t\t" . "\$('select').select2({width: '100%'});" . "\r\n" . "            \$('#e2_reseller').select2({" . "\r\n\t\t\t" . '  ajax: {' . "\r\n\t\t\t\t" . "url: './api'," . "\r\n\t\t\t\t" . "dataType: 'json'," . "\r\n\t\t\t\t" . 'data: function (params) {' . "\r\n\t\t\t\t" . '  return {' . "\r\n\t\t\t\t\t" . 'search: params.term,' . "\r\n\t\t\t\t\t" . "action: 'reguserlist'," . "\r\n\t\t\t\t\t" . 'page: params.page' . "\r\n\t\t\t\t" . '  };' . "\r\n\t\t\t\t" . '},' . "\r\n\t\t\t\t" . 'processResults: function (data, params) {' . "\r\n\t\t\t\t" . '  params.page = params.page || 1;' . "\r\n\t\t\t\t" . '  return {' . "\r\n\t\t\t\t\t" . 'results: data.items,' . "\r\n\t\t\t\t\t" . 'pagination: {' . "\r\n\t\t\t\t\t\t" . 'more: (params.page * 100) < data.total_count' . "\r\n\t\t\t\t\t" . '}' . "\r\n\t\t\t\t" . '  };' . "\r\n\t\t\t\t" . '},' . "\r\n\t\t\t\t" . 'cache: true,' . "\r\n\t\t\t\t" . 'width: "100%"' . "\r\n\t\t\t" . '  },' . "\r\n\t\t\t" . "  placeholder: 'Search for an owner...'" . "\r\n\t\t\t" . '});' . "\r\n\t\t\t" . 'var rPage = getParam("page");' . "\r\n" . '            if (!rPage) { rPage = 1; }' . "\r\n" . '            var rEntries = getParam("entries");' . "\r\n" . '            if (!rEntries) { rEntries = ';
		echo intval($rSettings['default_entries']);
		echo '; }' . "\r\n\t\t\t" . 'var rTable = $("#datatable-users").DataTable({' . "\r\n\t\t\t\t" . 'language: {' . "\r\n\t\t\t\t\t" . 'paginate: {' . "\r\n\t\t\t\t\t\t" . "previous: \"<i class='mdi mdi-chevron-left'>\"," . "\r\n\t\t\t\t\t\t" . "next: \"<i class='mdi mdi-chevron-right'>\"," . "\r\n\t\t\t\t\t" . '},' . "\r\n\t\t\t\t\t" . 'infoFiltered: ""' . "\r\n\t\t\t\t" . '},' . "\r\n\t\t\t\t" . 'drawCallback: function() {' . "\r\n\t\t\t\t\t" . 'bindHref(); refreshTooltips();' . "\r\n" . '                    if ($("#datatable-users").DataTable().page.info().page > 0) {' . "\r\n" . '                        setParam("page", $("#datatable-users").DataTable().page.info().page+1);' . "\r\n" . '                    } else {' . "\r\n" . '                        delParam("page");' . "\r\n" . '                    }' . "\r\n" . '                    var rOrder = $("#datatable-users").DataTable().order()[0];' . "\r\n" . '                    setParam("order", rOrder[0]); setParam("dir", rOrder[1]);' . "\r\n" . '                    ';

		if (hasPermissions('adv', 'edit_e2')) {
			echo '                    // Multi Actions' . "\r\n" . '                    multiAPI("clear");' . "\r\n" . '                    $("#datatable-users tr").click(function() {' . "\r\n" . '                        if (window.rShiftHeld) {' . "\r\n" . "                            if (\$(this).hasClass('selectedfilter')) {" . "\r\n" . "                                \$(this).removeClass('selectedfilter').removeClass('ui-selected').removeClass(\"selected\");" . "\r\n" . '                                window.rSelected.splice($.inArray($(this).find("td:eq(0)").text(), window.rSelected), 1);' . "\r\n" . '                            } else {            ' . "\r\n" . "                                \$(this).addClass('selectedfilter').addClass('ui-selected').addClass(\"selected\");" . "\r\n" . '                                window.rSelected.push($(this).find("td:eq(0)").text());' . "\r\n" . '                            }' . "\r\n" . '                        }' . "\r\n" . '                        $("#multi_enigmas_selected").html(window.rSelected.length + " devices");' . "\r\n" . '                        if (window.rSelected.length > 0) {' . "\r\n" . '                            if ("#header_stats") {' . "\r\n" . '                                $("#header_stats").hide();' . "\r\n" . '                            }' . "\r\n" . '                            $("#multiselect_enigmas").show();' . "\r\n" . '                        } else {' . "\r\n" . '                            if ("#header_stats") {' . "\r\n" . '                                $("#header_stats").show();' . "\r\n" . '                            }' . "\r\n" . '                            $("#multiselect_enigmas").hide();' . "\r\n" . '                        }' . "\r\n" . '                    });' . "\r\n" . '                    ';
		}

		echo "\t\t\t\t" . '},' . "\r\n\t\t\t\t" . 'responsive: false,' . "\r\n\t\t\t\t" . 'processing: true,' . "\r\n\t\t\t\t" . 'serverSide: true,' . "\r\n" . '                searchDelay: 250,' . "\r\n\t\t\t\t" . 'ajax: {' . "\r\n\t\t\t\t\t" . 'url: "./table",' . "\r\n\t\t\t\t\t" . '"data": function(d) {' . "\r\n\t\t\t\t\t\t" . 'd.id = "enigmas",' . "\r\n\t\t\t\t\t\t" . 'd.filter = getFilter(),' . "\r\n\t\t\t\t\t\t" . 'd.reseller = getReseller()' . "\r\n\t\t\t\t\t" . '}' . "\r\n\t\t\t\t" . '},' . "\r\n\t\t\t\t" . 'columnDefs: [' . "\r\n\t\t\t\t\t" . '{"className": "dt-center", "targets": [0,2,3,5,6,7,8,9,10]},' . "\r\n\t\t\t\t\t";

		if (CoreUtilities::$rSettings['redis_handler']) {
			echo "\t\t\t\t\t" . '{"orderable": false, "targets": [6,10]},' . "\r\n\t\t\t\t\t";
		} else {
			echo "\t\t\t\t\t" . '{"orderable": false, "targets": [10]},' . "\r\n\t\t\t\t\t";
		}

		echo "\t\t\t\t\t" . '{"visible": false, "targets": [1]}' . "\r\n\t\t\t\t" . '],' . "\r\n" . '                ';

		if ($rMobile) {
			echo 'scrollX: true,';
		}

		echo "\t\t\t\t" . 'order: [[ ';
		echo (isset(CoreUtilities::$rRequest['order']) ? intval(CoreUtilities::$rRequest['order']) : 0);
		echo ', "';
		echo (in_array(strtolower(CoreUtilities::$rRequest['dir']), array('asc', 'desc')) ? strtolower(CoreUtilities::$rRequest['dir']) : 'desc');
		echo '" ]],' . "\r\n\t\t\t\t" . 'pageLength: parseInt(rEntries),' . "\r\n\t\t\t\t" . 'lengthMenu: [10, 25, 50, 250, 500, 1000],' . "\r\n" . '                displayStart: (parseInt(rPage)-1) * parseInt(rEntries)' . "\r\n\t\t\t" . '});' . "\r\n" . '            function doSearch(rValue) {' . "\r\n" . '                clearTimeout(window.rSearch); window.rSearch = setTimeout(function(){ rTable.search(rValue).draw(); }, 500);' . "\r\n" . '            }' . "\r\n\t\t\t" . '$("#datatable-users").css("width", "100%");' . "\r\n\t\t\t" . "\$('#e2_search').keyup(function(){" . "\r\n\t\t\t\t" . 'if (!window.rClearing) {' . "\r\n" . '                    delParam("page");' . "\r\n" . '                    rTable.page(0);' . "\r\n\t\t\t\t\t" . 'if ($("#e2_search").val()) {' . "\r\n\t\t\t\t\t\t" . 'setParam("search", $("#e2_search").val());' . "\r\n\t\t\t\t\t" . '} else {' . "\r\n\t\t\t\t\t\t" . 'delParam("search");' . "\r\n\t\t\t\t\t" . '}' . "\r\n\t\t\t\t\t" . 'checkClear();' . "\r\n\t\t\t\t\t" . 'doSearch($(this).val());' . "\r\n\t\t\t\t" . '}' . "\r\n\t\t\t" . '});' . "\r\n\t\t\t" . "\$('#e2_show_entries').change(function(){" . "\r\n\t\t\t\t" . 'if (!window.rClearing) {' . "\r\n" . '                    delParam("page");' . "\r\n" . '                    rTable.page(0);' . "\r\n\t\t\t\t\t" . 'if ($("#e2_show_entries").val()) {' . "\r\n\t\t\t\t\t\t" . 'setParam("entries", $("#e2_show_entries").val());' . "\r\n\t\t\t\t\t" . '} else {' . "\r\n\t\t\t\t\t\t" . 'delParam("entries");' . "\r\n\t\t\t\t\t" . '}' . "\r\n\t\t\t\t\t" . 'checkClear();' . "\r\n\t\t\t\t\t" . 'rTable.page.len($(this).val()).draw();' . "\r\n\t\t\t\t" . '}' . "\r\n\t\t\t" . '});' . "\r\n\t\t\t" . "\$('#e2_filter').change(function(){" . "\r\n\t\t\t\t" . 'if (!window.rClearing) {' . "\r\n" . '                    delParam("page");' . "\r\n" . '                    rTable.page(0);' . "\r\n\t\t\t\t\t" . 'if ($("#e2_filter").val()) {' . "\r\n\t\t\t\t\t\t" . 'setParam("filter", $("#e2_filter").val());' . "\r\n\t\t\t\t\t" . '} else {' . "\r\n\t\t\t\t\t\t" . 'delParam("filter");' . "\r\n\t\t\t\t\t" . '}' . "\r\n\t\t\t\t\t" . 'checkClear();' . "\r\n\t\t\t\t\t" . 'rTable.ajax.reload( null, false );' . "\r\n\t\t\t\t" . '}' . "\r\n\t\t\t" . '});' . "\r\n\t\t\t" . "\$('#e2_reseller').change(function(){" . "\r\n\t\t\t\t" . 'if (!window.rClearing) {' . "\r\n" . '                    delParam("page");' . "\r\n" . '                    rTable.page(0);' . "\r\n\t\t\t\t\t" . 'if ($("#e2_reseller").val()) {' . "\r\n\t\t\t\t\t\t" . 'setParam("owner", $("#e2_reseller").val());' . "\r\n\t\t\t\t\t" . '} else {' . "\r\n\t\t\t\t\t\t" . 'delParam("owner");' . "\r\n\t\t\t\t\t" . '}' . "\r\n\t\t\t\t\t" . 'checkClear();' . "\r\n\t\t\t\t\t" . 'rTable.ajax.reload( null, false );' . "\r\n\t\t\t\t" . '}' . "\r\n\t\t\t" . '});' . "\r\n\t\t\t" . "if (\$('#e2_search').val()) {" . "\r\n\t\t\t\t" . "rTable.search(\$('#e2_search').val()).draw();" . "\r\n\t\t\t" . '}' . "\r\n" . '            $("#btn-export-csv").click(function() {' . "\r\n" . '                $.toast("Generating CSV report...");' . "\r\n" . '                window.location.href = "api?action=report&params=" + encodeURIComponent(JSON.stringify($("#datatable-users").DataTable().ajax.params()));' . "\r\n\t\t\t" . '});' . "\r\n\t\t\t" . 'checkClear();' . "\r\n\t\t" . '});' . "\r\n" . '        ' . "\r\n\t\t";
		?>
    <?php if (CoreUtilities::$rSettings['enable_search']): ?>
        $(document).ready(function() {
            initSearch();
        });
    <?php endif; ?>
</script>
<script src="assets/js/listings.js"></script>
</body>

</html>