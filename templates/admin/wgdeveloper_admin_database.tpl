
<!-- Header -->
<{include file='db:wgdeveloper_admin_header.tpl' }>

    <div id="wgd-wrapper">
        <div class="wgd-row">
            <div class="wgd-left wgd-form">
                <{if $form_db_tables|default:''}>
                    <div><{$form_db_tables|default:false}></div>
                <{/if}>
                <div class="sql_button xo-buttons wgd-buttons">
                    <a class="<{if !$table_selected|default:false}>disabled-link<{/if}>" href="database.php?op=enter_sql&amp;table_name=<{$table_name|default:''}>" title="<{$smarty.const._AM_WGDEVELOPER_DB_SQL_ENTER}>"><i class='fa fa-database'></i> <{$smarty.const._AM_WGDEVELOPER_DB_SQL_ENTER}></a>
                    <a class="<{if !$table_selected|default:false}>disabled-link<{/if}>" href="database.php?op=drop_table&amp;table_name=<{$table_name|default:''}>" title="<{$smarty.const._AM_WGDEVELOPER_DB_SQL_DROP}>"><i class='fa fa-trash'></i> <{$smarty.const._AM_WGDEVELOPER_DB_SQL_DROP}></a>
                    <a class="<{if !$table_selected|default:false}>disabled-link<{/if}>" href="database.php?op=truncate_table&amp;table_name=<{$table_name|default:''}>" title="<{$smarty.const._AM_WGDEVELOPER_DB_SQL_TRUNCATE}>"><i class='fa fa-eraser'></i> <{$smarty.const._AM_WGDEVELOPER_DB_SQL_TRUNCATE}></a>
                </div>
            </div>
            <div class="wgd-right">
                <{if $show_list|default:false}>
                    <div class="wgd-tabs">
                        <div class="wgd-tab-btn active" data-tab="wgd-tab-a"><{$smarty.const._AM_WGDEVELOPER_DB_TAB_VALUES}></div>
                        <div class="wgd-tab-btn" data-tab="wgd-tab-b"><{$smarty.const._AM_WGDEVELOPER_DB_TAB_COLUMNS}></div>
                    </div>

                    <div id="wgd-tab-a" class="wgd-tab-content active">
                        <!-- Content Tab Values -->
                        <{if $table_selected|default:false}>
                            <{if count($table_columns|default:[]) > 0}>
                            <table id="tablevalues" class="display">
                                <thead>
                                <tr>
                                    <th><{$smarty.const._AM_WGDEVELOPER_ACTION}></th>
                                    <{foreach item=col from=$table_columns}>
                                        <th><{$col|escape:'html'}></th>
                                    <{/foreach}>
                                </tr>
                                </thead>
                                <tbody>
                                <{foreach item=row from=$table_rows}>
                                    <tr>
                                        <td class="xo-buttons wgd-buttons">
                                            <{if $has_primarykey|default:false}>
                                                <{foreach $row as $key => $value}>
                                                    <{if $key == $primarykey|default:''}>
                                                        <a href="database.php?op=delete&amp;table_name=<{$table_name|default:''}>&amp;primary_key=<{$key|default:''}>&amp;primary_value=<{$value|default:''}>" title="<{$smarty.const._DELETE}>"><i class='fa fa-trash'></i></a>
                                                        <button type="button"
                                                                class="wgd-details-btn"
                                                                title="Details anzeigen">
                                                            <i class="fa fa-eye"></i>
                                                        </button>
                                                        <a href="database.php?op=edit&amp;table_name=<{$table_name|default:''}>&amp;primary_key=<{$key|default:''}>&amp;primary_value=<{$value|default:''}>" title="<{$smarty.const._DELETE}>"><i class='fa fa-pencil'></i></a>
                                                        <{break}>
                                                    <{/if}>
                                                <{/foreach}>
                                            <{/if}>
                                        </td>
                                        <{foreach item=col from=$table_columns}>
                                        <td data-full-value="<{$row.$col|escape:'html'}>"><{$row.$col|truncate:300}></td>
                                        <{/foreach}>
                                    </tr>
                                    <{/foreach}>
                                </tbody>
                            </table>
                            <div class="clear">&nbsp;</div>
                            <{else}>
                            <{$smarty.const._AM_WGDEVELOPER_DB_NO_DATA}>
                            <{/if}>
                        <{else}>
                            <{$smarty.const._AM_WGDEVELOPER_DB_NO_TABLE}>
                        <{/if}>
                    </div>

                    <div id="wgd-tab-b" class="wgd-tab-content">
                        <!-- Content Tab Columns -->
                        <{if $table_selected|default:false}>
                            <{if $column_info|default:[]}>
                            <table id="tablecolumns" class="display">
                                <thead>
                                    <tr>
                                        <th class="center"><{$smarty.const._AM_WGDEVELOPER_DB_COL_NAME}></th>
                                        <th class="center"><{$smarty.const._AM_WGDEVELOPER_DB_COL_TYPE}></th>
                                        <th class="center"><{$smarty.const._AM_WGDEVELOPER_DB_COL_NULL}></th>
                                        <th class="center"><{$smarty.const._AM_WGDEVELOPER_DB_COL_KEY}></th>
                                        <th class="center"><{$smarty.const._AM_WGDEVELOPER_DB_COL_DEFAULT}></th>
                                        <th class="center"><{$smarty.const._AM_WGDEVELOPER_DB_COL_EXTRA}></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <{foreach item=col from=$column_info}>
                                        <tr>
                                            <td><{$col.name}></td>
                                            <td class="center"><{$col.type}></td>
                                            <td class="center"><{$col.null}></td>
                                            <td class="center"><{$col.key}></td>
                                            <td class="center"><{$col.default}></td>
                                            <td class="center"><{$col.extra}></td>
                                        </tr>
                                    <{/foreach}>
                                </tbody>
                            </table>
                            <{/if}>
                        <{else}>
                            <{$smarty.const._AM_WGDEVELOPER_DB_NO_TABLE}>
                        <{/if}>
                    </div>
                <{/if}> <!-- show_list -->
                <{if $form_delete|default:''}>
                    <{$form_delete}>
                <{/if}>
                <{if $form_edit|default:''}>
                    <{$form_edit}>
                <{/if}>
                <{if $show_sql|default:''}>
                    <{include file='db:wgdeveloper_admin_database_sql.tpl' }>
                <{/if}>
            </div>
        </div>
    </div>



