<?php

/*
 * webtrees - unlinked individual block (custom module)
 *
 * Copyright (C) 2026 Hermann Hartenthaler.
 * Copyright (C) 2021 Mats O Jansson.
 *
 * webtrees: online genealogy application
 * Copyright (C) 2026 webtrees development team.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);

namespace Hartenthaler\Webtrees\Module\UnlinkedIndividual;

use Aura\Router\RouterContainer;
use Fisharebest\Localization\Translation;
use Hartenthaler\Webtrees\Module\UnlinkedIndividual\Http\UnlinkedAction;
use Hartenthaler\Webtrees\Module\UnlinkedIndividual\Http\UnlinkedPage;
use Fisharebest\Webtrees\Auth;
use Fisharebest\Webtrees\Http\RequestHandlers\TreePage;
use Fisharebest\Webtrees\I18N;
use Fisharebest\Webtrees\Module\AbstractModule;
use Fisharebest\Webtrees\Module\ModuleBlockInterface;
use Fisharebest\Webtrees\Module\ModuleBlockTrait;
use Fisharebest\Webtrees\Module\ModuleCustomInterface;
use Fisharebest\Webtrees\Module\ModuleCustomTrait;
use Fisharebest\Webtrees\Registry;
use Fisharebest\Webtrees\Tree;
use Illuminate\Support\Str;

class UnlinkedIndividualModule extends AbstractModule implements ModuleCustomInterface, ModuleBlockInterface
{
    public const CUSTOM_TITLE       = 'Unlinked individual';
    public const CUSTOM_MODULE      = 'hh-unlinked-individual';
    public const CUSTOM_AUTHOR      = 'Hermann Hartenthaler';
    public const CUSTOM_GITHUB_USER = 'hartenthaler';
    public const GITHUB_REPO        = self::CUSTOM_GITHUB_USER . '/' . self::CUSTOM_MODULE;
    public const CUSTOM_WEBSITE     = 'https://github.com/' . self::GITHUB_REPO . '/';
    public const CUSTOM_VERSION     = '2.2.6.0';
    public const CUSTOM_LAST        = 'https://github.com/' . self::CUSTOM_GITHUB_USER . '/' .
                                      self::CUSTOM_MODULE . '/raw/main/latest-version.txt';

    use ModuleCustomTrait;
    use ModuleBlockTrait;

    /**
     * Bootstrap.  This function is called on *enabled* modules.
     * It is a good place to register routes and views.
     *
     * @return void
     */
    public function boot(): void
    {
        $router_container = Registry::container()->get(RouterContainer::class);
        assert($router_container instanceof RouterContainer);
        $router = $router_container->getMap();

        $router->get(UnlinkedPage::class, '/tree/{tree}/unlinked-individual');
        $router->post(UnlinkedAction::class, '/tree/{tree}/unlinked-individual');
    }

    /**
     * Where does this module store its resources
     *
     * @return string
     */
    public function resourcesFolder(): string
    {
        return __DIR__ . '/resources/';
    }

    /**
     * How should this module be identified in the control panel, etc.?
     *
     * @return string
     */
    public function title(): string
    {
        return I18N::translate('Unlinked individual');
    }

    /**
     * A sentence describing what this module does.
     *
     * @return string
     */
    public function description(): string
    {
        return I18N::translate('Create an unlinked individual');
    }

    /**
     * The person or organisation who created this module.
     *
     * @return string
     */
    public function customModuleAuthorName(): string
    {
        return self::CUSTOM_AUTHOR;
    }

    /**
     * The version of this module.
     *
     * @return string
     */
    public function customModuleVersion(): string
    {
        return self::CUSTOM_VERSION;
    }

    /**
     * A URL that will provide the latest version of this module.
     *
     * @return string
     */
    public function customModuleLatestVersionUrl(): string
    {
        return self::CUSTOM_LAST;
    }

    /**
     * Where to get support for this module.  Perhaps a github repository?
     *
     * @return string
     */
    public function customModuleSupportUrl(): string
    {
        return self::CUSTOM_WEBSITE;
    }

    /*
     * Additional/updated translations.
     *
     * @param string $language
     *
     * @return array<string,string>
     */
    public function customTranslations(string $language): array
    {
        $languageFile = match ($language) {
            'de' => 'de',
            'sv' => 'sv',
            default => '',
        };

        if ($languageFile === '') {
            return [];
        }

        $languageFolder = $this->resourcesFolder() . 'lang' . DIRECTORY_SEPARATOR;
        $poFile = $languageFolder . $languageFile . '.po';
        $moFile = $languageFolder . $languageFile . '.mo';

        if (is_file($poFile)) {
            return (new Translation($poFile))->asArray();
        }

        if (is_file($moFile)) {
            return (new Translation($moFile))->asArray();
        }

        return [];
    }

    /**
     * Generate the HTML content of this block.
     *
     * @param Tree	$tree
     * @param int	$block_id
     * @param string	$context
     * @param string[]	$config
     *
     * @return string
     */
    public function getBlock(Tree $tree, int $block_id, string $context, array $config = []): string
    {
        if (!$this->canCreateUnlinkedIndividual($tree)) {
            return '';
        }

        $url = route(UnlinkedPage::class, [
            'tree' => $tree->name(),
            'url'  => route(TreePage::class, ['tree' => $tree->name()]),
        ]);

        $content  = '<a class="btn btn-primary" href="' . e($url) . '">';
        $content .= I18N::translate('Create an unlinked individual');
        $content .= '</a>';

        if ($context !== self::CONTEXT_EMBED) {
            return view('modules/block-template', [
                'block'      => Str::kebab($this->name()),
                'id'         => $block_id,
                'config_url' => '',
                'title'      => $this->title(),
                'content'    => $content,
            ]);
        }

        return $content;
    }

    /**
     * Should this block load asynchronously using AJAX?
     *
     * Simple blocks are faster in-line, more complex ones can be loaded later.
     *
     * @return bool
     */
    public function loadAjax(): bool
    {
        return false;
    }

    /**
     * Can this block be shown on the tree’s home page?
     *
     * @return bool
     */
    public function isTreeBlock(): bool
    {
        return true;
    }

    public static function canCreateUnlinkedIndividual(Tree $tree): bool
    {
        return Auth::isAdmin() || Auth::isManager($tree) || Auth::isModerator($tree) || Auth::isEditor($tree);
    }
}
