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
    'jquery',
    'ko',
    'uiRegistry'
], function (
    _,
    utils,
    registry,
    Actions,
    $,
    ko,
    uiRegistry
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
        showInEditor: function (action, recordId, actionObject) {
            var form = this.getFormDataSource();
            var revision = actionObject.revision;
            $.each(revision, function ( key, value ) {
                form.set('data.' + key,value);
            });
            this.updateTinyMCEContent(revision.content);
        },
        getFormDataSource: function () {
            return uiRegistry.get('cms_block_form.cms_block_form').source;
        },
        updateTinyMCEContent: function (value) {
            if (!this.hasTinyMCE()) {
                return;
            }
            
            window.tinyMCE.activeEditor.setContent(value);
        },
        hasTinyMCE: function () {
            if ( typeof window.tinyMCE == "undefined"
                || typeof window.tinyMCE.activeEditor== "undefined" ) {
            return false;
            }
            return true;
        }
    });
});
