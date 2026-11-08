<{if $form_sql|default:''}>
    <{if $show_btn_toggle|default:''}>
        <span class="xo-buttons">
            <button type="button"
                    id="toggle-sql"
                    aria-expanded="<{if $show_form_sql|default:false}>true<{else}>false<{/if}>"
                    aria-controls="sql-container">
                <{if $show_form_sql|default:false}>
                    <{$smarty.const._AM_WGDEVELOPER_DB_SQL_HIDE}>
                <{else}>
                    <{$smarty.const._AM_WGDEVELOPER_DB_SQL_SHOW}>
                <{/if}>
            </button>
        </span>
    <{/if}>
    <div class="sql-container<{if $show_form_sql|default:false}> is-visible<{/if}>"
         id="sql-container">
        <div class="left">
            <{if $sql_error|default:0 > 0}>
            <div class="errorSQL">
                <p><{$smarty.const._AM_WGDEVELOPER_DB_SQL_ERROR_NB}>: <{$sql_error|default:false}></p>
                <p><{$smarty.const._AM_WGDEVELOPER_DB_SQL_ERROR_STATE}>: <{$sql_state|default:false}></p>
                <p><{$smarty.const._AM_WGDEVELOPER_DB_SQL_ERROR_MESSAGE}>: <{$sql_message|default:false}></p>
            </div>
            <{/if}>
            <p class="wgdeveloper-form-header"><{$smarty.const._AM_WGDEVELOPER_DB_SQL_ENTER}></p>
            <{$form_sql}>
        </div>

        <div class="right">
            <{if $column_info|default:[]}>
            <h3><{$lang_column|default:''}></h3>
            <table class="outer sql-container-col">
                <thead>
                    <tr class="head">
                        <th><{$smarty.const._AM_WGDEVELOPER_DB_COL_NAME}>*</th>
                        <th><{$smarty.const._AM_WGDEVELOPER_DB_COL_TYPE}></th>
                    </tr>
                </thead>
                <tbody>
                <{foreach item=col from=$column_info}>
                    <tr class="<{cycle values='odd, even'}>">
                        <td class="sql-column-name"
                            data-column="<{$col.name|escape:'html'}>"
                            title="Double-click to insert column name">
                            <{$col.name|escape:'html'}>
                        </td>
                        <td><{$col.type}></td>
                    </tr>
                <{/foreach}>
                </tbody>
            </table>
            <span class="wdg-info">* <{$smarty.const._AM_WGDEVELOPER_DB_SQL_ADD_FIELDS}></span>
            <{/if}>
        </div>
    </div>
<{/if}>

<{if $sql_result|default:''}>
    <hr class="hr-sqlcode">
    <{$smarty.const._AM_WGDEVELOPER_DB_SQL_EXECUTED}>:
    <div class="sqlcode"><{$sql_code|escape}></div>


    <{if $sql_result.success}>
        <div class="successSQL">
            <{$sql_result.message|escape}>
        </div>

        <{if $sql_result.type == 'select'}>
            <div style="overflow-x:auto;">
                <table id="tablevalues" class="display">
                    <thead>
                    <tr>
                        <{foreach item=column from=$sql_result.columns}>
                        <th><{$column|escape}></th>
                        <{/foreach}>
                    </tr>
                    </thead>

                    <tbody>
                    <{foreach item=row from=$sql_result.rows}>
                        <tr>
                            <{foreach item=column from=$sql_result.columns}>
                            <td><{$row[$column]|escape}></td>
                            <{/foreach}>
                        </tr>
                        <{/foreach}>

                    <{if !$sql_result.rows}>
                        <tr>
                            <td colspan="<{$sql_result.columns|@count|default:1}>">
                                <strong><{$smarty.const._AM_WGDEVELOPER_DB_SQL_NO_RECORDS}></strong>
                            </td>
                        </tr>
                        <{/if}>
                    </tbody>
                </table>
            </div>
        <{elseif $sql_result.type == 'write'}><!-- sql_result.type != 'select' -->
            <{if isset($sql_result.affected) && $sql_result.affected !== null}>
                <p><{$smarty.const._AM_WGDEVELOPER_DB_SQL_AFFECTED_ROWS}>: <strong><{$sql_result.affected|default:''}></strong></p>
            <{/if}>
        <{/if}><!-- sql_result.type == 'select' -->
    <{else}><!-- $sql_result.success -->

        <div class="errorSQL">
            <strong>SQL error</strong>

            <{if isset($sql_result.code) && $sql_result.code}>
            (<{$sql_result.code|default:''}>)
            <{/if}>

            <p><{$sql_result.message|escape}></p>
        </div>
    <{/if}><!-- $sql_result.success -->

<{/if}>
<div class="xo-buttons wdg-buttons wdg-buttons-bar">
    <a href="database.php?op=listg&amp;table_name=<{$table_name|default:''}>" title="<{$smarty.const._AM_WGDEVELOPER_DB_TABLE_GOTO}>"><i class='fa fa-table'></i> <{$smarty.const._AM_WGDEVELOPER_DB_TABLE_GOTO}></a>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        // Toggle SQL container visibility.
        const toggleButton = document.getElementById('toggle-sql');
        const sqlContainer = document.getElementById('sql-container');

        if (toggleButton && sqlContainer) {

            const showText = '<{$smarty.const._AM_WGDEVELOPER_DB_SQL_SHOW|escape:"javascript"}>';
            const hideText = '<{$smarty.const._AM_WGDEVELOPER_DB_SQL_HIDE|escape:"javascript"}>';

            toggleButton.addEventListener('click', function () {

                const isVisible = sqlContainer.classList.toggle('is-visible');

                toggleButton.textContent = isVisible ? hideText : showText;
                toggleButton.setAttribute('aria-expanded', isVisible ? 'true' : 'false');

            });
        }

        // Insert column names into the SQL textarea on double-click.
        const sqlTextarea = document.querySelector('textarea[name="sql_code"]');

        if (sqlTextarea) {

            document.querySelectorAll('.sql-column-name').forEach(function (column) {

                column.addEventListener('dblclick', function () {

                    const columnName = this.dataset.column;

                    if (!columnName) {
                        return;
                    }

                    // Get the current cursor position.
                    const start = sqlTextarea.selectionStart;
                    const end = sqlTextarea.selectionEnd;

                    // Insert the column name at the current cursor position.
                    sqlTextarea.value =
                        sqlTextarea.value.substring(0, start) +
                        columnName +
                        sqlTextarea.value.substring(end);

                    // Move the cursor to the end of the inserted column name.
                    const newPosition = start + columnName.length;

                    sqlTextarea.focus();
                    sqlTextarea.setSelectionRange(newPosition, newPosition);

                });

            });

        } else {
            console.warn('SQL textarea not found.');
        }

    });
</script>
