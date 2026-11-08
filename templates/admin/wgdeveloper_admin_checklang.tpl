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
    .wgd-filter button:not(.active) {
        cursor: pointer;
    }
    .wgd-filter button .active {
        opacity: 0.5;
        cursor: default;
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
            <{if $clresults|default:''}>
                <h3 class="center">
                    <{$smarty.const._WGDEVELOPER_CHECKLANG_RESULTS}>
                </h3>
                <div class="col-xs-12">
                    <div class="xo-buttons wgd-filter" role="group" aria-label="Filter" style="margin-bottom:15px;">
                        <button id="cl-filter-all" type="button" class="active">
                            <{$smarty.const._WGDEVELOPER_CHECKLANG_SHOW_ALL}>
                        </button>
                        <button id="cl-filter-errors" type="button" class="">
                            <{$smarty.const._WGDEVELOPER_CHECKLANG_SHOW_WARNINGS}>
                        </button>
                    </div>
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

                </div>
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
            <{else}>
                <h3 class="center">
                    <{$smarty.const._WGDEVELOPER_CHECKLANG_NO_MODULE}>
                </h3>
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
            filterAll.classList.remove('active');
            filterErrors.classList.remove('active');

            button.classList.add('active');
        }

        function showAll() {
            rows.forEach(function (row) {
                row.style.display = '';
            });

            setActiveButton(filterAll);
        }

        function showErrors() {
            rows.forEach(function (row) {
                if (row.getAttribute('data-found') === '0') {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });

            setActiveButton(filterErrors);
        }

        filterAll.addEventListener('click', showAll);
        filterErrors.addEventListener('click', showErrors);

    });
</script>
