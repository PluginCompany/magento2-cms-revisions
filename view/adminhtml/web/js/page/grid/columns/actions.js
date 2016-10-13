/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of CMS Revisions for Magento 2, released under the MIT License.
 * See the LICENSE file in the project root.
 */
define([
    'underscore',
    'mageUtils',
    'uiRegistry',
    'Magento_Ui/js/grid/columns/actions',
    'Magento_Ui/js/modal/confirm',
    'Magento_Ui/js/modal/modal',
    'jquery',
    'text!PluginCompany_CmsRevisions/js/templates/page/previewprompt.html',
    'ko'
], function (
    _,
    utils,
    registry,
    Actions,
    Confirm,
    Modal,
    $,
    PreviewpromptTemplate,
    ko
) {
    'use strict';
    return Actions.extend({
        applyAction: function (actionIndex, rowIndex) {
            var action = this.getAction(rowIndex, actionIndex),
                callback = this._getCallback(action);

            action.confirm ?
                this._confirm(action, callback) :
                callback();

            return this;
        },
        preview: function (action, recordId, actionObject) {
            this
                .setParametersFromActionObject(actionObject)
                .showPreviewPrompt()
            ;
        },
        setParametersFromActionObject: function (actionObject) {
            console.log(actionObject);
            this.storeArray = this.makeArrayFromObject(actionObject['stores']);
            this.revisionId = actionObject.revisionId;
            return this;
        },
        showPreviewPrompt: function () {
            this
                .initPreviewPromptViewModel()
                .initPreviewPromptBindedTemplate()
                .displayPreviewPromptModal()
            ;
        },
        initPreviewPromptViewModel: function () {
            this.previewViewModel = {
                stores: this.storeArray,
                selectedStoreUrl: ko.observable()
            };
            return this;
        },
        initPreviewPromptBindedTemplate: function () {
            this.previewPromptBindedTemplate = this.getFirstPreviewPromptDiv();
            ko.applyBindings(this.previewViewModel,this.previewPromptBindedTemplate[0]);
            return this;
        },
        getFirstPreviewPromptDiv: function () {
          return $(PreviewpromptTemplate).find('div').first();  
        },
        makeArrayFromObject: function (myObj) {
            var arr = $.map(myObj, function (value, index) {
                return [value];
            });
            return arr;
        },
        displayPreviewPromptModal: function () {
            var that = this;
            this.previewPromptBindedTemplate
                .modal({
                    title: 'Select store view',
                    autoOpen: true,
                    closed: function () {
                        // on close
                    },
                    buttons: [{
                        text: 'Show Preview',
                        attr: {
                            'data-action': 'confirm'
                        },
                        'class': 'action-primary',
                        click: function () {
                            that.openRevisionPreviewPage();
                            this.closeModal();
                        }
                    }]
                });
            return this;
        },
        openRevisionPreviewPage: function () {
            var url = this.getPreviewUrlWithParams();
            this.openUrlInNewTab(url);
        },
        getPreviewUrlWithParams: function () {
            var url = this.getSelectedStorePreviewUrl();
            url = url.split('?');
            url.splice(1, 0, 'page_revision_id/' + this.revisionId);
            url[2] = '?' + url[2];
            url = url.join('');
            return url;
        },
        getSelectedStorePreviewUrl: function () {
            return this.previewViewModel.selectedStoreUrl();
        },
        openUrlInNewTab: function (url) {
            var win = window.open(url, '_blank');
            win.focus();
        }
    });
});
