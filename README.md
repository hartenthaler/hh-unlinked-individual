# webtrees module for Unlinked Individual (hh-unlinked-individual)

[![Latest Release](https://img.shields.io/github/v/release/hartenthaler/hh-unlinked-individual)](https://github.com/hartenthaler/hh-unlinked-individual/releases/latest)
[![Downloads](https://img.shields.io/github/downloads/hartenthaler/hh-unlinked-individual/total)](https://github.com/hartenthaler/hh-unlinked-individual/releases)
[![License: GPL v3](https://img.shields.io/badge/License-GPL%20v3-blue.svg)](http://www.gnu.org/licenses/gpl-3.0)
![webtrees major version](https://img.shields.io/badge/webtrees-v2.2.x-green)

This [webtrees](https://www.webtrees.net) custom module adds a small tree-home-page block that lets editors create a new individual without linking it to an existing family or person first.

The module is a webtrees 2.2 revival of the older `wt-UnlinkedIndividual` module by Mats O Jansson.

## Contents

* [Purpose](#purpose)
* [Main features](#main-features)
* [Program flow](#program-flow)
* [Requirements](#requirements)
* [Installation](#installation)
* [Configuration](#configuration)
* [Translations](#translations)
* [Status](#status)
* [Credits](#credits)
* [License](#license)

## Purpose

webtrees already supports creating unlinked individuals in the tree administration area.
This module makes the same workflow available from the tree home page for users who have edit permissions.

This is useful when editors frequently add individuals that are not yet connected to the known family structure, for example newly discovered people whose relationships still need to be researched.

## Main features

The module provides:

* a tree-home-page block with a button to create an unlinked individual
* access restricted to editors, moderators, managers, and administrators
* use of the standard webtrees 2.2 unlinked-individual workflow
* return to the tree page or direct navigation to the newly created individual

## Program flow

1. An authorized user opens a tree home page.
1. The block shows a button labeled `Create an unlinked individual`.
1. The button opens the standard webtrees route `/tree/{tree}/add-unlinked-individual`.
1. webtrees renders the standard `edit/new-individual` form.
1. On submit, webtrees handles validation, GEDCOM creation, and record creation.
1. The first save button returns to the tree page; the second save button opens the new individual.

## Requirements

This module requires:

* webtrees 2.2
* PHP 8.3 or later
* editor, moderator, manager, or administrator permissions for users who should create unlinked individuals

## Installation

Manual installation:

1. Make a database backup.
1. Download the latest release from <https://github.com/hartenthaler/hh-unlinked-individual/releases/latest>.
1. Unzip the package into `webtrees/modules_v4`.
1. Ensure the folder is named `hh-unlinked-individual`.
1. Enable the module in the webtrees control panel.

## Configuration

The module has no control-panel settings.

To use it, add the `Unlinked individual` block to the tree home page. Users without sufficient edit permissions will not see any button and cannot use the module routes directly.

## Translations

Translations are loaded from `resources/lang/`.
The module looks for a `.po` file first and falls back to a compiled `.mo` file.

Current translation files:

* `de.po`
* `nl.po`
* `sv.po`

## Status

The module infrastructure and program flow have been updated for webtrees 2.2.
The current implementation intentionally links to the standard webtrees unlinked-individual workflow instead of maintaining a custom form or submit handler.

## Credits

Original module:

* Mats O Jansson

webtrees 2.2 revival:

* Hermann Hartenthaler

Translations:

* TheDutchJewel - Dutch

## License

This module is licensed under the GNU General Public License v3.0 or later.
