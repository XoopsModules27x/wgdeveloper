<style>
    .wgd-row {
        display: flex;
        align-items: flex-start;
    }

    .wgd-left {
        width: 215px;
        box-sizing: border-box;
    }

    .wgd-right {
        width: 75%;
        box-sizing: border-box;
    }

    /* --- Tabs --- */
    .wgd-tabs {
        display: flex;
        border-bottom: 1px solid #ccc;
        margin-bottom: 15px;
    }
    .wgd-tab-btn {
        padding: 8px 16px;
        cursor: pointer;
        background: #f2f2f2;
        border: 1px solid #ccc;
        border-bottom: none;
        margin-right: 4px;
        border-radius: 4px 4px 0 0;
        font-weight: normal;
    }
    .wgd-tab-btn.active {
        background: #fff;
        font-weight: bold;
        border-bottom: 1px solid #fff;
        margin-bottom: -1px;
    }
    .wgd-tab-content {
        display: none;
    }
    .wgd-tab-content.active {
        display: block;
    }
    .wgd-form form {
        width:200px;
    }
    .wgd-cl-icon16 {
        width:16px;
    }
    .wgd-cl-icon24 {
        width:24px;
    }

    /* --- Filter-Toggle --- */
    .wgd-filter button {
        cursor: pointer;
    }
    .wgd-filter button.active {
        opacity: 0.5;
        cursor: default;
        pointer-events: none;
    }
</style>

<!-- Header -->
<{include file='db:wgdeveloper_admin_header.tpl' }>

<div id="wgd-wrapper">
    <div class="wgd-row">
        <div class="wgd-left wgd-form">
            <{if $form_modules|default:''}>
            <{$form_modules|default:false}>
            <{/if}>
        </div>
        <div class="wgd-right">
            <h3 class="center">
                <{if $mod_selected|default:false}>
                    <{$smarty.const._WGDEVELOPER_CHECKLANG_RESULTS}>
                <{else}>
                    <{$smarty.const._WGDEVELOPER_CHECKLANG_NO_MODULE}>
                <{/if}>
            </h3>
            <{if $mod_selected|default:false}>
            <div class="col-xs-12">
                <div class="xo-buttons wgd-filter" role="group" aria-label="Filter" style="margin-bottom:15px;">
                    <a class="" href="checklang.php?op=exec&mod_id=<{$mod_id|default:0}>" title="<{$lang_exec|default:''}>"><{$lang_exec|default:''}></a>
                    <{if $clresults|default:''}>
                    <button id="cl-filter-all" type="button" class="active" aria-pressed="true">
                        <{$smarty.const._WGDEVELOPER_CHECKLANG_SHOW_ALL}>
                    </button>
                    <button id="cl-filter-errors" type="button" class="" aria-pressed="false">
                        <{$smarty.const._WGDEVELOPER_CHECKLANG_SHOW_WARNINGS}>
                    </button>
                    <{/if}>
                </div>
                <{if $clresults|default:''}>
                <table class="outer" id="cl-results">
                    <thead>
                    <tr>
                        <th></th>
                        <th><{$smarty.const._WGDEVELOPER_CHECKLANG_FILE}></th>
                        <th><{$smarty.const._WGDEVELOPER_CHECKLANG_DEFINE}></th>
                        <th><{$smarty.const._WGDEVELOPER_CHECKLANG_FIRST}></th>
                    </tr>
                    </thead>
                    <tbody>
                    <{foreach item=clresultfile from=$clresults key=key}>
                        <{foreach item=clresult from=$clresultfile}>
                        <tr data-found="<{$clresult.found}>">
                            <td>
                                <{if $clresult.found}>
                            <img class="wgd-cl-icon24" src="<{$wgdeveloper_icons32_url}>/ok.png" alt="<{$smarty.const._WGDEVELOPER_CHECKLANG_RESULT_SUCCESS}>" title="<{$smarty.const._WGDEVELOPER_CHECKLANG_RESULT_SUCCESS}>">
                                <{else}>
                            <img class="wgd-cl-icon24" src="<{$wgdeveloper_icons32_url}>/warning.png" alt="<{$smarty.const._WGDEVELOPER_CHECKLANG_RESULT_FAILED}>" title="<{$smarty.const._WGDEVELOPER_CHECKLANG_RESULT_FAILED}>">
                                <{/if}>
                            </td>
                            <td>
                                <{$key}>
                            </td>
                            <td>
                                <{$clresult.define}>
                            </td>
                            <td>
                                <{if $clresult.first}>
                                <{$clresult.first}>
                                <{/if}>
                            </td>
                        </tr>
                        <{/foreach}>
                        <{/foreach}>
                    </tbody>
                </table>

                <div class="col-xs-12" style="margin-bottom:30px;font-size:80%">
                    <p><{$smarty.const._WGDEVELOPER_CHECKLANG_RESULT_LEGEND}></p>
                    <p>
                        <img class="wgd-cl-icon16" src="<{$wgdeveloper_icons32_url}>/ok.png" alt="<{$smarty.const._WGDEVELOPER_CHECKLANG_RESULT_SUCCESS}>">
                        <{$smarty.const._WGDEVELOPER_CHECKLANG_RESULT_SUCCESS}>
                        <{$smarty.const._WGDEVELOPER_CHECKLANG_RESULT_SUCCESS_DESCR}>
                    </p>
                    <p>
                        <img class="wgd-cl-icon16" src="<{$wgdeveloper_icons32_url}>/warning.png" alt="<{$smarty.const._WGDEVELOPER_CHECKLANG_RESULT_FAILED}>">
                        <{$smarty.const._WGDEVELOPER_CHECKLANG_RESULT_FAILED}>
                    </p>
                </div>
                <{/if}>
            </div>
            <{/if}>
        </div>
    </div>
</div>

<{if $error|default:''}>
    <div class="errorMsg"><strong><{$error|default:false}></strong></div>
    <{/if}>

<!-- Footer -->
<{include file='db:wgdeveloper_admin_footer.tpl' }>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        var table = document.getElementById('cl-results');
        var filterAll = document.getElementById('cl-filter-all');
        var filterErrors = document.getElementById('cl-filter-errors');

        if (!table || !filterAll || !filterErrors) {
            return;
        }

        var rows = table.querySelectorAll('tbody tr');

        function setActiveButton(button) {
            [filterAll, filterErrors].forEach(function (btn) {
                var isActive = (btn === button);
                btn.classList.toggle('active', isActive);
                btn.setAttribute('aria-pressed', isActive ? 'true' : 'false');
            });
        }

        function showAll() {
            rows.forEach(function (row) {
                row.style.display = '';
            });
            setActiveButton(filterAll);
        }

        function showErrors() {
            rows.forEach(function (row) {
                row.style.display = (row.getAttribute('data-found') === '0') ? '' : 'none';
            });
            setActiveButton(filterErrors);
        }

        filterAll.addEventListener('click', showAll);
        filterErrors.addEventListener('click', showErrors);

        // Initialzustand nach dem Laden: "Alle" ist gewählt (inaktiv)
        showAll();
    });
</script>
