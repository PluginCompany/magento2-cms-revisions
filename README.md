# CMS Revisions for Magento 2

**Version control for Magento 2 CMS pages and blocks.**

![Page revisions](docs/images/screenshots/01-page-revisions.webp)

Saves a revision of a CMS page or block every time it is saved.

Preview, edit and restore earlier revisions from the admin, and see who changed what and when.

> [!NOTE]
> CMS Revisions is free and open source under the MIT License (since 2026). Plugin Company no longer actively develops this extension, but issues and pull requests are welcome.
>
> Version 100.2.2 fixes a security issue in earlier versions; see [CHANGELOG.md](CHANGELOG.md).

## Features

- Automatic revision of CMS pages and blocks on every save
- Restore a revision with one click
- Preview page revisions in the storefront
- Edit block revisions in the content editor
- Unlimited revisions per page or block
- Shows who made each revision and when
- Recover accidentally deleted or overwritten content

## Screenshots

<table>
  <tr>
    <td width="50%" valign="top"><img src="docs/images/screenshots/02-block-revisions.webp" alt="Block revisions"><br><sub>Block revisions</sub></td>
    <td width="50%"></td>
  </tr>
</table>

## Compatibility

- **Magento:** 2.1.x – 2.4.x
- **PHP:** 5.5 – 8.x (Composer constraint `~5.5.0|~5.6.0|~7.0.2|~7.1|~7.2|~7.3|~8.0`)
- **Latest version:** 100.2.2 (released 2026-10-23)
- **Composer package:** `plugincompany/cmsrevisions`
- **Module:** `PluginCompany_CmsRevisions`

## Installation

### Composer (recommended)

Run in your Magento 2 root directory:

```bash
composer config repositories.plugin.company composer https://plugin.company/
composer require plugincompany/cmsrevisions
bin/magento module:enable PluginCompany_CmsRevisions
bin/magento setup:upgrade
```

The plugin.company Composer repository is public and points to the release tags of this GitHub repository; no account or credentials are needed. In production mode also run `bin/magento setup:di:compile` and `bin/magento setup:static-content:deploy`, then `bin/magento cache:flush`.

### Manual installation (ZIP)

Download the ZIP of the latest release from the [tags page](https://github.com/PluginCompany/magento2-cms-revisions/tags) ([100.2.2](https://github.com/PluginCompany/magento2-cms-revisions/archive/refs/tags/100.2.2.zip)) and copy its contents to `app/code/PluginCompany/CmsRevisions/`, so that `app/code/PluginCompany/CmsRevisions/registration.php` exists:

```bash
unzip magento2-cms-revisions-100.2.2.zip
mkdir -p app/code/PluginCompany/CmsRevisions
cp -R magento2-cms-revisions-100.2.2/. app/code/PluginCompany/CmsRevisions/
bin/magento module:enable PluginCompany_CmsRevisions
bin/magento setup:upgrade
```

See the [installation guide](docs/02-installing-the-extension.md) for the complete step-by-step instructions.

### Upgrading from a purchased copy

If you bought this extension from plugin.company before, you can switch to this open-source release without a license key. Your configuration and data stay as they are.

1. Keep the `plugin.company` repository entry in your `composer.json`: it now serves these GitHub releases with the same package names and version numbers. Credentials for plugin.company in `auth.json` are no longer needed.
2. Remove `plugincompany/licensemanager` from the `require` section of your `composer.json` if it is listed there.
3. Run `composer update plugincompany/cmsrevisions plugincompany/licensemanager` (or simply `composer update`), then `bin/magento setup:upgrade`.

If you installed the ZIP into `app/code/`, replace the files in `app/code/PluginCompany/CmsRevisions/` with this release. The License Manager module (`app/code/PluginCompany/LicenseManager/`) is not needed by these releases; once all your Plugin Company extensions are updated you can disable it with `bin/magento module:disable PluginCompany_LicenseManager`, delete the folder and run `bin/magento setup:upgrade`.

## Documentation

- [Documentation in this repository](docs/README.md) (Installation Guide, User Manual)
- Online documentation: <https://plugin.company/knowledge-base/docs/magento2-cms-revisions/>
- Product page: <https://plugin.company/magento-extensions/cms-revisions.html>
- Magento 1 / OpenMage version: [magento1-cms-revisions](https://github.com/PluginCompany/magento1-cms-revisions)

## Changelog

See [CHANGELOG.md](CHANGELOG.md) for the release history.

## Contributing

Found a bug or have an improvement? Please open an [issue](https://github.com/PluginCompany/magento2-cms-revisions/issues) or send a [pull request](https://github.com/PluginCompany/magento2-cms-revisions/pulls). Since the extension is no longer actively developed by Plugin Company, fixes from the community are the best way to keep it working on current Magento 2 and PHP versions.

## License

Plugin Company's code in this repository is released under the [MIT License](LICENSE), Copyright (c) 2014-2026 Plugin Company.

No third-party libraries are bundled; see [THIRD-PARTY-NOTICES.md](THIRD-PARTY-NOTICES.md).