<{if $error|default:''}>
    <div class="errorMsg"><strong><{$error|default:false}></strong></div>
<{/if}>

<div id="wgd-details-modal"
     class="wgd-modal"
     role="dialog"
     aria-modal="true"
     aria-labelledby="wgd-modal-title">

    <div class="wgd-modal-dialog">
        <div class="wgd-modal-header">
            <h3 id="wgd-modal-title"><{$smarty.const._AM_WGDEVELOPER_DB_DETAILS}></h3>

            <button type="button"
                    class="wgd-modal-close"
                    aria-label="<{$smarty.const._CLOSE}>">
                &times;
            </button>
        </div>
        <div class="wgd-modal-body" id="wgd-modal-content"></div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var buttons = document.querySelectorAll('.wgd-tab-btn');
        buttons.forEach(function (btn) {
            btn.addEventListener('click', function () {
                var target = btn.getAttribute('data-tab');

                document.querySelectorAll('.wgd-tab-btn').forEach(function (b) {
                    b.classList.remove('active');
                });
                document.querySelectorAll('.wgd-tab-content').forEach(function (c) {
                    c.classList.remove('active');
                });

                btn.classList.add('active');
                document.getElementById(target).classList.add('active');
            });
        });
        // start code for modal
        const table = document.getElementById('tablevalues');
        const modal = document.getElementById('wgd-details-modal');
        const content = document.getElementById('wgd-modal-content');
        const closeButton = modal?.querySelector('.wgd-modal-close');

        if (!table || !modal || !content || !closeButton) {
            return;
        }

        // Open modal
        table.addEventListener('click', function (event) {
            const button = event.target.closest('.wgd-details-btn');

            if (!button) {
                return;
            }

            const row = button.closest('tr');

            if (!row) {
                return;
            }

            const headers = Array.from(
                table.querySelectorAll('thead th')
            ).slice(1);

            const cells = Array.from(
                row.querySelectorAll('td')
            ).slice(1);

            const detailsTable = document.createElement('table');
            const tbody = document.createElement('tbody');

            headers.forEach(function (header, index) {
                const cell = cells[index];

                if (!cell) {
                    return;
                }

                const tr = document.createElement('tr');
                const th = document.createElement('th');
                const td = document.createElement('td');

                th.textContent = header.textContent.trim();
                td.textContent = cell.dataset.fullValue
                    ?? cell.textContent.trim();

                tr.append(th, td);
                tbody.appendChild(tr);
            });

            detailsTable.appendChild(tbody);
            content.replaceChildren(detailsTable);

            modal.classList.add('show');
            closeButton.focus();
        });

        // Close modal
        function closeModal() {
            modal.classList.remove('show');
        }

        closeButton.addEventListener('click', closeModal);

        // Close when clicking outside the dialog
        modal.addEventListener('click', function (event) {
            if (event.target === modal) {
                closeModal();
            }
        });

        // Close with Escape
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' &&
                modal.classList.contains('show')) {
                closeModal();
            }
        });
    });
</script>


<{if $error|default:''}>
    <div class="errorMsg"><strong><{$error|default:false}></strong></div>
<{/if}>

<link href="<{$wgdeveloper_assets_url|default:''}>/datatables/datatables.min.css" rel="stylesheet">

<script src="<{$wgdeveloper_assets_url|default:''}>/datatables/datatables.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const options = {
            scrollCollapse: true,
            scrollY: '400px',
            paging: true,
            pageLength: <{$limit|default:10}>,
            lengthMenu: [10, 25, 50, 100, -1],
            language: { url: '<{$wgdeveloper_datatables_lang_url|default:''}>' },
            layout: {
                bottomEnd: { paging: { firstLast: false } }
            }
        };

        const tables = {};

        if (document.getElementById('tablevalues')) {
            tables.tablevalues = new DataTable('#tablevalues', Object.assign({}, options, {
                order: [[1, 'asc']],
                columnDefs: [
                    { orderable: false, targets: 0 },
                    { width: '200px', targets: 0 }
                ]
            }));
        }

        if (document.getElementById('tablecolumns')) {
            tables.tablecolumns = new DataTable('#tablecolumns', Object.assign({}, options, {
                ordering: false
            }));
        }

        // bind search
        document.addEventListener('input', function (e) {
            if (!e.target.matches('.dt-search input')) return;
                const dt = tables[e.target.getAttribute('aria-controls')];
                if (dt) {
                dt.search(e.target.value).draw();
            }
        });

        // calc column new when changing tab
        document.querySelectorAll('.wgd-tab-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                setTimeout(function () {
                    DataTable.tables({ visible: true, api: true }).columns.adjust();
                }, 0);
            });
        });
    });
</script>


<!-- Footer -->
<{include file='db:wgdeveloper_admin_footer.tpl' }>
