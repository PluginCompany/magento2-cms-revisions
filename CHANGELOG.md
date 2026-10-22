# Changelog: CMS Revisions for Magento 2

All releases of this extension, newest first. The released versions are commits on `main`, each tagged with its version number (e.g. [`100.2.2`](https://github.com/PluginCompany/magento2-cms-revisions/releases/tag/100.2.2)); Composer installs these tags.

## 100.2.2 — 2026-10-23

- Security fix: restoring a page or block revision now requires an admin login and the Restore Revision permission.
- Security fix: revision previews now use signed links from the admin revision grid that expire after 1 hour; other preview URLs show the 404 page, and preview pages are no longer stored in the full page cache.
- Security fix: deleting revisions now requires the Delete Revision permission (before, any logged-in admin user could delete them).
- Earlier versions let unauthenticated requests restore and preview revisions.
- Verified by static analysis of the changed files (PHPCompatibility, php -l on PHP 5.6, 7.4, 8.1 and 8.3); not run on a live store.

## 100.2.1 — 2023-02-13

- PHP 8.x compatibility fix (Magento 2.4.4 / 2.4.5)

## 100.2.0 — 2021-04-23

- Added ACL permissions functionality for CMS Block Revisions: General, restore & delete
- Added ACL permissions functionality for CMS Page Revisions: General, restore & delete
- Fixed confirmation popup text rendering issue for Magento versions below v2.4.0 (issue was introduced in v100.1.11)

## 100.1.11 — 2020-08-12

- Fixed confirmation popup text rendering issue for Magento 2.4.x
- Verified compatibility with Magento 2.4.x and PHP 7.4.x

## 100.1.10 — 2020-07-12

- Verified compatibility with Magento ECE / EE 2.3.5 + PageBuilder
- Updated PHP and licence manager version requirements in composer.json

## 100.1.9 — 2019-08-16

- Removed block revision 'Edit' button when Page Builder module is active

## 100.1.8 — 2019-04-16

- Removed magento framework dependency from composer.json
- Updated license manager dependency in composer.json

## 100.1.7 — 2018-07-19

- License manager update to latest version

## 100.1.6 — 2018-03-01

- Fixed conflict with other extensions generating CMS pages and blocks during installation

## 100.1.5 — 2018-02-07

- Fix for block saving issue introduced in v100.1.4

## 100.1.4 — 2018-02-03

- Fixed issue where selecting all revisions and then deselecting one or more resulted in the delete mass action not working properly
- Fix for double creation of revisions when saving page / block

## 100.1.3 — 2017-12-11

- Removed some redundant XML tags

## 100.1.2 — 2017-12-09

- Bugfix for block revisions in Magento EE 2.2
- Bugfix for all revisions being shown when creating new page / block
- Additional code changes for Magento Marketplace validation

## 100.1.1 — 2017-12-07

- Code changes (mainly comments) for Magento Marketplace validation

## 100.1.0 — 2017-09-26

- Magento 2.2 compatibility
- Code refactoring
- Updated installer so initial revisions are generated on install
- Preview CMS page bug fix

## 100.0.2 — 2017-08-31

- Fixed compiler issues
- Composer json update

## 100.0.0 — 2016-10-13

- First release of Magento 2 CMS Revisions extension by Plugin Company.
